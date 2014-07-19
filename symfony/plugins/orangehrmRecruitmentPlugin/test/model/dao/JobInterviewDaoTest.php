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
 * @group Recruitment
 */
class JobInterviewDaoTest extends PHPUnit_Framework_TestCase {

    private $jobInterviewDao;
    protected $fixture;

    /**
     * Set up method
     */
    protected function setUp() {

        $this->jobInterviewDao = new JobInterviewDao();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmRecruitmentPlugin/test/fixtures/CandidateDao.yml';
        TestDataService::populate($this->fixture);
    }

    /**
     * Test getInterviewListByCandidateIdAndInterviewDate for check return objects
     */
    public function testGetInterviewListByCandidateIdAndInterviewDateAndTimeForCorrectObjects() {

        $interviewList = $this->jobInterviewDao->getInterviewListByCandidateIdAndInterviewDateAndTime(4, '2011-08-18', '9:00:00', '11:00:00');
        $this->assertTrue($interviewList[0] instanceof JobInterview);
    }

    /**
     * Test getInterviewListByCandidateIdAndInterviewDate for existing results
     */
    public function testGetInterviewListByCandidateIdAndInterviewDateAndTimeForExistingResults() {

        $interviewList = $this->jobInterviewDao->getInterviewListByCandidateIdAndInterviewDateAndTime(1, '2011-08-03', '8:30:00', '10:30:00');
        $this->assertEquals(1, count($interviewList));
    }

    /**
     * Test getInterviewListByCandidateIdAndInterviewDate for not existing results
     */
    public function testGetInterviewListByCandidateIdAndInterviewDateAndTimeForNotExistingResults() {

        $interviewList = $this->jobInterviewDao->getInterviewListByCandidateIdAndInterviewDateAndTime(5, '2011-08-18', '9:00:00', '11:00:00');
        $this->assertTrue(true, empty($interviewList));
    }

    public function testGetInterviewById() {
        $interviewId = 1;
        $interview = $this->jobInterviewDao->getInterviewById($interviewId);
        $this->assertTrue($interview instanceof JobInterview);
        $this->assertEquals($interview->getInterviewDate(), '2011-08-03');
        $this->assertEquals($interview->getInterviewTime(), '09:00:00');
    }

    public function testGetInterviewersByInterviewId() {
        $interviewId = 1;
        $interviewers = $this->jobInterviewDao->getInterviewersByInterviewId($interviewId);
        $this->assertTrue($interviewers[0] instanceof JobInterviewInterviewer);
        $this->assertEquals($interviewers[0]->getInterviewId(), 1);
        $this->assertEquals($interviewers[0]->getInterviewerId(), 3);
    }

    public function testGetInterviewsByCandidateVacancyId() {
        $candidateVacancyId = 1;
        $interviews = $this->jobInterviewDao->getInterviewsByCandidateVacancyId($candidateVacancyId);
        $this->assertTrue($interviews[0] instanceof JobInterview);
        $this->assertEquals($interviews[0]->getInterviewDate(), '2011-08-03');
        $this->assertEquals($interviews[0]->getInterviewTime(), '09:00:00');
    }

    public function testGetInterviewScheduledHistoryByInterviewId() {
        $interviewId = 1;
        $history = $this->jobInterviewDao->getInterviewScheduledHistoryByInterviewId($interviewId);
        $this->assertTrue($history instanceof CandidateHistory);
        $this->assertEquals($history->getCandidateId(), 1);
        $this->assertEquals($history->getNote(), 'This is my third note');
    }

    public function testSaveJobInterview() {

        $interview = new JobInterview();
        $interview->id = 12;
        $interview->interviewName = '1st Interview';
        $interview->interviewDate = '2011-05-05';

        $this->jobInterviewDao->saveJobInterview($interview);
        $this->assertNotNull($interview->getId());
    }

    public function testSaveJobInterviewForNullId() {

        TestDataService::truncateTables(array(
            'JobInterview',
            'JobInterviewInterviewer',
            'JobInterviewAttachment',
        ));

        $interview = new JobInterview();
        $interview->setId(null);
        $interview->setInterviewName('1st Interview');
        $interview->setInterviewDate('2011-05-05');

        $return = $this->jobInterviewDao->saveJobInterview($interview);
        $this->assertTrue($return);
    }

    public function testUpdateJobInterview() {
        $interview = new JobInterview();
        $interview->id = 2;
        $interview->candidateId = 1;
        $interview->candidateVacancyId = 1;
        $interview->interviewTime = '12:30';
        $interview->interviewName = '1st Interview';
        $interview->interviewDate = '2011-05-05';
        $interview->note = 'Scheduled';
        $result = $this->jobInterviewDao->updateJobInterview($interview);
        $this->assertEquals($result, 1);
    }

}

