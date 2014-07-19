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
class CustomerServiceTest extends PHPUnit_Framework_TestCase {
	
	private $customerService;
	private $fixture;

	/**
	 * Set up method
	 */
	protected function setUp() {
		$this->customerService = new CustomerService();
		$this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/ProjectDao.yml';
		TestDataService::populate($this->fixture);
	}
	
	public function testGetCustomerList() {

		$customerList = TestDataService::loadObjectList('Customer', $this->fixture, 'Customer');

		$customerDao = $this->getMock('CustomerDao');
		$customerDao->expects($this->once())
			->method('getCustomerList')
			->with("","","","","")
			->will($this->returnValue($customerList));

		$this->customerService->setCustomerDao($customerDao);

		$result = $this->customerService->getCustomerList("","","","","");
		$this->assertEquals($result, $customerList);
	}
	
	public function testGetCustomerCount() {

		$customerDao = $this->getMock('CustomerDao');
		$customerDao->expects($this->once())
			->method('getCustomerCount')
			->with("")
			->will($this->returnValue(2));

		$this->customerService->setCustomerDao($customerDao);

		$result = $this->customerService->getCustomerCount("");
		$this->assertEquals($result,2);
	}
	
	public function testGetCustomerById() {

		$customerList = TestDataService::loadObjectList('Customer', $this->fixture, 'Customer');

		$customerDao = $this->getMock('CustomerDao');
		$customerDao->expects($this->once())
			->method('getCustomerById')
			->with(1)
			->will($this->returnValue($customerList[0]));

		$this->customerService->setCustomerDao($customerDao);

		$result = $this->customerService->getCustomerById(1);
		$this->assertEquals($result,$customerList[0]);
	}
	
	public function testDeleteCustomer() {

		$customerList = TestDataService::loadObjectList('Customer', $this->fixture, 'Customer');

		$customerDao = $this->getMock('CustomerDao');
		$customerDao->expects($this->once())
			->method('deleteCustomer')
			->with(1)
			->will($this->returnValue(1));

		$this->customerService->setCustomerDao($customerDao);

		$result = $this->customerService->deleteCustomer(1);
		$this->assertEquals($result,1);
	}
	
	public function testGetAllCustomers() {

		$customerList = TestDataService::loadObjectList('Customer', $this->fixture, 'Customer');

		$customerDao = $this->getMock('CustomerDao');
		$customerDao->expects($this->once())
			->method('getAllCustomers')
			->with(false)
			->will($this->returnValue($customerList));

		$this->customerService->setCustomerDao($customerDao);

		$result = $this->customerService->getAllCustomers(false);
		$this->assertEquals($result,$customerList);
	}
	
	public function testHasCustomerGotTimesheetItems() {

		$customerDao = $this->getMock('CustomerDao');
		$customerDao->expects($this->once())
			->method('hasCustomerGotTimesheetItems')
			->with(1)
			->will($this->returnValue(true));

		$this->customerService->setCustomerDao($customerDao);

		$result = $this->customerService->hasCustomerGotTimesheetItems(1);
		$this->assertEquals($result,true);
	}
	
	
}

?>
