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

class SearchLocationForm extends BaseForm {

	private $countryService;

	/**
	 * Returns Country Service
	 * @returns CountryService
	 */
	public function getCountryService() {
		if (is_null($this->countryService)) {
			$this->countryService = new CountryService();
		}
		return $this->countryService;
	}

	public function configure() {

		$this->userObj = sfContext::getInstance()->getUser()->getAttribute('user');
		$countries = $this->getCountryList();

		$this->setWidgets(array(
		    'name' => new sfWidgetFormInputText(),
		    'city' => new sfWidgetFormInputText(),
		    'country' => new sfWidgetFormSelect(array('choices' => $countries)),
		));

		$this->setValidators(array(
		    'name' => new sfValidatorString(array('required' => false, 'max_length' => 100)),
		    'city' => new sfValidatorString(array('required' => false, 'max_length' => 100)),
		    'country' => new sfValidatorString(array('required' => false, 'max_length' => 100)),
		));

		$this->widgetSchema->setNameFormat('searchLocation[%s]');
	}

	public function setDefaultDataToWidgets($searchClues) {
		$this->setDefault('name', $searchClues['name']);
		$this->setDefault('city', $searchClues['city']);
		$this->setDefault('country', $searchClues['country']);
	}

	/**
	 * Returns Country List
	 * @return array
	 */
	private function getCountryList() {
		$list = array("" => "-- " . __('Select') . " --");
		$countries = $this->getCountryService()->getCountryList();
		foreach ($countries as $country) {
			$list[$country->cou_code] = $country->cou_name;
		}
		return $list;
	}

}

?>
