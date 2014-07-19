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

class orangehrmAdminPluginAllTests {

    protected function setUp() {

    }

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('orangehrmCoreLeavePluginAllTest');

        /* Dao Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/SystemUserDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/SkillDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/OrganizationDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/CompanyStructureDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/ProjectDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/JobTitleDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/CustomerDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/LocationDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/OperationalCountryDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/CountryDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/EmploymentStatusDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/SkillDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/LanguageDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/LicenseDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/EducationDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/MembershipDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/NationalityDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/PayGradeDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/JobCategoryDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/EmailNotificationDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/WorkShiftDaoTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/dao/ModuleDaoTest.php');

        /* Service Test Cases */
        $suite->addTestFile(dirname(__FILE__) . '/model/service/LocalizationServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/PimCsvDataImportServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/CompanyStructureServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/JobTitleServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/CustomerServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/ProjectServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/LocationServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/OperationalCountryServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/CountryServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/EmploymentStatusServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/MembershipServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/NationalityServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/PayGradeServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/JobCategoryServiceTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/model/service/WorkShiftServiceTest.php');
        
        return $suite;
    }

    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

}

if (PHPUnit_MAIN_METHOD == 'orangehrmAdminPluginAllTests::main') {
    orangehrmCoreLeavePluginAllTests::main();
}

?>
