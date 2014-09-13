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
class CandidateServiceTest extends PHPUnit_Framework_TestCase {

    private $candidateService;
    protected $fixture;

    /**
     * Set up method
     */
    protected function setUp() {

        $this->candidateService = new CandidateService();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmRecruitmentPlugin/test/fixtures/CandidateDao.yml';
        TestDataService::populate($this->fixture);
    }

    /**
     * Testing getAllCandidatesList
     */
    public function testGetAllCandidatesList() {

        $allCandidatesList = TestDataService::loadObjectList('JobCandidate', $this->fixture, 'JobCandidate');
	$allowedCandidateList = array(1,2,3);
        $candidateDao = $this->getMock('CandidateDao');

        $candidateDao->expects($this->once())
                ->method('getCandidateList')
                ->will($this->returnValue($allCandidatesList));

        $this->candidateService->setCandidateDao($candidateDao);

        $readCandidatesList = $this->candidateService->getCandidateList($allowedCandidateList);
        $this->assertEquals($readCandidatesList, $allCandidatesList);
    }

    /**
     * Testing getAllCandidatesList
     */
    public function testSearchCandidates() {

        $searchParam = new CandidateSearchParameters();

        $searchParam->setJobTitleCode('JOB002');

        $candidatesVacancyList = TestDataService::loadObjectList('JobCandidateVacancy', $this->fixture, 'JobCandidateVacancy');
        $canVacList = array($candidatesVacancyList[2], $candidatesVacancyList[3], $candidatesVacancyList[4]);
        $candidateDao = $this->getMock('CandidateDao');

        $candidateDao->expects($this->once())
                ->method('searchCandidates')
                ->with('')
                ->will($this->returnValue($canVacList));

        $this->candidateService->setCandidateDao($candidateDao);

        $readCanVacList = $this->candidateService->searchCandidates($searchParam);
        $this->assertEquals($readCanVacList, $canVacList);
    }

    /**
     * Testing getCandidateRecordsCount
     */
    public function testGetCandidateRecordsCount() {

        $searchParam = new CandidateSearchParameters();
        $searchParam->setJobTitleCode('JOB002');
        
        $candidateService = $this->getMock('CandidateService', array('buildSearchCountQuery'));        
        $candidateService->expects($this->once())
                ->method('buildSearchCountQuery')
                ->with($searchParam)
                ->will($this->returnValue('searchCountQuery'));        

        $candidateDao = $this->getMock('CandidateDao', array('getCandidateRecordsCount'));
        $candidateDao->expects($this->once())
                ->method('getCandidateRecordsCount')
                ->with('searchCountQuery')
                ->will($this->returnValue(4));    
        
        $candidateService->setCandidateDao($candidateDao);
        
        $result = $candidateService->getCandidateRecordsCount($searchParam);
        $this->assertEquals($result, 4);

    }

    /**
     *
     */
    public function testSaveCandidate() {

        $candidate = new JobCandidate();

        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('saveCandidate')
                ->with($candidate)
                ->will($this->returnValue(true));

        $this->candidateService->setCandidateDao($candidateDao);

        $return = $this->candidateService->saveCandidate($candidate);
        $this->assertTrue($return);
    }

    /**
     * 
     */
    public function testSaveCandidateVacancy() {

        $candidateVacancy = new JobCandidateVacancy();

        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('saveCandidateVacancy')
                ->with($candidateVacancy)
                ->will($this->returnValue(true));

        $this->candidateService->setCandidateDao($candidateDao);

        $return = $this->candidateService->saveCandidateVacancy($candidateVacancy);
        $this->assertTrue($return);
    }

