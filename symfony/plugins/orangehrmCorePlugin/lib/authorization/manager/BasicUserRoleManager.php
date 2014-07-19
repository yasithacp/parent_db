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
 * Description of BasicUserRoleManager
 *
 */
class BasicUserRoleManager extends AbstractUserRoleManager {
    
    protected $employeeService;
    protected $systemUserService;
    protected $screenPermissionService;
    protected $operationalCountryService;
    protected $locationService;
    
    public function getLocationService() {
        if (empty($this->locationService)) {
            $this->locationService = new LocationService();
        }        
        return $this->locationService;
    }

    public function setLocationService($locationService) {
        $this->locationService = $locationService;
    }

    public function getOperationalCountryService() {
        if (empty($this->operationalCountryService)) {
            $this->operationalCountryService = new OperationalCountryService();
        }         
        return $this->operationalCountryService;
    }

    public function setOperationalCountryService($operationalCountryService) {
        $this->operationalCountryService = $operationalCountryService;
    }

        
    public function getScreenPermissionService() {
        if (empty($this->screenPermissionService)) {
            $this->screenPermissionService = new ScreenPermissionService();
        }         
        return $this->screenPermissionService;
    }

    public function setScreenPermissionService($screenPermissionService) {
        $this->screenPermissionService = $screenPermissionService;
    }

    public function getSystemUserService() {
        if (empty($this->systemUserService)) {
            $this->systemUserService = new SystemUserService();
        }        
        return $this->systemUserService;
    }

    public function setSystemUserService($systemUserService) {
        $this->systemUserService = $systemUserService;
    }

    public function getEmployeeService() {
        
        if (empty($this->employeeService)) {
            $this->employeeService = new EmployeeService();
        }
        return $this->employeeService;
    }

    public function setEmployeeService($employeeService) {
        $this->employeeService = $employeeService;
    }

        
    public function getAccessibleEntities($entityType, $operation = null, $returnType = null,
            $rolesToExclude = array(), $rolesToInclude = array()) {
        
        $allEmployees = array();
        
        $filteredRoles = $this->filterRoles($this->userRoles, $rolesToExclude, $rolesToInclude); 
        
        foreach ($filteredRoles as $role) {  
            $employees = array();

            switch ($entityType) {
                case 'Employee':
                    $employees = $this->getAccessibleEmployees($role, $operation, $returnType);
                    break;  
            }
            
            if (count($employees) > 0) {
                $allEmployees = $this->mergeEmployees($allEmployees, $employees);
            }
        }        

        return $allEmployees;
    }
    
    
    /**
     * TODO: 'locations', 'system users', 'operational countries', 
     *       'user role' (only ess for regional admin),
     * 
     * @param type $entityType
     * @param type $operation
     * @param type $returnType
     * @return type 
     */
    public function getAccessibleEntityIds($entityType, $operation = null, $returnType = null,
            $rolesToExclude = array(), $rolesToInclude = array()) {
    
        $allIds = array();
        $filteredRoles = $this->filterRoles($this->userRoles, $rolesToExclude, $rolesToInclude);                
        
        foreach ($filteredRoles as $role) {  
            $ids = array();

            switch ($entityType) {
                case 'Employee':
                    $ids = $this->getAccessibleEmployeeIds($role, $operation, $returnType);
                    break;
                case 'SystemUser':
                    $ids = $this->getAccessibleSystemUserIds($role, $operation, $returnType);
                    break;
                case 'OperationalCountry':
                    $ids = $this->getAccessibleOperationalCountryIds($role, $operation, $returnType);
                    break;
                case 'UserRole':
                    $ids = $this->getAccessibleUserRoleIds($role, $operation, $returnType);
                    break;
                case 'Location':
                    $ids = $this->getAccessibleLocationIds($role, $operation, $returnType);
                    break;
                    
            }
            
            if (count($ids) > 0) {
                $allIds = array_unique(array_merge($allIds, $ids));
            }
        }
        
        return $allIds;
    }
    
    
    public function isEntityAccessible($entityType, $entityId, $operation = null, 
            $rolesToExclude = array(), $rolesToInclude = array()) {
        $entityIds = $this->getAccessibleEntityIds($entityType, $operation, null, $rolesToExclude, $rolesToInclude);
        
        $accessible = in_array($entityId, $entityIds);
        
        return $accessible;
    }
    
    public function areEntitiesAccessible($entityType, $entityIds, $operation = null, 
            $rolesToExclude = array(), $rolesToInclude = array()) {
        $accessibleIds = $this->getAccessibleEntityIds($entityType, $operation, null, $rolesToExclude, $rolesToInclude);
        $intersection = array_intersect($accessibleIds, $entityIds);
        
        $accessible = false;
        
        if (count($entityIds) == count($intersection)) {
            $diff = array_diff($entityIds, $intersection);
            if (count($diff) == 0) {
                $accessible = true;
            }
        }
        
        return $accessible;        
    }
    
    public function getAccessibleModules() {
        
    }
    
