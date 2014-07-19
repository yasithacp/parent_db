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
class WorkShiftServiceTest extends PHPUnit_Framework_TestCase {
	
	private $workShiftService;
	private $fixture;

	/**
	 * Set up method
	 */
	protected function setUp() {
		$this->workShiftService = new WorkShiftService();
		$this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/WorkShiftDao.yml';
		TestDataService::populate($this->fixture);
	}
	
	public function testGetWorkShiftList() {

		$workShiftList = TestDataService::loadObjectList('WorkShift', $this->fixture, 'WorkShift');

		$workShiftDao = $this->getMock('WorkShiftDao');
		$workShiftDao->expects($this->once())
			->method('getWorkShiftList')
			->will($this->returnValue($workShiftList));

		$this->workShiftService->setWorkShiftDao($workShiftDao);

		$result = $this->workShiftService->getWorkShiftList();
		$this->assertEquals($result, $workShiftList);
	}
	
	public function testGetWorkShiftById() {

		$workShiftList = TestDataService::loadObjectList('WorkShift', $this->fixture, 'WorkShift');

		$workShiftDao = $this->getMock('WorkShiftDao');
		$workShiftDao->expects($this->once())
			->method('getWorkShiftById')
			->with(1)
			->will($this->returnValue($workShiftList[0]));

		$this->workShiftService->setWorkShiftDao($workShiftDao);

		$result = $this->workShiftService->getWorkShiftById(1);
		$this->assertEquals($result, $workShiftList[0]);
	}
	
	public function testGetWorkShiftEmployeeListById() {

		$workShiftList = TestDataService::loadObjectList('EmployeeWorkShift', $this->fixture, 'EmployeeWorkShift');

		$workShiftDao = $this->getMock('WorkShiftDao');
		$workShiftDao->expects($this->once())
			->method('getWorkShiftEmployeeListById')
			->with(1)
			->will($this->returnValue($workShiftList));

		$this->workShiftService->setWorkShiftDao($workShiftDao);

		$result = $this->workShiftService->getWorkShiftEmployeeListById(1);
		$this->assertEquals($result, $workShiftList);
	}
	
	public function testGetWorkShiftEmployeeList() {

		$workShiftList = TestDataService::loadObjectList('EmployeeWorkShift', $this->fixture, 'EmployeeWorkShift');

		$workShiftDao = $this->getMock('WorkShiftDao');
		$workShiftDao->expects($this->once())
			->method('getWorkShiftEmployeeList')
			->will($this->returnValue($workShiftList));

		$this->workShiftService->setWorkShiftDao($workShiftDao);

		$result = $this->workShiftService->getWorkShiftEmployeeList();
		$this->assertEquals($result, $workShiftList);
	}
	
	public function testUpdateWorkShift() {

		$workShiftList = TestDataService::loadObjectList('WorkShift', $this->fixture, 'WorkShift');

		$workShiftDao = $this->getMock('WorkShiftDao');
		$workShiftDao->expects($this->once())
			->method('updateWorkShift')
			->with($workShiftList[0])
			->will($this->returnValue(true));

		$this->workShiftService->setWorkShiftDao($workShiftDao);

		$result = $this->workShiftService->updateWorkShift($workShiftList[0]);
		$this->assertTrue($result);
	}
}

?>
