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

class orangehrmCorePluginAllTests {

    protected function setUp() {

    }

    public static function suite() {

        $suite = new PHPUnit_Framework_TestSuite('orangehrmCorePluginAllTest');

        /* Component Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/components/ListHeaderTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/components/PropertyPopulatorTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/components/LinkCellTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/components/ButtonTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/components/LabelCellTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/components/SortableHeaderCellTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/components/ListHeaderTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/components/CheckboxTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/components/HeaderCellTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/components/ohrmCellFilterTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/components/EnumCellFilterTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/components/CellTest.php');
        
        /* Dao Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/dao/ConfigDaoTest.php');

        /* Service Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/service/ConfigServiceTest.php');

        /* Factory Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/factory/SimpleUserRoleFactoryTest.php');

        /* AccessFlowStateMachine Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/AccessFlowStateMachineDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/AccessFlowStateMachineServiceTest.php');

        /* ReportGenerator Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/ReportableDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/ReportableServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/ReportGeneratorServiceTest.php');

        /* BaseService Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/model/service/BaseServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/BaseServiceDataTest.php');
        
        /* form validators */
        $suite->addTestFile(dirname(__FILE__) . '/form/validate/ohrmValidatorSchemaCompareTest.php');

        /* Authorization */
        $suite->addTestFile(dirname(__FILE__) . '/authorization/service/UserRoleManagerServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/authorization/manager/BasicUserRoleManagerTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/authorization/dao/ScreenPermissionDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/authorization/service/ScreenPermissionServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/authorization/dao/ScreenDaoTest.php');
        
        return $suite;
    }

    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

}

if (PHPUnit_MAIN_METHOD == 'orangehrmCorePluginAllTests::main') {
    orangehrmCorePluginAllTests::main();
}

