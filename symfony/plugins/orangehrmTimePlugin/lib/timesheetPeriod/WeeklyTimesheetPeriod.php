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

class WeeklyTimesheetPeriod extends TimesheetPeriod {

    private $startDate;

    public function calculateDaysInTheTimesheetPeriod($currentDate, $xml) {


        $userObj = sfContext::getInstance()->getUser()->getAttribute('user');
        $clientTimeZoneOffset = $userObj->getUserTimeZoneOffset();
        date_default_timezone_set($this->getLocalTimezone($clientTimeZoneOffset));
        $this->startDate = $xml->StartDate;
        $day = date('N', strtotime($currentDate));

        $diff = $this->startDate - $day;
        if ($diff > 0) {
            $diff-=7;
        }

        $sign = ($diff < 0) ? "" : "+";

        $r = mktime('0', '0', '0', date('m', strtotime("{$sign}{$diff} day", strtotime($currentDate))), date('d', strtotime("{$sign}{$diff} day", strtotime($currentDate))), date('Y', strtotime("{$sign}{$diff} day", strtotime($currentDate))));

        for ($i = 0; $i < 7; $i++) {
            $dates[$i] = date("Y-m-d H:i", strtotime("+" . $i . " day", $r));
        }

        return $dates;


    }

    public function setTimesheetPeriodAndStartDate($startDay) {

        return "<TimesheetPeriod><PeriodType>Weekly</PeriodType><ClassName>WeeklyTimesheetPeriod</ClassName><StartDate>" . $startDay . "</StartDate><Heading>Week</Heading></TimesheetPeriod>";
    }

    public function getLocalTimezone($clientTimeZoneOffset) {


        $offset = $clientTimeZoneOffset;
        $zonelist =
                array
                    (
                    'Kwajalein' => -12.00,
                    'Pacific/Midway' => -11.00,
                    'Pacific/Honolulu' => -10.00,
                    'America/Anchorage' => -9.00,
                    'America/Los_Angeles' => -8.00,
                    'America/Denver' => -7.00,
                    'America/Tegucigalpa' => -6.00,
                    'America/New_York' => -5.00,
                    'America/Caracas' => -4.50,
                    'America/Halifax' => -4.00,
                    'America/St_Johns' => -3.50,
                    'America/Argentina/Buenos_Aires' => -3.00,
                    'America/Sao_Paulo' => -3.00,
                    'Atlantic/South_Georgia' => -2.00,
                    'Atlantic/Azores' => -1.00,
                    'Europe/Dublin' => 0,
                    'Europe/Belgrade' => 1.00,
                    'Europe/Minsk' => 2.00,
                    'Asia/Kuwait' => 3.00,
                    'Asia/Tehran' => 3.50,
                    'Asia/Muscat' => 4.00,
                    'Asia/Yekaterinburg' => 5.00,
                    'Asia/Kolkata' => 5.50,
                    'Asia/Katmandu' => 5.45,
                    'Asia/Dhaka' => 6.00,
                    'Asia/Rangoon' => 6.50,
                    'Asia/Krasnoyarsk' => 7.00,
                    'Asia/Brunei' => 8.00,
                    'Asia/Seoul' => 9.00,
                    'Australia/Darwin' => 9.50,
                    'Australia/Canberra' => 10.00,
                    'Asia/Magadan' => 11.00,
                    'Pacific/Fiji' => 12.00,
                    'Pacific/Tongatapu' => 13.00
        );
        $index = array_keys($zonelist, $offset);
        if (sizeof($index) != 1)
            return false;
        return $index[0];
    }

}

?>