    public function isModuleAccessible($module) {
        
    }
    
    public function isScreenAccessible($module, $screen, $field) {
        
    }
    
    public function isFieldAccessible($module, $screen, $field) {
        
    }
    
    public function getScreenPermissions($module, $action) {
        $permissions = $this->getScreenPermissionService()->getScreenPermissions($module, $action, $this->userRoles);
        
        return $permissions;
    }
    
    protected function getUserRoles(SystemUser $user) {
        
        $user = $this->getSystemUserService()->getSystemUser($user->id);

        $roles = array($user->getUserRole());
        
        // Check for supervisor:
        $empNumber = $user->getEmpNumber();
        if (!empty($empNumber)) {
            if ($this->getEmployeeService()->isSupervisor($empNumber)) {
                $supervisorRole = $this->getSystemUserService()->getUserRole('Supervisor');
                if (!empty($supervisorRole)) {
                    $roles[] = $supervisorRole;
                }
            }
        }
        
        
        return $roles;
    }    
    
    protected function getAccessibleEmployees($role, $operation = null, $returnType = null) {
        $employees = array();
        
        if ('Admin' == $role->getName()) {
            $employees = $this->getEmployeeService()->getEmployeeList('empNumber', 'ASC', true);
        } else if ('Supervisor' == $role->getName()) {
            $empNumber = $this->getUser()->getEmpNumber();
            if (!empty($empNumber)) {
                $employees = $this->getEmployeeService()->getSupervisorEmployeeChain($empNumber, true);
            }
        }
        
        $employeesWithIds = array();
        
        foreach ($employees as $employee) {
            $employeesWithIds[$employee->getEmpNumber()] = $employee;
        }

        return $employeesWithIds;        
    }
    
    protected function mergeEmployees($empList1, $empList2) {
        
        foreach ($empList2 as $id=>$emp) {
            if (!isset($empList1[$id])) {
                $empList1[$id] = $emp;
            }
        }
        return $empList1;
    }
    
    protected function getAccessibleEmployeeIds($role, $operation = null, $returnType = null) {
        
        $employees = array();
        
        if ('Admin' == $role->getName()) {
            $employees = $this->getEmployeeService()->getEmployeeList('empNumber', 'ASC', true);
        } else if ('Supervisor' == $role->getName()) {
            $empNumber = $this->getUser()->getEmpNumber();
            if (!empty($empNumber)) {
                $employees = $this->getEmployeeService()->getSupervisorEmployeeChain($empNumber, true);
            }
        }
        
        $ids = array();
        
        foreach ($employees as $employee) {
            $ids[] = $employee->getEmpNumber();
        }

        return $ids;
        
    }
    
    protected function getAccessibleSystemUserIds($role, $operation = null, $returnType = null) {
        
        $systemUsers = array();
        
        if ('Admin' == $role->getName()) {
            $systemUsers = $this->getSystemUserService()->getSystemUsers();
        }
        
        $ids = array();
        
        foreach ($systemUsers as $user) {
            $ids[] = $user->getId();
        }

        return $ids;        
    }    
    
    
    protected function getAccessibleOperationalCountryIds($role, $operation = null, $returnType = null) {
        
        $operationalCountries = array();
        
        if ('Admin' == $role->getName()) {
            $operationalCountries = $this->getOperationalCountryService()->getOperationalCountryList();
        }
        
        $ids = array();
        
        foreach ($operationalCountries as $country) {
            $ids[] = $country->getId();
        }

        return $ids;        
    }    
    
    protected function getAccessibleUserRoleIds($role, $operation = null, $returnType = null) {
        
        $userRoles = array();
        
        if ('Admin' == $role->getName()) {
            $userRoles = $this->getSystemUserService()->getAssignableUserRoles();
        }
        
        $ids = array();
        
        foreach ($userRoles as $role) {
            $ids[] = $role->getId();
        }

        return $ids;        
    } 
    
    protected function getAccessibleLocationIds($role, $operation = null, $returnType = null) {
        
        $locations = array();
        
        if ('Admin' == $role->getName()) {
            $locations = $this->getLocationService()->getLocationList();
        }
        
        $ids = array();
        
        foreach ($locations as $location) {
            $ids[] = $location->getId();
        }

        return $ids;        
    }    
    
    protected function filterRoles($userRoles, $rolesToExclude, $rolesToInclude) {
        
        if (!empty($rolesToExclude)) {
            
            $temp = array();
            
            foreach ($userRoles as $role) {  
                if (!in_array($role->getName(), $rolesToExclude)) {
                    $temp[] = $role;
                }
            }         
            
            $userRoles = $temp;
        }
        
        if (!empty($rolesToInclude)) {
            $temp = array();
            
            foreach ($userRoles as $role) {  
                if (in_array($role->getName(), $rolesToInclude)) {
                    $temp[] = $role;
                }
            }         
            
            $userRoles = $temp;            
        }  
        
        return $userRoles;
    }
}

