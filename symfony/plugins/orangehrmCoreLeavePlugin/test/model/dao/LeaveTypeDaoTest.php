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


require_once sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

/**
 * @group CoreLeave 
 */
class LeaveTypeDaoTest extends PHPUnit_Framework_TestCase {

    public $leaveTypeDao;

    protected function setUp() {

        $this->leaveTypeDao = new LeaveTypeDao();
        TestDataService::populate(sfConfig::get('sf_plugins_dir') . '/orangehrmCoreLeavePlugin/test/fixtures/LeaveTypeDao.yml');
    }

    /* Common methods */

    private function _getLeaveTypeObjectWithValues() {

        $leaveType = new LeaveType();
        $leaveType->setLeaveTypeName('Special');
        $leaveType->setAvailableFlag(1);

        return $leaveType;
    }

    /* Testing saveLeaveType() */

    public function testSaveLeaveTypeReturnValue() {
        TestDataService::truncateTables(array('LeaveType'));
        $this->assertTrue($this->leaveTypeDao->saveLeaveType($this->_getLeaveTypeObjectWithValues()));
    }

    public function testSaveLeaveTypeCheckSavedType() {
        TestDataService::truncateTables(array('LeaveType'));

        $this->leaveTypeDao->saveLeaveType($this->_getLeaveTypeObjectWithValues());

        $savedLeaveTypes = TestDataService::fetchLastInsertedRecords('LeaveType', 1);

        $this->assertEquals('LTY001', $savedLeaveTypes[0]->getLeaveTypeId());
        $this->assertEquals('Special', $savedLeaveTypes[0]->getLeaveTypeName());
    }

    /**
     * @expectedException DaoException
     */
    public function testSaveLeaveTypeDuplicateKey() {

        /* Following creates LTY008 and makes the entry in hs_hr_unique_id 8 */
        $this->leaveTypeDao->saveLeaveType($this->_getLeaveTypeObjectWithValues());

        /* Changing entry in hs_hr_unique_id to 7 */
        TestDataService::adjustUniqueId(LeaveType, 7, true);

        /* Following should throw an exception for LTY008 */
        $this->leaveTypeDao->saveLeaveType($this->_getLeaveTypeObjectWithValues());
    }

    public function testSaveLeaveTypeWithOperationalCountry() {

        TestDataService::truncateSpecificTables(array('LeaveType'));

        $leaveType = $this->_getLeaveTypeObjectWithValues();
        $leaveType->setOperationalCountryId(1);

        $this->leaveTypeDao->saveLeaveType($leaveType);

        $savedLeaveType = TestDataService::fetchLastInsertedRecord('LeaveType', 'leave_type_id');
        $this->assertEquals(1, $savedLeaveType->getOperationalCountryId());
    }

    public function testSaveLeaveTypeWithoutOperationalCountry() {

        TestDataService::truncateSpecificTables(array('LeaveType'));

        $leaveType = $this->_getLeaveTypeObjectWithValues();

        $this->leaveTypeDao->saveLeaveType($leaveType);

        $savedLeaveType = TestDataService::fetchLastInsertedRecord('LeaveType', 'leave_type_id');
        $this->assertTrue(is_null($savedLeaveType->getOperationalCountryId()));
    }

    /**
     * @expectedException DaoException
     */
    public function testSaveLeaveTypeWithInvalidOperationalCountry() {

        $leaveType = $this->_getLeaveTypeObjectWithValues();
        $leaveType->setOperationalCountryId(41);

        $this->leaveTypeDao->saveLeaveType($leaveType);
    }

    /* Testing deleteLeaveType() */

    public function testDeleteLeaveTypeReturnValue() {

        $this->assertTrue($this->leaveTypeDao->deleteLeaveType(array('LTY001', 'LTY002')));
        $this->assertTrue($this->leaveTypeDao->deleteLeaveType(array('LTY004')));
    }

