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

class orangehrmTimePluginAllTests {

    protected function setUp() {

    }

    public static function suite() {

        $suite = new PHPUnit_Framework_TestSuite('orangehrmTimePluginAllTest');

        /* Dao Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/TimesheetDaoTest.php');

        /* Service Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/model/service/TimesheetServiceTest.php');

         /* TimesheetPeriodService Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/model/service/TimesheetPeriodServiceTest.php');

	/* TimesheetPeriodDao Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/TimesheetPeriodDaoTest.php');
        
        /* MonthlyTimesheetPeriodTest*/
         $suite->addTestFile(dirname(__FILE__) . '/timesheetPeriod/MonthlyTimesheetPeriodTest.php');
         
         /* WeeklyTimesheetPeriodTest*/
         $suite->addTestFile(dirname(__FILE__) . '/timesheetPeriod/WeeklyTimesheetPeriodTest.php');


        return $suite;
    }

    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

}

if (PHPUnit_MAIN_METHOD == 'orangehrmTimePluginAllTests::main') {
    orangehrmTimePluginAllTests::main();
}
?>
