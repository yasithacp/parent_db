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
 * Test for LeavePeriodDao class
 * 
 * @group CoreLeave 
 */
class LeaveEntitlementDaoTest extends PHPUnit_Framework_TestCase {

    public $leaveEntitlementDao;
    public $leaveType;
    public $leavePeriod;
    public $employee;
    protected $empNumber;
    protected $leavePeriodId;
    protected $leaveTypeId;

    protected function setUp() {
        TestDataService::truncateSpecificTables(array('Employee', 'LeaveType'));

        // Save leave type
        $leaveTypeData = sfYaml::load(sfConfig::get('sf_plugins_dir') . '/orangehrmCoreLeavePlugin/test/fixtures/leaveType.yml');
        $leaveTypeDao = new LeaveTypeDao();
        $leaveType = new LeaveType();
        $leaveType->setLeaveTypeName($leaveTypeData['leaveType']['LT_001']['name']);
//                $leaveType->setLeaveRules($leaveTypeData['leaveType']['LT_001']['rule']);
        $leaveTypeDao->saveLeaveType($leaveType);
        $this->leaveType = $leaveType;
        $this->leaveTypeId = $leaveType->getLeaveTypeId();

        // Save leave Period
        $leavePeriodData = sfYaml::load(sfConfig::get('sf_plugins_dir') . '/orangehrmCoreLeavePlugin/test/fixtures/leavePeriod.yml');
        $leavePeriodService = new LeavePeriodService();
        $leavePeriodService->setLeavePeriodDao(new LeavePeriodDao());
        $leavePeriod = new LeavePeriod();
        $leavePeriod->setStartDate($leavePeriodData['leavePeriod']['1']['startDate']);
        $leavePeriod->setEndDate($leavePeriodData['leavePeriod']['1']['endDate']);
        $leavePeriodService->saveLeavePeriod($leavePeriod);
        $this->leavePeriod = $leavePeriod;
        $this->leavePeriodId = $leavePeriod->getLeavePeriodId();

        // Save Employee
        $employeeservice = new EmployeeService();
        $this->employee = new Employee();
        $employeeservice->addEmployee($this->employee);
        $this->empNumber = $this->employee->getEmpNumber();

        // save leave quota
        $this->leaveEntitlement = sfYaml::load(sfConfig::get('sf_plugins_dir') . '/orangehrmCoreLeavePlugin/test/fixtures/leaveEntitlement.yml');
        $this->leaveEntitlementDao = new LeaveEntitlementDao();
    }

    public function tearDown() {

        $q = Doctrine_Query::create()
                ->delete('Employee em')
                ->where('em.empNumber=?', $this->empNumber);

        $q->execute();

        $q = Doctrine_Query::create()
                ->delete('LeaveType lt')
                ->where('lt.leaveTypeId=?', $this->leaveTypeId);

        $q->execute();

        $q = Doctrine_Query::create()
                ->delete('LeavePeriod lp')
                ->where('lp.leavePeriodId=?', $this->leavePeriodId);

        $q->execute();
    }

    /**
     * 
     * @cover getEmployeeLeaveEntitlement
     */
    public function testGetEmployeeLeaveEntitlement() {
        $result = $this->leaveEntitlementDao->getEmployeeLeaveEntitlement($this->employee->getEmpNumber(), $this->leaveType->getLeaveTypeId(), $this->leavePeriod->getLeavePeriodId());
        $this->assertFalse($result);
    }

    /**
     * @cover saveEmployeeLeaveEntitlement
     * @return unknown_type
     */
    public function testSaveEmployeeLeaveEntitlement() {
        $result = $this->leaveEntitlementDao->saveEmployeeLeaveEntitlement($this->employee->getEmpNumber(), $this->leaveType->getLeaveTypeId(), $this->leavePeriod->getLeavePeriodId(), 10);
        $this->assertTrue($result);
    }

