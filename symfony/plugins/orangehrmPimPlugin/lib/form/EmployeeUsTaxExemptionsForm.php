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

class EmployeeUsTaxExemptionsForm extends sfForm {

    private $countryService;
    private $employeeService;

    public function configure() {

        $status = array(0 => "-- " . __('Select') . " --", 'S' => __('Single'), 'M' => __('Married'), 'NRA' => __('Non Resident Alien'), 'NA' => __('Not Applicable'));
        $states = $this->getStatesList();
        $empNumber = $this->getOption('empNumber');
        $employee = $this->getEmployeeService()->getEmployee($empNumber);
        $this->fullName = $employee->getFullName();
        $empTaxExemption = $this->getEmployeeService()->getEmployeeTaxExemptions($empNumber);

        //creating widgets
        $this->setWidgets(array(
            'empNumber' => new sfWidgetFormInputHidden(),
            'federalStatus' => new sfWidgetFormSelect(array('choices' => $status)),
            'federalExemptions' => new sfWidgetFormInputText(),
            'state' => new sfWidgetFormSelect(array('choices' => $states)),
            'stateStatus' => new sfWidgetFormSelect(array('choices' => $status)),
            'stateExemptions' => new sfWidgetFormInputText(),
            'unempState' => new sfWidgetFormSelect(array('choices' => $states)),
            'workState' => new sfWidgetFormSelect(array('choices' => $states)),
        ));

        $this->widgetSchema->setNameFormat('tax[%s]');

        //Setting validators
        $this->setValidators(array(
            'empNumber' => new sfValidatorString(array('required' => true)),
            'federalStatus' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($status))),
            'federalExemptions' => new sfValidatorInteger(array('required' => false, 'max' => 99)),
            'state' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($states))),
            'stateStatus' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($status))),
            'stateExemptions' => new sfValidatorInteger(array('required' => false, 'max' => 99)),
            'unempState' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($states))),
            'workState' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($states))),
        ));

            $this->setDefault('empNumber', $empNumber);
            
        if($empTaxExemption != null){
        //setting the default values            
            $this->setDefault('federalStatus', $empTaxExemption->getFederalStatus());
            $this->setDefault('federalExemptions', $empTaxExemption->getFederalExemptions());
            $this->setDefault('state', $empTaxExemption->getState());
            $this->setDefault('stateStatus', $empTaxExemption->getStateStatus());
            $this->setDefault('stateExemptions', $empTaxExemption->getStateExemptions());
            $this->setDefault('unempState', $empTaxExemption->getUnemploymentState());
            $this->setDefault('workState', $empTaxExemption->getWorkState());

        }
    }

    /**
     * Get EmpUsTaxExemption object
     */
    public function getEmpUsTaxExemption() {
        
        $empNumber = $this->getValue('empNumber');
        $empUsTaxExemption = $this->getEmployeeService()->getEmployeeTaxExemptions($empNumber);

            if($empUsTaxExemption == null){
                $empUsTaxExemption = new EmpUsTaxExemption();
                $empUsTaxExemption->empNumber = $this->getValue('empNumber');
            }
            
        $empUsTaxExemption->federalStatus = $this->getValue('federalStatus');
        $empUsTaxExemption->federalExemptions = $this->getValue('federalExemptions');
        $empUsTaxExemption->state = $this->getValue('state');
        $empUsTaxExemption->stateStatus= $this->getValue('stateStatus');
        $empUsTaxExemption->stateExemptions = $this->getValue('stateExemptions');
        $empUsTaxExemption->unemploymentState = $this->getValue('unempState');
        $empUsTaxExemption->workState = $this->getValue('workState');

        return $empUsTaxExemption;
    }

    /**
     * Get EmployeeService
     * @returns EmployeeService
     */
    public function getEmployeeService() {
        if (is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }

    /**
     * Set EmployeeService
     * @param EmployeeService $employeeService
     */
    public function setEmployeeService(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }

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

    /**
     * Returns States List
     * @return array
     */
    private function getStatesList() {
        $list = array("" => "-- " . __('Select') . " --");
        $states = $this->getCountryService()->getProvinceList();
        foreach ($states as $state) {
            $list[$state->province_code] = $state->province_name;
        }
        return $list;
    }

}
