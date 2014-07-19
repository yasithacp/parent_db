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

class ohrmWidgetDateInterval extends ohrmWidgetDateRange {
        /**
     * This method generates the where clause part.
     * @param string $fieldNames
     * @param string $value
     * @return string
     */
    public function generateWhereClausePart($fieldNames, $dateRanges) {

        $fromDate = "1970-01-01";
        $toDate = date("Y-m-d");

        $fieldArray = explode(",", $fieldNames);
        $field1 = $fieldArray[0];
        $field2 = $fieldArray[1];

        if (($dateRanges["from"] != "YYYY-MM-DD") && ($dateRanges["to"] != "YYYY-MM-DD")) {
            $fromDate = $dateRanges["from"];
            $toDate = $dateRanges["to"];
        } else if (($dateRanges["from"] == "YYYY-MM-DD") && ($dateRanges["to"] != "YYYY-MM-DD")) {
            $toDate = $dateRanges["to"];
        } else if (($dateRanges["from"] != "YYYY-MM-DD") && ($dateRanges["to"] == "YYYY-MM-DD")) {
            $fromDate = $dateRanges["from"];
        }

//        Case 1
        $sqlPartForField1 = "( " . $field1. " " . $this->getWhereClauseCondition() . " '" . $fromDate . "' AND '" . $toDate . "' )";
        $sqlPartForField2 = "( " . $field2. " " . $this->getWhereClauseCondition() . " '" . $fromDate . "' AND '" . $toDate . "' )";

        $sqlForCase1 = " ( " . $sqlPartForField1 . " AND " . $sqlPartForField2 . " ) ";

//        Case 2
        $sqlPartForField1 = " ( " . $field1 . " > '" . $fromDate . "' AND " . $field1 . " < '" . $toDate . "' ) " ;
        $sqlPartForField2 = " ( ".$field2 . " > '" . $toDate . "' ) ";

        $sqlForCase2 = " ( " .$sqlPartForField1 . " AND " . $sqlPartForField2 . " ) ";

//        Case 3
        $sqlPartForField1 = " ( " . $field1 . " < '" . $fromDate . "' ) ";
        $sqlPartForField2 = " ( " . $field2 . " > '" . $fromDate . "' AND " . $field2 . " < '" . $toDate . "' ) " ;

        $sqlForCase3 = " ( " .$sqlPartForField1 . " AND " . $sqlPartForField2 . " ) ";

        $sql = " ( " . $sqlForCase1 . " OR " . $sqlForCase2 . " OR " . $sqlForCase3 . " ) ";
        return $sql;
    }
}

