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

class CustomerService extends BaseService {

	private $customerDao;

	/**
	 * Construct
	 */
	public function __construct() {
		$this->customerDao = new CustomerDao();
	}

	/**
	 *
	 * @return <type>
	 */
	public function getCustomerDao() {
		return $this->customerDao;
	}

	/**
	 *
	 * @param CustomerDao $customerDao 
	 */
	public function setCustomerDao(CustomerDao $customerDao) {
		$this->customerDao = $customerDao;
	}

	/**
	 * Get Customer List
	 * 
	 * Get Customer List with pagination.
	 * 
	 * @param type $noOfRecords
	 * @param type $offset
	 * @param type $sortField
	 * @param type $sortOrder
	 * @param type $activeOnly
	 * @return type 
	 */
	public function getCustomerList($limit=50, $offset=0, $sortField='name', $sortOrder='ASC', $activeOnly = true) {
		return $this->customerDao->getCustomerList($limit, $offset, $sortField, $sortOrder, $activeOnly);
	}

	/**
	 * Get Active customer cout.
	 *
	 * Get the total number of active customers for list component.
	 * 
	 * @param type $activeOnly
	 * @return type 
	 */
	public function getCustomerCount($activeOnly) {
		return $this->customerDao->getCustomerCount($activeOnly);
	}

	/**
	 * Get customer by id
	 * 
	 * @param type $customerId
	 * @return type 
	 */
	public function getCustomerById($customerId) {
		return $this->customerDao->getCustomerById($customerId);
	}

	/**
	 * Delete customer
	 * 
	 * Set customer 'is_deleted' parameter to 1.
	 * 
	 * @param type $customerId
	 * @return type 
	 */
	public function deleteCustomer($customerId) {
		return $this->customerDao->deleteCustomer($customerId);
	}

	/**
	 * 
	 * Get all customer list
	 * 
	 * Get all active customers
	 * 
	 * @param type $activeOnly
	 * @return type 
	 */
	public function getAllCustomers($activeOnly) {
		return $this->customerDao->getAllCustomers($activeOnly);
	}

	/**
	 * Check wheather the customer has any timesheet records
	 * 
	 * @param type $customerId
	 * @return type 
	 */
	public function hasCustomerGotTimesheetItems($customerId) {
		return $this->customerDao->hasCustomerGotTimesheetItems($customerId);
	}

}

?>
