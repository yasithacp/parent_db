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
 * Description of TimesheetServiceTest
 *
 * @group Time
 */
class TimesheetServiceTest extends PHPUnit_Framework_Testcase {

    private $timesheetService;
    private $fixture;

    protected function setUp() {

       
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmTimePlugin/test/fixtures/TimesheetService.yml';
        TestDataService::populate($this->fixture);
        $this->timesheetService = new TimesheetService();
    }

    /* test both getTimesheetDao() and setTimesheetDao() */

    public function testGetAndSetTimesheetDao() {

        $timesheetDao = new TimesheetDao();
        $this->timesheetService->setTimesheetDao($timesheetDao);

        $this->assertTrue($this->timesheetService->getTimesheetDao() instanceof TimesheetDao);
    }

    /* test getTimesheetDao() with no argument */

    public function testGetTimesheetDao() {

        $this->assertTrue($this->timesheetService->getTimesheetDao() instanceof TimesheetDao);
    }

    /* test both getEmployeeDao() and setEmployeeDao() */

    public function testGetAndSetEmployeeDao() {

        $employeeDao = new EmployeeDao();
        $this->timesheetService->setEmployeeDao($employeeDao);

        $this->assertTrue($this->timesheetService->getEmployeeDao() instanceof EmployeeDao);
    }

    /* test getEmployeeDao() with no argument */

    public function testGetEmployeeDao() {

        $this->assertTrue($this->timesheetService->getEmployeeDao() instanceof EmployeeDao);
    }

    /* test saveTimesheet() */

    public function testSaveTimesheet() {

        $timesheets = TestDataService::loadObjectList('Timesheet', $this->fixture, 'Timesheet');

        $timesheet = $timesheets[0];

        $timesheetDaoMock = $this->getMock('TimesheetDao', array('saveTimesheet'));

        $timesheetDaoMock->expects($this->once())
                ->method('saveTimesheet')
                ->with($timesheet)
                ->will($this->returnValue($timesheet));

        $this->timesheetService->setTimesheetDao($timesheetDaoMock);
        $this->assertTrue($this->timesheetService->saveTimesheet($timesheet) instanceof Timesheet);
    }

    /* test saveTimesheetActionLog */

    public function testSaveTimesheetActionLog() {

        $timesheetActionLogRecords = TestDataService::loadObjectList('TimesheetActionLog', $this->fixture, 'TimesheetActionLog');
        $timesheetActionLog = $timesheetActionLogRecords[0];

        $timesheetDaoMock = $this->getMock('TimesheetDao', array('saveTimesheetActionLog'));

        $timesheetDaoMock->expects($this->once())
                ->method('saveTimesheetActionLog')
                ->with($timesheetActionLog)
                ->will($this->returnValue($timesheetActionLog));

        $this->timesheetService->setTimesheetDao($timesheetDaoMock);
        $this->assertTrue($this->timesheetService->saveTimesheetActionLog($timesheetActionLog) instanceof TimesheetActionLog);
    }

    /* test getTimesheetById() */

    public function testGetTimesheetById() {

        $timesheetId = 1;
        $timesheet = TestDataService::fetchObject('Timesheet', $timesheetId);

        $timesheetDaoMock = $this->getMock('TimesheetDao', array('getTimesheetById'));
        $timesheetDaoMock->expects($this->once())
                ->method('getTimesheetById')
                ->with($timesheetId)
                ->will($this->returnValue($timesheet));

        $this->timesheetService->setTimesheetDao($timesheetDaoMock);
        $gotTimesheet = $this->timesheetService->getTimesheetById($timesheetId);

        $this->assertTrue($gotTimesheet instanceof Timesheet);
        $this->assertEquals($timesheet, $gotTimesheet);
    }

    /* test getTimesheetItemById() */

