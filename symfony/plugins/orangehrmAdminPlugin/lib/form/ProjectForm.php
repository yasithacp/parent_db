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

class ProjectForm extends BaseForm {

	private $customerService;
	public $projectId;
	public $numberOfProjectAdmins = 5;
	public $edited = false;

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

		$this->projectId = $this->getOption('projectId');

		$this->setWidgets(array(
		    'projectId' => new sfWidgetFormInputHidden(),
		    'customerId' => new sfWidgetFormInputHidden(),
		    'customerName' => new sfWidgetFormInputText(),
		    'projectName' => new sfWidgetFormInputText(),
		    'projectAdminList' => new sfWidgetFormInputHidden(),
		    'description' => new sfWidgetFormTextArea(),
		));

		for ($i = 1; $i <= $this->numberOfProjectAdmins; $i++) {
			$this->setWidget('projectAdmin_' . $i, new sfWidgetFormInputText());
		}

		$this->setValidators(array(
		    'projectId' => new sfValidatorNumber(array('required' => false)),
		    'customerId' => new sfValidatorNumber(array('required' => true)),
		    'customerName' => new sfValidatorString(array('required' => true, 'max_length' => 52, 'trim'=>true)),
		    'projectName' => new sfValidatorString(array('required' => true, 'max_length' => 52, 'trim'=>true)),
		    'projectAdminList' => new sfValidatorString(array('required' => false)),
		    'description' => new sfValidatorString(array('required' => false, 'max_length' => 256)),
		));

		for ($i = 1; $i <= $this->numberOfProjectAdmins; $i++) {
			$this->setValidator('projectAdmin_' . $i, new sfValidatorString(array('required' => false, 'max_length' => 100)));
		}

		$this->widgetSchema->setNameFormat('addProject[%s]');

		if ($this->projectId != null) {
			$this->setDefaultValues($this->projectId);
		}
	}

	private function setDefaultValues($projectId) {

		$project = $this->getProjectService()->getProjectById($this->projectId);
		$this->setDefault('projectId', $projectId);
		$this->setDefault('customerId', $project->getCustomer()->getCustomerId());
		$this->setDefault('customerName', $project->getCustomer()->getName());
		$this->setDefault('projectName', $project->getName());
		$this->setDefault('description', $project->getDescription());

		$admins = $project->getProjectAdmin();
		$this->setDefault('projectAdmin_1', $admins[0]->getEmployee()->getFullName());
		for ($i = 1; $i <= count($admins); $i++) {
			$this->setDefault('projectAdmin_' . $i, $admins[$i - 1]->getEmployee()->getFullName());
		}
		$this->setDefault('projectAdminList', count($admins));
	}

	public function save() {

		$id = $this->getValue('projectId');
		if (empty($id)) {

			$project = new Project();
			$projectAdminsArray = $this->getValue('projectAdminList');
			$projectAdmins = explode(",", $projectAdminsArray);
			$projectId = $this->saveProject($project);
			$this->saveProjectAdmins($projectAdmins, $projectId);
		} else {
			$this->edited = true;
			$project = $this->getProjectService()->getProjectById($id);
			$projectId = $this->saveProject($project);
			$projectAdmins = explode(",", $this->getValue('projectAdminList'));
			$existingProjectAdmins = $project->getProjectAdmin();
			$idList = array();
			if ($existingProjectAdmins[0]->getEmpNumber() != "") {
				foreach ($existingProjectAdmins as $existingProjectAdmin) {
					$id = $existingProjectAdmin->getEmpNumber();
					if (!in_array($id, $projectAdmins)) {
						$existingProjectAdmin->delete();
					} else {
						$idList[] = $id;
					}
				}
			}

			$this->resultArray = array();

			$adminList = array_diff($projectAdmins, $idList);
			$newList = array();
			foreach ($adminList as $admin) {
				$newList[] = $admin;
			}
			$projectAdmins = $newList;
			$this->saveProjectAdmins($projectAdmins, $project->getProjectId());
		}
		return $project->getProjectId();
	}

	protected function saveProjectAdmins($projectAdmins, $projectId) {

		if ($projectAdmins[0] != null) {
			for ($i = 0; $i < count($projectAdmins); $i++) {
				$projectAdmin = new ProjectAdmin();
				$projectAdmin->setProjectId($projectId);
				$projectAdmin->setEmpNumber($projectAdmins[$i]);
				$projectAdmin->save();
			}
		}
	}

	protected function saveProject($project) {

		$project->setCustomerId($this->getValue('customerId'));
		$project->setName(trim($this->getValue('projectName')));
		$project->setDescription($this->getValue('description'));
		$project->setIsDeleted(Project::ACTIVE_PROJECT);
		$project->save();
		return $project->getProjectId();
	}

	protected function getCustomerList() {

		$list = array("" => "-- " . __('Select') . " --");
		$customerList = $this->getCustomerService()->getAllCustomers();
		foreach ($customerList as $customer) {

			$list[$customer->getCustomerId()] = $customer->getName();
		}
		return $list;
	}

	public function getEmployeeListAsJson() {

		$jsonArray = array();
		$employeeService = new EmployeeService();
		$employeeService->setEmployeeDao(new EmployeeDao());

		$employeeList = $employeeService->getEmployeeList('empNumber', 'ASC');
		$employeeUnique = array();
		foreach ($employeeList as $employee) {

			if (!isset($employeeUnique[$employee->getEmpNumber()])) {

				$name = $employee->getFirstName() . " " . $employee->getMiddleName();
				$name = trim(trim($name) . " " . $employee->getLastName());

				$employeeUnique[$employee->getEmpNumber()] = $name;
				$jsonArray[] = array('name' => $name, 'id' => $employee->getEmpNumber());
			}
		}

		$jsonString = json_encode($jsonArray);

		return $jsonString;
	}

	public function getCustomerListAsJson() {

		$jsonArray = array();

		$customerList = $this->getCustomerService()->getAllCustomers(true);


		foreach ($customerList as $customer) {

			$jsonArray[] = array('name' => $customer->getName(), 'id' => $customer->getCustomerId());
		}

		$jsonString = json_encode($jsonArray);

		return $jsonString;
	}

	public function getActivityListAsJson($projectId) {

		$jsonArray = array();

		if (!empty($projectId)) {

			$activityList = $this->getProjectService()->getActivityListByProjectId($projectId);

			foreach ($activityList as $activity) {
				$jsonArray[] = array('name' => $activity->getName(), 'id' => $activity->getActivityId());
			}

			$jsonString = json_encode($jsonArray);
		}
		return $jsonString;
	}

	public function getCustomerProjectListAsJson() {

		$jsonArray = array();

		$projectList = $this->getProjectService()->getAllProjects();


		foreach ($projectList as $project) {
			if ($this->projectId != $project->getProjectId()) {
				$jsonArray[] = array('name' => $project->getCustomer()->getName() . " - ##" . $project->getName(), 'id' => $project->getProjectId());
			}
		}

		$jsonString = json_encode($jsonArray);

		return $jsonString;
	}

}

?>
