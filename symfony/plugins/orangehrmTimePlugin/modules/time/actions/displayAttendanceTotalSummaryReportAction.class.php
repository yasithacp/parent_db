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

class displayAttendanceTotalSummaryReportAction extends displayReportAction {

    public function setParametersForListComponent() {

        $param = array();

        if ($this->getRequest()->hasParameter("empName")) {
            $param['empName'] = $this->getRequest()->getParameter("empName");
        }

        if ($this->getRequest()->hasParameter("empStatusName")) {
            $param['empStatusName'] = $this->getRequest()->getParameter("empStatusName");
        }

        if ($this->getRequest()->hasParameter("jobTitName")) {
            $param['jobTitName'] = $this->getRequest()->getParameter("jobTitName");
        }

        if ($this->getRequest()->hasParameter("subUnit")) {
            $param['subUnit'] = $this->getRequest()->getParameter("subUnit");
        }

        $param['attendanceDateRangeFrom'] = $this->getRequest()->getParameter("attendanceDateRangeFrom");
        $param['attendanceDateRangeTo'] = $this->getRequest()->getParameter("attendanceDateRangeTo");

        return $param;
    }

    public function setConfigurationFactory() {

        $confFactory = new AttendanceTotalSummaryReportListConfigurationFactory();

        $this->setConfFactory($confFactory);
    }

    public function setListHeaderPartial() {

        ohrmListComponent::setHeaderPartial("time/attendanceTotalSummaryReportHeader");
    }

    public function setValues() {

    }

}

