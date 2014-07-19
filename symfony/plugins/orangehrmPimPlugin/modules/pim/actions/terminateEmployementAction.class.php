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

class terminateEmployementAction extends basePimAction {

    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    public function execute($request) {
        $empNumber = $request->getParameter('empNumber');
        $terminatedId = $request->getParameter('terminatedId');
        $employee = $this->getEmployeeService()->getEmployee($empNumber);
        $paramForTerminationForm = array('empNumber' => $empNumber, 'employee' => $employee);
        $this->form = new EmployeeTerminateForm(array(), $paramForTerminationForm, true);

        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        
        if (!$this->isAllowedAdminOnlyActions($loggedInEmpNum, $empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
        
        if ($this->getRequest()->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->terminateEmployement($empNumber, $terminatedId);
                $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::UPDATE_SUCCESS)));
            }

            $this->redirect('pim/viewJobDetails?empNumber=' . $empNumber);
        }
    }

}

