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
    define('PHPUnit_MAIN_METHOD', 'AllTests::main');
}


require_once 'PHPUnit/TextUI/TestRunner.php';

set_include_path(get_include_path() . PATH_SEPARATOR . "../build");

require_once 'common/AllTests.php';
require_once 'utils/AllTests.php';
require_once 'models/AllTests.php';
require_once 'controllers/AllTests.php';
require_once 'logger/AllTests.php';
require_once 'dao/AllTests.php';
require_once 'extractor/AllTests.php';

class AllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('OrangeHRM');

        $suite->addTest(common_AllTests::suite());
        $suite->addTest(utils_AllTests::suite());
        $suite->addTest(models_AllTests::suite());
        $suite->addTest(controllers_AllTests::suite());
        $suite->addTest(logger_AllTests::suite());
        $suite->addTest(dao_AllTests::suite());
        $suite->addTest(extractor_AllTests::suite());

        return $suite;
    }
}

if (PHPUnit_MAIN_METHOD == 'AllTests::main') {
    AllTests::main();
}
?>
