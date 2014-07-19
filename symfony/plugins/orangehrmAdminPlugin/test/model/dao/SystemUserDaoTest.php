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

require_once sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

/**
 * @group Admin
 */
class SystemUserDaoTest extends PHPUnit_Framework_TestCase {
    
        private $systemUserDao;
	protected $fixture;

	/**
	 * Set up method
	 */
	protected function setUp() {

		$this->systemUserDao = new SystemUserDao();
		$this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/SystemUser.yml';
		TestDataService::populate($this->fixture);
	}

	public function testSaveSystemUser() {
                $systemUser =   new SystemUser();
                $systemUser->setUserRoleId(1);
                $systemUser->setEmpNumber(2);
                $systemUser->setUserName('orangehrm');
                $systemUser->setUserPassword('orangehrm');
                
                $this->systemUserDao->saveSystemUser( $systemUser );
                
                $saveUser =   Doctrine :: getTable('SystemUser')->find($systemUser->getId());
		$this->assertEquals( $saveUser->getUserName(),'orangehrm');
                $this->assertEquals( $saveUser->getUserPassword(),'orangehrm');
	}
        
        /**
         * @expectedException DaoException
         */
        public function testSaveSystemUserWithExistingUserName() {
                $systemUser =   new SystemUser();
                $systemUser->setUserRoleId(1);
                $systemUser->setEmpNumber(2);
                $systemUser->setUserName('samantha');
                $systemUser->setUserPassword('orangehrm');
                
                $this->systemUserDao->saveSystemUser( $systemUser );
                 
	}
        
        public function testIsExistingSystemUserForNonEsistingUser(){
            $result = $this->systemUserDao->isExistingSystemUser( 'google' );
            $this->assertFalse( $result);
        }
    
        public function testIsExistingSystemUserForEsistingUser(){
            $result = $this->systemUserDao->isExistingSystemUser( 'Samantha' );
            $this->assertTrue( $result instanceof SystemUser);
        }
        
        public function testGetSystemUser(){
           
                
            $result = $this->systemUserDao->getSystemUser( 1 );
           
            $this->assertEquals( $result->getUserName(),'samantha');
            $this->assertEquals( $result->getUserPassword(),'samantha');
        }
        
        
        public function testGetSystemUserForNonExistingId(){
            $result = $this->systemUserDao->getSystemUser( 100 );
            $this->assertFalse( $result);
        }
        
        public function testGetSystemUsers(){
            $result = $this->systemUserDao->getSystemUsers(  );
            $this->assertEquals(3, count(  $result));
        }
        
        public function testDeleteSystemUsers(){
            $this->systemUserDao->deleteSystemUsers( array(1,2,3));
            $result = $this->systemUserDao->getSystemUsers(  );
            $this->assertEquals(0, count(  $result));
        }
        
        public function testGetAssignableUserRoles(){
            $result = $this->systemUserDao->getAssignableUserRoles();
            $this->assertEquals($result[0]->getName(),'Admin');
            $this->assertEquals($result[1]->getName(),'ESS');
            $this->assertEquals(2, count(  $result));
        }
        
        public function testGetAdminUserCount() {
            
            $this->assertEquals(1, $this->systemUserDao->getAdminUserCount());
            $this->assertEquals(2, $this->systemUserDao->getAdminUserCount(false));
            $this->assertEquals(2, $this->systemUserDao->getAdminUserCount(true, false));
            $this->assertEquals(3, $this->systemUserDao->getAdminUserCount(false, false));
            
        }
        
        public function testUpdatePassword() {
            
            $this->assertEquals(1, $this->systemUserDao->updatePassword(1, 'samantha2'));
            
            $userObject = TestDataService::fetchObject('SystemUser', 1);
            
            $this->assertEquals('samantha2', $userObject->getUserPassword());
            
        }
        
}
?>
