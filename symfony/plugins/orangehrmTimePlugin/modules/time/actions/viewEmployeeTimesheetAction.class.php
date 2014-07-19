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

class viewEmployeeTimesheetAction extends sfAction {

    private $employeeNumber;
    private $timesheetService;

    public function getTimesheetService() {

        if (is_null($this->timesheetService)) {

            $this->timesheetService = new TimesheetService();
        }

        return $this->timesheetService;
    }

    public function execute($request) {

        $this->form = new viewEmployeeTimesheetForm();


        if ($request->isMethod("post")) {


            $this->form->bind($request->getParameter('time'));

            if ($this->form->isValid()) {

                $this->employeeId = $this->form->getValue('employeeId');
                $startDaysListForm = new startDaysListForm(array(), array('employeeId' => $this->employeeId));
                $dateOptions = $startDaysListForm->getDateOptions();

                if ($dateOptions == null) {

                    $this->getContext()->getUser()->setFlash('errorMessage', __("No Timesheets Found"));
                    $this->redirect('time/createTimesheetForSubourdinate?' . http_build_query(array('employeeId' => $this->employeeId)));
                }

                $this->redirect('time/viewTimesheet?' . http_build_query(array('employeeId' => $this->employeeId)));
            }
        }

        $userObj = $this->getContext()->getUser()->getAttribute("user");
        $this->form->employeeList = $userObj->getEmployeeList();


        $this->pendingApprovelTimesheets = $userObj->getActionableTimesheets();
    }

}

