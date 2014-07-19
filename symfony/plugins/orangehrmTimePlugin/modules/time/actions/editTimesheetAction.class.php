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

class editTimesheetAction extends sfAction {

    private $timesheetService;
    private $timesheetPeriodService;
    private $totalRows = 0;


    public function getTimesheetService() {

        if (is_null($this->timesheetService)) {

            $this->timesheetService = new TimesheetService();
        }

        return $this->timesheetService;
    }

    public function getTimesheetPeriodService() {

        if (is_null($this->timesheetPeriodService)) {

            $this->timesheetPeriodService = new TimesheetPeriodService();
        }

        return $this->timesheetPeriodService;
    }

    public function execute($request) {



        $userObj = $this->getContext()->getUser()->getAttribute('user');
        $employeeIdOfTheUser = $userObj->getEmployeeNumber();

        $this->backAction = $request->getParameter('actionName');
        $this->timesheetId = $request->getParameter('timesheetId');
        $this->employeeId = $request->getParameter('employeeId');


        if ($this->employeeId == $employeeIdOfTheUser) {
            $this->employeeName == null;
        } else {

            $employeeService = new EmployeeService();
            $employee = $employeeService->getEmployee($this->employeeId);
            $this->employeeName = $employee->getFirstName() . " " . $employee->getLastName();
        }



        $timesheet = $this->getTimesheetService()->getTimesheetById($this->timesheetId);

        $this->date = $timesheet->getStartDate();
        $this->endDate = $timesheet->getEndDate();
        $this->startDate = $this->date;
        $this->noOfDays = $this->timesheetService->dateDiff($this->startDate, $this->endDate);
        $values = array('date' => $this->startDate, 'employeeId' => $this->employeeId, 'timesheetId' => $this->timesheetId, 'noOfDays' => $this->noOfDays);
        $this->timesheetForm = new TimesheetForm(array(), $values);
        $this->currentWeekDates = $this->timesheetForm->getDatesOfTheTimesheetPeriod($this->startDate, $this->endDate);
        $this->timesheetItemValuesArray = $this->timesheetForm->getTimesheet($this->startDate, $this->employeeId, $this->timesheetId);

        $this->messageData = array($request->getParameter('message[0]'), $request->getParameter('message[1]'));

        if ($this->timesheetItemValuesArray == null) {

            $this->totalRows = 0;
            $this->timesheetForm = new TimesheetForm(array(), $values);
        } else {

            $this->totalRows = sizeOf($this->timesheetItemValuesArray);
            $this->timesheetForm = new TimesheetForm(array(), $values);
        }

        if ($request->isMethod('post')) {


            if ($request->getParameter('btnSave')) {
                $backAction = $this->backAction;
                $this->getTimesheetService()->saveTimesheetItems($request->getParameter('initialRows'), $this->employeeId, $this->timesheetId, $this->currentWeekDates, $this->totalRows);
                $this->messageData = array('SUCCESS', __(TopLevelMessages::SAVE_SUCCESS));
                $startingDate = $this->timesheetService->getTimesheetById($this->timesheetId)->getStartDate();
                $this->redirect('time/' . $backAction . '?' . http_build_query(array('message' => $this->messageData, 'timesheetStartDate' => $startingDate, 'employeeId' => $this->employeeId)));
            }



            if ($request->getParameter('buttonRemoveRows')) {
              

                $this->messageData = array('SUCCESS', __('Successfully Removed'));

            }
        }
    }

}

