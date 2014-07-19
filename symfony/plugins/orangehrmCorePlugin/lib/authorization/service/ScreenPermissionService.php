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
 * Description of ScreenPermissionService
 *
 */
class ScreenPermissionService {
    
    private $screenPermissionDao;    
    private $screenDao;
    
    public function getScreenDao() {
        if (empty($this->screenDao)) {
            $this->screenDao = new ScreenDao();
        }         
        return $this->screenDao;
    }

    public function setScreenDao($screenDao) {       
        $this->screenDao = $screenDao;
    }

    public function getScreenPermissionDao() {
        if (empty($this->screenPermissionDao)) {
            $this->screenPermissionDao = new ScreenPermissionDao();
        }
        return $this->screenPermissionDao;
    }

    public function setScreenPermissionDao($screenPermissionDao) {
        $this->screenPermissionDao = $screenPermissionDao;
    }

        
    
    /**
     * Get Screen Permissions for given module, action for the given roles
     * @param string $module Module Name
     * @param string $actionUrl Action Name
     * @param string $roles Array of Role names or Array of UserRole objects
     */
    public function getScreenPermissions($module, $actionUrl, $roles) {
        $screenPermissions = $this->getScreenPermissionDao()->getScreenPermissions($module, $actionUrl, $roles);
        
        $permission = null;

        // if empty, give all permissions
        if (count($screenPermissions) == 0) {
            
            // If screen not defined, give all permissions, if screen is defined, 
            // but don't give any permissions.
            $screen = $this->getScreenDao()->getScreen($module, $actionUrl);
            if ($screen === false) {
                $permission = new ResourcePermission(true, true, true, true);
            } else {
                $permission = new ResourcePermission(false, false, false, false);
            }
        } else {
            $read = false;
            $create = false;            
            $update = false;
            $delete = false;
            
            foreach ($screenPermissions as $screenPermission) {
                if ($screenPermission->can_read) {
                    $read = true;
                }
                if ($screenPermission->can_create) {
                    $create = true;
                }
                if ($screenPermission->can_update) {
                    $update = true;
                }
                if ($screenPermission->can_delete) {
                    $delete = true;
                }             
            }
            
            $permission = new ResourcePermission($read, $create, $update, $delete);
        }
        
        return $permission;
    }
}

