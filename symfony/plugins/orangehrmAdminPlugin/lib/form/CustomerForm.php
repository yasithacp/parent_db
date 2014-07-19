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


class CustomerForm extends BaseForm {

	private $customerService;

	public function getCustomerService() {
		if (is_null($this->customerService)) {
			$this->customerService = new CustomerService();
			$this->customerService->setCustomerDao(new CustomerDao());
		}
		return $this->customerService;
	}

	public function configure() {
		
		$this->customerId = $this->getOption('customerId');
		if (isset($this->customerId)) {
			$customer = $this->getCustomerService()->getCustomerById($this->customerId);
		}

		$this->setWidgets(array(
		    'customerId' => new sfWidgetFormInputHidden(),
		    'customerName' => new sfWidgetFormInputText(),
		    'description' => new sfWidgetFormTextArea(),
		));

		$this->setValidators(array(
		    'customerId' => new sfValidatorNumber(array('required' => false)),
		    'customerName' => new sfValidatorString(array('required' => true, 'max_length' => 52)),
		    'description' => new sfValidatorString(array('required' => false, 'max_length' => 255)),
		));

		$this->widgetSchema->setNameFormat('addCustomer[%s]');

		if (isset($customer) && $customer != null) {

			$this->setDefault('customerName', $customer->getName());
			$this->setDefault('description', $customer->getDescription());
		}
				
	}

	public function save() {

		$this->resultArray = array();
		$customerId = $this->getValue('customerId');
		if ($customerId > 0) {
			$service = $this->getCustomerService();
			$customer = $service->getCustomerById($customerId);
			$this->resultArray['messageType'] = 'success';
			$this->resultArray['message'] = __(TopLevelMessages::UPDATE_SUCCESS);
		} else {
			$customer = new Customer();
			$this->resultArray['messageType'] = 'success';
			$this->resultArray['message'] = __(TopLevelMessages::SAVE_SUCCESS);
		}

		$customer->setName(trim($this->getValue('customerName')));
		$customer->setDescription($this->getValue('description'));
		$customer->save();
		return $this->resultArray;
	}

	public function getCustomerListAsJson() {
		
		$list = array();
		$customerList = $this->getCustomerService()->getAllCustomers();
		foreach ($customerList as $customer) {
			$list[] = array('id' => $customer->getCustomerId(), 'name' => $customer->getName());
		}
		return json_encode($list);
	}

}

?>
