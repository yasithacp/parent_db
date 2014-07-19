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

class saveTimesheetAction extends sfAction {

    private $timesheetForm;

    public function execute(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            $this->getTimesheetForm()->bind($request->getParameterHolder()->getAll());

            if ($request->getParameter('btnSave')) {
                if ($this->numberOfRows == null) {
                    $this->getTimesheetService()->saveTimesheetItems($request->getParameter('initialRows'), 1, 1, $this->currentWeekDates, $this->totalRows);
                    $this->messageData = array('SUCCESS', __(TopLevelMessages::SAVE_SUCCESS));
                    $this->redirect('time/editTimesheet');
                } else {
                    $this->getTimesheetService()->saveTimesheetItems($request->getParameter('initialRows'), $this->employeeId, $this->timesheetId, $this->currentWeekDates, $this->totalRows);
                    $this->messageData = array('SUCCESS', __(TopLevelMessages::SAVE_SUCCESS));
                    $this->redirect('time/editTimesheet');
                }
            }
            if ($request->getParameter('btnRemoveRows')) {
                if ($this->numberOfRows == null) {
                    $this->messageData = array('WARNING', __("Can not delete an empty row"));
                    $this->redirect('time/editTimesheet');
                } else {
                    $this->getTimesheetService()->deleteTimesheetItems($request->getParameter('initialRows'), $this->employeeId, $this->timesheetId);
                    $this->messageData = array('SUCCESS', __(TopLevelMessages::DELETE_SUCCESS));
                    $this->redirect('time/editTimesheet');
                }
            }
        }
    }

    public function getTimesheetForm() {

        if (is_null($this->timesheetForm)) {
            $this->timesheetForm = new TimesheetForm();
        }

        return $this->timesheetForm;
    }

}