    public function testDeleteLeaveTypeValues() {

        $this->assertTrue($this->leaveTypeDao->deleteLeaveType(array('LTY001')));
        $deletedTypeObject = TestDataService::fetchObject('LeaveType', 'LTY001');

        $this->assertEquals('LTY001', $deletedTypeObject->getLeaveTypeId());
        $this->assertEquals(0, $deletedTypeObject->getAvailableFlag());
    }

    public function testDeleteLeaveTypeList() {

        $this->assertTrue($this->leaveTypeDao->deleteLeaveType(array('LTY001', 'LTY002')));

        $leaveTypeList = $this->leaveTypeDao->getDeletedLeaveTypeList();

        $this->assertEquals(4, count($leaveTypeList));

        $this->assertEquals('LTY001', $leaveTypeList[0]->getLeaveTypeId());
        $this->assertEquals('LTY002', $leaveTypeList[1]->getLeaveTypeId());
    }

    /* Testing getLeaveTypeList() */

    public function testGetLeaveTypeListObjectTypes() {

        $leaveTypeList = $this->leaveTypeDao->getLeaveTypeList();

        foreach ($leaveTypeList as $leaveTypeObj) {
            $this->assertTrue($leaveTypeObj instanceof LeaveType);
        }
    }

    public function testGetLeaveTypeListCount() {

        $leaveTypeList = $this->leaveTypeDao->getLeaveTypeList();
        $this->assertEquals(5, count($leaveTypeList));
    }

    public function testGetLeaveTypeListWrongResult() {

        $leaveTypeList = $this->leaveTypeDao->getLeaveTypeList();

        foreach ($leaveTypeList as $leaveTypeObj) {
            $this->assertNotEquals('LTY003', $leaveTypeObj->getLeaveTypeId());
            $this->assertNotEquals('Company', $leaveTypeObj->getLeaveTypeName());
        }
    }

    public function testGetLeaveTypeListValuesAndOrder() {

        $leaveTypeList = $this->leaveTypeDao->getLeaveTypeList();

        $this->assertEquals('LTY004', $leaveTypeList[0]->getLeaveTypeId());
        $this->assertEquals('Annual', $leaveTypeList[0]->getLeaveTypeName());

        $this->assertEquals('LTY006', $leaveTypeList[4]->getLeaveTypeId());
        $this->assertEquals('Wesak', $leaveTypeList[4]->getLeaveTypeName());
    }

    public function testGetLeaveTypeListForOperationalCountry() {
        $leaveTypeList = $this->leaveTypeDao->getLeaveTypeList(1);
        $this->assertEquals(2, count($leaveTypeList));
        $this->assertEquals('LTY004', $leaveTypeList[0]->getLeaveTypeId());
        $this->assertEquals('LTY001', $leaveTypeList[1]->getLeaveTypeId());

        $leaveTypeList = $this->leaveTypeDao->getLeaveTypeList(2);
        $this->assertEquals(2, count($leaveTypeList));
        $this->assertEquals('LTY007', $leaveTypeList[0]->getLeaveTypeId());
        $this->assertEquals('LTY002', $leaveTypeList[1]->getLeaveTypeId());
    }

    /* Testing getDeletedLeaveTypeList() */

    public function testGetDeletedLeaveTypeListObjectTypes() {

        $leaveTypeList = $this->leaveTypeDao->getDeletedLeaveTypeList();

        foreach ($leaveTypeList as $leaveTypeObj) {
            $this->assertTrue($leaveTypeObj instanceof LeaveType);
        }
    }

    public function testGetDeletedLeaveTypeListCount() {

        $leaveTypeList = $this->leaveTypeDao->getDeletedLeaveTypeList();
        $this->assertEquals(2, count($leaveTypeList));
    }

