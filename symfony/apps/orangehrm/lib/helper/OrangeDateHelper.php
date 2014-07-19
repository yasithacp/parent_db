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
 * Formats date using current date format.
 *
 * @param Date $date in YYYY-MM-DD format
 * @return formatted date.
 */

function set_datepicker_date_format($date) {

    $dateFormat = sfContext::getInstance()->getUser()->getDateFormat();

    if (empty($date)) {
        $formattedDate = null;
    } else {
        $dateArray = explode('-', $date);
        $dateTime = new DateTime();
        $year = $dateArray[0];
        $month = $dateArray[1];
        $day = $dateArray[2];
        
        // For timestamp fields, clean time part from $day (day will look like "21 00:00:00"
        $day = trim($day);
        $spacePos = strpos($day, ' ');
        if ($spacePos !== FALSE) {
            $day = substr($day, 0, $spacePos);
        }
        
        $dateTime->setDate($year, $month, $day);
        $formattedDate = $dateTime->format($dateFormat);
    }

    return $formattedDate;
}

function get_datepicker_date_format($symfonyDateFormat) {
    $jsDateFormat = "";

    $len = strlen($symfonyDateFormat);

    for ($i = 0; $i < $len; $i++) {
        $char = $symfonyDateFormat{$i};
        switch ($char) {
            case 'j':
                $jsDateFormat .= 'd';
                break;
            case 'd':
                $jsDateFormat .= 'dd';
                break;
            case 'l':
                $jsDateFormat .= 'DD';
                break;
            case 'L':
                $jsDateFormat .= 'DD';
                break;
            case 'n':
                $jsDateFormat .= 'm';
                break;
            case 'm':
                $jsDateFormat .= 'mm';
                break;
            case 'F':
                $jsDateFormat .= 'MM';
                break;
            case 'Y':
                $jsDateFormat .= 'yy';
                break;
            default:
                $jsDateFormat .= $char;
                break;
        }
    }
    return($jsDateFormat);
}