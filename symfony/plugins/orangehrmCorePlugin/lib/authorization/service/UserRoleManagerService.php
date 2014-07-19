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
 * Description of UserRoleManagerService
 *
 */
class UserRoleManagerService {
    
    const KEY_USER_ROLE_MANAGER_CLASS = "authorize_user_role_manager_class";
    
    protected $configDao;    
    protected $authenticationService;
    protected $systemUserService;
    
    public function getConfigDao() {
        
        if (empty($this->configDao)) {
            $this->configDao = new ConfigDao();
        }
        return $this->configDao;
    }

    public function setConfigDao($configDao) {
        $this->configDao = $configDao;
    }

    public function getAuthenticationService() {
        if (empty($this->authenticationService)) {
            $this->authenticationService = new AuthenticationService();
        }        
        return $this->authenticationService;
    }

    public function setAuthenticationService($authenticationService) {
        $this->authenticationService = $authenticationService;
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

    
    public function getUserRoleManagerClassName() {
        return $this->getConfigDao()->getValue(self::KEY_USER_ROLE_MANAGER_CLASS);
    }
    
    public function getUserRoleManager() {
        $class = $this->getUserRoleManagerClassName();
        
        $manager = null;
        
        if (class_exists($class)) {
            try {
                $manager = new $class;
            } catch (Exception $e) {
                throw new ServiceException('Exception when initializing user role manager:' . $e->getMessage());
            }
        } else {
            throw new ServiceException('User Role Manager class ' . $class . ' not found.');
        }
        
        if (!$manager instanceof AbstractUserRoleManager) {
            throw new ServiceException('User Role Manager class ' . $class . ' is not a subclass of AbstractUserRoleManager');
        }
        
        // Set System User object in manager
        $userId = $this->getAuthenticationService()->getLoggedInUserId();
        $systemUser = $this->getSystemUserService()->getSystemUser($userId);  
        
        if ($systemUser instanceof SystemUser) {
            $manager->setUser($systemUser);
        } else {
            throw new ServiceException('Logged in user does not have corresponding SystemUser record. UserId=' . $userId );
        }
        
        return $manager;
    }
}