    public function testGetTimesheetItemById() {
        $timesheetItemId = 2;
        $timesheetItem = TestDataService::fetchObject('TimesheetItem', $timesheetItemId);

        $timesheetDaoMock = $this->getMock('TimesheetDao', array('getTimesheetItemById'));
        $timesheetDaoMock->expects($this->once())
                ->method('getTimesheetItemById')
                ->with($timesheetItemId)
                ->will($this->returnValue($timesheetItem));

        $this->timesheetService->setTimesheetDao($timesheetDaoMock);
        $recievedTimesheetItem = $this->timesheetService->getTimesheetItemById($timesheetItemId);

        //$this->assertTrue($recievedTimesheetItem instanceof TimesheetItem);
        $this->assertEquals($timesheetItem, $recievedTimesheetItem);
    }

    /* test getTimesheetByStartDate() */

    public function testGetTimesheetByStartDate() {

        $startDate = "2011-04-18";
        $timesheets = TestDataService::loadObjectList('Timesheet', $this->fixture, 'Timesheet');
        $temp = $timesheets[0];

        $timesheetDaoMock = $this->getMock('TimesheetDao', array('getTimesheetByStartDate'));
        $timesheetDaoMock->expects($this->once())
                ->method('getTimesheetByStartDate')
                ->with($startDate)
                ->will($this->returnValue($temp));

        $this->timesheetService->setTimesheetDao($timesheetDaoMock);
        $gotTimesheet = $this->timesheetService->getTimesheetByStartDate($startDate);

        $this->assertTrue($gotTimesheet instanceof Timesheet);
        //$this->assertEquals( 1 , count($gotTimesheet));
        $this->assertEquals("2011-04-18", $gotTimesheet->getStartDate());
    }

    

    public function testGetTimesheetByStartDateAndEmployeeId() {

        $employeeId = 1;
        $timesheetId = 1;
        $startDate = "2011-04-18";
        $timesheet = TestDataService::fetchObject('Timesheet', $timesheetId);

        $timesheetDaoMock = $this->getMock('TimesheetDao', array('getTimesheetByStartDateAndEmployeeId'));
        $timesheetDaoMock->expects($this->once())
                ->method('getTimesheetByStartDateAndEmployeeId')
                ->with($startDate, $employeeId)
                ->will($this->returnValue($timesheet));

        $this->timesheetService->setTimesheetDao($timesheetDaoMock);
        $gotTimesheet = $this->timesheetService->getTimesheetByStartDateAndEmployeeId($startDate, $employeeId);

        $this->assertTrue($gotTimesheet instanceof Timesheet);
        $this->assertEquals($timesheet, $gotTimesheet);
    }

    /* test getTimesheetByEmployeeId()  */

    public function testGetTimesheetByEmployeeId() {

        $employeeId = 2;
        $timesheetId = 2;
        $timesheet = TestDataService::fetchObject('Timesheet', $timesheetId);

        $timesheetDaoMock = $this->getMock('TimesheetDao', array('getTimesheetByEmployeeId'));
        $timesheetDaoMock->expects($this->once())
                ->method('getTimesheetByEmployeeId')
                ->with($employeeId)
                ->will($this->returnValue($timesheet));

        $this->timesheetService->setTimesheetDao($timesheetDaoMock);
        $retrievedTimesheet = $this->timesheetService->getTimesheetByEmployeeId($employeeId);

        $this->assertTrue($retrievedTimesheet instanceof Timesheet);
        $this->assertEquals($timesheet, $retrievedTimesheet);
    }

    /* test getTimesheetByEmployeeIdAndState()  */

