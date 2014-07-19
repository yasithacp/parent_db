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

class TimesheetPeriodService {

	private $timesheetPeriodDao;

	/**
	 * Get the TimesheetPeriod Data Access Object
	 * @return TimesheetPeriodDao
	 */
	public function getTimesheetPeriodDao() {


		if (is_null($this->timesheetPeriodDao)) {
			$this->timesheetPeriodDao = new TimesheetPeriodDao();
		}

		return $this->timesheetPeriodDao;
	}

	public function setTimesheetPeriodDao(TimesheetPeriodDao $timesheetPeriodDao) {

		$this->timesheetPeriodDao = $timesheetPeriodDao;
	}

	public function getDefinedTimesheetPeriod($currentDate) {

		$xmlString = $this->getTimesheetPeriodDao()->getDefinedTimesheetPeriod();
		$xml = simplexml_load_String($xmlString);
       

		return $this->getDaysOfTheTimesheetPeriod($xml, $currentDate);
	}

	public function getDaysOfTheTimesheetPeriod($xml, $currentDate) {

		$timesheetPeriodFactory = new TimesheetPeriodFactory();
		$timesheetPeriodObject = $timesheetPeriodFactory->createTimesheetPeriod($xml);
		return $timesheetPeriodObject->calculateDaysInTheTimesheetPeriod($currentDate, $xml);
	}

	public function isTimesheetPeriodDefined() {
		return $this->getTimesheetPeriodDao()->isTimesheetPeriodDefined();
	}

	public function setTimesheetPeriod($startDay) {

		$timesheetPeriodFactory = new TimesheetPeriodFactory();
		$timesheetPeriodObject = $timesheetPeriodFactory->setTimesheetPeriod();
		$xml = $timesheetPeriodObject->setTimesheetPeriodAndStartDate($startDay);
		$this->getTimesheetPeriodDao()->setTimesheetPeriod();
		return $this->getTimesheetPeriodDao()->setTimesheetPeriodAndStartDate($xml);
	}

    public function getTimesheetHeading(){
        
        $xmlString = $this->getTimesheetPeriodDao()->getDefinedTimesheetPeriod();
		$xml = simplexml_load_String($xmlString);
        
        return $xml->Heading;
       
        
    }


}

?>
