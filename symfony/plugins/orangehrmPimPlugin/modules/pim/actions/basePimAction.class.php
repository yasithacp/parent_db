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

abstract class basePimAction extends sfAction {
    
    private $employeeService;
    
    public function preExecute() {
        $sessionVariableManager = new DatabaseSessionManager();
        $sessionVariableManager->setSessionVariables(array(
            'orangehrm_user' => Auth::instance()->getLoggedInUserId(),
        ));
        $sessionVariableManager->registerVarables();
        $this->setOperationName(OrangeActionHelper::getActionDescriptor($this->getModuleName(), $this->getActionName()));
    }

    /**
     * Get EmployeeService
     * @returns EmployeeService
     */
    public function getEmployeeService() {
        if(is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }

    /**
     * Set EmployeeService
     * @param EmployeeService $employeeService
     */
    public function setEmployeeService(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }
    
    protected function isSupervisor($loggedInEmpNum, $empNumber) {

        if(isset($_SESSION['isSupervisor']) && $_SESSION['isSupervisor']) {

            $empService = $this->getEmployeeService();
            $subordinates = $empService->getSupervisorEmployeeChain($loggedInEmpNum, true);

            foreach($subordinates as $employee) {
                if($employee->getEmpNumber() == $empNumber) {
                    return true;
                }
            }
        }
        return false;
    }
    
    protected function IsActionAccessible($empNumber) {
        
        $isValidUser = true;
        
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();     
        
        $userRoleManager = $this->getContext()->getUserRoleManager();            
        $accessible = $userRoleManager->isEntityAccessible('Employee', $empNumber);
            
        if ($empNumber != $loggedInEmpNum && (!$accessible)) {
            $isValidUser = false;
        }      
        
        return $isValidUser;
    }

    protected function isAllowedAdminOnlyActions($loggedInEmpNumber, $empNumber) {

        if ($loggedInEmpNumber == $empNumber) {
            return false;
        }

        $userRoleManager = $this->getContext()->getUserRoleManager();   
        $excludeRoles = array('Supervisor');
        
        $accessible = $userRoleManager->isEntityAccessible('Employee', $empNumber, null, $excludeRoles);
        
        if ($accessible) {
            return true;
        }

        return false;

    }

    protected function setOperationName($actionName) {
        $sessionVariableManager = new DatabaseSessionManager();
        $sessionVariableManager->setSessionVariables(array(
            'orangehrm_action_name' => $actionName,
        ));
        $sessionVariableManager->registerVarables();
    }

}