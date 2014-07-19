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

class WorkShiftForm extends BaseForm {

	private $workShiftService;

	public function getWorkShiftService() {
		if (is_null($this->workShiftService)) {
			$this->workShiftService = new WorkShiftService();
			$this->workShiftService->setWorkShiftDao(new WorkShiftDao());
		}
		return $this->workShiftService;
	}

	public function configure() {

		$employeeList = $this->getEmployeeList();
		$this->setWidgets(array(
		    'workShiftId' => new sfWidgetFormInputHidden(),
		    'name' => new sfWidgetFormInputText(),
		    'hours' => new sfWidgetFormInputText(),
		    'availableEmp' => new sfWidgetFormSelectMany(array('choices' => $employeeList)),
		    'assignedEmp' => new sfWidgetFormSelectMany(array('choices' => array())),
		));

		$this->setValidators(array(
		    'workShiftId' => new sfValidatorNumber(array('required' => false)),
		    'name' => new sfValidatorString(array('required' => true, 'max_length' => 52)),
		    'hours' => new sfValidatorNumber(array('required' => true)),
		    'availableEmp' => new sfValidatorPass(),
		    'assignedEmp' => new sfValidatorPass(),
		));

		$this->widgetSchema->setNameFormat('workShift[%s]');
	}

	public function save() {

		$workShiftId = $this->getValue('workShiftId');
		if (empty($workShiftId)) {
			$workShift = new WorkShift();
			$empArray = $this->getValue('assignedEmp');
			$workShift->setName($this->getValue('name'));
			$workShift->setHoursPerDay($this->getValue('hours'));
			$workShift->save();
		} else {
			$workShift = $this->getWorkShiftService()->getWorkShiftById($workShiftId);
			$workShift->setName($this->getValue('name'));
			$workShift->setHoursPerDay($this->getValue('hours'));
			$this->getWorkShiftService()->updateWorkShift($workShift);

			$employees = $this->getValue('assignedEmp');
			$existingEmployees = $workShift->getEmployeeWorkShift();
			$idList = array();
			if ($existingEmployees[0]->getEmpNumber() != "") {
				foreach ($existingEmployees as $existingEmployee) {
					$id = $existingEmployee->getEmpNumber();
					if (!in_array($id, $employees)) {
						$existingEmployee->delete();
					} else {
						$idList[] = $id;
					}
				}
			}

			$this->resultArray = array();

			$employeeList = array_diff($employees, $idList);
			$newList = array();
			foreach ($employeeList as $employee) {
				$newList[] = $employee;
			}
			$empArray = $newList;
		}
		$this->_saveEmployeeWorkShift($workShift->getId(), $empArray);
	}

	private function _saveEmployeeWorkShift($workShiftId, $empArray) {

		for ($i = 0; $i < sizeof($empArray); $i++) {
			$empWorkShift = new EmployeeWorkShift();
			$empWorkShift->setWorkShiftId($workShiftId);
			$empWorkShift->setEmpNumber($empArray[$i]);
			$empWorkShift->save();
		}
	}

	public function getEmployeeList() {

		$temp = array();
		$existWorkShiftEmpList = array();
		$employeeService = new EmployeeService();
		$employeeService->setEmployeeDao(new EmployeeDao());
		$employeeList = $employeeService->getEmployeeList('lastName', 'ASC', true);

		$workShiftEmpList = $this->getWorkShiftService()->getWorkShiftEmployeeList();
		foreach ($workShiftEmpList as $workShiftEmp) {
			$existWorkShiftEmpList[] = $workShiftEmp->emp_number;
		}
		foreach ($employeeList as $employee) {
			if (!in_array($employee->getEmpNumber(), $existWorkShiftEmpList)) {
				$temp[$employee->getEmpNumber()] = $employee->getFullName();
			}
		}
		return $temp;
	}

	public function getEmployeeListAsJson() {

		$jsonArray = array();
		$existWorkShiftEmpList = array();
		$employeeService = new EmployeeService();
		$employeeService->setEmployeeDao(new EmployeeDao());

		$workShiftEmpList = $this->getWorkShiftService()->getWorkShiftEmployeeList();
		foreach ($workShiftEmpList as $workShiftEmp) {
			$existWorkShiftEmpList[] = $workShiftEmp->emp_number;
		}

		$employeeList = $employeeService->getEmployeeList('lastName', 'ASC', true);
		$employeeUnique = array();
		foreach ($employeeList as $employee) {

			if (!isset($employeeUnique[$employee->getEmpNumber()])) {

				$name = $employee->getFullName();

				$employeeUnique[$employee->getEmpNumber()] = $name;
				if (!in_array($employee->getEmpNumber(), $existWorkShiftEmpList)) {
					$jsonArray[] = array('name' => $name, 'id' => $employee->getEmpNumber());
				}
			}
		}
		$jsonString = json_encode($jsonArray);

		return $jsonString;
	}

	public function getWorkShiftListAsJson() {

		$jsonArray = array();
		$workShiftList = $this->getWorkShiftService()->getWorkShiftList();

		foreach ($workShiftList as $workShift) {
			$jsonArray[] = array('name' => $workShift->getName(), 'id' => $workShift->getId());
		}

		$jsonString = json_encode($jsonArray);
		return $jsonString;
	}

}

