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
 *  @group Attendance
 */
class AttendanceDaoTest extends PHPUnit_Framework_TestCase {

    private $attendanceDao;

    /**
     * Set up method
     */
    protected function setUp() {

        $this->attendanceDao = new AttendanceDao();    
        TestDataService::truncateSpecificTables(array('AttendanceRecord','Employee'));
        TestDataService::populate(sfConfig::get('sf_plugins_dir') . '/orangehrmAttendancePlugin/test/fixtures/AttendanceDao.yml');
    }

    /**
     * @group orangehrmAttendancePlugin
     */
    public function testSaveNewPunchRecord() {        

        $punchRecord = new AttendanceRecord();

        $punchRecord->setState("PUNCHED IN");
        $punchRecord->setEmployeeId(2);
        $punchRecord->setPunchInUserTime('2011-05-27 12:10:00');
        $punchRecord->setPunchInTimeOffset('Asia/Calcutta');
        $punchRecord->setPunchInUtcTime('2011-05-27 5:10:23');

        $savedRecord = $this->attendanceDao->SavePunchRecord($punchRecord);

        $this->assertNotNull($savedRecord->getId());
        $this->assertEquals($savedRecord->getState(), "PUNCHED IN");
        $this->assertEquals($savedRecord->getPunchInUserTime(), '2011-05-27 12:10:00');
        $this->assertEquals($savedRecord->getPunchInTimeOffset(), 'Asia/Calcutta');
    }

    /**
     * @group orangehrmAttendancePlugin
     */
    public function testSavePunchRecordForExistingPunchRecord() {

        $attendanceRecord = TestDataService::fetchObject('AttendanceRecord', 1);

        $attendanceRecord->setState("PUNCHED IN");

        $saveRecord = $this->attendanceDao->savePunchRecord($attendanceRecord);

        $this->assertEquals($saveRecord->getState(), 'PUNCHED IN');
        $this->assertEquals($saveRecord->getPunchInTimeOffset(), 'Asia/Calcutta');
    }

    /**
     * @group orangehrmAttendancePlugin
     */
    public function testGetLastPunchRecord() {

        $employeeId = 2;
        $actionableStatesList = array(PluginAttendanceRecord::STATE_PUNCHED_IN);

        $attendanceRecord = $this->attendanceDao->getLastPunchRecord($employeeId, $actionableStatesList);

        $this->assertEquals($attendanceRecord->getId(), 2);
        $this->assertEquals($attendanceRecord->getEmployeeId(), $employeeId);
        $this->assertEquals($attendanceRecord->getPunchInTimeOffset(), 'Asia/Calcutta');
    }

    /**
     * @group orangehrmAttendancePlugin
     */
    public function testGetLastPunchRecordForNonExistingRecord() {

        $employeeId = 4;
        $actionableStatesList = array(PluginAttendanceRecord::STATE_PUNCHED_IN);

        $attendanceRecord = $this->attendanceDao->getLastPunchRecord($employeeId, $actionableStatesList);

        $this->assertNull($attendanceRecord);
    }

    /**
     * @group orangehrmAttendancePlugin
     */
    public function testCheckForPunchOutOverLappingRecords() {
        $punchInTime = "2011-06-10 15:21:00";
        $punchOutTime = "2011-06-10 15:40:00";
        $employeeId = 5;
        $recordId = 121;

        $records = $this->attendanceDao->checkForPunchOutOverLappingRecords($punchInTime, $punchOutTime, $employeeId, $recordId);

        $this->assertEquals($records, 0);

        $punchInTime = "2011-06-10 15:21:00";
        $punchOutTime = "2011-06-10 15:50:00";
        $employeeId = 5;

        $records = $this->attendanceDao->checkForPunchOutOverLappingRecords($punchInTime, $punchOutTime, $employeeId, $recordId);
        $this->assertEquals($records, 0);

        $punchInTime = "2011-06-10 15:21:00";
        $punchOutTime = "2011-06-10 15:50:00";
        $employeeId = 5;

        $records = $this->attendanceDao->checkForPunchOutOverLappingRecords($punchInTime, $punchOutTime, $employeeId, $recordId);
        $this->assertEquals($records, 0);
    }

    /**
     * @group orangehrmAttendancePlugin
     */
    public function testCheckForPunchInOverLappingRecords() {
        $punchInTime = "2011-04-03 15:21:00";
        $employeeId = 5;
        $records = $this->attendanceDao->checkForPunchInOverLappingRecords($punchInTime, $employeeId);
        $this->assertEquals($records, 0);
    }

