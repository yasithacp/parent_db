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


class HolidayDao extends BaseDao {

    /**
     * Add and Update Holiday
     * @param Holiday $holiday
     * @return boolean
     */
    public function saveHoliday(Holiday $holiday) {

        try {
            $holiday->save();
            return $holiday;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Read Holiday by given holiday id
     * @param $holidayId
     * @return Holiday
     */
    public function readHoliday($holidayId) {
        try {
            $holiday = Doctrine::getTable('Holiday')
                    ->find($holidayId);

            return $holiday;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Read Holiday by given Date
     * @param $date
     * @return Holiday
     */
    public function readHolidayByDate($date, OperationalCountry $operationalCountry = null) {
        try {

            $q = Doctrine_Query::create()
                    ->from("Holiday")
                    ->where("date = ? OR (recurring=1 AND MONTH(date)=? AND DAY(date)=?)", array($date, date('m', strtotime($date)), date('d', strtotime($date))));

            if (!is_null($operationalCountry)) {
                $q->addWhere('operational_country_id = ?', $operationalCountry->getId());
            }

            $result = $q->fetchOne();

            return $result;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Delete Holiday by given holiday id
     * @param $holidayId
     * @return none
     */
    public function deleteHoliday($holiday) {
        try {
            $q = Doctrine_Query::create()
                    ->delete('Holiday')
                    ->whereIn('id', $holiday);
            $holidayDeleted = $q->execute();
            if ($holidayDeleted > 0) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Get Holiday List
     * @return Holiday Collection
     */
    public function getHolidayList($year = null, OperationalCountry $operationalCountry = null, $offset = 0, $limit = 10) {

        try {
            if (!isset($year)) {
                $year = date('Y');
            }
            $q = Doctrine_Query::create()
                    ->select('*')
                    ->addSelect("IF( h.recurring=1 && YEAR(h.date) <= {$year}, DATE_FORMAT(h.date, '{$year}-%m-%d'), h.date ) fdate")
                    ->from('Holiday h')
                    ->where('h.recurring = ? OR h.date >=?', array(1, "{$year}-01-01"))
                    ->orderBy('fdate ASC');
                    
            if (!is_null($operationalCountry)) {
                $q->addWhere('operational_country_id = ?', $operationalCountry->getId());
            }

            $q->offset($offset)->limit($limit);
            $holidayList = $q->execute();
            return $holidayList;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function searchHolidays($startDate = null, $endDate = null) {

        $startDateTimeStamp = (is_null($startDate)) ? strtotime(date("Y-m-d")) : strtotime($startDate);
        $endDateTimeStamp = (is_null($endDate)) ? strtotime(date("Y-m-d")) : strtotime($endDate);

        try {
            $q = Doctrine_Query::create()
                    ->select('*')
                    ->from('Holiday h')
                    ->where("h.recurring = 1 OR h.date BETWEEN '" . date("Y-m-d", $startDateTimeStamp) . "' AND '" . date("Y-m-d", $endDateTimeStamp) . "'")
                    ->orderBy('h.date ASC');

            $holidayList = $q->execute();
            return $holidayList;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Get Full Holiday List
     * @return Holiday Collection
     */
    public function getFullHolidayList() {

        try {

            $q = Doctrine_Query::create()
                    ->select('*')
                    ->from('Holiday')
                    ->orderBy('date ASC');

            $holidayList = $q->execute();
            return $holidayList;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

}