    /**
     * 
     */
    public function testGetCandidateVacancyById() {
        $candidateVacancyList = TestDataService::loadObjectList('JobCandidateVacancy', $this->fixture, 'JobCandidateVacancy');
        $requiredObject = $candidateVacancyList[2];

        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('getCandidateVacancyById')
                ->with(3)
                ->will($this->returnValue($requiredObject));

        $this->candidateService->setCandidateDao($candidateDao);

        $result = $this->candidateService->getCandidateVacancyById(3);
        $this->assertEquals($requiredObject, $result);
    }
    /**
     *
     */
    public function testGetCandidateHistoryById() {
        $candidateHistoryList = TestDataService::loadObjectList('CandidateHistory', $this->fixture, 'CandidateHistory');

        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('getCandidateHistoryById')
                ->with(1)
                ->will($this->returnValue($candidateHistoryList[0]));

        $this->candidateService->setCandidateDao($candidateDao);

        $result = $this->candidateService->getCandidateHistoryById(1);
        $this->assertEquals($candidateHistoryList[0], $result);
    }
    
    public function testProcessCandidatesVacancyArray() {
        
        $candidateVacancyId = array('1_2', '4_5');
        $result = $this->candidateService->processCandidatesVacancyArray($candidateVacancyId);
        $expextedResult = array(1, 4);
        $this->assertEquals($expextedResult, $result);
    }
    
    public function testDeleteCandidateVacanciesTestDeleteCandidate(){
        
        $candidateId = 1;
        $toBeDeleteCandidateIds = array(1);
        $toBeDeletedRecords = array(1 =>array(1, 3));
        
        $candidateDao = $this->getMock('CandidateDao', array('getAllVacancyIdsForCandidate', 'deleteCandidates', 'deleteCandidateVacancies'));
        
        $candidateDao->expects($this->any())
                     ->method('getAllVacancyIdsForCandidate')
                     ->with($candidateId)
                     ->will($this->returnValue(array(1, 3)));
        
        $candidateDao->expects($this->once())
                     ->method('deleteCandidates')
                     ->with($toBeDeleteCandidateIds)
                     ->will($this->returnValue(true));        
       
        $this->candidateService->setCandidateDao($candidateDao);
        $result = $this->candidateService->deleteCandidateVacancies($toBeDeletedRecords);
        $this->assertEquals(true ,$result);
        
    }
    
    public function testDeleteCandidateVacanciesForFalse() {
        $toBeDeletedRecords = array();
        $result = $this->candidateService->deleteCandidateVacancies($toBeDeletedRecords);
        $this->assertEquals(false ,$result);
        
        $candidateId = 1;
        $toBeDeleteCandidateIds = array(1);
        $toBeDeletedRecords = array(1 =>array(1, 3));
        
        $candidateDao = $this->getMock('CandidateDao', array('getAllVacancyIdsForCandidate', 'deleteCandidates', 'deleteCandidateVacancies'));
        
        $candidateDao->expects($this->any())
                     ->method('getAllVacancyIdsForCandidate')
                     ->with($candidateId)
                     ->will($this->returnValue(array(1, 3)));
        
        $candidateDao->expects($this->once())
                     ->method('deleteCandidates')
                     ->with($toBeDeleteCandidateIds)
                     ->will($this->returnValue(false));        
       
        $this->candidateService->setCandidateDao($candidateDao);
        $result = $this->candidateService->deleteCandidateVacancies($toBeDeletedRecords);
        $this->assertEquals(false ,$result);
    }


    public function testDeleteCandidateVacanciesTestDeleteCandidateVacancy(){
        
        $candidateId = 2;
        $toBeDeletedRecords = array(2 => array(1, 3));
        $toBeDeleteCandidateVacancies = array(array(2, 1), array(2, 3));
        
        $candidateDao = $this->getMock('CandidateDao', array('getAllVacancyIdsForCandidate', 'deleteCandidates', 'deleteCandidateVacancies'));
        
        $candidateDao->expects($this->any())
                     ->method('getAllVacancyIdsForCandidate')
                     ->with($candidateId)
                     ->will($this->returnValue(array(1, 2, 3)));
        
        $candidateDao->expects($this->once())
                     ->method('deleteCandidateVacancies')
                     ->with($toBeDeleteCandidateVacancies)
                     ->will($this->returnValue(true));
       
        $this->candidateService->setCandidateDao($candidateDao);
        $result = $this->candidateService->deleteCandidateVacancies($toBeDeletedRecords);
        $this->assertEquals(true ,$result);
        
    }
    
