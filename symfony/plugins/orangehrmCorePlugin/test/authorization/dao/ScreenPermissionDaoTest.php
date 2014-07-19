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
 * Description of ScreenPermissionDaoTest
 *
 */
class ScreenPermissionDaoTest  extends PHPUnit_Framework_TestCase {
    
    /** @property ScreenPermissionDao $dao */
    private $dao;
    
    /**
     * Set up method
     */
    protected function setUp() {        
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmCorePlugin/test/fixtures/ScreenPermissionDao.yml';
        
        TestDataService::populate($this->fixture);
                
        $this->dao = new ScreenPermissionDao();
    }
    
    public function testGetScreenPermission() {
        $permissions = $this->dao->getScreenPermissions('pim', 'viewEmployeeList', array('Admin'));
        $this->assertNotNull($permissions);
        $this->assertEquals(1, count($permissions));
        $this->verifyPermissions($permissions[0], true, true, true, true);
       
        
        $permissions = $this->dao->getScreenPermissions('pim', 'viewEmployeeList', array('ESS'));
        $this->assertNotNull($permissions);
        $this->assertEquals(1, count($permissions));
        $this->verifyPermissions($permissions[0], false, false, false, false);
        
        $permissions = $this->dao->getScreenPermissions('pim', 'viewEmployeeList', array('Supervisor'));
        $this->assertNotNull($permissions);
        $this->assertEquals(1, count($permissions));
        $this->verifyPermissions($permissions[0], true, false, true, false);
        
        $permissions = $this->dao->getScreenPermissions('pim', 'viewEmployeeList', array('Admin', 'Supervisor', 'ESS'));
        $this->assertNotNull($permissions);
        $this->assertEquals(3, count($permissions));
        
        foreach($permissions as $permission) {
            $roleId = $permission->getUserRoleId();
            if ($roleId == 1) {
                // Admin
                $this->verifyPermissions($permission, true, true, true, true);
            } else if ($roleId == 2) {
                // Supervisor
                $this->verifyPermissions($permission, false, false, false, false);            
            } else if ($roleId == 3) {
                // ESS
                $this->verifyPermissions($permission, true, false, true, false);    
            } else {
                $this->fail("Unexpected roleId=" . $roleId);
            }
        }
        
        $permissions = $this->dao->getScreenPermissions('pim', 'viewEmployeeListNoneExisting', array('Admin', 'Supervisor', 'ESS'));
        $this->assertTrue($permissions instanceof Doctrine_Collection);
        $this->assertEquals(0, count($permissions));
        
    }
    
    protected function verifyPermissions($permission, $read, $create, $update, $delete) {
        $this->assertEquals($read, $permission->can_read);
        $this->assertEquals($create, $permission->can_create);
        $this->assertEquals($update, $permission->can_update);
        $this->assertEquals($delete, $permission->can_delete);        
    }
}

