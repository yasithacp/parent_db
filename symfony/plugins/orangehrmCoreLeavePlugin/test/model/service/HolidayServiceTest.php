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
class HolidayServiceTest extends PHPUnit_Framework_TestCase {

    private $holidayService;
    private $fixture;


    protected function setUp() {
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmCoreLeavePlugin/test/fixtures/HolidayService.yml';
        $this->holidayService	=	new HolidayService();
    }

    /* testing setHolidayDao and getHolidayDao */

    public function testGetSetHolidayDao() {

        $holidayDao = new HolidayDao();
        $this->holidayService->setHolidayDao($holidayDao);

        $this->assertTrue($this->holidayService->getHolidayDao() instanceof HolidayDao);

    }

    /* test saveHoliday */

    public function testSaveHoliday() {

        $holidays   = TestDataService::loadObjectList('Holiday', $this->fixture, 'Holiday');
        $holiday    = $holidays[0];

        $holidayDao = $this->getMock('HolidayDao', array('saveHoliday'));
        $holidayDao->expects($this->once())
                ->method('saveHoliday')
                ->with($holiday)
                ->will($this->returnValue($holiday));

        $this->holidayService->setHolidayDao($holidayDao);

        $this->assertTrue($this->holidayService->saveHoliday($holiday) instanceof Holiday);

    }

    /* test readHoliday */

    public function testReadHoliday() {

        $holidays   = TestDataService::loadObjectList('Holiday', $this->fixture, 'Holiday');

        $holidayDao = $this->getMock('HolidayDao', array('readHoliday'));
        $holidayDao->expects($this->once())
                ->method('readHoliday')
                ->with(1)
                ->will($this->returnValue($holidays[0]));

        $this->holidayService->setHolidayDao($holidayDao);
        $readHoliday = $this->holidayService->readHoliday(1);

        $this->assertTrue($readHoliday instanceof Holiday);
        $this->assertEquals($holidays[0], $readHoliday);

    }

    /* test readHoliday returns null in Dao */

    public function testReadHolidayReturnsNullInDao() {

        $holidayDao = $this->getMock('HolidayDao', array('readHoliday'));
        $holidayDao->expects($this->once())
                ->method('readHoliday')
                ->with(10)
                ->will($this->returnValue(null));

        $this->holidayService->setHolidayDao($holidayDao);
        $readHoliday = $this->holidayService->readHoliday(10);

        $this->assertTrue($readHoliday instanceof Holiday);

    }

    /* test deleteHoliday */

    public function testDeleteHoliday() {

        $holidayDao = $this->getMock('HolidayDao', array('deleteHoliday'));
        $holidayDao->expects($this->once())
                ->method('deleteHoliday')
                ->with(array(1,2))
                ->will($this->returnValue(true));

        $this->holidayService->setHolidayDao($holidayDao);
        $this->assertTrue($this->holidayService->deleteHoliday(array(1, 2)));

    }

    /* test readHolidayByDate */

    public function testReadHolidayByDate() {

        $holidays   = TestDataService::loadObjectList('Holiday', $this->fixture, 'Holiday');

        $holidayDao = $this->getMock('HolidayDao', array('readHolidayByDate'));
        $holidayDao->expects($this->once())
                ->method('readHolidayByDate')
                ->with("2010-05-27")
                ->will($this->returnValue($holidays[0]));

        $this->holidayService->setHolidayDao($holidayDao);
        $this->assertTrue($this->holidayService->readHolidayByDate("2010-05-27") instanceof Holiday);

    }

    /* test readHolidayByDate returns null in Dao */

    public function testReadHolidayByDateReturnsNullInDao() {

        $holidayDao = $this->getMock('HolidayDao', array('readHolidayByDate'));
        $holidayDao->expects($this->once())
                ->method('readHolidayByDate')
                ->with("5555-10-21")
                ->will($this->returnValue(null));

        $this->holidayService->setHolidayDao($holidayDao);
        $this->assertTrue($this->holidayService->readHolidayByDate("5555-10-21") instanceof Holiday);

    }

    /* test isHoliday */

    public function testIsHoliday() {

        $holidays   = TestDataService::loadObjectList('Holiday', $this->fixture, 'Holiday');

        $holidayDao = $this->getMock('HolidayDao', array('readHolidayByDate'));
        $holidayDao->expects($this->once())
                ->method('readHolidayByDate')
                ->with("2010-05-28")
                ->will($this->returnValue($holidays[1]));

        $this->holidayService->setHolidayDao($holidayDao);

        $this->assertTrue($this->holidayService->isHoliday("2010-05-28"));
    }

    /* test isHoliday made to return false */

