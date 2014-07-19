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
class LocationServiceTest extends PHPUnit_Framework_TestCase {
	
	private $locationService;
	private $fixture;

	/**
	 * Set up method
	 */
	protected function setUp() {
		$this->locationService = new LocationService();
		$this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/LocationDao.yml';
		TestDataService::populate($this->fixture);
	}
	
	public function testgetLocationById() {

		$locationList = TestDataService::loadObjectList('Location', $this->fixture, 'Location');

		$locationDao = $this->getMock('LocationDao');
		$locationDao->expects($this->once())
			->method('getLocationById')
			->with(1)
			->will($this->returnValue($locationList[0]));

		$this->locationService->setLocationDao($locationDao);

		$result = $this->locationService->getLocationById(1);
		$this->assertEquals($result,$locationList[0]);
	}
	
	public function testSearchLocations() {

		$locationList = TestDataService::loadObjectList('Location', $this->fixture, 'Location');
		$srchClues = array(
		    'name' => 'location 1'
		);
		
		$locationDao = $this->getMock('LocationDao');
		$locationDao->expects($this->once())
			->method('searchLocations')
			->with($srchClues)
			->will($this->returnValue($locationList[0]));

		$this->locationService->setLocationDao($locationDao);

		$result = $this->locationService->searchLocations($srchClues);
		$this->assertEquals($result,$locationList[0]);
	}
	
	public function testGetSearchLocationListCount() {

		$locationList = TestDataService::loadObjectList('Location', $this->fixture, 'Location');
		$srchClues = array(
		    'name' => 'location 1'
		);
		
		$locationDao = $this->getMock('LocationDao');
		$locationDao->expects($this->once())
			->method('getSearchLocationListCount')
			->with($srchClues)
			->will($this->returnValue(1));

		$this->locationService->setLocationDao($locationDao);

		$result = $this->locationService->getSearchLocationListCount($srchClues);
		$this->assertEquals($result,1);
	}
	
	public function testGetNumberOfEmplyeesForLocation() {

		$locationList = TestDataService::loadObjectList('Location', $this->fixture, 'Location');

		$locationDao = $this->getMock('LocationDao');
		$locationDao->expects($this->once())
			->method('getNumberOfEmplyeesForLocation')
			->with(1)
			->will($this->returnValue(2));

		$this->locationService->setLocationDao($locationDao);

		$result = $this->locationService->getNumberOfEmplyeesForLocation(1);
		$this->assertEquals($result,2);
	}
	
	public function testGetLocationList() {

		$locationList = TestDataService::loadObjectList('Location', $this->fixture, 'Location');

		$locationDao = $this->getMock('LocationDao');
		$locationDao->expects($this->once())
			->method('getLocationList')
			->will($this->returnValue($locationList));

		$this->locationService->setLocationDao($locationDao);

		$result = $this->locationService->getLocationList();
		$this->assertEquals($result,$locationList);
	}
}
