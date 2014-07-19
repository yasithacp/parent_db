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
 * viewSalaryAction
 */
class viewSalaryListAction extends basePimAction {

    public function execute($request) {
        
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        $loggedInUserName = $_SESSION['fname'];
        
        $salary = $request->getParameter('salary');
        $empNumber = (isset($salary['emp_number'])) ? $salary['emp_number']: $request->getParameter('empNumber');
        $this->empNumber = $empNumber;
        $this->essUserMode = !$this->isAllowedAdminOnlyActions($loggedInEmpNum, $empNumber);

        $this->ownRecords = ($loggedInEmpNum == $empNumber)?true:false;

        $adminMode = $this->getUser()->hasCredential(Auth::ADMIN_ROLE);
        $this->isSupervisor = $this->isSupervisor($loggedInEmpNum, $empNumber);

        $this->essMode = !$adminMode && !empty($loggedInEmpNum) && ($empNumber == $loggedInEmpNum);
        
        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
            
                       
        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }
        
        $employee = $this->getEmployeeService()->getEmployee($empNumber);
        $params = array('empNumber' => $empNumber, 'ESS' => $this->essMode,
                       'employee' => $employee,
                       'loggedInUser' => $loggedInEmpNum,
                       'loggedInUserName' => $loggedInUserName);
        
        $this->form = new EmployeeSalaryForm(array(), $params, true);
        
        // TODO: Use embedForm or mergeForm?
        $this->directDepositForm = new EmployeeDirectDepositForm(array(), array(), true);

        if ($this->getRequest()->isMethod('post')) {

            // Handle the form submission    
            $this->form->bind($request->getParameter($this->form->getName()));                       

            if ($this->form->isValid()) {

                $salary = $this->form->getSalary();
                
                $setDirectDebit = $this->form->getValue('set_direct_debit');
                $directDebitOk = true;
                
                if (!empty($setDirectDebit)) {

                    $this->directDepositForm->bind($request->getParameter($this->directDepositForm->getName()));
                    
                    if ($this->directDepositForm->isValid()) {
                        $this->directDepositForm->getDirectDeposit($salary);
                    } else {
                        
                        $validationMsg = '';
                        foreach($this->directDepositForm->getWidgetSchema()->getPositions() as $widgetName) {
                            if($this->directDepositForm[$widgetName]->hasError()) {
                                $validationMsg .= $widgetName . ' ' . __($this->directDepositForm[$widgetName]->getError()->getMessageFormat());
                            }
                        }

                        $this->getUser()->setFlash('templateMessage', array('warning', $validationMsg));                        
                        $directDebitOk = false;
                    }
                } else {
                    $salary->directDebit->delete();
                    $salary->clearRelated('directDebit');
                }
                
                if ($directDebitOk) {
                    $service = $this->getEmployeeService();
                    $this->setOperationName('UPDATE SALARY');
                    $service->saveEmpBasicsalary($salary);                

                    $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));  
                }
            } else {
                $validationMsg = '';
                foreach($this->form->getWidgetSchema()->getPositions() as $widgetName) {
                    if($this->form[$widgetName]->hasError()) {
                        $validationMsg .= $widgetName . ' ' . __($this->form[$widgetName]->getError()->getMessageFormat());
                    }
                }

                $this->getUser()->setFlash('templateMessage', array('warning', $validationMsg));
            }
            $this->redirect('pim/viewSalaryList?empNumber=' . $empNumber);
        } else {        
            $this->salaryList = $this->getEmployeeService()->getSalary($empNumber);            
        }

    }


}
