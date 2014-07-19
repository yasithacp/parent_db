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
class EmploymentStatusServiceTest extends PHPUnit_Framework_TestCase {
	
	private $empStatService;
	private $fixture;

	/**
	 * Set up method
	 */
	protected function setUp() {
		$this->empStatService = new EmploymentStatusService();
		$this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/EmploymentStatusDao.yml';
		TestDataService::populate($this->fixture);
	}
	
	public function testGetEmploymentStatusList() {

		$empStatusList = TestDataService::loadObjectList('EmploymentStatus', $this->fixture, 'EmploymentStatus');

		$empStatusDao = $this->getMock('EmploymentStatusDao');
		$empStatusDao->expects($this->once())
			->method('getEmploymentStatusList')
			->will($this->returnValue($empStatusList));

		$this->empStatService->setEmploymentStatusDao($empStatusDao);

		$result = $this->empStatService->getEmploymentStatusList();
		$this->assertEquals($result, $empStatusList);
	}
	
	public function testGetEmploymentStatusById() {

		$empStatusList = TestDataService::loadObjectList('EmploymentStatus', $this->fixture, 'EmploymentStatus');

		$empStatusDao = $this->getMock('EmploymentStatusDao');
		$empStatusDao->expects($this->once())
			->method('getEmploymentStatusById')
			->with(1)
			->will($this->returnValue($empStatusList[0]));

		$this->empStatService->setEmploymentStatusDao($empStatusDao);

		$result = $this->empStatService->getEmploymentStatusById(1);
		$this->assertEquals($result, $empStatusList[0]);
	}
}

?>
