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

class LeaveRequestDaoSearchTest extends PHPUnit_Framework_TestCase {

    /**
     * Set up method
     */
    protected function setUp() {

        TestDataService::truncateTables(array('Employee', 'LeaveType', 'LeavePeriod', 'Leave'));
        TestDataService::populate(sfConfig::get('sf_plugins_dir') . '/orangehrmCoreLeavePlugin/test/fixtures/LeaveRequestDaoSearch.yml');
    }

    /**
     * @group orangehrmLeaveListDataExtractorCsvPlugin
     */
    public function testGetLeaveRequestSearchResultAsArray1() {

        $searchParams = new ParameterObject(array(
                    'dateRange' => new DateRange("2010-09-01", "2010-09-07"),
                    'statuses' => null,
                    'employeeFilter' => null,
                    'leavePeriod' => null,
                    'leaveType' => null,
                    'noOfRecordsPerPage' => '',
                    'cmbWithTerminated' => 'yes',
                ));

        $dao = new LeaveRequestDao();
        $this->assertEquals(3, sizeof($dao->getLeaveRequestSearchResultAsArray($searchParams)));
    }

    /**
     * @group orangehrmLeaveListDataExtractorCsvPlugin
     */
    public function testGetDetailedLeaveRequestSearchResultAsArray2() {

        $searchParams = new ParameterObject(array(
                    'dateRange' => new DateRange("2010-09-01", "2010-09-07"),
                    'statuses' => null,
                    'employeeFilter' => null,
                    'leavePeriod' => null,
                    'leaveType' => null,
                    'noOfRecordsPerPage' => '',
                    'cmbWithTerminated' => 'yes',
                ));

        $dao = new LeaveRequestDao();
        $this->assertEquals(6, sizeof($dao->getDetailedLeaveRequestSearchResultAsArray($searchParams)));
    }

    /**
     * @group orangehrmLeaveListDataExtractorCsvPlugin
     */
    public function testGetLeaveRequestSearchResultAsArray3() {

        $searchParams = new ParameterObject(array(
                    'dateRange' => new DateRange("2010-09-01", "2010-09-07"),
                    'statuses' => null,
                    'employeeFilter' => array(3),
                    'leavePeriod' => null,
                    'leaveType' => null,
                    'noOfRecordsPerPage' => '',
                    'cmbWithTerminated' => 'yes',
                ));

        $dao = new LeaveRequestDao();
        $this->assertEquals(1, sizeof($dao->getLeaveRequestSearchResultAsArray($searchParams)));
    }

    /**
     * @group orangehrmLeaveListDataExtractorCsvPlugin
     */
    public function testGetDetailedLeaveRequestSearchResultAsArray4() {

        $searchParams = new ParameterObject(array(
                    'dateRange' => new DateRange("2010-09-01", "2010-09-07"),
                    'statuses' => null,
                    'employeeFilter' => array(3),
                    'leavePeriod' => null,
                    'leaveType' => null,
                    'noOfRecordsPerPage' => '',
                    'cmbWithTerminated' => 'yes',
                ));

        $dao = new LeaveRequestDao();
        $this->assertEquals(2, sizeof($dao->getDetailedLeaveRequestSearchResultAsArray($searchParams)));
    }

    /**
     * @group orangehrmLeaveListDataExtractorCsvPlugin
     */
    public function testGetLeaveRequestSearchResultAsArray5() {

        $searchParams = new ParameterObject(array(
                    'dateRange' => new DateRange("2010-09-01", "2010-09-07"),
                    'statuses' => null,
                    'employeeFilter' => null,
                    'leavePeriod' => null,
                    'leaveType' => 'LTY002',
                    'noOfRecordsPerPage' => '',
                    'cmbWithTerminated' => 'yes',
                ));

        $dao = new LeaveRequestDao();
        $this->assertEquals(1, sizeof($dao->getLeaveRequestSearchResultAsArray($searchParams)));
    }

    /**
     * @group orangehrmLeaveListDataExtractorCsvPlugin
     */
    public function testGetDetailedLeaveRequestSearchResultAsArray6() {

        $searchParams = new ParameterObject(array(
                    'dateRange' => new DateRange("2010-09-01", "2010-09-07"),
                    'statuses' => null,
                    'employeeFilter' => null,
                    'leavePeriod' => null,
                    'leaveType' => 'LTY002',
                    'noOfRecordsPerPage' => '',
                    'cmbWithTerminated' => 'yes',
                ));

        $dao = new LeaveRequestDao();
        $this->assertEquals(2, sizeof($dao->getDetailedLeaveRequestSearchResultAsArray($searchParams)));
    }

}

?>
