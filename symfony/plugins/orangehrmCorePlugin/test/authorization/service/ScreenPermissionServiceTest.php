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
 * Description of ScreenPermissionServiceTest
 *
 */
class ScreenPermissionServiceTest extends PHPUnit_Framework_TestCase {
    
    /** @property ScreenPermissionService $service */
    private $service;
    
    /**
     * Set up method
     */
    protected function setUp() {
        $this->service = new ScreenPermissionService();
    }    
    
    /**
     * Test case for when no permissions are defined for given user role(s).
     * Behavior is to allow access if the screen is not defined, unless prohibited through a rule in the database.
     * This allows to progressively update the rules in code. 
     */
    public function testGetScreenPermissionsNoneWithNoScreen() {
        $module = 'xim';
        $action = 'doThis';
        $roles = '';
        
        $permissionDao = $this->getMock('ScreenPermissionDao', array('getScreenPermissions'));
        $emptyDoctrineCollection = new Doctrine_Collection('ScreenPermission');
        
        $permissionDao->expects($this->once())
                ->method('getScreenPermissions')
                ->with($module, $action, $roles)
                ->will($this->returnValue($emptyDoctrineCollection));
        
        $this->service->setScreenPermissionDao($permissionDao);
        
        $screenDao = $this->getMock('ScreenDao', array('getScreen'));
        $screenDao->expects($this->once())
                ->method('getScreen')
                ->with($module, $action)
                ->will($this->returnValue(false));        
        
        $this->service->setScreenDao($screenDao);
        
        $permissions = $this->service->getScreenPermissions($module, $action, $roles);
        
        $this->assertTrue($permissions instanceof ResourcePermission);
        $this->verifyPermissions($permissions, true, true, true, true);

    }
    
    public function testGetScreenPermissionsNoneWithScreenDefined() {
        $module = 'xim';
        $action = 'doThis';
        $roles = '';
        
        $mockDao = $this->getMock('ScreenPermissionDao', array('getScreenPermissions'));
        $emptyDoctrineCollection = new Doctrine_Collection('ScreenPermission');
        
        $mockDao->expects($this->once())
                ->method('getScreenPermissions')
                ->with($module, $action, $roles)
                ->will($this->returnValue($emptyDoctrineCollection));
        
        $this->service->setScreenPermissionDao($mockDao);
        
        $screen = new Screen();
        $screen->setName('abc');
        
        $screenDao = $this->getMock('ScreenDao', array('getScreen'));
        $screenDao->expects($this->once())
                ->method('getScreen')
                ->with($module, $action)
                ->will($this->returnValue($screen));        
        
        $this->service->setScreenDao($screenDao);        
        
        $permissions = $this->service->getScreenPermissions($module, $action, $roles);
        
        $this->assertTrue($permissions instanceof ResourcePermission);
        $this->verifyPermissions($permissions, false, false, false, false);

    }
    
    public function testGetScreenPermissionsOne() {
        $module = 'xim';
        $action = 'doThis';
        $roles = array('Admin');
        

        $doctrineCollection = new Doctrine_Collection('ScreenPermission');
        $screenPermission1 = new ScreenPermission();
        $screenPermission1->fromArray(array('id' => 1, 'user_role_id' => 1, 'screen_id' => 1, 
                                           'can_read' => 1, 'can_create' => 0, 
                                           'can_update'=> 0, 'can_delete'=> 1));
        $screenPermission2 = new ScreenPermission();
        $screenPermission2->fromArray(array('id' => 1, 'user_role_id' => 1, 'screen_id' => 1, 
                                           'can_read' => 0, 'can_create' => 1, 
                                           'can_update'=> 0, 'can_delete'=> 1));
        
        $screenPermissions = array($screenPermission1, $screenPermission2);
        $doctrineCollection->setData($screenPermissions);
        
        $mockDao = $this->getMock('ScreenPermissionDao', array('getScreenPermissions'));        
        $mockDao->expects($this->once())
                ->method('getScreenPermissions')
                ->with($module, $action, $roles)
                ->will($this->returnValue($doctrineCollection));
        
        $this->service->setScreenPermissionDao($mockDao);
        
        $permissions = $this->service->getScreenPermissions($module, $action, $roles);
        
        $this->assertTrue($permissions instanceof ResourcePermission);
        $this->verifyPermissions($permissions, true, true, false, true);        
    }
    
