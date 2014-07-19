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

class PayGradeCurrencyForm extends BaseForm {
	
	private $payGradeService;
	public $payGradeId;

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
		    'currencyId' => new sfWidgetFormInputHidden(),
		    'payGradeId' => new sfWidgetFormInputHidden(),
		    'currencyName' => new sfWidgetFormInputText(),
		    'minSalary' => new sfWidgetFormInputText(),
		    'maxSalary' => new sfWidgetFormInputText(),
		));

		$this->setValidators(array(
		    'currencyId' => new sfValidatorString(array('required' => false)),
		    'payGradeId' => new sfValidatorNumber(array('required' => false)),
		    'currencyName' => new sfValidatorString(array('required' => true)),
		    'minSalary' => new sfValidatorNumber(array('required' => false)),
		    'maxSalary' => new sfValidatorNumber(array('required' => false)),
		));

		$this->widgetSchema->setNameFormat('payGradeCurrency[%s]');		
	}
	
	public function save(){
		
		$currencyId = $this->getValue('currencyId');
		$currencyName = $this->getValue('currencyName');
		$temp = explode(" - ", trim($currencyName));
		
		if(!empty ($currencyId)){
			$currency = $this->getPayGradeService()->getCurrencyByCurrencyIdAndPayGradeId($currencyId, $this->payGradeId);
		} else {
			$currency = new PayGradeCurrency();
		}
		
		$currency->setPayGradeId($this->payGradeId);
		$currency->setCurrencyId($temp[0]);
		$currency->setMinSalary(sprintf("%01.2f", $this->getValue('minSalary')));
		$currency->setMaxSalary(sprintf("%01.2f", $this->getValue('maxSalary')));
		$currency->save();
		return $this->payGradeId;
	}
	
}

?>