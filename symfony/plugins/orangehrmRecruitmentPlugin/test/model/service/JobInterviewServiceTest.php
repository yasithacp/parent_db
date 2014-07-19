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
class JobInterviewServiceTest extends PHPUnit_Framework_TestCase {

    private $jobInterviewService;
    protected $fixture;

    /**
     * Set up method
     */
    protected function setUp() {

        $this->jobInterviewService = new JobInterviewService();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmRecruitmentPlugin/test/fixtures/CandidateDao.yml';
        TestDataService::populate($this->fixture);
    }
    
    public function testTemp() {
        $this->assertTrue(true);
    }
    
//    /*
//     * Test getInterviewListByCandidateIdAndInterviewDateAndTime for true
//     */
//    public function testGetInterviewListByCandidateIdAndInterviewDateAndTimeForTrue() {
//        
//        $interviewList = TestDataService::loadObjectList('JobInterview', $this->fixture, 'JobInterview');
//        $requiredObject = $interviewList[1]; 
//        
//        $parameters = array('candidateId' => 4, 'interviewDate' => '2011-08-18', 'fromTime' => '09:00:00', 'toTime' => '11:00:00');
//
//        $jobInterviewDao = $this->getMock('JobInterviewDao', array('getInterviewListByCandidateIdAndInterviewDateAndTime'));
//
//            $jobInterviewDao->expects($this->once())
//                           ->method('getInterviewListByCandidateIdAndInterviewDateAndTime')
//                           ->with($parameters)
//                           ->will($this->returnValue($requiredObject));
//
//            $this->jobInterviewService->setJobInterviewDao($jobInterviewDao);
//
//            $result = $this->jobInterviewService->getInterviewListByCandidateIdAndInterviewDateAndTime($parameters[0], $parameters[1], '09:30:00');
//
//            $this->assertEquals(true, $result);
//        
//    }
//    
//    /*
//     * Test getInterviewListByCandidateIdAndInterviewDateAndTime for false
//     */
//    public function testGetInterviewListByCandidateIdAndInterviewDateAndTimeFaorFalse() {
//        
//        $requiredObject = array(); 
//        
//        $parameters = array('candidateId' => 4, 'interviewDate' => '2011-08-18', 'fromTime' => '09:00:00', 'toTime' => '11:00:00');
//
//        $jobInterviewDao = $this->getMock('JobInterviewDao', array('getInterviewListByCandidateIdAndInterviewDateAndTime'));
//
//            $jobInterviewDao->expects($this->once())
//                           ->method('getInterviewListByCandidateIdAndInterviewDateAndTime')
//                           ->with($parameters)
//                           ->will($this->returnValue($requiredObject));
//
//            $this->jobInterviewService->setJobInterviewDao($jobInterviewDao);
//
//            $result = $this->jobInterviewService->getInterviewListByCandidateIdAndInterviewDateAndTime($parameters[0], $parameters[1], '09:30:00');
//
//            $this->assertEquals(false, $result);
//        
//    }
    
    public function testGetInterviewById() {

        $interviews = TestDataService::loadObjectList('JobInterview', $this->fixture, 'JobInterview');
        $expectedresult = $interviews[0];

        $jobInterviewDao = $this->getMock('JobInterviewDao');
        $jobInterviewDao->expects($this->once())
                ->method('getInterviewById')
                ->with(1)
                ->will($this->returnValue($expectedresult));

        $this->jobInterviewService->setJobInterviewDao($jobInterviewDao);

        $return = $this->jobInterviewService->getInterviewById(1);
        $this->assertEquals($expectedresult, $return);
    }
    
    public function testGetInterviewersByInterviewId() {

        $interviewInterViewer = TestDataService::loadObjectList('JobInterviewInterviewer', $this->fixture, 'JobInterviewInterviewer');
        $expectedresult = $interviewInterViewer;
        $jobInterviewDao = $this->getMock('JobInterviewDao');
        $jobInterviewDao->expects($this->once())
                ->method('getInterviewersByInterviewId')
                ->with(1)
                ->will($this->returnValue($expectedresult));

        $this->jobInterviewService->setJobInterviewDao($jobInterviewDao);

        $return = $this->jobInterviewService->getInterviewersByInterviewId(1);
        $this->assertEquals($expectedresult, $return);
    }

    public function testGetInterviewsByCandidateVacancyId() {

        $interviews = TestDataService::loadObjectList('JobInterview', $this->fixture, 'JobInterview');
        $expectedresult = $interviews[1];
        $jobInterviewDao = $this->getMock('JobInterviewDao');
        $jobInterviewDao->expects($this->once())
                ->method('getInterviewsByCandidateVacancyId')
                ->with(10)
                ->will($this->returnValue($expectedresult));

        $this->jobInterviewService->setJobInterviewDao($jobInterviewDao);

        $return = $this->jobInterviewService->getInterviewsByCandidateVacancyId(10);
        $this->assertEquals($expectedresult, $return);
    }
    
    public function testSaveJobInterview() {

        $jobInterview = new JobInterview();

        $jobInterviewDao = $this->getMock('JobInterviewDao');
        $jobInterviewDao->expects($this->once())
                ->method('saveJobInterview')
                ->with($jobInterview)
                ->will($this->returnValue(true));

        $this->jobInterviewService->setJobInterviewDao($jobInterviewDao);

        $return = $this->jobInterviewService->saveJobInterview($jobInterview);
        $this->assertTrue($return);
    }
    
    public function testUpdateJobInterview() {

        $jobInterview = new JobInterview();
        
        $jobInterviewDao = $this->getMock('JobInterviewDao');
        $jobInterviewDao->expects($this->once())
                ->method('updateJobInterview')
                ->with($jobInterview)
                ->will($this->returnValue($jobInterview));

        $this->jobInterviewService->setJobInterviewDao($jobInterviewDao);

        $return = $this->jobInterviewService->updateJobInterview($jobInterview);
        $this->assertTrue($return instanceof JobInterview);
    }
    
    public function testGetInterviewScheduledHistoryByInterviewId() {

        $candidateHistory = TestDataService::loadObjectList('CandidateHistory', $this->fixture, 'CandidateHistory');
        $expectedresult = $candidateHistory[2];
        $jobInterviewDao = $this->getMock('JobInterviewDao');
        $jobInterviewDao->expects($this->once())
                ->method('getInterviewScheduledHistoryByInterviewId')
                ->with(1)
                ->will($this->returnValue($expectedresult));

        $this->jobInterviewService->setJobInterviewDao($jobInterviewDao);

        $return = $this->jobInterviewService->getInterviewScheduledHistoryByInterviewId(1);
        $this->assertEquals($expectedresult, $return);
    }
    
    public function testGetJobInterviewDao(){
        $dao = $this->jobInterviewService->getJobInterviewDao();
        $this->assertTrue($dao instanceof JobInterviewDao);
    }
    
}