    public function testGetDeletedLeaveTypeListWrongResult() {

        $leaveTypeList = $this->leaveTypeDao->getDeletedLeaveTypeList();

        foreach ($leaveTypeList as $leaveTypeObj) {
            $this->assertNotEquals('LTY001', $leaveTypeObj->getLeaveTypeId());
            $this->assertNotEquals('Casual', $leaveTypeObj->getLeaveTypeName());
        }
    }

    public function testGetDeletedLeaveTypeListValuesAndOrder() {

        $leaveTypeList = $this->leaveTypeDao->getDeletedLeaveTypeList();

        $this->assertEquals('LTY003', $leaveTypeList[0]->getLeaveTypeId());
        $this->assertEquals('Company', $leaveTypeList[0]->getLeaveTypeName());

        $this->assertEquals('LTY005', $leaveTypeList[1]->getLeaveTypeId());
        $this->assertEquals('Lesure', $leaveTypeList[1]->getLeaveTypeName());
    }

    public function testGetDeletedLeaveTypeListForOperationalCountry() {
        $leaveTypeList = $this->leaveTypeDao->getDeletedLeaveTypeList(1);
        $this->assertEquals(0, count($leaveTypeList));

        $leaveTypeList = $this->leaveTypeDao->getDeletedLeaveTypeList(2);
        $this->assertEquals(1, count($leaveTypeList));
        $this->assertEquals('LTY003', $leaveTypeList[0]->getLeaveTypeId());
    }

    /* Testing readLeaveType() */

    public function testReadLeaveTypeObjectType() {

        // Active type
        $this->assertTrue($this->leaveTypeDao->readLeaveType('LTY001') instanceof LeaveType);

        // Deleted type
        $this->assertTrue($this->leaveTypeDao->readLeaveType('LTY003') instanceof LeaveType);
    }

    public function testReadLeaveTypeObjectWrongArgument() {

        $this->assertFalse($this->leaveTypeDao->readLeaveType('Casual'));
    }

    public function testReadLeaveTypeValues() {

        $leaveTypeObject = $this->leaveTypeDao->readLeaveType('LTY001');

        $this->assertEquals('LTY001', $leaveTypeObject->getLeaveTypeId());
        $this->assertEquals('Casual', $leaveTypeObject->getLeaveTypeName());
    }

    /* Testing readLeaveTypeByName() */

    public function testReadLeaveTypeByNameType() {

        $this->assertTrue($this->leaveTypeDao->readLeaveTypeByName('Casual') instanceof LeaveType);
        $this->assertTrue($this->leaveTypeDao->readLeaveTypeByName('Casual ') instanceof LeaveType);
    }

    public function testReadLeaveTypeByNameValues() {

        $leaveTypeObject = $this->leaveTypeDao->readLeaveTypeByName('Casual');

        $this->assertEquals('LTY001', $leaveTypeObject->getLeaveTypeId());
        $this->assertEquals('Casual', $leaveTypeObject->getLeaveTypeName());
    }

    /* Testing undeleteLeaveType() */

    public function testUndeleteLeaveTypeReturnValue() {

        $this->assertTrue($this->leaveTypeDao->undeleteLeaveType('LTY003'));
        $this->assertFalse($this->leaveTypeDao->undeleteLeaveType('LTY001'));
    }

    public function testUndeleteLeaveTypeValues() {

        $this->assertTrue($this->leaveTypeDao->undeleteLeaveType('LTY003'));
        $undeletedTypeObject = TestDataService::fetchObject('LeaveType', 'LTY003');

        $this->assertEquals('LTY003', $undeletedTypeObject->getLeaveTypeId());
        $this->assertEquals(1, $undeletedTypeObject->getAvailableFlag());
    }

    public function testUndeleteLeaveTypeList() {

        $this->assertTrue($this->leaveTypeDao->undeleteLeaveType('LTY003'));

        $leaveTypeList = $this->leaveTypeDao->getLeaveTypeList();

        $this->assertEquals(6, count($leaveTypeList));
        $this->assertEquals('Company', $leaveTypeList[3]->getLeaveTypeName());
    }

}