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
 * Form class for add employee action
 */
class EmployeeAddForm extends BaseForm {

    public function configure() {

        // Note: Widget names were kept from old non-symfony version
        $this->setWidgets(array(
            'txtEmployeeId' => new sfWidgetFormInputText(),
            'txtEmpFirstName' => new sfWidgetFormInputText(),
			'txtEmpMiddleName' => new sfWidgetFormInputText(),
            'txtEmpLastName' => new sfWidgetFormInputText(),
            'txtEmpNickName' => new sfWidgetFormInputText(),

            // this parameter is for php file upload
            'MAX_FILE_SIZE' => new sfWidgetFormInputHidden(),
            'photofile' => new sfWidgetFormInputFile()
        ));

        $employeeService = new EmployeeService();

        $this->setDefault('txtEmployeeId', $employeeService->getDefaultEmployeeId());
        $this->setValidators(array(
            'txtEmployeeId' => new sfValidatorString(array('required' => false)),
            'txtEmpFirstName' => new sfValidatorString(array('required' => true),
                   array('required' => 'First Name Empty!')),
            'txtEmpMiddleName' => new sfValidatorString(array('required' => false)),
            'txtEmpLastName' => new sfValidatorString(array('required' => true),
                   array('required' => 'Last Name Empty!')),
            'txtEmpNickName' => new sfValidatorString(array('required' => false)),
            'MAX_FILE_SIZE' => new sfValidatorString(array('required' => true)),
            'photofile' => new sfValidatorFile(array('required' => false)),
        ));
    }

    /**
     * Get employee object with values filled using form values
     */
    public function getEmployee() {

        $employee = new Employee();
        $employee->employeeId = $this->getValue('txtEmployeeId');
        $employee->firstName = $this->getValue('txtEmpFirstName');
        $employee->middleName = $this->getValue('txtEmpMiddleName');
        $employee->lastName = $this->getValue('txtEmpLastName');
        $employee->nickName = $this->getValue('txtEmpNickName');

        return $employee;
    }

}

