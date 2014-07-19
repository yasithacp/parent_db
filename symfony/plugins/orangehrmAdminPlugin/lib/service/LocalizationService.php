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

class LocalizationService {

    public function convertPHPFormatDateToISOFormatDate($inputPHPFormat, $date) {
        $dateFormat = new sfDateFormat();
        try {
            $symfonyPattern = $this->__getSymfonyDateFormatPattern($inputPHPFormat);
            $dateParts = $dateFormat->getDate($date, $symfonyPattern);

            if (is_array($dateParts) && isset($dateParts['year']) && isset($dateParts['mon']) && isset($dateParts['mday'])) {

                $day = $dateParts['mday'];
                $month = $dateParts['mon'];
                $year = $dateParts['year'];

                // Additional check done for 3 digit years, or more than 4 digit years
                if (checkdate($month, $day, $year) && ($year >= 1000) && ($year <= 9999)) {
                    $dateTime = new DateTime();
                    $dateTime->setTimezone(new DateTimeZone(date_default_timezone_get()));
                    $dateTime->setDate($year, $month, $day);

                    $date = $dateTime->format('Y-m-d');
                    return $date;
                }
                else
                    return "Invalid date";
            }
        } catch (Exception $e) {
            return "Invalid date";
        }
    }

    private function __getSymfonyDateFormatPattern($pattern) {
        $symfonyDateFormat = "";

        $len = strlen($pattern);

        for ($i = 0; $i < $len; $i++) {
            $char = $pattern{$i};
            switch ($char) {
                case 'j':
                    $symfonyDateFormat .= 'd';
                    break;
                case 'd':
                    $symfonyDateFormat .= 'dd';
                    break;
                case 'D':
                    $symfonyDateFormat .= 'EE';
                    break;
                case 'l':
                    $symfonyDateFormat .= 'EEEE';
                    break;
                case 'n':
                    $symfonyDateFormat .= 'M';
                    break;
                case 'm':
                    $symfonyDateFormat .= 'MM';
                    break;
                case 'M':
                    $symfonyDateFormat .= 'MMM';
                    break;
                case 'F':
                    $symfonyDateFormat .= 'MMMM';
                    break;
                case 'y':
                    $symfonyDateFormat .= 'yy';
                    break;
                case 'Y':
                    $symfonyDateFormat .= 'y';
                    break;
                default:
                    $symfonyDateFormat .= $char;
                    break;
            }
        }
        return $symfonyDateFormat;
    }

    public function getSupportedLanguageListFromYML() {
        $languageList = array();
        $languages = sfYaml::load(sfConfig::get("sf_plugins_dir") . '/orangehrmAdminPlugin/config/supported_languages.yml');
        foreach ($languages['languages'] as $lang) {
            $languageList[$lang['key']] = $lang['value'];
        }
        return $languageList;
    }

}
