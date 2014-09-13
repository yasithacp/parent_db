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

class displayAttendanceTotalSummaryReportCriteriaAction extends displayReportCriteriaAction {

    public function setReportCriteriaInfoInRequest($formValues) {

        $employeeService = new EmployeeService();
        $jobService = new JobService();
	$empStatusService = new EmploymentStatusService();
        $companyStructureService = new CompanyStructureService();
        
        if (isset($formValues["employee"])) {
            $empNumber = $formValues["employee"];
            $employee = $employeeService->getEmployee($empNumber);
            $empName = $employee->getFirstAndLastNames();
            $this->getRequest()->setParameter('empName', $empName);
        }

        if (isset($formValues["employment_status"]) && ($formValues["employment_status"]!=0)) {
            $estatCode = $formValues["employment_status"];
            $estat = $empStatusService->getEmploymentStatusById($estatCode);
            $estatName = $estat->getName();
            $this->getRequest()->setParameter("empStatusName", $estatName);
        }

        if (isset($formValues["job_title"]) && ($formValues["job_title"]!=0)) {
            $jobTitCode = $formValues["job_title"];
            $jobTitle = $jobService->readJobTitle($jobTitCode);
            $jobTitName = $jobTitle->getJobTitName();
            $this->getRequest()->setParameter("jobTitName", $jobTitName);
        }

        if (isset($formValues["sub_unit"]) && ($formValues["job_title"]!=0)) {
            $value = $formValues["sub_unit"];
            $id = $value;
            $subunit = $companyStructureService->getSubunitById($id);
            $subUnitName = $subunit->getName();
            $this->getRequest()->setParameter("subUnit", $subUnitName);
        }

        $this->getRequest()->setParameter('attendanceDateRangeFrom', $formValues["attendance_date_range"]["from"]);
        $this->getRequest()->setParameter('attendanceDateRangeTo', $formValues["attendance_date_range"]["to"]);
    }

    public function setForward() {

        $this->forward('time', 'displayAttendanceTotalSummaryReport');
    }

    public function setStaticColumns($formValues) {

    }

}

