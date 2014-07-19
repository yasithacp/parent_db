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

class ohrmListSummaryHelper {

    private static $collection = array();
    private static $count = array();

    /**
     *
     * @param mixed $value
     * @param string $function 
     */
    public static function collectValue($value, $function) {

        if (!isset(self::$collection[$function])) {
            self::$collection[$function] = 0;
            self::$count[$function] = 0;
        }

        self::$collection[$function] += $value;
        self::$count[$function]++;
    }

    /**
     *
     * @param string $function
     * @param mixed $decimals
     * @return mixed 
     */
    public static function getAggregateValue($function, $decimals) {
        $aggregateValue = null;

        switch ($function) {
            case 'SUM':
                if (isset(self::$collection['SUM'])) {
                    $aggregateValue = self::$collection['SUM'];
                }
                break;
            default:
                // TODO: Warn. Unsupported function
                break;
        }

        return number_format($aggregateValue, $decimals);
    }

}

