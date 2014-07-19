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
 * ohrmDateValidator validates dates in the current date format.
 */
class ohrmDateValidator extends sfValidatorBase {
    const OUTPUT_FORMAT = 'Y-m-d';

    /**
     * Configure validator.
     * Output format is always yyyy-mm-dd
     * 
     * @param <type> $options
     * @param <type> $messages
     */
    protected function configure($options = array(), $messages = array()) {

        $this->addMessage('bad_format', '"%value%" does not match the date format (%date_format%).');
        $this->addOption('date_format', null);
        $this->addOption('date_format_error');
        $this->addOption('min', null);
        $this->addOption('max', null);
    }

    /**
     * @see sfValidatorBase
     */
    protected function doClean($value) {

        $date = null;
        $valid = false;

        $trimmedValue = trim($value);
        $pattern = $this->getOption('date_format');

        // If not required and empty or the format pattern, return valid.
        if (!$this->getOption('required') &&
                ( ($trimmedValue == '') || (strcasecmp($trimmedValue, get_datepicker_date_format($pattern)) == 0 ) )) {
            return null;
        }
        $localizationService = new LocalizationService();
        $result = $localizationService->convertPHPFormatDateToISOFormatDate($pattern, $trimmedValue);
        $valid = ($result == "Invalid date") ? false : true;
        if (!$valid) {
            throw new sfValidatorError($this, 'bad_format', array('value' => $value, 'date_format' => $this->getOption('date_format_error') ? $this->getOption('date_format_error') : get_datepicker_date_format($pattern)));
        }
        return $result;
    }

}
