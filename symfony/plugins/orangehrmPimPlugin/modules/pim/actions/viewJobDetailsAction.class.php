<?php

/**
 * OrangeHRM Enterprise is a closed sourced comprehensive Human Resource Management (HRM)
 * System that captures all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM Inc is the owner of the patent, copyright, trade secrets, trademarks and any
 * other intellectual property rights which subsist in the Licensed Materials. OrangeHRM Inc
 * is the owner of the media / downloaded OrangeHRM Enterprise software files on which the
 * Licensed Materials are received. Title to the Licensed Materials and media shall remain
 * vested in OrangeHRM Inc. For the avoidance of doubt title and all intellectual property
 * rights to any design, new software, new protocol, new interface, enhancement, update,
 * derivative works, revised screen text or any other items that OrangeHRM Inc creates for
 * Customer shall remain vested in OrangeHRM Inc. Any rights not expressly granted herein are
 * reserved to OrangeHRM Inc.
 *
 * You should have received a copy of the OrangeHRM Enterprise  proprietary license file along
 * with this program; if not, write to the OrangeHRM Inc. 538 Teal Plaza, Secaucus , NJ 0709
 * to get the file.
 *
 */


/**
 * ViewJobDetailsAction
 */
class viewJobDetailsAction extends basePimAction {

    public function execute($request) {
        
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        $loggedInUserName = $_SESSION['fname'];
        
        $job = $request->getParameter('job');
        $empNumber = (isset($job['emp_number'])) ? $job['emp_number']: $request->getParameter('empNumber');
        $this->empNumber = $empNumber;

        $this->ownRecords = ($loggedInEmpNum == $empNumber)?true:false;
        $this->allowEdit = $this->isAllowedAdminOnlyActions($loggedInEmpNum, $empNumber);

        $adminMode = $this->getUser()->hasCredential(Auth::ADMIN_ROLE);

        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
        
        $this->essMode = !$adminMode && !empty($loggedInEmpNum) && ($empNumber == $loggedInEmpNum);
                       
        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }
        
        $employee = $this->getEmployeeService()->getEmployee($empNumber);
        $param = array('empNumber' => $empNumber, 'ESS' => $this->essMode,
                       'employee' => $employee,
                       'loggedInUser' => $loggedInEmpNum,
                       'loggedInUserName' => $loggedInUserName);
        $paramForTerminationForm = array('empNumber' => $empNumber, 'employee' => $employee);
        $this->form = new EmployeeJobDetailsForm(array(), $param, true);
        $this->employeeTerminateForm = new EmployeeTerminateForm(array(), $paramForTerminationForm, true);

        if ($this->getRequest()->isMethod('post')) {

            if (!$this->allowEdit) {
                $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
            }
                    
            // Handle the form submission           
            $this->form->bind($request->getParameter($this->form->getName()), 
                    $request->getFiles($this->form->getName()));

            if ($this->form->isValid()) {

                // save data
                $service = new EmployeeService();
                $service->saveJobDetails($this->form->getEmployee(), false);
                $this->form->updateAttachment();
                
                
                $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::UPDATE_SUCCESS)));  
            } else {
                $validationMsg = '';
                foreach($this->form->getWidgetSchema()->getPositions() as $widgetName) {
                    if($this->form[$widgetName]->hasError()) {
                        $validationMsg .= $this->form[$widgetName]->getError()->getMessageFormat();
                    }
                }

                $this->getUser()->setFlash('templateMessage', array('warning', $validationMsg));
            }
            
            $this->redirect('pim/viewJobDetails?empNumber=' . $empNumber);
        }

    }


}
