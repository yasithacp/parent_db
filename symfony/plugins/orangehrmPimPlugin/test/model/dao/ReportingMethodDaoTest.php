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
class ReportingMethodDaoTest extends PHPUnit_Framework_TestCase {

	private $reportingMethodDao;
	protected $fixture;

	/**
	 * Set up method
	 */
	protected function setUp() {

		$this->reportingMethodDao = new ReportingMethodDao();
		$this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmPimPlugin/test/fixtures/ReportingMethodDao.yml';
		TestDataService::populate($this->fixture);
	}

    public function testAddReportingMethod() {
        
        $reportingMethod = new ReportingMethod();
        $reportingMethod->setName('Finance');
        
        $this->reportingMethodDao->saveReportingMethod($reportingMethod);
        
        $savedReportingMethod = TestDataService::fetchLastInsertedRecord('ReportingMethod', 'id');
        
        $this->assertTrue($savedReportingMethod instanceof ReportingMethod);
        $this->assertEquals('Finance', $savedReportingMethod->getName());
        
    }
    
    public function testEditReportingMethod() {
        
        $reportingMethod = TestDataService::fetchObject('ReportingMethod', 3);
        $reportingMethod->setName('Finance HR');
        
        $this->reportingMethodDao->saveReportingMethod($reportingMethod);
        
        $savedReportingMethod = TestDataService::fetchLastInsertedRecord('ReportingMethod', 'id');
        
        $this->assertTrue($savedReportingMethod instanceof ReportingMethod);
        $this->assertEquals('Finance HR', $savedReportingMethod->getName());
        
    }
    
    public function testGetReportingMethodById() {
        
        $reportingMethod = $this->reportingMethodDao->getReportingMethodById(1);
        
        $this->assertTrue($reportingMethod instanceof ReportingMethod);
        $this->assertEquals('Indirect', $reportingMethod->getName());
        
    }
    
    public function testGetReportingMethodList() {
        
        $reportingMethodList = $this->reportingMethodDao->getReportingMethodList();
        
        foreach ($reportingMethodList as $reportingMethod) {
            $this->assertTrue($reportingMethod instanceof ReportingMethod);
        }
        
        $this->assertEquals(3, count($reportingMethodList));        
        
        /* Checking record order */
        $this->assertEquals('Direct', $reportingMethodList[0]->getName());
        $this->assertEquals('Indirect', $reportingMethodList[2]->getName());
        
    }
    
    public function testDeleteReportingMethods() {
        
        $result = $this->reportingMethodDao->deleteReportingMethods(array(1, 2));
        
        $this->assertEquals(2, $result);
        $this->assertEquals(1, count($this->reportingMethodDao->getReportingMethodList()));       
        
    }
    
    public function testDeleteWrongRecord() {
        
        $result = $this->reportingMethodDao->deleteReportingMethods(array(4));
        
        $this->assertEquals(0, $result);
        
    }
    
    public function testIsExistingReportingMethodName() {
        
        $this->assertTrue($this->reportingMethodDao->isExistingReportingMethodName('Indirect'));
        $this->assertTrue($this->reportingMethodDao->isExistingReportingMethodName('INDIRECT'));
        $this->assertTrue($this->reportingMethodDao->isExistingReportingMethodName('indirect'));
        $this->assertTrue($this->reportingMethodDao->isExistingReportingMethodName('  Indirect  '));
        
    }
    
    public function testGetReportingMethodByName() {
        
        $object = $this->reportingMethodDao->getReportingMethodByName('Indirect');
        $this->assertTrue($object instanceof ReportingMethod);
        $this->assertEquals(1, $object->getId());
        
        $object = $this->reportingMethodDao->getReportingMethodByName('INDIRECT');
        $this->assertTrue($object instanceof ReportingMethod);
        $this->assertEquals(1, $object->getId());
        
        $object = $this->reportingMethodDao->getReportingMethodByName('indirect');
        $this->assertTrue($object instanceof ReportingMethod);
        $this->assertEquals(1, $object->getId());

        $object = $this->reportingMethodDao->getReportingMethodByName('  Indirect  ');
        $this->assertTrue($object instanceof ReportingMethod);
        $this->assertEquals(1, $object->getId());
        
        $object = $this->reportingMethodDao->getReportingMethodByName('Supervisor');
        $this->assertFalse($object);        
        
    }      
    
}