    public function testGetScreenPermissionsTwo() {
        $module = 'xim';
        $action = 'doThis';
        $roles = array('Admin', 'ESS');
        

        $doctrineCollection = new Doctrine_Collection('ScreenPermission');
        $screenPermission1 = new ScreenPermission();
        $screenPermission1->fromArray(array('id' => 1, 'user_role_id' => 1, 'screen_id' => 1, 
                                           'can_read' => 1, 'can_create' => 0, 
                                           'can_update'=> 0, 'can_delete'=> 1));
        $screenPermission2 = new ScreenPermission();
        $screenPermission2->fromArray(array('id' => 1, 'user_role_id' => 1, 'screen_id' => 1, 
                                           'can_read' => 0, 'can_create' => 1, 
                                           'can_update'=> 0, 'can_delete'=> 1));
        
        $screenPermissions = array($screenPermission1, $screenPermission2);
        $doctrineCollection->setData($screenPermissions);
        
        $mockDao = $this->getMock('ScreenPermissionDao', array('getScreenPermissions'));        
        $mockDao->expects($this->once())
                ->method('getScreenPermissions')
                ->with($module, $action, $roles)
                ->will($this->returnValue($doctrineCollection));
        
        $this->service->setScreenPermissionDao($mockDao);
        
        $permissions = $this->service->getScreenPermissions($module, $action, $roles);
        
        $this->assertTrue($permissions instanceof ResourcePermission);
        $this->verifyPermissions($permissions, true, true, false, true);        
    }
    
    public function testGetScreenPermissionsMany() {
        $module = 'xim';
        $action = 'doThis';
        $roles = array('Admin', 'ESS', 'Supervisor');
        
        
        $doctrineCollection = new Doctrine_Collection('ScreenPermission');
        $screenPermission1 = new ScreenPermission();
        $screenPermission1->fromArray(array('id' => 1, 'user_role_id' => 1, 'screen_id' => 1, 
                                           'can_read' => 0, 'can_create' => 0, 
                                           'can_update'=> 0, 'can_delete'=> 0));
        $screenPermission2 = new ScreenPermission();
        $screenPermission2->fromArray(array('id' => 1, 'user_role_id' => 1, 'screen_id' => 1, 
                                           'can_read' => 0, 'can_create' => 1, 
                                           'can_update'=> 0, 'can_delete'=> 0));
        
        $screenPermission3 = new ScreenPermission();
        $screenPermission3->fromArray(array('id' => 1, 'user_role_id' => 1, 'screen_id' => 1, 
                                           'can_read' => 0, 'can_create' => 1, 
                                           'can_update'=> 0, 'can_delete'=> 1));
        
        $screenPermissions = array($screenPermission1, $screenPermission2, $screenPermission3);
        $doctrineCollection->setData($screenPermissions);
        
        $mockDao = $this->getMock('ScreenPermissionDao', array('getScreenPermissions'));        
        $mockDao->expects($this->once())
                ->method('getScreenPermissions')
                ->with($module, $action, $roles)
                ->will($this->returnValue($doctrineCollection));
        
        $this->service->setScreenPermissionDao($mockDao);
        
        $permissions = $this->service->getScreenPermissions($module, $action, $roles);
        
        $this->assertTrue($permissions instanceof ResourcePermission);
        $this->verifyPermissions($permissions, false, true, false, true);         
    }
    
    protected function verifyPermissions(ResourcePermission $permission, $read, $create, $update, $delete) {
        $this->assertEquals($read, $permission->canRead());
        $this->assertEquals($create, $permission->canCreate());
        $this->assertEquals($update, $permission->canUpdate());
        $this->assertEquals($delete, $permission->canDelete());        
    }    
}

