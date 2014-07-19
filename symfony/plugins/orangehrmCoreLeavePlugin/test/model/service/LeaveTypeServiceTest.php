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
 * Leave Type rule service
 * @group CoreLeave 
 */
 class LeaveTypeServiceTest extends PHPUnit_Framework_TestCase{
    
    private $leaveTypeService;
    protected $fixture;

    /**
     * PHPUnit setup function
     */
    public function setup() {
            
        $this->leaveTypeService =   new LeaveTypeService();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmCoreLeavePlugin/test/fixtures/LeaveTypeService.yml';
            
    }
    
    /* Tests for getLeaveTypeList() */

    public function testGetLeaveTypeList() {

        $leaveTypeList = TestDataService::loadObjectList('LeaveType', $this->fixture, 'set1');

        $leaveTypeDao = $this->getMock('LeaveTypeDao', array('getLeaveTypeList'));
        $leaveTypeDao->expects($this->once())
                     ->method('getLeaveTypeList')
                     ->will($this->returnValue($leaveTypeList));

        $this->leaveTypeService->setLeaveTypeDao($leaveTypeDao);
        $returnedLeaveTypeList = $this->leaveTypeService->getLeaveTypeList();
        
        $this->assertEquals(5, count($returnedLeaveTypeList));
        
        foreach ($returnedLeaveTypeList as $leaveType) {
            $this->assertTrue($leaveType instanceof LeaveType);
        }

    }

    public function testGetLeaveTypeListWithOperationalCountryId() {

        $leaveTypeList = TestDataService::loadObjectList('LeaveType', $this->fixture, 'set1');

        $leaveTypeDao = $this->getMock('LeaveTypeDao', array('getLeaveTypeList'));
        $leaveTypeDao->expects($this->once())
                     ->method('getLeaveTypeList')
                     ->with($this->equalTo(2))
                     ->will($this->returnValue($leaveTypeList));

        $this->leaveTypeService->setLeaveTypeDao($leaveTypeDao);
        $returnedLeaveTypeList = $this->leaveTypeService->getLeaveTypeList(2);
        
        $this->assertEquals(5, count($returnedLeaveTypeList));
        
        foreach ($returnedLeaveTypeList as $leaveType) {
            $this->assertTrue($leaveType instanceof LeaveType);
        }            
    }
    
    /* Tests for saveLeaveType() */

    public function testSaveLeaveType() {

        $leaveTypeList = TestDataService::loadObjectList('LeaveType', $this->fixture, 'set1');
        $leaveType = $leaveTypeList[0];

        $leaveTypeDao = $this->getMock('LeaveTypeDao', array('saveLeaveType'));
        $leaveTypeDao->expects($this->once())
                     ->method('saveLeaveType')
                     ->with($leaveType)
                     ->will($this->returnValue(true));

        $this->leaveTypeService->setLeaveTypeDao($leaveTypeDao);

        $this->assertTrue($this->leaveTypeService->saveLeaveType($leaveType));

    }

    /* Tests for readLeaveType */

    public function testReadLeaveType() {

        $leaveTypeList = TestDataService::loadObjectList('LeaveType', $this->fixture, 'set1');
        $leaveType = $leaveTypeList[0];

        $leaveTypeDao = $this->getMock('LeaveTypeDao', array('readLeaveType'));
        $leaveTypeDao->expects($this->once())
                     ->method('readLeaveType')
                     ->with('LTY001')
                     ->will($this->returnValue($leaveType));

        $this->leaveTypeService->setLeaveTypeDao($leaveTypeDao);

        $leaveType = $this->leaveTypeService->readLeaveType('LTY001');

        $this->assertTrue($leaveType instanceof LeaveType);

    }


    
 }