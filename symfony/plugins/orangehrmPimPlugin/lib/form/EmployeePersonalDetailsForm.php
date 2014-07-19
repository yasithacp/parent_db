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

require_once ROOT_PATH . '/lib/common/LocaleUtil.php';

class EmployeePersonalDetailsForm extends BaseForm {

    private $nationalityService;
    private $employeeService;
    private $widgets = array();
    private $gender;
    public $fullName;

    /**
     * Get NationalityService
     * @returns NationalityService
     */
    public function getNationalityService() {
        if(is_null($this->nationalityService)) {
            $this->nationalityService = new NationalityService();
        }
        return $this->nationalityService;
    }

    /**
     * Set NationalityService
     * @param NationalityService $nationalityService
     */
    public function setNationalityService(NationalityService $nationalityService) {
        $this->nationalityService = $nationalityService;
    }

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
        
        $ess = $this->getOption('ESS', false);
        $empNumber = $this->getOption('empNumber');
        $employee = $this->getEmployeeService()->getEmployee($empNumber);
        $this->gender = ($employee->emp_gender != "")?$employee->emp_gender:"";
        $this->fullName = $employee->getFullName();

        //initializing the components
        $this->widgets = array(
            'txtEmpID' => new sfWidgetFormInputHidden(),
            'txtEmpLastName' => new sfWidgetFormInputText(),
            'txtEmpFirstName' => new sfWidgetFormInputText(),
			'txtEmpMiddleName' => new sfWidgetFormInputText(),
            'txtEmpNickName' => new sfWidgetFormInputText(),
            'optGender' => new sfWidgetFormChoice(array('expanded' => true, 'choices'  => array(1 => __("Male"), 2 => __("Female")))),
            'cmbNation' => new sfWidgetFormSelect(array('choices' => $this->getNationalityList())),
            'txtOtherID' => new sfWidgetFormInputText(),
            'cmbMarital' => new sfWidgetFormSelect(array('choices'=>array(0 => "-- " . __('Select') . " --", 'Single' => __('Single'), 'Married' => __('Married'), 'Other' => __('Other')))),
            'chkSmokeFlag' => new sfWidgetFormInputCheckbox(),
            'txtLicExpDate' => new ohrmWidgetDatePickerNew(array(), array('id' => 'personal_txtLicExpDate')),
            'txtMilitarySer' => new sfWidgetFormInputText(),
        );

        //setting default values
        $this->widgets['txtEmpID']->setAttribute('value', $employee->empNumber);
        $this->widgets['txtEmpLastName']->setAttribute('value', $employee->lastName);
        $this->widgets['txtEmpFirstName']->setAttribute('value', $employee->firstName);
        $this->widgets['txtEmpMiddleName']->setAttribute('value', $employee->middleName);
        $this->widgets['txtEmpNickName']->setAttribute('value', $employee->nickName);

        //setting the default selected nation code
        $this->widgets['cmbNation']->setDefault($employee->nation_code);

        //setting default marital status
        $this->widgets['cmbMarital']->setDefault($employee->emp_marital_status);
          
        if($employee->smoker) {
            $this->widgets['chkSmokeFlag']->setAttribute('checked', 'checked');
        }

        $this->widgets['chkSmokeFlag']->setAttribute('value', 1);
        $this->widgets['txtLicExpDate']->setAttribute('value', set_datepicker_date_format($employee->emp_dri_lice_exp_date));
        $this->widgets['txtMilitarySer']->setAttribute('value', $employee->militaryService);
        $this->widgets['optGender']->setDefault($this->gender);
        $this->widgets['txtOtherID']->setAttribute('value', $employee->otherId);
      
        // Widgets for non-ess mode only
            //initializing and setting default values
            $this->widgets['txtEmployeeId'] = new sfWidgetFormInputText();
            $this->widgets['txtEmployeeId']->setAttribute('value', $employee->employeeId);

            $this->widgets['txtNICNo']  = new sfWidgetFormInputText();
            $this->widgets['txtNICNo']->setAttribute('value', $employee->ssn);

            $this->widgets['txtSINNo'] = new sfWidgetFormInputText();
            $this->widgets['txtSINNo']->setAttribute('value', $employee->sin);

            $this->widgets['DOB'] = new ohrmWidgetDatePickerNew(array(), array('id' => 'personal_DOB'));
            $this->widgets['DOB']->setAttribute('value', set_datepicker_date_format($employee->emp_birthday));
            
