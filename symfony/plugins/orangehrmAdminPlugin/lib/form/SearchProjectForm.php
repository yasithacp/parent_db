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

class SearchProjectForm extends BaseForm {

	private $customerService;
	private $projectService;
	private $userObj;

	public function getProjectService() {
		if (is_null($this->projectService)) {
			$this->projectService = new ProjectService();
			$this->projectService->setProjectDao(new ProjectDao());
		}
		return $this->projectService;
	}

	public function getCustomerService() {
		if (is_null($this->customerService)) {
			$this->customerService = new CustomerService();
			$this->customerService->setCustomerDao(new CustomerDao());
		}
		return $this->customerService;
	}

	public function configure() {

		$this->userObj = sfContext::getInstance()->getUser()->getAttribute('user');

		$this->setWidgets(array(
		    'customer' => new sfWidgetFormInputText(),
		    'project' => new sfWidgetFormInputText(),
		    'projectAdmin' => new sfWidgetFormInputText()
		));

		$this->setValidators(array(
		    'customer' => new sfValidatorString(array('required' => false, 'max_length' => 100)),
		    'project' => new sfValidatorString(array('required' => false, 'max_length' => 100)),
		    'projectAdmin' => new sfValidatorString(array('required' => false, 'max_length' => 100))
		));

		$this->widgetSchema->setNameFormat('searchProject[%s]');
	}

	public function setDefaultDataToWidgets($searchClues) {
		$this->setDefault('customer', $searchClues['customer']);
		$this->setDefault('project', $searchClues['project']);
		$this->setDefault('projectAdmin', $searchClues['projectAdmin']);
	}

	public function getProjectAdminListAsJson() {

		$jsonArray = array();
		$employeeService = new EmployeeService();
		$employeeService->setEmployeeDao(new EmployeeDao());

		$employeeList = $employeeService->getEmployeeList();

		foreach ($employeeList as $employee) {
			$jsonArray[] = array('name' => $employee->getFullName(), 'id' => $employee->getEmpNumber());
		}

		$jsonString = json_encode($jsonArray);

		return $jsonString;
	}

	public function getCustomerListAsJson() {

		$allowedProjectList = $this->userObj->getAllowedProjectList();
		$allowedCustomerList = array();
		foreach ($allowedProjectList as $projectId) {
			$project = $this->getProjectService()->getProjectById($projectId);
			$allowedCustomerList[] = $project->getCustomerId();
		}
		$jsonArray = array();
		$customerList = $this->getCustomerService()->getAllCustomers();		
		$allowedCustomers = array();
		foreach ($customerList as $customer) {
			if (in_array($customer->getCustomerId(), $allowedCustomerList)) {
				$allowedCustomers[] = $customer;
			}
		}		
		foreach ($allowedCustomers as $customer) {
			$jsonArray[] = array('name' => $customer->getName(), 'id' => $customer->getCustomerId());
		}
		$jsonString = json_encode($jsonArray);
		return $jsonString;
	}

	public function getProjectListAsJson() {

		$allowedProjectList = $this->userObj->getAllowedProjectList();
		$jsonArray = array();
		$projectList = $this->getProjectService()->getAllProjects();

		$allowedProjets = array();
		foreach ($projectList as $project) {
			if (in_array($project->getProjectId(), $allowedProjectList)) {
				$allowedProjets[] = $project;
			}
		}

		foreach ($allowedProjets as $project) {
			$jsonArray[] = array('name' => $project->getName(), 'id' => $project->getProjectId());
		}
		$jsonString = json_encode($jsonArray);
		return $jsonString;
	}

}

