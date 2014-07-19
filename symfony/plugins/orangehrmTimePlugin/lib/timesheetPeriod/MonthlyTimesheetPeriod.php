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

class MonthlyTimesheetPeriod extends TimesheetPeriod {

    public function calculateDaysInTheTimesheetPeriod($currentDate, $xml) {

        $startDay = (String) $xml->StartDate;
        ;
        list($year, $month, $day) = explode("-", $currentDate);
        if ($startDay <= $day) {
            $start_of_month = mktime(00, 00, 00, $month, $startDay, $year);
            $end_of_month = mktime(23, 59, 59, $month + 1, $startDay, $year);
        } else {
            $start_of_month = mktime(00, 00, 00, $month - 1, $startDay, $year);
            $end_of_month = mktime(23, 59, 59, $month, $startDay, $year);
        }
        $startDate = date('Y-m-d', $start_of_month);
        $endDate = date('Y-m-d', $end_of_month);

        if ($startDate < $endDate) {
            $dates_range[] = $startDate;
            $startDate = strtotime($startDate);
            $endDate = strtotime($endDate);
            while ($startDate != $endDate) {
                $startDate = mktime(0, 0, 0, date("m", $startDate), date("d", $startDate) + 1, date("Y", $startDate));
                $dates_range[] = date('Y-m-d', $startDate);
            }
        }
        array_pop($dates_range);
        return $dates_range;
    }

    public function setTimesheetPeriodAndStartDate($startDay) {

        return "<TimesheetPeriod><PeriodType>Monthly</PeriodType><ClassName>MonthlyTimesheetPeriod</ClassName><StartDate>" . $startDay . "</StartDate><Heading>Month</Heading></TimesheetPeriod>";
    }

    public function getDatesOfTheTimesheetPeriod($startDate, $endDate) {
        
         $userObj = sfContext::getInstance()->getUser()->getAttribute('user');
        $clientTimeZoneOffset = $userObj->getUserTimeZoneOffset();
        $serverTimezoneOffset = ((int) date('Z'));
        $timeStampDiff = $clientTimeZoneOffset * 3600 - $serverTimezoneOffset;
        
        

        if ($startDate < $endDate) {
            $dates_range[] = $startDate;
            
            $startDate = strtotime($startDate) + $timeStampDiff;
            $endDate = strtotime($endDate) + $timeStampDiff;
            while (date('Y-m-d', $startDate) != date('Y-m-d', $endDate)) {
                $startDate = mktime(0, 0, 0, date("m", $startDate), date("d", $startDate) + 1, date("Y", $startDate));
                $dates_range[] = date('Y-m-d', $startDate);
            }
        }
        return $dates_range;
    }

}

?>
