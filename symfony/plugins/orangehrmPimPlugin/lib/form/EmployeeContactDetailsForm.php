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

class EmployeeConactDetailsForm extends sfForm {

    private $countryService;
    private $employeeService;
    private $widgets = array();
    public $fullName;
    public $empNumber;

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

        $countries = $this->getCountryList();
        $states = $this->getStatesList();
        $this->empNumber = $this->getOption('empNumber');
        $employee = $this->getEmployeeService()->getEmployee($this->empNumber);
        $this->fullName = $employee->getFullName();

        //creating widgets
        $this->widgets['empNumber'] = new sfWidgetFormInputHidden();
        $this->widgets['country'] = new sfWidgetFormSelect(array('choices' => $countries));
        $this->widgets['state'] = new sfWidgetFormSelect(array('choices' => $states));
        $this->widgets['street1'] = new sfWidgetFormInput();
        $this->widgets['street2'] = new sfWidgetFormInput();
        $this->widgets['city'] = new sfWidgetFormInput();
        $this->widgets['province'] = new sfWidgetFormInput();
        $this->widgets['emp_zipcode'] = new sfWidgetFormInput();
        $this->widgets['emp_hm_telephone'] = new sfWidgetFormInput();
        $this->widgets['emp_mobile'] = new sfWidgetFormInput();
        $this->widgets['emp_work_telephone'] = new sfWidgetFormInput();
        $this->widgets['emp_work_email'] = new sfWidgetFormInput();
        $this->widgets['emp_oth_email'] = new sfWidgetFormInput();

        //setting the default values
        $this->widgets['empNumber']->setDefault($employee->empNumber);
        $this->widgets['country']->setDefault($employee->country);
        $this->widgets['state']->setDefault($employee->province);
        $this->widgets['street1']->setDefault($employee->street1);
        $this->widgets['street2']->setDefault($employee->street2);
        $this->widgets['city']->setDefault($employee->city);
        $this->widgets['province']->setDefault($employee->province);
        $this->widgets['emp_zipcode']->setDefault($employee->emp_zipcode);
        $this->widgets['emp_hm_telephone']->setDefault($employee->emp_hm_telephone);
        $this->widgets['emp_mobile']->setDefault($employee->emp_mobile);
        $this->widgets['emp_work_telephone']->setDefault($employee->emp_work_telephone);
        $this->widgets['emp_work_email']->setDefault($employee->emp_work_email);
        $this->widgets['emp_oth_email']->setDefault($employee->emp_oth_email);
        
        $this->setWidgets($this->widgets);

        //setting validators
        $this->setValidators(array(
                'empNumber' => new sfValidatorString(array('required' => true)),
                'country' => new sfValidatorString(array('required' => false)),
                'state' => new sfValidatorString(array('required' => false)),
                'street1' => new sfValidatorString(array('required' => false)),
                'street2' => new sfValidatorString(array('required' => false)),
                'city' => new sfValidatorString(array('required' => false)),
                'province' => new sfValidatorString(array('required' => false)),
                'emp_zipcode' => new sfValidatorString(array('required' => false)),
                'emp_hm_telephone' => new sfValidatorString(array('required' => false)),
                'emp_mobile' => new sfValidatorString(array('required' => false)),
                'emp_work_telephone' => new sfValidatorString(array('required' => false)),
                'emp_work_email' => new sfValidatorEmail(array('required' => false)),
                'emp_oth_email' => new sfValidatorEmail(array('required' => false)),
        ));

        $this->widgetSchema->setNameFormat('contact[%s]');
        
        // set up your post validator method
        $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array(
            'callback' => array($this, 'postValidation')
          ))
        );
    }
    
    public function postValidation($validator, $values) {

        $emails = $this->getEmailList();
        
        $errorList = array();
        $emailList = array();
        foreach ($emails as $email) {
            if($email['empNo'] == $this->empNumber) {
                continue;
            }
            if($email['workEmail']){
                $emailList[] = $email['workEmail'];
            }
            if($email['othEmail']){
                $emailList[] = $email['othEmail'];
            }
        }
            if($values['emp_work_email'] !="" && $values['emp_oth_email'] != "") {
                if($values['emp_work_email'] == $values['emp_oth_email']) {
                    $errorList['emp_oth_email'] = new sfValidatorError($validator, __("This email already exists"));
                }
            }
            if (in_array($values['emp_work_email'], $emailList)) {
                $errorList['emp_work_email'] = new sfValidatorError($validator, __("This email already exists"));
            }
            if (in_array($values['emp_oth_email'], $emailList)) {
                $errorList['emp_oth_email'] = new sfValidatorError($validator, __("This email already exists"));
            }
            if (count($errorList) > 0) {

                throw new sfValidatorErrorSchema($validator, $errorList);
            }
        return $values;
        
    }

    /**
     * Get Employee object
     */
    public function getEmployee() {
        $employee = new Employee();
        $employee->empNumber = $this->getValue('empNumber');
        $employee->street1 = $this->getValue('street1');
        $employee->street2 = $this->getValue('street2');
        $employee->city = $this->getValue('city');
        $employee->country = $this->getValue('country');

        $province = $this->getValue('province');
        if($employee->country == "US") {
            $province = $this->getValue('state');
        }

        $employee->province = $province;
        $employee->emp_zipcode = $this->getValue('emp_zipcode');
        $employee->emp_hm_telephone = $this->getValue('emp_hm_telephone');
        $employee->emp_mobile = $this->getValue('emp_mobile');
        $employee->emp_work_telephone = $this->getValue('emp_work_telephone');
        $employee->emp_work_email = $this->getValue('emp_work_email');
        $employee->emp_oth_email = $this->getValue('emp_oth_email');

        return $employee;
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
        $list = array(0 => "-- " . __('Select') . " --");
        $countries = $this->getCountryService()->getCountryList();
        foreach($countries as $country) {
            $list[$country->cou_code] = $country->cou_name;
        }
        return $list;
    }

    /**
     * Returns States List
     * @return array
     */
    private function getStatesList() {
        $list = array("" => "-- " . __('Select') . " --");
        $states = $this->getCountryService()->getProvinceList();
        foreach($states as $state) {
            $list[$state->province_code] = $state->province_name;
        }
        return $list;
    }
    
    /**
     * Returns email List
     * @return array
     */
    public function getEmailList() {
        $list = array();
        $emailList = $this->getEmployeeService()->getEmailList();
        foreach($emailList as $k=>$email) {
            $list[] = array('empNo' => $email['empNumber'], 'workEmail' => $email['emp_work_email'], 'othEmail' => $email['emp_oth_email']);

        }
        return $list;
    }
}
?>