    /**
     * @expectedException DaoException
     */
    public function testSaveEmployeeLeaveEntitlementForEmpty() {
        $result = $this->leaveEntitlementDao->saveEmployeeLeaveEntitlement(null, null, null, null);
    }

    /**
     * 
     * @return unknown_type
     */
    public function testReadEmployeeLeaveEntitlement() {
        $this->leaveEntitlementDao->saveEmployeeLeaveEntitlement($this->employee->getEmpNumber(), $this->leaveType->getLeaveTypeId(), $this->leavePeriod->getLeavePeriodId(), 7);
        $result = $this->leaveEntitlementDao->readEmployeeLeaveEntitlement($this->employee->getEmpNumber(), $this->leaveType->getLeaveTypeId(), $this->leavePeriod->getLeavePeriodId());
        $this->assertTrue($result instanceof EmployeeLeaveEntitlement);
    }

    /**
     * @cover overwriteEmployeeLeaveEntitlement
     * @return unknown_type
     */
    public function testOverwriteEmployeeLeaveEntitlement() {
        $this->leaveEntitlementDao->saveEmployeeLeaveEntitlement($this->employee->getEmpNumber(), $this->leaveType->getLeaveTypeId(), $this->leavePeriod->getLeavePeriodId(), 10);
        $result = $this->leaveEntitlementDao->overwriteEmployeeLeaveEntitlement($this->employee->getEmpNumber(), $this->leaveType->getLeaveTypeId(), $this->leavePeriod->getLeavePeriodId(), 12);
        $this->assertTrue($result);
    }

    /**
     * check for null leave carried forward
     * @covers LeaveEntitlementDao::saveEmployeeLeaveCarriedForward
     * @return void
     */
    public function testSaveEmployeeLeaveCarriedForwardForEmpty() {
        try {
            $result = $this->leaveEntitlementDao->saveEmployeeLeaveCarriedForward(null, null, null, 10);
        } catch (DaoException $e) {
            return true;
        }

        $this->fail('An Expected exception was not returned');
    }

    /**
     * check for null leave Brought forward
     * @covers LeaveEntitlementDao::saveEmployeeLeaveBroughtForward
     * @return void
     */
    public function testSaveEmployeeLeaveBroughtForwardForEmpty() {
        try {
            $result = $this->leaveEntitlementDao->saveEmployeeLeaveBroughtForward(null, null, null, 10);
        } catch (DaoException $e) {
            return true;
        }

        $this->fail('An Expected exception was not returned');
    }

    /**
     *
     * @covers LeaveEntitlementDao::saveEmployeeLeaveCarriedForward
     * @return void
     */
    public function testSaveEmployeeLeaveCarriedForward() {
        $this->leaveEntitlementDao->saveEmployeeLeaveEntitlement($this->employee->getEmpNumber(), $this->leaveType->getLeaveTypeId(), $this->leavePeriod->getLeavePeriodId(), 10);
        $result = $this->leaveEntitlementDao->saveEmployeeLeaveCarriedForward($this->employee->getEmpNumber(), $this->leaveType->getLeaveTypeId(), $this->leavePeriod->getLeavePeriodId(), 12);
        $this->assertTrue($result);
    }

    /**
     *
     * @covers LeaveEntitlementDao::saveEmployeeLeaveBroughtForward
     * @return void
     */
    public function testSaveEmployeeLeaveBroughtForward() {
        $this->leaveEntitlementDao->saveEmployeeLeaveEntitlement($this->employee->getEmpNumber(), $this->leaveType->getLeaveTypeId(), $this->leavePeriod->getLeavePeriodId(), 10);
        $result = $this->leaveEntitlementDao->saveEmployeeLeaveBroughtForward($this->employee->getEmpNumber(), $this->leaveType->getLeaveTypeId(), $this->leavePeriod->getLeavePeriodId(), 12);
        $this->assertTrue($result);
    }

}