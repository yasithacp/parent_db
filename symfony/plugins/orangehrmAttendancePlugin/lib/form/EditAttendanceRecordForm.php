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

class EditAttendanceRecordForm extends sfForm {

    private $attendanceService;
    public $nonEditableOutDate = array();

    public function configure() {
        $employeeId = $this->getOption('employeeId');
        $date = $this->getOption('date');
        $records = $this->getAttendanceService()->getAttendanceRecord($employeeId, $date);
        $totalRows = sizeOf($records);
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        if ($records != null) {

            for ($i = 1; $i <= $totalRows; $i++) {

                $this->setWidget('recordId_' . $i, new sfWidgetFormInputHidden());
                $this->setWidget('InOffset_' . $i, new sfWidgetFormInputHidden());
                $this->setWidget('OutOffset_' . $i, new sfWidgetFormInputHidden());
                $this->setWidget('punchInDate_' . $i, new sfWidgetFormInputText());
                $this->setWidget('punchInTime_' . $i, new sfWidgetFormInputText());
                $this->setWidget('inNote_' . $i, new sfWidgetFormInputText(array(), array('class' => 'inNote')));
                $this->setWidget('punchOutDate_' . $i, new sfWidgetFormInputText());
                $this->setWidget('punchOutTime_' . $i, new sfWidgetFormInputText());
                $this->setWidget('outNote_' . $i, new sfWidgetFormInputText(array(), array('class' => 'outNote')));
            }
            $this->widgetSchema->setNameFormat('attendance[%s]');
            for ($i = 1; $i <= $totalRows; $i++) {
                $this->setValidator('recordId_' . $i, new sfValidatorString());
                $this->setValidator('InOffset_' . $i, new sfValidatorString());
                $this->setValidator('OutOffset_' . $i, new sfValidatorString());
                $this->setValidator('punchInDate_' . $i, new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true),
                    array('invalid' => 'Date format should be ' . $inputDatePattern)));
                $this->setValidator('punchInTime_' . $i, new sfValidatorDateTime(array('required' => __('Enter Punch In Time'))));
                $this->setValidator('inNote_' . $i, new sfValidatorString(array('required' => false, 'max_length' => 255)));
                $this->setValidator('punchOutDate_' . $i, new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => false),
                    array('invalid' => 'Date format should be ' . $inputDatePattern)));
                $this->setValidator('punchOutTime_' . $i, new sfValidatorDateTime(array('required' => false)));
                $this->setValidator('outNote_' . $i, new sfValidatorString(array('required' => false, 'max_length' => 255)));
            }
            $i = 1;
            foreach ($records as $record) {


                if ($record->getPunchOutUserTime() == null) {

                    $this->setDefault('recordId_' . $i, $record->getId());
                    $this->setDefault('InOffset_' . $i, $record->getPunchInTimeOffset());
                    $this->setDefault('punchInDate_' . $i, date($inputDatePattern, strtotime($record->getPunchInUserTime())));
                    $this->setDefault('punchInTime_' . $i, date('H:i', strtotime($record->getPunchInUserTime())));
                    $this->setDefault('inNote_' . $i, $record->getPunchInNote());
                    $this->setDefault('outNote_' . $i, "");
                    $this->setDefault('OutOffset_' . $i, 0);
                } else {

                    $this->setDefault('recordId_' . $i, $record->getId());
                    $this->setDefault('InOffset_' . $i, $record->getPunchInTimeOffset());
                    $this->setDefault('OutOffset_' . $i, $record->getPunchOutTimeOffset());
                    $this->setDefault('punchInDate_' . $i, date($inputDatePattern, strtotime($record->getPunchInUserTime())));
                    $this->setDefault('punchInTime_' . $i, date('H:i', strtotime($record->getPunchInUserTime())));
                    $this->setDefault('inNote_' . $i, $record->getPunchInNote());
                    $this->setDefault('punchOutDate_' . $i, date($inputDatePattern, strtotime($record->getPunchOutUserTime())));
                    $this->setDefault('punchOutTime_' . $i, date('H:i', strtotime($record->getPunchOutUserTime())));
                    $this->setDefault('outNote_' . $i, $record->getPunchOutNote());
                    if (date('Y-m-d', strtotime($record->getPunchOutUserTime())) != $date) {
                        $this->nonEditableOutDate[] = $i;
                    }
                }

                $i++;
            }
        }
    }

    /**
     * Get the Timesheet Data Access Object
     * @return AttendanceService
     */
    public function getAttendanceService() {

        if (is_null($this->attendanceService)) {
            $this->attendanceService = new AttendanceService();
        }

        return $this->attendanceService;
    }

    /**
     * Set TimesheetData Access Object
     * @param AttendanceService $TimesheetDao
     * @return void
     */
    public function setAttedanceService(AttendanceService $attendanceService) {

        $this->attendanceService = $attendanceService;
    }

    public function save($totalRows, $form) {
        $this->isValid;
        $this->form = $form;
        $errorArray = array();
        for ($i = 1; $i <= $totalRows; $i++) {


            $id = $this->form->getValue('recordId_' . $i);
            $inOffset = $this->form->getValue('InOffset_' . $i);
            $outOffset = $this->form->getValue('OutOffset_' . $i);
            $punchInDate = $this->form->getValue('punchInDate_' . $i);
            $punchInTime = $this->form->getValue('punchInTime_' . $i);

            $punchOutDate = $this->form->getValue('punchOutDate_' . $i);
            $punchOutTime = $this->form->getValue('punchOutTime_' . $i);

            $attendanceRecord = $this->getAttendanceService()->getAttendanceRecordById($id);
            $punchInDateTime = $punchInDate . " " . date('H:i', strtotime($punchInTime));

            if (!$punchOutDate == null) {
                $punchOutDateTime = $punchOutDate . " " . date('H:i', strtotime($punchOutTime));
            } else {
                $punchOutDateTime = null;
            }
            $employeeId = $this->getAttendanceService()->getAttendanceRecordById($id)->getEmployeeId();
            $punchInUtcTime = date('Y-m-d H:i', strtotime($punchInDateTime) - $inOffset * 3600);
            if (!$punchOutDateTime == null) {
                $punchOutUtcTime = date('Y-m-d H:i', strtotime($punchOutDateTime) - $outOffset * 3600);
            } else {
                $punchOutUtcTime = null;
            }
            $this->isValid = $this->getAttendanceService()->checkForPunchInOutOverLappingRecordsWhenEditing($punchInUtcTime, $punchOutUtcTime, $employeeId, $id);

            if ($this->isValid == '0') {
                $errorArray[] = $i;
            } else {

                $attendanceRecord->setPunchInUserTime($punchInDateTime);



                $timeStampDiff = $inOffset * 3600 - date('Z');
                $attendanceRecord->setPunchInUtcTime(date('Y-m-d H:i', strtotime($punchInDateTime) - $inOffset * 3600));

                if ($this->form->getValue('punchOutDate_' . $i) == null) {

                    $attendanceRecord->setPunchOutNote("");
                    $attendanceRecord->setPunchOutUserTime(null);
                    $attendanceRecord->setPunchOutUtcTime(null);
                } else {

                    $attendanceRecord->setPunchOutUserTime($punchOutDateTime);
                    $timeStampDiff = $outOffset * 3600 - date('Z');
                    $attendanceRecord->setPunchOutUtcTime(date('Y-m-d H:i', strtotime($punchOutDateTime) - $outOffset * 3600));
                }
                $this->getAttendanceService()->savePunchRecord($attendanceRecord);
            }
        }
        return $errorArray;
    }

}

