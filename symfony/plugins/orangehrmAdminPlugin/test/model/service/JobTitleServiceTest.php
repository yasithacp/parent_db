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
class JobTitleServiceTest extends PHPUnit_Framework_TestCase {

    private $JobTitleService;
    protected $fixture;

    /**
     * Set up method
     */
    protected function setUp() {
        $this->JobTitleService = new JobTitleService();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/JobTitleDao.yml';
        TestDataService::populate($this->fixture);
    }

    public function testGetJobTitleList() {
        
        $jobTitleList = TestDataService::loadObjectList('JobTitle', $this->fixture, 'JobTitle');

        $jobTitleDao = $this->getMock('JobTitleDao');

        $jobTitleDao->expects($this->once())
                ->method('getJobTitleList')
                ->with("", "", "")
                ->will($this->returnValue($jobTitleList));

        $this->JobTitleService->setJobTitleDao($jobTitleDao);
        $result = $this->JobTitleService->getJobTitleList("", "", "");
        $this->assertEquals($jobTitleList, $result);
    }

    public function testDeleteJobTitle() {

        $toBeDeletedJobTitleIds = array(1, 2);

        $jobTitleDao = $this->getMock('JobTitleDao');

        $jobTitleDao->expects($this->once())
                ->method('deleteJobTitle')
                ->with($toBeDeletedJobTitleIds)
                ->will($this->returnValue(2));

        $this->JobTitleService->setJobTitleDao($jobTitleDao);
        $result = $this->JobTitleService->deleteJobTitle($toBeDeletedJobTitleIds);
        $this->assertEquals(2, $result);
    }

    public function testGetJobTitleById() {

        $jobTitleList = TestDataService::loadObjectList('JobTitle', $this->fixture, 'JobTitle');
        $jobTitleDao = $this->getMock('JobTitleDao');

        $jobTitleDao->expects($this->once())
                ->method('getJobTitleById')
                ->with(1)
                ->will($this->returnValue($jobTitleList[0]));

        $this->JobTitleService->setJobTitleDao($jobTitleDao);
        $result = $this->JobTitleService->getJobTitleById(1);
        $this->assertEquals($jobTitleList[0], $result);
    }

}

