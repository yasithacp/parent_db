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

class EmployeeLicenseForm extends sfForm {
    
    private $employeeService;
    public $fullName;
    private $widgets = array();
    public $empLicenseList;

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

        $this->empLicenseList = $this->getEmployeeService()->getLicense($empNumber);

        //initializing the components
        $this->widgets = array(
            'emp_number' => new sfWidgetFormInputHidden(),
            'code' => new sfWidgetFormSelect(array('choices' => $this->_getLicenseList())),
            'license_no' => new sfWidgetFormInputText(),
            'date' => new ohrmWidgetDatePickerNew(array(), array('id' => 'license_date')),
            'renewal_date' => new ohrmWidgetDatePickerNew(array(), array('id' => 'license_renewal_date'))
        );

        $this->widgets['emp_number']->setDefault($empNumber);
        $this->setWidgets($this->widgets);

        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        
        $this->setValidator('emp_number', new sfValidatorString(array('required' => false)));
        $this->setValidator('code', new sfValidatorString(array('required' => true,
            'max_length' => 13)));
        $this->setValidator('license_no', new sfValidatorString(array('required' => false,
            'max_length' => 50)));

        $this->setValidator('date', new ohrmDateValidator(
                array('date_format'=>$inputDatePattern, 'required' => false),
                array('invalid'=>'Date format should be'. $inputDatePattern)));

        $this->setValidator('renewal_date', new ohrmDateValidator(
                array('date_format'=>$inputDatePattern, 'required' => false),
                array('invalid'=>'Date format should be'. $inputDatePattern)));

        $this->widgetSchema->setNameFormat('license[%s]');
    }

    private function _getLicenseList() {
        $licenseService = new LicenseService();
        $licenseList = $licenseService->getLicenseList();
        $list = array("" => "-- " . __('Select') . " --");

        foreach($licenseList as $license) {
            $list[$license->getId()] = $license->getName();
        }
        
        // Clear already used license items
        foreach ($this->empLicenseList as $empLicense) {
            if (isset($list[$empLicense->licenseId])) {
                unset($list[$empLicense->licenseId]);
            }
        }
        return $list;
    }
}
?>