    public function testGetTimesheetByEmployeeIdAndState() {

        $employeeId = 2;

        $timesheetId1 = 2;
        $timesheetId2 = 8;

        $stateList = array('SUBMITTED', 'ACCEPTED');

        $timesheet1 = TestDataService::fetchObject('Timesheet', $timesheetId1);
        $timesheet2 = TestDataService::fetchObject('Timesheet', $timesheetId2);

        $timesheetArray = array($timesheet1, $timesheet2);

        $timesheetDaoMock = $this->getMock('TimesheetDao', array('getTimesheetByEmployeeIdAndState'));
        $timesheetDaoMock->expects($this->once())
                ->method('getTimesheetByEmployeeIdAndState')
                ->with($employeeId, $stateList)
                ->will($this->returnValue($timesheetArray));

        $this->timesheetService->setTimesheetDao($timesheetDaoMock);
        $retrievedTimesheet = $this->timesheetService->getTimesheetByEmployeeIdAndState($employeeId, $stateList);

        $this->assertEquals(2, count($retrievedTimesheet));
        $this->assertTrue($retrievedTimesheet[0] instanceof Timesheet);
        $this->assertEquals($timesheet1, $retrievedTimesheet[0]);
        $this->assertEquals($timesheet2, $retrievedTimesheet[1]);
    }

    public function testGetStartAndEndDatesList() {

        $daysArray = $this->timesheetService->getStartAndEndDatesList(1);
        $startDates = $daysArray[0];
        $endDates = $daysArray[1];
        $this->assertEquals($startDates[0]['startDate'], "2011-04-18");
        $this->assertEquals($endDates[0]['endDate'], "2011-04-19");
    }

    public function testGetPendingApprovelTimesheetsForAdmin() {
        $timesheetId = 6;
        $timesheet = TestDataService::fetchObject('Timesheet', $timesheetId);
        $timesheetDaoMock = $this->getMock('TimesheetDao', array('getPendingApprovelTimesheetsForAdmin'));
        $timesheetDaoMock->expects($this->once())
                ->method('getPendingApprovelTimesheetsForAdmin')
                ->will($this->returnValue($timesheet));
        $this->timesheetService->setTimesheetDao($timesheetDaoMock);
        $retrievedTimesheets = $this->timesheetService->getPendingApprovelTimesheetsForAdmin();


        $this->assertTrue($retrievedTimesheets instanceof Timesheet);
        $this->assertEquals($timesheet, $retrievedTimesheets);
    }

    public function testConvertDurationToHours() {
        
        $timesheetService = $this->getMock('TimesheetService', array('getTimesheetTimeFormat'));
        $timesheetService->expects($this->exactly(2))
                         ->method('getTimesheetTimeFormat')
                         ->will($this->returnValue(1));

        $durationInHours = $timesheetService->convertDurationToHours(3600);
        $durationInHours1 = $timesheetService->convertDurationToHours(5400);

        $this->assertEquals($durationInHours, '1:00');
        $this->assertEquals($durationInHours1, '1:30');
    }

    public function testConvertDurationToSeconds() {

        $durationInSecs = $this->timesheetService->convertDurationToSeconds(1);
        $durationInSecs1 = $this->timesheetService->convertDurationToSeconds(1.5);
        $this->assertEquals($durationInSecs, 3600);
        $this->assertEquals($durationInSecs1, 5400);
    }

    public function testgetTimesheetActionLogByTimesheetId() {

        $timesheetActionLogId = 1;
        $timesheetActionLogRecord = TestDataService::fetchObject('TimesheetActionLog', $timesheetActionLogId);
//                $timesheetActionLog = $timesheetActionLogRecords[0];

        $timesheetDaoMock = $this->getMock('TimesheetDao', array('getTimesheetActionLogByTimesheetId'));

        $timesheetDaoMock->expects($this->once())
                ->method('getTimesheetActionLogByTimesheetId')
                ->with($timesheetActionLogId)
                ->will($this->returnValue($timesheetActionLogRecord));
        $this->timesheetService->setTimesheetDao($timesheetDaoMock);

        $retrievedTimesheetActionLog = $this->timesheetService->getTimesheetActionLogByTimesheetId($timesheetActionLogId);

        $this->assertTrue($retrievedTimesheetActionLog instanceof TimesheetActionLog);
        $this->assertEquals($timesheetActionLogRecord, $retrievedTimesheetActionLog);
    }

