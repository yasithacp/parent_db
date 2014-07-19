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
class LicenseDaoTest extends PHPUnit_Framework_TestCase {

	private $licenseDao;
	protected $fixture;

	/**
	 * Set up method
	 */
	protected function setUp() {

		$this->licenseDao = new LicenseDao();
		$this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/LicenseDao.yml';
		TestDataService::populate($this->fixture);
	}

    public function testAddLicense() {
        
        $license = new License();
        $license->setName('Bicycle');
        
        $this->licenseDao->saveLicense($license);
        
        $savedLicense = TestDataService::fetchLastInsertedRecord('License', 'id');
        
        $this->assertTrue($savedLicense instanceof License);
        $this->assertEquals('Bicycle', $savedLicense->getName());
        
    }
    
    public function testEditLicense() {
        
        $license = TestDataService::fetchObject('License', 3);
        $license->setName('Moon Pilot');
        
        $this->licenseDao->saveLicense($license);
        
        $savedLicense = TestDataService::fetchLastInsertedRecord('License', 'id');
        
        $this->assertTrue($savedLicense instanceof License);
        $this->assertEquals('Moon Pilot', $savedLicense->getName());
        
    }
    
    public function testGetLicenseById() {
        
        $license = $this->licenseDao->getLicenseById(1);
        
        $this->assertTrue($license instanceof License);
        $this->assertEquals('Ship Captain', $license->getName());
        
    }
    
    public function testGetLicenseList() {
        
        $licenseList = $this->licenseDao->getLicenseList();
        
        foreach ($licenseList as $license) {
            $this->assertTrue($license instanceof License);
        }
        
        $this->assertEquals(3, count($licenseList));        
        
        /* Checking record order */
        $this->assertEquals('Driving', $licenseList[0]->getName());
        $this->assertEquals('Ship Captain', $licenseList[2]->getName());
        
    }
    
    public function testDeleteLicenses() {
        
        $result = $this->licenseDao->deleteLicenses(array(1, 2));
        
        $this->assertEquals(2, $result);
        $this->assertEquals(1, count($this->licenseDao->getLicenseList()));       
        
    }
    
    public function testDeleteWrongRecord() {
        
        $result = $this->licenseDao->deleteLicenses(array(4));
        
        $this->assertEquals(0, $result);
        
    }
    
    public function testIsExistingLicenseName() {
        
        $this->assertTrue($this->licenseDao->isExistingLicenseName('Ship Captain'));
        $this->assertTrue($this->licenseDao->isExistingLicenseName('SHIP CAPTAIN'));
        $this->assertTrue($this->licenseDao->isExistingLicenseName('ship captain'));
        $this->assertTrue($this->licenseDao->isExistingLicenseName('  Ship Captain  '));
        
    }
    
    public function testGetLicenseByName() {
        
        $object = $this->licenseDao->getLicenseByName('Ship Captain');
        $this->assertTrue($object instanceof License);
        $this->assertEquals(1, $object->getId());
        
        $object = $this->licenseDao->getLicenseByName('SHIP CAPTAIN');
        $this->assertTrue($object instanceof License);
        $this->assertEquals(1, $object->getId());
        
        $object = $this->licenseDao->getLicenseByName('ship captain');
        $this->assertTrue($object instanceof License);
        $this->assertEquals(1, $object->getId());

        $object = $this->licenseDao->getLicenseByName('  Ship Captain  ');
        $this->assertTrue($object instanceof License);
        $this->assertEquals(1, $object->getId());
        
        $object = $this->licenseDao->getLicenseByName('Bike Riding');
        $this->assertFalse($object);        
        
    }        
    
}
