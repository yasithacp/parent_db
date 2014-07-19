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

class EmployeeImmigrationDetailsForm extends sfForm {

    public $fullName;
    private $employeeService;
    private $countryService;
    public $empPassports;
    public $countries;
    
    /**
     * Get EmployeeService
     * @returns EmployeeService
     */
    public function getEmployeeService() {
        if(is_null($this->employeeService)) {
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

    public function configure() {
        
        $empNumber = $this->getOption('empNumber');
        $employee = $this->getEmployeeService()->getEmployee($empNumber);
        $this->fullName = $employee->getFullName();
        $this->countries = $this->getCountryList();
        $this->empPassports = $this->getEmployeeService()->getEmployeePassport($empNumber);

        $this->setWidgets(array(
                'emp_number' => new sfWidgetFormInputHidden(array('default' => $empNumber)),
                'seqno' => new sfWidgetFormInputHidden(),
                'type_flag' => new sfWidgetFormChoice(array('expanded' => true, 'choices'  => array(
                    EmpPassport::TYPE_PASSPORT => __('Passport'), EmpPassport::TYPE_VISA => __('Visa')), 'default' => EmpPassport::TYPE_PASSPORT)),
                'country' => new sfWidgetFormSelect(array('choices' => $this->countries)),
                'number' => new sfWidgetFormInputText(),
                'i9_status' => new sfWidgetFormInputText(),
                'passport_issue_date' => new ohrmWidgetDatePickerNew(array(), array('id' => 'immigration_passport_issue_date')),
                'passport_expire_date' => new ohrmWidgetDatePickerNew(array(), array('id' => 'immigration_passport_expire_date')),
                'i9_review_date' => new ohrmWidgetDatePickerNew(array(), array('id' => 'immigration_i9_review_date')),
                'comments' => new sfWidgetFormTextarea(),
        ));

        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $this->setValidators(array(
                'emp_number' => new sfValidatorNumber(array('required' => false)),
                'seqno' => new sfValidatorNumber(array('required' => false)),
                'type_flag' => new sfValidatorChoice(array('required' => true,
                        'choices' => array(EmpPassport::TYPE_PASSPORT, EmpPassport::TYPE_VISA))),
                'country' => new sfValidatorString(array('required' => false)),
                'number' => new sfValidatorString(array('required' => true, 'trim'=>true)),
                'i9_status' => new sfValidatorString(array('required' => false, 'trim'=>true)),
                'passport_issue_date' => new ohrmDateValidator(array('date_format'=>$inputDatePattern, 'required'=>false), array('invalid'=>"Date format should be". $inputDatePattern)),
                'passport_expire_date' => new ohrmDateValidator(array('date_format'=>$inputDatePattern, 'required'=>false), array('invalid'=>"Date format should be". $inputDatePattern)),
                'i9_review_date' => new ohrmDateValidator(array('date_format'=>$inputDatePattern, 'required'=>false), array('invalid'=>"Date format should be". $inputDatePattern)),
                'comments' => new sfValidatorString(array('required' => false))
        ));

        $this->widgetSchema->setNameFormat('immigration[%s]');
    }

    /**
     * Returns Country Service
     * @returns CountryService
     */
    public function getCountryService() {
        if(is_null($this->countryService)) {
            $this->countryService = new CountryService();
        }
        return $this->countryService;
    }

    /**
     * Returns Country List
     * @return array
     */
    private function getCountryList() {
        $list = array("" => "-- " . __('Select') . " --");
        $countries = $this->getCountryService()->getCountryList();
        foreach($countries as $country) {
            $list[$country->cou_code] = $country->cou_name;
        }
        return $list;
    }

    public function populateEmployeePassport() {

        $empPassport = $this->getEmployeeService()->getEmployeePassport($this->getValue('emp_number'), $this->getValue('seqno'));
        
        if(!$empPassport instanceof EmpPassport) {
            $empPassport = new EmpPassport();
        }

        $empPassport->emp_number = $this->getValue('emp_number');
        $empPassport->seqno = $this->getValue('seqno');
        $empPassport->type_flag = $this->getValue('type_flag');

        $country = $this->getValue('country');
        if(!empty($country)) {
            $empPassport->country = $country;
        } else {
            $empPassport->country = null;
        }

        $empPassport->country = $this->getValue('country');
        $empPassport->number = $this->getValue('number');
        $empPassport->i9_status = $this->getValue('i9_status');
        $empPassport->passport_issue_date = $this->getValue('passport_issue_date');
        $empPassport->passport_expire_date = $this->getValue('passport_expire_date');
        $empPassport->i9_review_date = $this->getValue('i9_review_date');
        $empPassport->comments = $this->getValue('comments');

        return $empPassport;
        
    }
}
?>
