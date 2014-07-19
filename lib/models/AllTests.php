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


if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'models_AllTests::main');
}


require_once 'PHPUnit/TextUI/TestRunner.php';

set_include_path(get_include_path() . PATH_SEPARATOR . "../../build");

require_once 'time/AllTests.php';
require_once 'leave/AllTests.php';
require_once 'eimadmin/AllTests.php';
require_once 'eimadmin/export/AllTests.php';
require_once 'eimadmin/import/AllTests.php';
require_once 'hrfunct/AllTests.php';
require_once 'recruitment/AllTests.php';
require_once 'benefits/AllTests.php';
require_once 'benefits/mail/AllTests.php';
require_once 'report/AllTests.php';

class models_AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('OrangeHRM model unit tests');

        $suite->addTest(models_time_AllTests::suite());
        $suite->addTest(models_leave_AllTests::suite());
        $suite->addTest(models_eimadmin_AllTests::suite());
        $suite->addTest(models_eimadmin_export_AllTests::suite());
        $suite->addTest(models_eimadmin_import_AllTests::suite());
        $suite->addTest(models_hrfunct_AllTests::suite());
        $suite->addTest(models_recruitment_AllTests::suite());
        $suite->addTest(models_benefits_AllTests::suite());
        $suite->addTest(models_benefits_mail_AllTests::suite());
        $suite->addTest(models_report_AllTests::suite());

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'models_AllTests::main') {
    models_AllTests::main();
}
?>
