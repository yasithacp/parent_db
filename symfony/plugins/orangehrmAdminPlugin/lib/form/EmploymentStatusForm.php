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

class EmploymentStatusForm extends BaseForm {

	public function getEmploymentStatusService() {
		if (is_null($this->empStatusService)) {
			$this->empStatusService = new EmploymentStatusService();
			$this->empStatusService->setEmploymentStatusDao(new EmploymentStatusDao());
		}
		return $this->empStatusService;
	}

	public function configure() {

		$this->setWidgets(array(
		    'empStatusId' => new sfWidgetFormInputHidden(),
		    'name' => new sfWidgetFormInputText(),
		));

		$this->setValidators(array(
		    'empStatusId' => new sfValidatorNumber(array('required' => false)),
		    'name' => new sfValidatorString(array('required' => true, 'max_length' => 52)),
		));

		$this->widgetSchema->setNameFormat('empStatus[%s]');
		
	}

	public function save() {

		$empStatusId = $this->getValue('empStatusId');
		if (!empty($empStatusId)) {
			$empStatus = $this->getEmploymentStatusService()->getEmploymentStatusById($empStatusId);
		} else {
			$empStatus = new EmploymentStatus();
		}
		$empStatus->setName($this->getValue('name'));
		$empStatus->save();
	}

	public function getEmploymentStatusListAsJson() {

		$list = array();
		$empStatusList = $this->getEmploymentStatusService()->getEmploymentStatusList();
		foreach ($empStatusList as $empStatus) {
			$list[] = array('id' => $empStatus->getId(), 'name' => $empStatus->getName());
		}
		return json_encode($list);
	}

}

?>