    /**
     * @group orangehrmAttendancePlugin
     */
    public function testGetSavedConfiguration() {

        $workflow = "ATTENDANCE";
        $state = "INITIAL";
        $role = "ESS USER";
        $action = "EDIT";
        $resultingState = "INITIAL";

        $RecordExist = $this->attendanceDao->getSavedConfiguration($workflow, $state, $role, $action, $resultingState);

        $this->assertTrue($RecordExist);

        $workflow = "ATTENDANCE";
        $state = "PUNCHED OUT";
        $role = "ESS USER";
        $action = "EDIT";
        $resultingState = "PUNCHED OUT";

        $RecordExist = $this->attendanceDao->getSavedConfiguration($workflow, $state, $role, $action, $resultingState);

        $this->assertFalse($RecordExist);
    }

    /**
     * @group orangehrmAttendancePlugin
     */
    public function testGetAttendanceRecord() {
        $employeeId = 5;
        $date = "2011-12-12";

        $records = $this->attendanceDao->getAttendanceRecord($employeeId, $date);
        $firstRecord = $records[0];
        $secondRecord = $records[1];

        $this->assertEquals($firstRecord->getEmployeeId(), 5);
        $this->assertEquals($firstRecord->getPunchInUserTime(), "2011-12-12 15:26:00");
        $this->assertEquals($secondRecord->getEmployeeId(), 5);
        $this->assertEquals($secondRecord->getPunchInUserTime(), "2011-12-12 19:26:00");

        $employeeId = 5;
        $date = "2012-12-21";
        $records = $this->attendanceDao->getAttendanceRecord($employeeId, $date);

        $this->assertEquals($records[0]->getEmployeeId(), 5);
        $this->assertEquals($records[0]->getPunchInUserTime(), "2012-12-21 01:10:00");
        $this->assertEquals($records[0]->getPunchInTimeOffset(), -9);

        $employeeId = 5;
        $date = "2012-02-28";
        $records = $this->attendanceDao->getAttendanceRecord($employeeId, $date);

        $this->assertEquals($records[0]->getEmployeeId(), 5);
        $this->assertEquals($records[0]->getPunchInUserTime(), "2012-02-28 23:46:00");
        $this->assertEquals($records[0]->getPunchInTimeOffset(), 6.5);
        $this->assertEquals($records[0]->getPunchOutUserTime(), "2012-02-29 17:42:00");

        $employeeId = 5;
        $date = "2016-02-28";
        $records = $this->attendanceDao->getAttendanceRecord($employeeId, $date);
        $this->assertNull($records[0]);
    }

    /**
     * @group orangehrmAttendancePlugin
     */
    public function testDeleteAttendanceRecords() {
        $attendanceRecordId = 4;
        $isDeleted = $this->attendanceDao->deleteAttendanceRecords($attendanceRecordId);

        $this->assertTrue($isDeleted);
    }

    /**
     * @group orangehrmAttendancePlugin
     */
    public function testGetAttendanceRecordById() {
        $id = 5;
        $attendanceRecord = $this->attendanceDao->getAttendanceRecordById($id);
        $this->assertEquals(5, $attendanceRecord->getId());
        $this->assertEquals(5, $attendanceRecord->getEmployeeId());
    }

    /**
     * @group orangehrmAttendancePlugin
     */
    public function testCheckForPunchInOutOverLappingRecordsWhenEditing() {

        $punchInTime = "2012-02-27 23:10:00";
        $punchOutTime = "2012-02-28 23:15:00";
        $employeeId = 5;
        $recordId = 22;
        $isDeleted = $this->attendanceDao->checkForPunchInOutOverLappingRecordsWhenEditing($punchInTime, $punchOutTime, $employeeId, $recordId);
        $this->assertEquals(0, $isDeleted);
    }

    /**
     * @group orangehrmAttendancePlugin
     */
    public function testSearchAttendanceRecords1() {
        
        $attendanceRecords = $this->attendanceDao->searchAttendanceRecords(1);      
        $this->assertEquals(1, sizeof($attendanceRecords));
    }
    
     /**
     * @group orangehrmAttendancePlugin
     */
    public function testSearchAttendanceRecords2() {
        
        $attendanceRecords = $this->attendanceDao->searchAttendanceRecords(5);      
        $this->assertEquals(7, sizeof($attendanceRecords));
    }
    
     /**
     * @group orangehrmAttendancePlugin
     */
    public function testSearchAttendanceRecords3() {
        
        $attendanceRecords = $this->attendanceDao->searchAttendanceRecords(2, array(3));      
        $this->assertEquals(0, sizeof($attendanceRecords));
    }
    
    /**
     * @group orangehrmAttendancePlugin
     */
    public function testSearchAttendanceRecords4() {
        
        $attendanceRecords = $this->attendanceDao->searchAttendanceRecords(2, array(1));      
        $this->assertEquals(1, sizeof($attendanceRecords));
    }

}