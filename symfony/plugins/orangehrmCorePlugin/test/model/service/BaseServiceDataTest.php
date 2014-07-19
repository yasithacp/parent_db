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

require_once sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

/**
 * @group Core
 */
class BaseServiceDataTest extends PHPUnit_Framework_TestCase {

    protected static $baseService;
    protected static$fixture;
    protected $resultRecords;

    /**
     * Set up method
     */
    public static function setUpBeforeClass() {

        self::$baseService = new BaseService();
        $sampleQueryExtensionsPath = sfConfig::get('sf_plugins_dir') . '/orangehrmCorePlugin/test/fixtures/sample_query_extensions.yml';
        PluginQueryExtensionManager::instance()->setQueryExtensions(sfYaml::load($sampleQueryExtensionsPath));

        self::$fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmCorePlugin/test/fixtures/base-service-test-data.yml';
        TestDataService::populate(self::$fixture);
    }

    /**
     * 
     */
    public function testDecorateQuery_SQL_SelectFields_SingleField() {
        $originalQuery = "SELECT `emp_firstname`, `emp_lastname` FROM `hs_hr_employee` WHERE `emp_number` = '1'";
        $expectedQuery = "SELECT `emp_firstname`, `emp_lastname`, `emp_middle_name` FROM `hs_hr_employee` WHERE `emp_number` = '1'";

        $this->validateResultRecords($originalQuery, $expectedQuery, 'SampleService_ForSelect', 'sampleMethod1');

        list($record) = $this->resultRecords;
        $this->assertEquals(3, count($record)); // Checking the no of columns
        $this->assertTrue(array_key_exists('emp_middle_name', $record)); // Checking whether the column exists
        $this->assertEquals('Teran', $record['emp_middle_name']); // Checking the value of the new column
    }

    public function testDecorateQuery_SQL_SelectFields_MultipleFields() {
        $originalQuery = "SELECT `emp_firstname`, `emp_lastname` FROM `hs_hr_employee` WHERE `emp_number` = '1'";
        $expectedQuery = "SELECT `emp_firstname`, `emp_lastname`, `emp_middle_name`, `job_title_code`, `joined_date` FROM `hs_hr_employee` WHERE `emp_number` = '1'";

        $this->validateResultRecords($originalQuery, $expectedQuery, 'SampleService_ForSelect', 'sampleMethod2');
        
        list($record) = $this->resultRecords;
        $this->assertEquals(5, count($record)); // Checking the no of columns
        $this->assertTrue(array_key_exists('emp_middle_name', $record)); // Checking whether the columns exists
        $this->assertTrue(array_key_exists('job_title_code', $record));
        $this->assertTrue(array_key_exists('joined_date', $record));
        $this->assertEquals('Teran', $record['emp_middle_name']); // Checking the value of a new column
    }

    protected function validateResultRecords($originalQuery, $expectedQuery, $service, $method) {
        $resultQuery = self::$baseService->decorateQuery($service, $method, $originalQuery);

        $expectedRecords = TestDataService::getRecords($expectedQuery);
        $resultRecords = TestDataService::getRecords($resultQuery);

        $this->assertEquals($expectedRecords, $resultRecords);
        $this->resultRecords = $resultRecords;
    }

}