    /**
     * 
     */
    public function testGetCandidateById() {
        $candidatesList = TestDataService::loadObjectList('JobCandidate', $this->fixture, 'JobCandidate');
        
        $requiredObject = $candidatesList[1];

        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('getCandidateById')
                ->with(2)
                ->will($this->returnValue($requiredObject));

        $this->candidateService->setCandidateDao($candidateDao);

        $result = $this->candidateService->getCandidateById(2);
        $this->assertEquals($requiredObject, $result);
    }
    
    /** 
     * 
     */
    public function testUpdateCandidate() {

        $candidate = new JobCandidate();
        
        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('updateCandidate')
                ->with($candidate)
                ->will($this->returnValue($candidate));

        $this->candidateService->setCandidateDao($candidateDao);

        $return = $this->candidateService->updateCandidate($candidate);
        $this->assertTrue($return instanceof JobCandidate);
    }
    
    /**
     * 
     */
    public function testSaveCandidateHistory() {

        $candidateHistory = new CandidateHistory();

        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('saveCandidateHistory')
                ->with($candidateHistory)
                ->will($this->returnValue(true));

        $this->candidateService->setCandidateDao($candidateDao);

        $return = $this->candidateService->saveCandidateHistory($candidateHistory);
        $this->assertTrue($return);
    }
    
    /**
     * 
     */
    public function testGetCandidateHistoryForCandidateId() {

        $candidatesHistory = TestDataService::loadObjectList('CandidateHistory', $this->fixture, 'CandidateHistory');
        $expectedresult = $candidatesHistory[0];

        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('getCandidateHistoryForCandidateId')
                ->with(1,1)
                ->will($this->returnValue($expectedresult));

        $this->candidateService->setCandidateDao($candidateDao);

        $return = $this->candidateService->getCandidateHistoryForCandidateId(1,1);
        $this->assertEquals($expectedresult, $return);
    }
    
    public function testDeleteCandidates() {

        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('deleteCandidates')
                ->with(array(1,2))
                ->will($this->returnValue(true));

        $this->candidateService->setCandidateDao($candidateDao);

        $return = $this->candidateService->deleteCandidate(array(1,2));
        $this->assertTrue($return);
    }
    
    
        /** 
     * 
     */
    public function testGetCandidateListForUserRole() {
        
        $exceptedValues = array(1,2);
        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('getCandidateListForUserRole')
                ->with(HiringManagerUserRoleDecorator::HIRING_MANAGER, 1)
                ->will($this->returnValue($exceptedValues));

        $this->candidateService->setCandidateDao($candidateDao);

        $return = $this->candidateService->getCandidateListForUserRole(HiringManagerUserRoleDecorator::HIRING_MANAGER, 1);
        $this->assertEquals($exceptedValues, $return);
    }
    
    public function testGetCanidateHistoryForUserRole() {
        
        $exceptedValues = array(1);
        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('getCanidateHistoryForUserRole')
                ->with(HiringManagerUserRoleDecorator::HIRING_MANAGER, 1, 1)
                ->will($this->returnValue($exceptedValues));

        $this->candidateService->setCandidateDao($candidateDao);

        $return = $this->candidateService->getCanidateHistoryForUserRole(HiringManagerUserRoleDecorator::HIRING_MANAGER, 1, 1);
        $this->assertEquals($exceptedValues, $return);
    }
    
    public function testIsHiringManager() {
        
        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('isHiringManager')
                ->with(1, 1)
                ->will($this->returnValue(1));

        $this->candidateService->setCandidateDao($candidateDao);

        $return = $this->candidateService->isHiringManager(1, 1);
        $this->assertEquals(1, $return);
    }
    
    public function testIsInterviewer() {
        
        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('isInterviewer')
                ->with(1, 1)
                ->will($this->returnValue(1));

        $this->candidateService->setCandidateDao($candidateDao);

        $return = $this->candidateService->isInterviewer(1, 1);
        $this->assertEquals(1, $return);
    }
    
