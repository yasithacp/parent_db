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
 * Description of UserRoleManagerFactoryTest
 *
 * @group Core
 */
class UserRoleManagerServiceTest extends PHPUnit_Framework_TestCase {
    
    /** @property UserRoleManagerService $service */
    private $service;
    
    /**
     * Set up method
     */
    protected function setUp() {
        $this->service = new UserRoleManagerService();
    }
    
    /**
     * Test the getConfigDao() and setConfigDao() method
     */
    public function testGetUserRoleManagerClassName() {
        $configDao = $this->getMock('ConfigDao', array('getValue'));
        $configDao->expects($this->once())
                ->method('getValue')
                ->with(UserRoleManagerService::KEY_USER_ROLE_MANAGER_CLASS)
                ->will($this->returnValue('TestUserRoleManager'));
        
        $this->service->setConfigDao($configDao);
        $class = $this->service->getUserRoleManagerClassName();
        $this->assertEquals('TestUserRoleManager', $class);
    }
    
    public function testGetUserRoleManagerExistingClass() {
        $configDao = $this->getMock('ConfigDao', array('getValue'));
        $configDao->expects($this->once())
                ->method('getValue')
                ->with(UserRoleManagerService::KEY_USER_ROLE_MANAGER_CLASS)
                ->will($this->returnValue('UnitTestUserRoleManager'));
        
        $authenticationService = $this->getMock('AuthenticationService', array('getLoggedInUserId'));
        $authenticationService->expects($this->once())
                              ->method('getLoggedInUserId')
                              ->will($this->returnValue(211));
        
        $systemUser = new SystemUser();
        $systemUser->setId(211);
        
        $systemUserService = $this->getMock('SystemUserService', array('getSystemUser'));
        $systemUserService->expects($this->once())
                          ->method('getSystemUser')
                          ->will($this->returnValue($systemUser));
        
        $this->service->setConfigDao($configDao);
        $this->service->setAuthenticationService($authenticationService);
        $this->service->setSystemUserService($systemUserService);
        
        $manager = $this->service->getUserRoleManager();
        $this->assertNotNull($manager);
        $this->assertTrue($manager instanceof AbstractUserRoleManager);
        $this->assertTrue($manager instanceof UnitTestUserRoleManager);
        $user = $manager->getUser();
        $this->assertEquals($systemUser, $user);
    }
    
    public function testGetUserRoleManagerInvalidClass() {
        $configDao = $this->getMock('ConfigDao', array('getValue'));
        $configDao->expects($this->once())
                ->method('getValue')
                ->with(UserRoleManagerService::KEY_USER_ROLE_MANAGER_CLASS)
                ->will($this->returnValue('InvalidUserRoleManager'));
        
        $this->service->setConfigDao($configDao);
        
        try {
            $manager = $this->service->getUserRoleManager();
            $this->fail("Should throw exception if user role manager is invalid");
        } catch (ServiceException $e) {
            // expected
        }
    } 
    
    public function testGetUserRoleManagerNonExistingClass() {
        $configDao = $this->getMock('ConfigDao', array('getValue'));
        $configDao->expects($this->once())
                ->method('getValue')
                ->with(UserRoleManagerService::KEY_USER_ROLE_MANAGER_CLASS)
                ->will($this->returnValue('xasdfasfdskfdaManager'));
        
        $this->service->setConfigDao($configDao);
        
        try {
            $manager = $this->service->getUserRoleManager();
            $this->fail("Should throw exception if user role manager class does not exist.");
        } catch (ServiceException $e) {
            // expected
        }
    }
    
}

class InvalidUserRoleManager {
   
}

class UnitTestUserRoleManager extends AbstractUserRoleManager {
    public function getAccessibleEntities($entityType, $operation = null, $returnType = null,
            $rolesToExclude = array(), $rolesToInclude = array()) {
        
    }    
    
    public function getAccessibleModules() {
        
    }
    
    public function isModuleAccessible($module) {
        
    }
    
    public function isScreenAccessible($module, $screen, $field) {
        
    }
    
    public function isFieldAccessible($module, $screen, $field) {
        
    }
    
    protected function getUserRoles(SystemUser $user) {
        
    }    
    public function getScreenPermissions($module, $screen) {
        
    }

    public function areEntitiesAccessible($entityType, $entityIds, $operation = null, $rolesToExclude = array(), $rolesToInclude = array()) {
        
    }

    public function isEntityAccessible($entityType, $entityId, $operation = null, $rolesToExclude = array(), $rolesToInclude = array()) {
        
    }

    public function getAccessibleEntityIds($entityType, $operation = null, $returnType = null, $rolesToExclude = array(), $rolesToInclude = array()) {
        
    }
}
