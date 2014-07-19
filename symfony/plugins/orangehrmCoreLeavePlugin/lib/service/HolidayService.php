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


class HolidayService extends BaseService {

    // Holiday Data Access Object
    private $holidayDao;

    /**
     * Get the Holiday Data Access Object
     * @return HolidayDao
     */
    public function getHolidayDao() {
        if (is_null($this->holidayDao)) {
            $this->holidayDao = new HolidayDao();
        }
        return $this->holidayDao;
    }

    /**
     * Set Holiday Data Access Object
     * @param HolidayDao $HolidayDao
     * @return void
     */
    public function setHolidayDao(HolidayDao $HolidayDao) {
        $this->holidayDao = $HolidayDao;
    }

    /**
     * Add, Update Holidays
     * @param Holiday $holiday
     * @return boolean
     */
    public function saveHoliday(Holiday $holiday) {

        return $this->getHolidayDao()->saveHoliday($holiday);
    }

    /**
     * Delete Holiday
     * @param int $holidayId
     * @return boolean
     */
    public function deleteHoliday($holidayId) {

        return $this->getHolidayDao()->deleteHoliday($holidayId);
    }

    /**
     * Read Holiday by given holidayId
     * @param int $holidayId
     * @return Holiday $Holiday
     */
    public function readHoliday($holidayId) {

        $holiday = $this->getHolidayDao()->readHoliday($holidayId);

        if (!$holiday instanceof Holiday) {
            $holiday = new Holiday();
        }

        return $holiday;
    }

    /**
     * Read Holiday by given Date
     * @param date $date
     * @param OperationalCountry $operationalCountry
     * @return Holiday $holiday
     */
    public function readHolidayByDate($date, OperationalCountry $operationalCountry = null) {

        $holiday = $this->getHolidayDao()->readHolidayByDate($date, $operationalCountry);

        if (!$holiday instanceof Holiday) {
            $holiday = new Holiday();
        }

        return $holiday;
    }

    /**
     * Get Holiday list
     * @param int $year
     * @param OperationalCountry $operationalCountry
     * @param int $offset
     * @param int $limit
     * @return Holidays $holidayList
     */
    public function getHolidayList($year = null, OperationalCountry $operationalCountry = null, $offset = 0, $limit = 50) {
        $holidayList = $this->getHolidayDao()->getHolidayList($year, $operationalCountry, $offset, $limit);
        return $holidayList;
    }

    /**
     * Search Holidays within a given leave period
     * @param String $startDate
     * @param String $endDate
     * @return Holidays
     */
    public function searchHolidays($startDate = null, $endDate = null) {

        $holidayList = array();
        $holidayList = $this->getHolidayDao()->searchHolidays($startDate, $endDate);

        $startDateTimeStamp = (is_null($startDate)) ? strtotime(date("Y-m-d")) : strtotime($startDate);
        $endDateTimeStamp = (is_null($endDate)) ? strtotime(date("Y-m-d")) : strtotime($endDate);

        $formattedHolidayList = array();
        foreach ($holidayList as $holiday) {
            if ($holiday->getRecurring() == 1) {
                $startYearRecurring = date("Y", $startDateTimeStamp) . '-' . date("m", strtotime($holiday->getDate())) . '-' . date("d", strtotime($holiday->getDate()));
                $startYearRecurringTimeStamp = strtotime($startYearRecurring);
                if (($startYearRecurringTimeStamp >= $startDateTimeStamp) && ($startYearRecurringTimeStamp <= $endDateTimeStamp)) {
                    $holiday->setDate(date("Y-m-d", $startYearRecurringTimeStamp));
                    $formattedHolidayList[] = $holiday;
                } else {
                    $endYearRecurring = date("Y", $endDateTimeStamp) . '-' . date("m", strtotime($holiday->getDate())) . '-' . date("d", strtotime($holiday->getDate()));
                    $endYearRecurringTimeStamp = strtotime($endYearRecurring);
                    if (($endYearRecurringTimeStamp >= $startDateTimeStamp) && ($endYearRecurringTimeStamp <= $endDateTimeStamp)) {
                        $holiday->setDate(date("Y-m-d", $endYearRecurringTimeStamp));
                        $formattedHolidayList[] = $holiday;
                    }
                }                
            } else {
                $formattedHolidayList[] = $holiday;
            }
        }

        return $formattedHolidayList;
    }

    /**
     * check whether the given date is a holiday
     *
     * @param date $day
     * @return boolean
     * 
     */
    public function isHoliday($day) {

        $holiday = $this->getHolidayDao()->readHolidayByDate($day);

        if ($holiday != null && $holiday->getLength() == WorkWeek::WORKWEEK_LENGTH_FULL_DAY) {
            return true;
        }

        return false;
    }

    /**
     * Findout whether day is a half day
     * @param Date $day
     * @returns boolean
     * @throws LeaveServiceException
     */
    public function isHalfDay($day) {

        $holiday = $this->getHolidayDao()->readHolidayByDate($day);

        if ($holiday != null && $holiday->getLength() >= WorkWeek::WORKWEEK_LENGTH_HALF_DAY && $holiday->getLength() < WorkShift::DEFAULT_WORK_SHIFT_LENGTH) {
            return true;
        }

        return false;
    }

    /**
     * check whether the given date is a holiday
     *
     * @param date $day
     * @return boolean
     * 
     */
    public function isHalfdayHoliday($day) {

        $holiday = $this->getHolidayDao()->readHolidayByDate($day);

        if ($holiday != null && $holiday->getLength() == Holiday::HOLIDAY_HALF_DAY_LENGTH) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get Holiday full holiday list
     * @return Holidays $holidayList
     */
    public function getFullHolidayList() {

        $holidayList = $this->getHolidayDao()->getFullHolidayList();
        return $holidayList;
    }

}
