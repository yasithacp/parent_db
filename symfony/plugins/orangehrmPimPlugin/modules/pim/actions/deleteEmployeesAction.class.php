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
 * delete employees list action
 */
class deleteEmployeesAction extends basePimAction {

    /**
     * Delete action. Deletes the employees with the given ids
     */
    public function execute($request) {
        
        $ids = $request->getParameter('chkSelectRow');

        $userRoleManager = $this->getContext()->getUserRoleManager();
        if (!$userRoleManager->areEntitiesAccessible('Employee', $ids)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
        
        $employeeService = $this->getEmployeeService();               
        $count = $employeeService->deleteEmployee($ids);

        if ($count == count($ids)) {
            $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::DELETE_SUCCESS)));
        } else {
            $this->getUser()->setFlash('templateMessage', array('failure', __('A Problem Occured When Deleting The Selected Employees')));
        }

        $this->redirect('pim/viewEmployeeList');
    }


}