    public function testGetActivityByActivityId() {

        $activityId = 1;
        $activity = TestDataService::fetchObject('ProjectActivity', $activityId);

        $activityDaoMock = $this->getMock('TimesheetDao', array('getActivityByActivityId'));
        $activityDaoMock->expects($this->once())
                ->method('getActivityByActivityId')
                ->with($activityId)
                ->will($this->returnValue($activity));

        $this->timesheetService->setTimesheetDao($activityDaoMock);
        $gotActivity = $this->timesheetService->getActivityByActivityId($activityId);

        $this->assertTrue($gotActivity instanceof ProjectActivity);
        $this->assertEquals($activity, $gotActivity);
    }

    public function testAddConvertTime() {

        $firstTime = '4:30';
        $timeToAdd = '1:40';
        $total = $this->timesheetService->addConvertTime($firstTime, $timeToAdd);
        $this->assertEquals('6:10', $total);
    }

    public function testDateDiff() {

        $start = "2011-06-27";
        $end = "2011-07-03";
        $noOfDays = $this->timesheetService->dateDiff($start, $end);
        $this->assertEquals('7', $noOfDays);
    }

    public function testGetLatestTimesheetEndDate() {

        $latestEndDate = "2011-04-28";
        $employeeId = 1;

        $timehseetDaoMock = $this->getMock('TimesheetDao', array('getLatestTimesheetEndDate'));
        $timehseetDaoMock->expects($this->once())
                ->method('getLatestTimesheetEndDate')
                ->with($employeeId)
                ->will($this->returnValue($latestEndDate));

        $this->timesheetService->setTimesheetDao($timehseetDaoMock);
        $obtaindeDate = $this->timesheetService->getLatestTimesheetEndDate($employeeId);

        $this->assertEquals($obtaindeDate, $latestEndDate);
    }

    public function testCheckForOverlappingTimesheets() {

        $employeeId = 1;
        $startDate = "2011-04-17";
        $endDate = "2011-04-20";
        $isValid = 0;

        $timehseetDaoMock = $this->getMock('TimesheetDao', array('checkForOverlappingTimesheets'));
        $timehseetDaoMock->expects($this->once())
                ->method('checkForOverlappingTimesheets')
                ->with($startDate, $endDate, $employeeId)
                ->will($this->returnValue($isValid));

        $this->timesheetService->setTimesheetDao($timehseetDaoMock);
        $testValue = $this->timesheetService->checkForOverlappingTimesheets($startDate, $endDate, $employeeId);

        $this->assertEquals($testValue, $isValid);
    }

    public function testCheckForMatchingTimesheetForCurrentDate() {

        $employeeId = 6;
        $currentDate = "2011-02-24";
        $timesheetId = 9;
        $timesheet = TestDataService::fetchObject('Timesheet', $timesheetId);

        $timehseetDaoMock = $this->getMock('TimesheetDao', array('checkForMatchingTimesheetForCurrentDate'));
        $timehseetDaoMock->expects($this->once())
                ->method('checkForMatchingTimesheetForCurrentDate')
                ->with($employeeId, $currentDate)
                ->will($this->returnValue($timesheet));

        $this->timesheetService->setTimesheetDao($timehseetDaoMock);
        $testTimesheet = $this->timesheetService->checkForMatchingTimesheetForCurrentDate($employeeId, $currentDate);

        $this->assertTrue($testTimesheet instanceof Timesheet);
        $this->assertEquals($timesheet, $testTimesheet);
    }

//    public function testCreatePreviousTimesheets(){
//
//        $currentTimesheetStartDate="2010-04-08";
//        $employeeId=8;
//        $r  =$this->timesheetService->createPreviousTimesheets($currentTimesheetStartDate, $employeeId);
//
//
//    }
}

