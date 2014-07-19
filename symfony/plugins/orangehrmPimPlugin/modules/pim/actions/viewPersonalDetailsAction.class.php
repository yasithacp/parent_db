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
 * personalDetailsAction
 *
 */
class viewPersonalDetailsAction extends basePimAction {

    /**
     * @param sfForm $form
     * @return
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    public function execute($request) {
        try {
            $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
            $this->showBackButton = true;
            $this->isLeavePeriodDefined();

            $personal = $request->getParameter('personal');
            $empNumber = (isset($personal['txtEmpID']))?$personal['txtEmpID']:$request->getParameter('empNumber');
            $this->empNumber = $empNumber;

            //hiding the back button if its self ESS view
            if($loggedInEmpNum == $empNumber) {
                
                $this->showBackButton = false;
            }
            
            // TODO: Improve            
            $adminMode = $this->getUser()->hasCredential(Auth::ADMIN_ROLE);

            if ($this->getUser()->hasFlash('templateMessage')) {
                list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
            }

            $essMode = !$adminMode && !empty($loggedInEmpNum) && ($empNumber == $loggedInEmpNum);
            $param = array('empNumber' => $empNumber, 'ESS' => $essMode);
            
            if (!$this->IsActionAccessible($empNumber)) {
                $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
            }
            
            $this->essMode = $essMode;
            
            $this->showDeprecatedFields = OrangeConfig::getInstance()->getAppConfValue(ConfigService::KEY_PIM_SHOW_DEPRECATED);
            $this->showSSN = OrangeConfig::getInstance()->getAppConfValue(ConfigService::KEY_PIM_SHOW_SSN);
            $this->showSIN = OrangeConfig::getInstance()->getAppConfValue(ConfigService::KEY_PIM_SHOW_SIN);

            $this->setForm(new EmployeePersonalDetailsForm(array(), $param, true));
            if ($request->isMethod('post')) {

                $this->form->bind($request->getParameter($this->form->getName()));
                if ($this->form->isValid()) {

                    $this->_checkWhetherEmployeeIdExists($this->form->getValue('txtEmployeeId'), $empNumber);

                    $employee = $this->form->getEmployee();
                    $this->getEmployeeService()->savePersonalDetails($employee, $essMode);
                    $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));
                    $this->redirect('pim/viewPersonalDetails?empNumber='. $empNumber);

                }
            }
        } catch( Exception $e) {
            print( $e->getMessage());
        }
    }

    private function isLeavePeriodDefined() {

        $leavePeriodService = new LeavePeriodService();
        $leavePeriodService->setLeavePeriodDao(new LeavePeriodDao());
        $leavePeriod = $leavePeriodService->getLeavePeriod(strtotime(date("Y-m-d")));
        $flag = 0;
        
        if($leavePeriod instanceof LeavePeriod) {
            $flag = 1;
        }

        $_SESSION['leavePeriodDefined'] = $flag;
    }

    protected function _checkWhetherEmployeeIdExists($employeeId, $empNumber) {

        if (!empty($employeeId)) {

            $employee = $this->getEmployeeService()->getEmployeeByEmployeeId($employeeId);

            if (($employee instanceof Employee) && trim($employee->getEmpNumber()) != trim($empNumber)) {
                $this->getUser()->setFlash('templateMessage', array('warning', __('Employee Id Exists')));
                $this->redirect('pim/viewPersonalDetails?empNumber='. $empNumber);
            }

        }

    }

}
