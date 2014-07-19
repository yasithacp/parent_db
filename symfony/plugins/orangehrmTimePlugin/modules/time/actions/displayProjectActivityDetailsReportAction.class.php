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

class displayProjectActivityDetailsReportAction extends displayReportAction {

    public function setConfigurationFactory() {

        $confFactory = new ProjectActivityDetailsReportListConfigurationFactory();

        $this->setConfFactory($confFactory);
    }

    public function setParametersForListComponent() {

        $projectService = new ProjectService();

        $projectId = $this->getRequest()->getParameter("projectId");
        $projectName = $projectService->getProjectNameWithCustomerName($projectId);

        $activityId = $this->getRequest()->getParameter("activityId");

        $reportGeneratorService = new ReportGeneratorService();
        $activityName = $reportGeneratorService->getProjectActivityNameByActivityId($activityId);
        $params = array(
            'projectName' => $projectName,
            'activityName' => $activityName,
            'projectDateRangeFrom' => $this->getRequest()->getParameter("from"),
            'projectDateRangeTo' => $this->getRequest()->getParameter("to"),
            'total' => $this->getRequest()->getParameter("total")
        );

        return $params;
    }

    public function setListHeaderPartial() {

        ohrmListComponent::setHeaderPartial("time/projectActivityDetailsReportHeader");
    }

    public function setValues() {

        $activityId = $this->getRequest()->getParameter("activityId");
        $fromDate = $this->getRequest()->getParameter("from");
        $toDate = $this->getRequest()->getParameter("to");
        $approved = $this->getRequest()->getParameter("onlyIncludeApprovedTimesheets");
        
        $values = array("activity_name" => $activityId, "project_date_range" => array("from" => $fromDate, "to" => $toDate), "only_include_approved_timesheets" => $approved);

        return $values;
    }

}

