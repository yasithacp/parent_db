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
 * @group CoreLeave 
 */
class HolidayDaoTest extends PHPUnit_Framework_TestCase {

    private $holidayDao;
    private $fixture;

    protected function setUp() {
        $this->holidayDao = new HolidayDao();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmCoreLeavePlugin/test/fixtures/HolidayDao.yml';
        TestDataService::populate($this->fixture);
    }

    /* test readHoliday */

    public function testReadHoliday() {

        $holiday = $this->holidayDao->readHoliday(1);

        $this->assertTrue($holiday instanceof Holiday);
        $this->assertEquals(1, $holiday->getRecurring());
        $this->assertEquals("2010-05-27", $holiday->getDate());
    }

    /**
     * @cover getHolidayList()
     */
    public function testGetHolidayList_RecurringHolidays() {

        $holidayList = $this->holidayDao->getHolidayList(2011);

        $this->assertTrue($holidayList instanceof Doctrine_Collection);
        $this->assertEquals(2, $holidayList->count());

        $holiday1 = $holidayList->get(0);
        $this->assertTrue($holiday1 instanceof Holiday);
        $this->assertEquals(1, $holiday1->getId());
        $this->assertEquals('Public Holiday', $holiday1->getDescription());

        $holiday1 = $holidayList->get(1);
        $this->assertTrue($holiday1 instanceof Holiday);
        $this->assertEquals(3, $holiday1->getId());
        $this->assertEquals('Home Holiday', $holiday1->getDescription());
    }

    /**
     * @cover getHolidayList()
     */
    public function testGetHolidayList_NonRecurringHolidays() {

        $holidayList = $this->holidayDao->getHolidayList(2010);

        $this->assertTrue($holidayList instanceof Doctrine_Collection);
        $this->assertEquals(4, $holidayList->count());

        $sampleData = sfYaml::load($this->fixture);
        $sampleData = $sampleData['Holiday'];

        foreach ($holidayList as $index => $holiday) {
            $this->assertTrue($holiday instanceof Holiday);
            $this->assertEquals($sampleData[$index]['id'], $holiday->getId());
            $this->assertEquals($sampleData[$index]['date'], $holiday->getDate());
            $this->assertEquals($sampleData[$index]['length'], $holiday->getLength());
        }
    }

    /**
     * @cover getHolidayList()
     */
    public function testGetHolidayList_WithOperationalCountryFilter() {
        $sriLanka = new OperationalCountry();
        $sriLanka->setId(1);
        $sriLanka->setCountryCode('LK');

        $holidayList = $this->holidayDao->getHolidayList(2010, $sriLanka);

        $this->assertTrue($holidayList instanceof Doctrine_Collection);
        $this->assertEquals(1, $holidayList->count());

        $holiday1 = $holidayList->get(0);
        $this->assertTrue($holiday1 instanceof Holiday);
        $this->assertEquals(2, $holiday1->getId());
        $this->assertEquals('Fullmoon Day', $holiday1->getDescription());
    }

    /* test getHolidayList without passing year */

    public function testGetHolidayListWithoutPassingYear() {

        $holidayList = $this->holidayDao->getHolidayList();

        $this->assertTrue($holidayList instanceof Doctrine_Collection);
        $this->assertEquals(2, $holidayList->count());

        $holiday1 = $holidayList->get(0);
        $this->assertTrue($holiday1 instanceof Holiday);
        $this->assertEquals(1, $holiday1->getId());
        $this->assertEquals('Public Holiday', $holiday1->getDescription());

        $holiday1 = $holidayList->get(1);
        $this->assertTrue($holiday1 instanceof Holiday);
        $this->assertEquals(3, $holiday1->getId());
        $this->assertEquals('Home Holiday', $holiday1->getDescription());
    }

    /* count getHolidayList */

    public function testCountGetHolidayListWithYear() {

        $holidayList = $this->holidayDao->getHolidayList($year = "2010");
        $this->assertEquals(4, count($holidayList));
    }

    public function testCountGetHolidayListWithoutYear() {

        $holidayList = $this->holidayDao->getHolidayList();
        $this->assertEquals(2, count($holidayList));
    }

    /* test saveHoliday */

    public function testSaveHoliday() {

        $holiday = TestDataService::fetchObject('Holiday', 1);

        $holiday->setLength(4);
        $holiday->setRecurring(0);
        $holiday->setDate("2010-05-30");

        $this->holidayDao->saveHoliday($holiday);
        $savedHoliday = TestDataService::fetchObject('Holiday', $holiday->getId());

        $this->assertEquals($holiday->getLength(), $savedHoliday->getLength());
        $this->assertEquals($holiday->getRecurring(), $savedHoliday->getRecurring());
        $this->assertEquals($holiday->getDate(), $savedHoliday->getDate());
    }

    /* test saveHoliday without an Id */

    public function testSaveHolidayWithNoId() {

        $holiday = new Holiday();
        $holiday->setLength(4);
        $holiday->setDescription("for dummies");

        $savedHoliday = $this->holidayDao->saveHoliday($holiday);

        $this->assertEquals($holiday, $savedHoliday);
    }

    /* test deleteHoliday */

    public function testDeleteHoliday() {

        $this->assertTrue($this->holidayDao->deleteHoliday(array(1, 2)));
        $holiday = TestDataService::fetchObject('Holiday', 2);

        $this->assertFalse($holiday instanceof Holiday);
    }

    /* test readHolidayByDate */

    public function testReadHolidayByDate() {

        $matchedHoliday = $this->holidayDao->readHolidayByDate('2010-05-27');
        $this->assertTrue($matchedHoliday instanceof Holiday);
        $this->assertEquals(1, $matchedHoliday->getId());

        $sriLanka = new OperationalCountry();
        $sriLanka->setId(1);
        $sriLanka->setCountryCode('LK');

        $matchedHoliday = $this->holidayDao->readHolidayByDate('2010-05-28', $sriLanka);
        $this->assertTrue($matchedHoliday instanceof Holiday);
        $this->assertEquals(2, $matchedHoliday->getId());
    }

    /* test getFullHolidayList */

    public function testGetFullHolidayList() {

        $holidayList = $this->holidayDao->getFullHolidayList();
        foreach ($holidayList as $holiday) {

            $this->assertTrue($holiday instanceof Holiday);
        }
    }

    /* test SearchHolidays */

    public function testSearchHolidays() {
        $holidayList = $this->holidayDao->searchHolidays('2010-01-01', '2010-12-31');
        foreach ($holidayList as $holiday) {
            $this->assertTrue($holiday instanceof Holiday);
        }
    }

}