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

class TimesheetPeriodDao {

    protected $configDao;
    
    public function setConfigDao($configDao) {
        $this->configDao = $configDao;
    }
    
    public function getConfigDao() {
        
        if (is_null($this->configDao)) {
            $this->configDao = new ConfigDao();
        }
        
        return $this->configDao;
        
    }

	public function getDefinedTimesheetPeriod() {

		try {
            return $this->getConfigDao()->getValue(ConfigService::KEY_TIMESHEET_PERIOD_AND_START_DATE);
 		} catch (Exception $ex) {
			throw new DaoException($ex->getMessage());
		}
	}

	public function isTimesheetPeriodDefined() {

		try {
            return $this->getConfigDao()->getValue(ConfigService::KEY_TIMESHEET_PERIOD_SET);
		} catch (Exception $ex) {
			throw new DaoException($ex->getMessage());
		}
	}

	public function setTimesheetPeriod() {

		try {
			$query = Doctrine_Query::create()
					->update('Config')
					->set("`value`",'?','Yes')
					->where("`key` ='timesheet_period_set' ");
	
			$query->execute();
			return true;
			
		} catch (Exception $ex) {
			throw new DaoException($ex->getMessage());
		}
	}

	public function setTimesheetPeriodAndStartDate($xml) {

		try {
			$query = Doctrine_Query::create()
					->update('Config')
					->set('`value`', '?', $xml)
					->where("`key` ='timesheet_period_and_start_date' ");
			$query->execute();
			return true;
			
		} catch (Exception $ex) {
			throw new DaoException($ex->getMessage());
		}
	}

}

