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
 * @group Admin
 */
class LocalizationServiceTest extends PHPUnit_Framework_TestCase {

    private $localizationService;

    /**
     * Set up method
     */
    protected function setUp() {
        $this->localizationService = new LocalizationService();
    }

    public function testConvertPHPFormatDateToISOFormatDate() {

       $this->assertEquals("1988-03-11", $this->localizationService->convertPHPFormatDateToISOFormatDate('d-m-Y', "11-03-1988"));
       $this->assertEquals("Invalid date", $this->localizationService->convertPHPFormatDateToISOFormatDate('d-m-Y', "1988-03-11"));

       $this->assertEquals("1988-03-11", $this->localizationService->convertPHPFormatDateToISOFormatDate('m-d-Y', "03-11-1988"));
       $this->assertEquals("Invalid date", $this->localizationService->convertPHPFormatDateToISOFormatDate('m-d-Y', "1988-00-11"));

       $this->assertEquals("1988-03-11", $this->localizationService->convertPHPFormatDateToISOFormatDate('Y-d-m', "1988-11-03"));
       $this->assertEquals("Invalid date", $this->localizationService->convertPHPFormatDateToISOFormatDate('Y-d-m', "1988-1223"));
    }

}
