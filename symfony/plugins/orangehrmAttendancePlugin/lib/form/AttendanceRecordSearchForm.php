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

class AttendanceRecordSearchForm extends sfForm {

    public function configure() {

        $date = $this->getOption('date');
        $employeeId = $this->getOption('employeeId');
        $trigger = $this->getOption('trigger');

        $this->setWidgets(array(
            'employeeName' => new sfWidgetFormInputText(array(), array('class' => 'inputFormatHint', 'id' => 'employee')),
            'date' => new sfWidgetFormInputText(array(), array('class' => 'date', 'margin' => '0')),
            'employeeId' => new sfWidgetFormInputHidden(),
        ));

        if ($trigger) {
            
            $this->setDefault('employeeName', $this->getEmployeeName($employeeId));
            $this->setDefault('date', set_datepicker_date_format($date));
       
            } else {
            
            $this->setDefault('employeeName', __('Type for hints').'...');
        }

        $this->widgetSchema->setNameFormat('attendance[%s]');

        $this->setValidators(array(
            'date' => new sfValidatorDate(array(), array('required' => __('Enter Date'))),
            'employeeName' => new sfValidatorString(array(), array('required' => __('Enter Employee Name'))),
            'employeeId' => new sfValidatorString(),
        ));
    }

    public function getEmployeeListAsJson($employeeList) {

        $jsonArray = array();
        $employeeService = new EmployeeService();
        $employeeService->setEmployeeDao(new EmployeeDao());

        $employeeUnique = array();
        foreach ($employeeList as $employee) {

            if (!isset($employeeUnique[$employee->getEmpNumber()])) {

                $name = $employee->getFullName();
                $employeeUnique[$employee->getEmpNumber()] = $name;
                $jsonArray[] = array('name' => $name, 'id' => $employee->getEmpNumber());
                
            }
        }

        $jsonString = json_encode($jsonArray);

        return $jsonString;
    }

    public function getEmployeeName($employeeId) {

        $employeeService = new EmployeeService();
        $employee = $employeeService->getEmployee($employeeId);
        if($employee->getMiddleName()!= null){
        return $employee->getFirstName() . " " . $employee->getMiddleName()." ". $employee->getLastName();
        
        }
        else{
            return $employee->getFirstName() . " " . $employee->getLastName();
        }
    }

}

?>
