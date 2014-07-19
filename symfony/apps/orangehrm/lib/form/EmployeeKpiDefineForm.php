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


/**
 * Form class for employee define kpi list in Performance
 */
class EmployeeKpiDefineForm extends BaseForm {
            
    public function configure() {

		$jobTitle = new JobTitle();
		
		$kpiDefinedJobTitles = $jobTitle->getJobTitlesDefined();
		
		if (empty($kpiDefinedJobTitles)) {
			$choices = array('-1'=> '- Select -');
		} else {
			foreach ($kpiDefinedJobTitles as $key => $val) {
				foreach ($val as $jobTitleId => $jobTitleName) {
					$arrFinal[$jobTitleId] = $jobTitleName;
				}
			}
			$choices = array('-1'=> '- Select -') + $arrFinal;
		}
		
        $this->setWidgets(array(
           	'JobTitle' =>  new sfWidgetFormDoctrineChoice(array('model' => 'JobTitle', 'add_empty' => '- Select -')),
			'JobTitleFrom' => new sfWidgetFormSelect(array('choices' => $choices)),
			'KpiDescription' => new sfWidgetFormTextarea(),
			'MinRate' => new sfWidgetFormInputText(),
			'MaxRate' => new sfWidgetFormInputText(),
			'DefaultScale' => new sfWidgetFormInputCheckbox (),
			'isCopy' => new sfWidgetFormInputHidden(),
			'KpiId' => new sfWidgetFormInputHidden(),
        ));
        
        $this->widgetSchema->setNameFormat('empdefinekpi[%s]');
		
        $this->setValidators(array(
			'JobTitle' =>  new sfValidatorDoctrineChoice(array('model' => 'JobTitle', 'column' => 'jobtit_code ', 'required' => true), array('required' => 'Please select Job Title')),
			'JobTitleFrom' => new sfValidatorString(array('required' => false)),			  
            'KpiDescription' => new sfValidatorString(array('required' => true, 'max_length' => 200), array('required' => 'Please enter KPI description', 'max_length' => 'Please enter KPI description less than 200 characters')),
			'MinRate' => new sfValidatorNumber(array('required' => false)),
			'MaxRate' => new sfValidatorNumber(array('required' => false)), 
			'DefaultScale' => new sfValidatorString(array('required' => false)),
			'isCopy' => new sfValidatorString(array('required' => false)),
			'KpiId' => new sfValidatorString(array('required' => false)),
        ));	
    	$this->validatorSchema->setPostValidator(
      	new sfValidatorCallback(array('callback' => array($this, 'checkMinMaxRates')))
    	);   	
    }
    /**
     * check if the minimum rate is higher than the maximum value
     * @param $validator
     * @param $values
     * @return array
     */
	public function checkMinMaxRates($validator, $values){
		
    	if (($values['MinRate'] > $values['MaxRate']) && (!is_null($values['MaxRate']) && !is_null($values['MinRate']))){
      		throw new sfValidatorError($validator, 'Minimum Scale is higher than Maximum Scale. Please correct the values properly.');   		
    	} else if((is_null($values['MinRate'])) && (!is_null($values['MaxRate']))) {
    		throw new sfValidatorError($validator, 'Minimum Scale is not entered.');
    	} else if((is_null($values['MaxRate'])) && (!is_null($values['MinRate']))) {
    		throw new sfValidatorError($validator, 'Maximum Scale is not entered.');
    	} else if($values['MinRate'] == $values['MaxRate'] && (!is_null($values['MaxRate']) && !is_null($values['MinRate']))) {
    		throw new sfValidatorError($validator, 'Enter a higher Maximum Scale.');
    	//} else if($values['MinRate'] == $values['MaxRate'] || ($values['MinRate'] == 0)) {
    		//throw new sfValidatorError($validator, 'Enter a higher Maximum Scale.');
    	} else {
    		return $values;
    	}
  	}
}

