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
 *  @group Admin
 */
class NationalityServiceTest extends PHPUnit_Framework_TestCase {

    private $nationalityService;
    private $fixture;

    /**
     * Set up method
     */
    protected function setUp() {
        $this->nationalityService = new NationalityService();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/NationalityDao.yml';
        TestDataService::populate($this->fixture);
    }

    public function testGetNationalityList() {

        $nationalityList = TestDataService::loadObjectList('Nationality', $this->fixture, 'Nationality');

        $nationalityDao = $this->getMock('NationalityDao');
        $nationalityDao->expects($this->once())
                ->method('getNationalityList')
                ->will($this->returnValue($nationalityList));

        $this->nationalityService->setNationalityDao($nationalityDao);

        $result = $this->nationalityService->getNationalityList();
        $this->assertEquals($result, $nationalityList);
    }

    public function testGetNationalityById() {

        $nationalityList = TestDataService::loadObjectList('Nationality', $this->fixture, 'Nationality');

        $nationalityDao = $this->getMock('NationalityDao');
        $nationalityDao->expects($this->once())
                ->method('getNationalityById')
                ->with(1)
                ->will($this->returnValue($nationalityList[0]));

        $this->nationalityService->setNationalityDao($nationalityDao);

        $result = $this->nationalityService->getNationalityById(1);
        $this->assertEquals($result, $nationalityList[0]);
    }

    public function testDeleteNationalities() {

        $nationalityList = array(1, 2, 3);

        $nationalityDao = $this->getMock('NationalityDao');
        $nationalityDao->expects($this->once())
                ->method('deleteNationalities')
                ->with($nationalityList)
                ->will($this->returnValue(3));

        $this->nationalityService->setNationalityDao($nationalityDao);

        $result = $this->nationalityService->deleteNationalities($nationalityList);
        $this->assertEquals($result, 3);
    }

}

