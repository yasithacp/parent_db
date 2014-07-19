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

class displayProjectReportCriteriaAction extends displayReportCriteriaAction {

    public function execute($request) {
        $this->userObj = $this->getContext()->getUser()->getAttribute('user');
        $accessibleMenus = $this->userObj->getAccessibleReportSubMenus();
        $hasRight = false;

        foreach ($accessibleMenus as $menu) {
            if ($menu->getDisplayName() === __("Project Reports")) {
                $hasRight = true;
                break;
            }
        }

        if (!$hasRight) {
            return $this->renderText(__("You are not allowed to view this page").'!');
        }
        parent::execute($request);
    }

    public function setReportCriteriaInfoInRequest($formValues) {

        $projectService = new ProjectService();
        $projectId = $formValues["project_name"];
        $projectName = $projectService->getProjectNameWithCustomerName($projectId);

        $this->getRequest()->setParameter('projectName', $projectName);
        $this->getRequest()->setParameter('projectDateRangeFrom', $formValues["project_date_range"]["from"]);
        $this->getRequest()->setParameter('projectDateRangeTo', $formValues["project_date_range"]["to"]);
    }

    public function setForward() {
        $this->forward('time', 'displayProjectReport');
    }

    public function hasStaticColumns() {
        return true;
    }

    public function setStaticColumns($formValues) {

        $staticColumns["fromDate"] = "";
        $staticColumns["toDate"] = "";
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        $datepickerDateFormat = get_datepicker_date_format($inputDatePattern);

        if (($formValues["project_date_range"]["from"] != $datepickerDateFormat) && ($formValues["project_date_range"]["to"] != $datepickerDateFormat)) {

            if ($formValues["project_date_range"]["from"] != '') {
                $staticColumns["fromDate"] = $formValues["project_date_range"]["from"];
            }
            if ($formValues["project_date_range"]["to"] != '') {
                $staticColumns["toDate"] = $formValues["project_date_range"]["to"];
            }
        } else if (($formValues["project_date_range"]["from"] != $datepickerDateFormat) && ($formValues["project_date_range"]["to"] == $datepickerDateFormat)) {

            if ($formValues["project_date_range"]["from"] != '') {
                $staticColumns["fromDate"] = $formValues["project_date_range"]["from"];
            }
        } else if (($formValues["project_date_range"]["from"] == $datepickerDateFormat) && ($formValues["project_date_range"]["to"] != $datepickerDateFormat)) {

            if ($formValues["project_date_range"]["to"] != '') {
                $staticColumns["toDate"] = $formValues["project_date_range"]["to"];
            }
        }

        $staticColumns["projectId"] = $formValues["project_name"];

        if ($formValues["only_include_approved_timesheets"] == "on") {
            $staticColumns["onlyIncludeApprovedTimesheets"] = "on";
        } else {
            $staticColumns["onlyIncludeApprovedTimesheets"] = "off";
        }

        return $staticColumns;
    }

}

