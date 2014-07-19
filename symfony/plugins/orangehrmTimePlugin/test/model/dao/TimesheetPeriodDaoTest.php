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
 * Description of TimesheetPeriodDaoTest
 *
 * @group Time
 */
class TimesheetPeriodDaoTest extends PHPUnit_Framework_TestCase {

	private $timesheetPeriodDao;

	/**
	 * Set up method
	 */
	protected function setUp() {
        TestDataService::truncateTables(array('Config'));
        TestDataService::populate(sfConfig::get('sf_plugins_dir') . '/orangehrmTimePlugin/test/fixtures/TimesheetPeriodDao.yml');
        $this->timesheetPeriodDao = new TimesheetPeriodDao();
        
        
	}

	public function testGetDefinedTimesheetPeriod() {

		$xmlString = $this->timesheetPeriodDao->getDefinedTimesheetPeriod();
		$this->assertEquals('<TimesheetPeriod><PeriodType>Weekly</PeriodType><ClassName>WeeklyTimesheetPeriod</ClassName><StartDate>1</StartDate><Heading>Week</Heading></TimesheetPeriod>', $xmlString);
	}

	public function testIsTimesheetPeriodDefined() {
		$isAllowed = $this->timesheetPeriodDao->isTimesheetPeriodDefined();
		//$this->assertEquals("Yes", $isAllowed);
	}

	public function testSetTimesheetPeriod() {
		$temp = $this->timesheetPeriodDao->setTimesheetPeriod();
		$this->assertTrue($temp);
	}

	public function testSetTimesheetPeriodAndStartDate() {
		$xml = "<TimesheetPeriod><PeriodType>Weekly</PeriodType><ClassName>WeeklyTimesheetPeriod</ClassName><StartDate>3</StartDate><Heading>Week</Heading></TimesheetPeriod>";
		$temp = $this->timesheetPeriodDao->setTimesheetPeriodAndStartDate($xml);
		$this->assertTrue($temp);
	}

}

?>
