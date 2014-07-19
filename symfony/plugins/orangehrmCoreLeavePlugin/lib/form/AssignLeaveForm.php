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
 * Form class for apply leave
 */
class AssignLeaveForm extends sfForm {

    public $leaveTypeList = array();

    protected $employeeService;
    
    /**
     * Configure ApplyLeaveForm
     *
     */
    public function configure() {

        $this->leaveTypeList = $this->getOption('leaveTypes');

        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());

        $this->setDefault('leaveBalance', '--');

        $this->getValidatorSchema()->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'postValidation'))));

        $this->getWidgetSchema()->setNameFormat('assignleave[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->getWidgetSchema()->setFormFormatterName('BreakTags');
    }        

    /**
     *
     * @return array
     */
    public function getLeaveTypeList() {
        
        $leaveTypeChoices = array();

        $leaveTypeChoices[''] = '--' . __('Select') . '--';
        
        foreach ($this->leaveTypeList as $leaveType) {
            $leaveTypeChoices[$leaveType->getLeaveTypeId()] = $leaveType->getLeaveTypeName();
        }
        
        return $leaveTypeChoices;
    }

    /**
     * Get Time Choices
     * @return unknown_type
     */
    private function getTimeChoices() {
        $startTime = strtotime("00:00");
        $endTime = strtotime("23:59");
        $interval = 60 * 15;
        $timeChoices = array();
        $timeChoices[''] = '';
        for ($i = $startTime; $i <= $endTime; $i+=$interval) {
            $timeVal = date('H:i', $i);
            $timeChoices[$timeVal] = $timeVal;
        }
        return $timeChoices;
    }    

    /**
     * Post validation
     * @param $validator
     * @param $values
     * @return unknown_type
     */
    public function postValidation($validator, $values) {

        $errorList = array();

        $fromDateTimeStamp = strtotime($values['txtFromDate']);
        $toDateTimeStamp = strtotime($values['txtToDate']);

        $fromTimetimeStamp = strtotime($values['txtFromTime']);
        $toTimetimeStamp = strtotime($values['txtToTime']);

        if ($fromDateTimeStamp === FALSE)
            $errorList['txtFromDate'] = new sfValidatorError($validator, 'Invalid From date');

        if ($toDateTimeStamp === FALSE)
            $errorList['txtToDate'] = new sfValidatorError($validator, 'Invalid To date');

        if ((is_int($fromDateTimeStamp) && is_int($toDateTimeStamp)) && ($toDateTimeStamp - $fromDateTimeStamp) < 0)
            $errorList['txtFromDate'] = new sfValidatorError($validator, ' From Date should be a previous date to To Date');

        if (($values['txtFromDate'] == $values['txtToDate']) && (is_int($fromTimetimeStamp) && is_int($toTimetimeStamp)) && ($toTimetimeStamp - $fromTimetimeStamp) < 0)
            $errorList['txtFromTime'] = new sfValidatorError($validator, ' From time should be a previous time to To time');

        if (count($errorList) > 0) {

            throw new sfValidatorErrorSchema($validator, $errorList);
        }

        $values['txtFromDate'] = date('Y-m-d', $fromDateTimeStamp);
        $values['txtToDate'] = date('Y-m-d', $toDateTimeStamp);
        $values['txtLeaveTotalTime'] = number_format($values['txtLeaveTotalTime'], 2);

        return $values;
    }

    /**
     * Calculate Applied Date range
     * @return int
     */
    public function calculateAppliedDateRange($leaveList) {
        $dateRange = 0;
        foreach ($leaveList as $leave) {
            $dateRange += $leave->getLeaveLengthDays();
        }
        return $dateRange;
    }

    protected function getEmployeeListAsJson() {

        $jsonArray = array();
        $employeeService = $this->getEmployeeService();
        
        $locationService = new LocationService();
        
        $employeeList = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntities('Employee');

        $employeeUnique = array();
        foreach ($employeeList as $employee) {
            $workShiftLength = 0;
            $employeeCountry = null;
            $terminationId = $employee->getTerminationId();
            if (!isset($employeeUnique[$employee->getEmpNumber()]) && empty($terminationId)) {
                $employeeWorkShift = $employeeService->getWorkShift($employee->getEmpNumber());
                if ($employeeWorkShift != null) {
                    $workShiftLength = $employeeWorkShift->getWorkShift()->getHoursPerDay();
                } else
                    $workShiftLength = WorkShift :: DEFAULT_WORK_SHIFT_LENGTH;

                /*$operatinalCountry = $employee->getOperationalCountry();
                if ($employee->getOperationalCountry() instanceof OperationalCountry) {
                    $employeeCountry = $operatinalCountry->getId();
                }*/
                
                $employeeLocations  = $employee->getLocations();
                if ($employeeLocations[0] instanceof Location){                
                    $location = $locationService->getLocationById($employeeLocations[0]->getId());
                    if ($location instanceof Location) {
                        $country = $location->getCountry();
                        if ($country instanceof Country) {
                            $employeeOperationalCountry = $country->getOperationalCountry();
                            if ($employeeOperationalCountry instanceof OperationalCountry) {
                                $employeeCountry = $employeeOperationalCountry->getId();
                            }
                        }
                    }
                }
                
                $name = $employee->getFullName();

                $employeeUnique[$employee->getEmpNumber()] = $name;
                $jsonArray[] = array('name' => $name, 'id' => $employee->getEmpNumber(), 'workShift' => $workShiftLength, 'country' => $employeeCountry);
            }
        }

        $jsonString = json_encode($jsonArray);

        return $jsonString;
    }
    
    /**
     *
     * @return array
     */
    public function getStylesheets() {
        $styleSheets = parent::getStylesheets();
        
        $styleSheets['/orangehrmCoreLeavePlugin/css/assignLeaveSuccess.css'] = 'all';
        $styleSheets['/orangehrmCoreLeavePlugin/css/common.css'] = 'all';
        
        return $styleSheets;
    }

    /**
     *
     * @return array
     */
    protected function getFormWidgets() {
        $widgets = array(
            'txtEmployee' => new ohrmWidgetEmployeeNameAutoFill(array('jsonList' => $this->getEmployeeListAsJson()), array('class' => 'formInputText')),
            'txtEmpWorkShift' => new sfWidgetFormInputHidden(),
            'txtLeaveType' => new sfWidgetFormChoice(array('choices' => $this->getLeaveTypeList()), array('class' => 'formSelect')),
            'leaveBalance' => new ohrmWidgetDiv(array(), array('style' => 'float:left;padding-top: 6px;')),
            'txtFromDate' => new ohrmWidgetDatePicker(array(), array('id' => 'assignleave_txtFromDate'), array('class' => 'formDateInput')),
            'txtToDate' => new ohrmWidgetDatePicker(array(), array('id' => 'assignleave_txtToDate'), array('class' => 'formDateInput')),
            'txtFromTime' => new sfWidgetFormChoice(array('choices' => $this->getTimeChoices()), array('class' => 'formSelect')),
            'txtToTime' => new sfWidgetFormChoice(array('choices' => $this->getTimeChoices()), array('class' => 'formSelect')),
            'txtLeaveTotalTime' => new sfWidgetFormInput(array(), array('readonly' => 'readonly', 'class' => 'formInputText')),
            'txtComment' => new sfWidgetFormTextarea(array(), array('rows' => '3', 'cols' => '30')),
        );

        return $widgets;
    }

    /**
     *
     * @return array
     */
    protected function getFormValidators() {
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $validators = array(
            'txtEmployee' => new ohrmValidatorEmployeeNameAutoFill(),
            'txtEmpWorkShift' => new sfValidatorString(array('required' => false)),
            'txtLeaveType' => new sfValidatorChoice(array('choices' => array_keys($this->getLeaveTypeList()))),
            'txtFromDate' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true),
                    array('invalid' => 'Date format should be ' . $inputDatePattern)),
            'txtToDate' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true),
                    array('invalid' => 'Date format should be ' . $inputDatePattern)),
            'txtComment' => new sfValidatorString(array('required' => false, 'trim' => true, 'max_length' => 1000)),
            'txtFromTime' => new sfValidatorString(array('required' => false)),
            'txtToTime' => new sfValidatorString(array('required' => false)),
            'txtLeaveTotalTime' => new sfValidatorNumber(array('required' => false)),
        );

        return $validators;
    }
    
    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $requiredMarker = ' <span class="required">*</span>';
        
        $labels = array(
            'txtEmployee' => __('Employee Name') . $requiredMarker,
            'txtLeaveType' => __('Leave Type') . $requiredMarker,
            'leaveBalance' => __('Leave Balance'),
            'txtFromDate' => __('From Date') . $requiredMarker,
            'txtToDate' => __('To Date') . $requiredMarker,
            'txtFromTime' => __('From Time'),
            'txtToTime' => __('To Time'),
            'txtLeaveTotalTime' => __('Total Hours'),
            'txtComment' => __('Comment'),
        );
        
        return $labels;
    }

    /**
     *
     * @return EmployeeService
     */
    public function getEmployeeService() {
        if (!($this->employeeService instanceof EmployeeService)) {
            $this->employeeService = new EmployeeService();
        }
        return $this->employeeService;
    }

    /**
     *
     * @param EmployeeService $service 
     */
    public function setEmployeeService(EmployeeService $service) {
        $this->employeeService = $service;
    }     
}