    public function testIsHolidayReturnsFalse() {

        $holidays   = TestDataService::loadObjectList('Holiday', $this->fixture, 'Holiday');

        $holidayDao = $this->getMock('HolidayDao', array('readHolidayByDate'));
        $holidayDao->expects($this->once())
                ->method('readHolidayByDate')
                ->with("5555-10-21")
                ->will($this->returnValue(null));

        $this->holidayService->setHolidayDao($holidayDao);

        $this->assertFalse($this->holidayService->isHoliday("5555-10-21"));
    }

    /* test getHolidayList */

    public function testGetHolidayList() {

        $holidays   = TestDataService::loadObjectList('Holiday', $this->fixture, 'Holiday');
        $holidayDao = $this->getMock('HolidayDao', array('getHolidayList'));
        $holidayDao->expects($this->once())
                ->method('getHolidayList')
                ->will($this->returnValue($holidays));

        $this->holidayService->setHolidayDao($holidayDao);

        $list = $this->holidayService->getHolidayList();
        $this->assertEquals(4, count($list));
        foreach($list as $holiday) {
            $this->assertTrue($holiday instanceof Holiday);
        }

    }

    /* test getFullHolidayList */

    public function testGetFullHolidayList() {

        $holidays   = TestDataService::loadObjectList('Holiday', $this->fixture, 'Holiday');
        $holidayDao = $this->getMock('HolidayDao', array('getFullHolidayList'));
        $holidayDao->expects($this->once())
                ->method('getFullHolidayList')
                ->will($this->returnValue($holidays));

        $this->holidayService->setHolidayDao($holidayDao);

        $list = $this->holidayService->getFullHolidayList();
        $this->assertEquals(4, count($list));

        foreach($list as $holiday) {
            $this->assertTrue($holiday instanceof Holiday);
        }

    }

    /* test isHalfDay */

    public function testIsHalfDay() {

        $holidays   = TestDataService::loadObjectList('Holiday', $this->fixture, 'Holiday');
        $holidayDao = $this->getMock('HolidayDao', array('readHolidayByDate'));
        $holidayDao->expects($this->once())
                ->method('readHolidayByDate')
                ->with("2010-05-28")
                ->will($this->returnValue($holidays[0]));

        $this->holidayService->setHolidayDao($holidayDao);
        $this->assertTrue($this->holidayService->isHalfDay("2010-05-28"));

    }

    /* test isHalfDay returns false */

    public function testIsHalfDayReturnsFalse() {

        $holidays   = TestDataService::loadObjectList('Holiday', $this->fixture, 'Holiday');
        $holidayDao = $this->getMock('HolidayDao', array('readHolidayByDate'));
        $holidayDao->expects($this->once())
                ->method('readHolidayByDate')
                ->with("5555-10-21")
                ->will($this->returnValue(null));

        $this->holidayService->setHolidayDao($holidayDao);
        $this->assertFalse($this->holidayService->isHalfDay("5555-10-21"));

    }

    /* test isHalfdayHoliday */

    public function testIsHalfdayHoliday() {

        $holidays   = TestDataService::loadObjectList('Holiday', $this->fixture, 'Holiday');

        $holidayDao = $this->getMock('HolidayDao', array('readHolidayByDate'));
        $holidayDao->expects($this->once())
                ->method('readHolidayByDate')
                ->with("2010-05-27")
                ->will($this->returnValue($holidays[0]));

        $this->holidayService->setHolidayDao($holidayDao);
        $this->assertTrue($this->holidayService->isHalfdayHoliday("2010-05-27"));

    }

    /* test isHalfdayHoliday */

    public function testIsHalfdayHolidayReturnsFalse() {

        $holidays   = TestDataService::loadObjectList('Holiday', $this->fixture, 'Holiday');

        $holidayDao = $this->getMock('HolidayDao', array('readHolidayByDate'));
        $holidayDao->expects($this->once())
                ->method('readHolidayByDate')
                ->with("2010-05-28")
                ->will($this->returnValue($holidays[1]));

        $this->holidayService->setHolidayDao($holidayDao);
        $this->assertFalse($this->holidayService->isHalfdayHoliday("2010-05-28"));

    }

    /* test searchHolidays */

    public function testSearchHolidays() {

        $holidays   = TestDataService::loadObjectList('Holiday', $this->fixture, 'Holiday');

        $holidayDao = $this->getMock('HolidayDao', array('searchHolidays'));
        $holidayDao->expects($this->once())
                ->method('searchHolidays')
                ->will($this->returnValue($holidays));

        $this->holidayService->setHolidayDao($holidayDao);
        $list = $this->holidayService->searchHolidays("2010-01-01", "2010-12-31");

        foreach($list as $holiday) {
            $this->assertTrue($holiday instanceof Holiday);
        }

    }

}
?>