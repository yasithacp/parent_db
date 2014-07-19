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


class WorkWeekService extends BaseService {

    protected $workWeekDao;

    /**
     * Get the WorkWeek Service
     * @return WorkWeekDao
     */
    public function getWorkWeekDao() {
        if (!($this->workWeekDao instanceof WorkWeekDao)) {
            $this->workWeekDao = new WorkWeekDao();
        }
        return $this->workWeekDao;
    }

    /**
     * Set the WorkWeek Service
     *
     * @param DayOffDao $DayOffDao
     * @return void
     */
    public function setWorkWeekDao(WorkWeekDao $workWeekDao) {
        $this->workWeekDao = $workWeekDao;
    }

    /**
     * Add, Update WorkWeek
     * @param DayOff $dayOff
     * @return boolean
     */
    public function saveWorkWeek(WorkWeek $workWeek) {
        return $this->getWorkWeekDao()->saveWorkWeek($workWeek);
    }

    /**
     * Delete WorkWeek
     * @param Integer $day
     * @return boolean
     */
    public function deleteWorkWeek($day) {
        return $this->getWorkWeekDao()->deleteWorkWeek($day);
    }

    /**
     * Read WorkWeek by given day
     * @param $day
     * @return $workWeek DayOff
     */
    public function readWorkWeek($day) {
        $workWeek = $this->getWorkWeekDao()->readWorkWeek($day);

        if (!$workWeek instanceof WorkWeek) {
            $workWeek = new WorkWeek();
        }

        return $workWeek;
    }

    /**
     *
     * @param integer $offset
     * @param integer $limit
     * @return attay Array of WorkWeek Objects
     */
    public function getWorkWeekList($offset = 0, $limit = 10) {
        $workWeekList = $this->getWorkWeekDao()->getWorkWeekList($offset, $limit);
        return $workWeekList;
    }

    /**
     *
     * @param $day
     * @return boolean
     */
    public function isWeekend($day, $fullDay, $operationalCountryId = null) {
        return $this->getWorkWeekDao()->isWeekend($day, $fullDay, $operationalCountryId);
    }
    
    /**
     *
     * @param int $workWeekId 
     * @return WorkWeek
     */
    public function getWorkWeekOfOperationalCountry($operationalCountryId = null) {
        try {
            return $this->getWorkWeekDao()->searchWorkWeek(array('operational_country_id' => $operationalCountryId))->getFirst();
        } catch (Exception $e) {
            throw new LeaveServiceException($e->getMessage());
        }
    }

}