    public function testGetCandidateVacancyByCandidateIdAndVacancyId() {

        $candidatesVacancy = TestDataService::loadObjectList('JobCandidateVacancy', $this->fixture, 'JobCandidateVacancy');
        $expectedresult = $candidatesVacancy[5];
        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('getCandidateVacancyByCandidateIdAndVacancyId')
                ->with(3,1)
                ->will($this->returnValue($expectedresult));

        $this->candidateService->setCandidateDao($candidateDao);

        $return = $this->candidateService->getCandidateVacancyByCandidateIdAndVacancyId(3,1);
        $this->assertEquals($expectedresult, $return);
    }

    public function testGetEmployeeService(){
        $service = $this->candidateService->getEmployeeService();
        $this->assertTrue($service instanceof EmployeeService);
    }
    
    public function testUpdateCandidateVacancy() {
        
        $candidatesVacancy = TestDataService::loadObjectList('JobCandidateVacancy', $this->fixture, 'JobCandidateVacancy');
        $candidateVacancy = $candidatesVacancy[0];
        $userObj = new User();
        $candidateService = $this->getMock('CandidateService', array ('getNextStateForCandidateVacancy'));
        $candidateService->expects($this->any())
                ->method('getNextStateForCandidateVacancy')
                ->with('SHORTLISTED', 3, $userObj)
                ->will($this->returnValue('REJECTED'));
        
        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('updateCandidateVacancy')
                ->with($candidateVacancy)
                ->will($this->returnValue(1));

        $candidateService->setCandidateDao($candidateDao);
        $return = $candidateService->updateCandidateVacancy($candidateVacancy,3, $userObj);
        $this->assertEquals(1, $return);              
    }
    
    public function testGetNextActionsForCandidateVacancy() {
        
        $userObj = new User();
        $return = $this->candidateService->getNextActionsForCandidateVacancy(3, $userObj);
        $this->assertEquals(array("" => __('No Actions')), $return);
        $expectedArray = array("" => "Select Action", 3 => "Reject", 4 => "Schedule Interview" );
        $allowedActions = array(3, 4);
        
        $userObj = $this->getMock('User',array ('getAllowedActions'));
        $userObj->expects($this->once())
                ->method('getAllowedActions')
                ->with(PluginWorkflowStateMachine::FLOW_RECRUITMENT, 2)
                ->will($this->returnValue($allowedActions));
        $return = $this->candidateService->getNextActionsForCandidateVacancy(2, $userObj);
        $this->assertEquals($expectedArray, $return);
    }
    
    public function testAddEmployee() {
        
        $employeeServiceMock = $this->getMock('EmployeeService', array('addEmployee'));
        $employeeServiceMock->expects($this->once())
                ->method('addEmployee')
                ->will($this->returnValue(true));
        
        $this->candidateService->setEmployeeService($employeeServiceMock);
        
        $employee = new Employee();
        $this->assertTrue($this->candidateService->addEmployee($employee));       
    }
    
        public function testUpdateCandidateHistory() {

        $candidateHistory = new CandidateHistory();
        
        $candidateDao = $this->getMock('CandidateDao');
        $candidateDao->expects($this->once())
                ->method('updateCandidateHistory')
                ->with($candidateHistory)
                ->will($this->returnValue(1));

        $this->candidateService->setCandidateDao($candidateDao);

        $return = $this->candidateService->updateCandidateHistory($candidateHistory);
        $this->assertEquals(1, $return);
    }
    
     public function testGetNextStateForCandidateVacancy() {
        
        $userObj = $this->getMock('User',array ('getNextState'));
        $userObj->expects($this->once())
                ->method('getNextState')
                ->with(PluginWorkflowStateMachine::FLOW_RECRUITMENT, 'SHORTLISTED', 3)
                ->will($this->returnValue('REJECTED'));

        $return = $this->candidateService->getNextStateForCandidateVacancy('SHORTLISTED',3, $userObj);
        $this->assertEquals('REJECTED', $return);              
    }
    
}