            $this->widgets['txtLicenNo'] = new sfWidgetFormInputText();
            $this->widgets['txtLicenNo']->setAttribute('value', $employee->licenseNo);
        
        $this->setWidgets($this->widgets);

        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        //setting server side validators
        $this->setValidators(array(
            'txtEmpID' => new sfValidatorString(array('required' => true)),
            'txtEmployeeId' => new sfValidatorString(array('required' => false)),
            'txtEmpFirstName' => new sfValidatorString(array('required' => true, 'max_length' => 30, 'trim' => true),
                   array('required' => 'First Name Empty!', 'max_length' => 'First Name Length exceeded 30 characters')),
            'txtEmpMiddleName' => new sfValidatorString(array('required' => false, 'max_length' => 30, 'trim' => true), array('max_length' => 'Middle Name Length exceeded 30 characters')),
            'txtEmpLastName' => new sfValidatorString(array('required' => true, 'max_length' => 30, 'trim' => true),
                   array('required' => 'Last Name Empty!', 'max_length' => 'Last Name Length exceeded 30 characters')),
            'txtEmpNickName' => new sfValidatorString(array('required' => false, 'trim' => true)),
            'optGender' => new sfValidatorChoice(array('required' => false,
                                                       'choices' => array(Employee::GENDER_MALE, Employee::GENDER_FEMALE),
                                                       'multiple' => false)),
            'cmbNation' => new sfValidatorChoice(array('required' => false, 'choices' => array_keys($this->getNationalityList()))),
            'txtOtherID' => new sfValidatorString(array('required' => false, 'max_length' => 30), array('max_length' => 'Last Name Length exceeded 30 characters')),
            'cmbMarital' => new sfValidatorString(array('required' => false)),
            'chkSmokeFlag' => new sfValidatorString(array('required' => false)),
            'txtLicExpDate' => new ohrmDateValidator(array('date_format'=>$inputDatePattern, 'required'=>false), array('invalid'=>"Date format should be $inputDatePattern")),
            'txtMilitarySer' => new sfValidatorString(array('required' => false))

        ));

            $this->setValidator('txtNICNo', new sfValidatorString(array('required' => false)));
            $this->setValidator('txtSINNo', new sfValidatorString(array('required' => false, 'max_length' => 30), array('max_length' => 'First Name Length exceeded 30 characters')));
            $this->setValidator('txtLicenNo', new sfValidatorString(array('required' => false, 'max_length' => 30), array('max_length' => 'License No length exceeded 30 characters')));
            $this->setValidator('DOB', new ohrmDateValidator(array('date_format'=>$inputDatePattern, 'required'=>false), array('invalid'=>"Date format should be". $inputDatePattern)));

        $this->widgetSchema->setNameFormat('personal[%s]');
    }

    private function getNationalityList() {
        $nationalityService = $this->getNationalityService();
        $nationalities = $nationalityService->getNationalityList();
        $list = array(0 => "-- " . __('Select') . " --");
        
        foreach($nationalities as $nationality) {
            $list[$nationality->getId()] = $nationality->getName();
        }
        return $list;
    }

    /**
     * Get Employee object with values filled using form values
     */
    public function getEmployee() {

        $ess = $this->getOption('ESS', false);

        $employee = new Employee();
        $employee->empNumber = $this->getValue('txtEmpID');
        $employee->firstName = $this->getValue('txtEmpFirstName');
        $employee->middleName = $this->getValue('txtEmpMiddleName');
        $employee->lastName = $this->getValue('txtEmpLastName');
        $employee->nickName = $this->getValue('txtEmpNickName');

        $nation = $this->getValue('cmbNation');
        $employee->nation_code = ($nation != '0') ? $nation : null;
        $employee->otherId = $this->getValue('txtOtherID');

        $employee->emp_marital_status = $this->getValue('cmbMarital');
        $employee->smoker = $this->getValue('chkSmokeFlag');
        
        $gender = $this->getValue('optGender');
        if (!empty($gender)) {
            $employee->emp_gender = $gender;
        }

        $employee->emp_dri_lice_exp_date = $this->getValue('txtLicExpDate');

        $employee->militaryService = $this->getValue('txtMilitarySer');

        if (!$ess) {
            $employee->employeeId = $this->getValue('txtEmployeeId');
            $employee->ssn = $this->getValue('txtNICNo');
            $employee->sin = $this->getValue('txtSINNo');
            $employee->emp_birthday = $this->getValue('DOB');
            $employee->licenseNo = $this->getValue('txtLicenNo');
        }

        return $employee;
    }

}

