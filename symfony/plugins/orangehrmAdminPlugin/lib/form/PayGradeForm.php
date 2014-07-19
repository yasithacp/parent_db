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

class PayGradeForm extends BaseForm {

	private $payGradeId;
	private $payGradeService;

	/**
	 * Get CurrencyService
	 * @returns CurrencyService
	 */
	public function getCurrencyService() {
		if (is_null($this->currencyService)) {
			$this->currencyService = new CurrencyService();
		}
		return $this->currencyService;
	}

	public function getPayGradeService() {
		if (is_null($this->payGradeService)) {
			$this->payGradeService = new PayGradeService();
			$this->payGradeService->setPayGradeDao(new PayGradeDao());
		}
		return $this->payGradeService;
	}

	public function configure() {

		$this->payGradeId = $this->getOption('payGradeId');

		$this->setWidgets(array(
		    'payGradeId' => new sfWidgetFormInputHidden(),
		    'name' => new sfWidgetFormInputText(),
		));

		$this->setValidators(array(
		    'payGradeId' => new sfValidatorNumber(array('required' => false)),
		    'name' => new sfValidatorString(array('required' => true, 'max_length' => 52)),
		));

		$this->widgetSchema->setNameFormat('payGrade[%s]');

		if ($this->payGradeId != null) {
			$this->setDefaultValues($this->payGradeId);
		}
	}

	private function setDefaultValues($payGradeId) {

		$payGrade = $this->getPayGradeService()->getPayGradeById($payGradeId);
		$this->setDefault('payGradeId', $payGradeId);
		$this->setDefault('name', $payGrade->getName());
	}

	public function save() {
		$payGradeId = $this->getValue('payGradeId');

		if (!empty($payGradeId)) {
			$payGrade = $this->getPayGradeService()->getPayGradeById($payGradeId);
		} else {
			$payGrade = new PayGrade();
		}
		$payGrade->setName($this->getValue('name'));
		$payGrade->save();

		return $payGrade->getId();
	}

	public function getCurrencyListAsJson() {
		
		$list = array();
		$currencies = $this->getCurrencyService()->getCurrencyList();
		foreach ($currencies as $currency) {
			$list[] = array('id' => $currency->getCurrencyId(), 'name' => $currency->getCurrencyId()." - ".__($currency->getCurrencyName()));
		}
		return json_encode($list);
	}
	
	public function getPayGradeListAsJson() {
		
		$list = array();
		$payGrades = $this->getPayGradeService()->getPayGradeList();
		foreach ($payGrades as $payGrade) {
			$list[] = array('id' => $payGrade->getId(), 'name' => $payGrade->getName());
		}
		return json_encode($list);
	}
	
	public function getAssignedCurrencyListAsJson($payGradeId) {
		
		$list = array();
		$currencies = $this->getPayGradeService()->getCurrencyListByPayGradeId($payGradeId);
		foreach ($currencies as $currency) {
			$list[] = array('id' => $currency->getCurrencyId(), 'name' => $currency->getCurrencyId()." - ".__($currency->getCurrencyType()->getCurrencyName()));
		}
		return json_encode($list);
	}
}

?>
