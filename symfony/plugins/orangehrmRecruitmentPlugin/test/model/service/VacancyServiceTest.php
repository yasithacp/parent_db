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
class VacancyServiceTest extends PHPUnit_Framework_TestCase {

	private $vacancyService;
	protected $fixture;

	/**
	 * Set up method
	 */
	protected function setUp() {

		$this->vacancyService = new VacancyService();
		$this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmRecruitmentPlugin/test/fixtures/CandidateDao.yml';
		TestDataService::populate($this->fixture);
	}

	/**
	 * Testing getHiringManagerList
	 */
	public function testGetHiringManagersList() {

		$hiringMangersList = array(1 => array('id' => 1, 'name' => "Kayla Abbey"), 2 => array('id' => 2, 'name' => "Ashley Abel"));

		$vacancyDao = $this->getMock('VacancyDao');
		$allowedVacancyList = array(1,2);
		$vacancyDao->expects($this->once())
			->method('getHiringManagersList')
			->with("", "")
			->will($this->returnValue($hiringMangersList));

		$this->vacancyService->setVacancyDao($vacancyDao);

		$readHiringManagersList = $this->vacancyService->getHiringManagersList("", "", $allowedVacancyList);
		$this->assertEquals($readHiringManagersList, $hiringMangersList);
	}

	/**
	 * Testing testGetVacancyListForJobTitle
	 */
	public function testGetVacancyListForJobTitle() {

		$jobTitle = "JOB002";
		$allVacancyList = TestDataService::loadObjectList('JobVacancy', $this->fixture, 'JobVacancy');
		$vacancyList = array($allVacancyList[1], $allVacancyList[2]);
		$allowedVacancyList = array(1,2);
		$vacancyDao = $this->getMock('VacancyDao');
		$vacancyDao->expects($this->once())
			->method('getVacancyListForJobTitle')
			->with($jobTitle)
			->will($this->returnValue($vacancyList));

		$this->vacancyService->setVacancyDao($vacancyDao);

		$readVacancyList = $this->vacancyService->getVacancyListForJobTitle($jobTitle, $allowedVacancyList);
		$this->assertEquals($readVacancyList, $vacancyList);
	}

	/**
	 * Testing testGetVacancyListForJobTitle
	 */
	public function testGetVacancyListForJobTitleHydrateMode() {

		$jobTitle = "JOB002";
		$readList = array(array('id' => 2, 'name' => 'A 2011'), array('id' => 3, 'name' => 'B 2011'));

		$vacancyDao = $this->getMock('VacancyDao');

		$vacancyDao->expects($this->once())
			->method('getVacancyListForJobTitle')
			->with($jobTitle, true)
			->will($this->returnValue($readList));

		$this->vacancyService->setVacancyDao($vacancyDao);

		$readVacancyList = $this->vacancyService->getVacancyListForJobTitle($jobTitle, true);
		$this->assertEquals($readVacancyList, $readList);
	}

	/**
	 * Testing testGetVacancyList
	 */
	public function testGetVacancyList() {

		$allVacancyList = TestDataService::loadObjectList('JobVacancy', $this->fixture, 'JobVacancy');

		$vacancyDao = $this->getMock('VacancyDao');

		$vacancyDao->expects($this->once())
			->method('getVacancyList')
			->will($this->returnValue($allVacancyList));

		$this->vacancyService->setVacancyDao($vacancyDao);

		$readVacancyList = $this->vacancyService->getVacancyList();
		$this->assertEquals($readVacancyList, $allVacancyList);
	}

	/**
	 * Testing testGetActiveVacancyList
	 */
	public function testGetAllVacancies() {

		$allVacancyList = TestDataService::loadObjectList('JobVacancy', $this->fixture, 'JobVacancy');
		$activeVacancyList = array($allVacancyList[0], $allVacancyList[2]);

		$vacancyDao = $this->getMock('VacancyDao');

		$vacancyDao->expects($this->once())
			->method('getAllVacancies')
			->will($this->returnValue($activeVacancyList));

		$this->vacancyService->setVacancyDao($vacancyDao);

		$readVacancyList = $this->vacancyService->getAllVacancies();
		$this->assertEquals($readVacancyList, $activeVacancyList);
	}

	/**
	 * Testing getPublishedVacancies()
	 */
	public function testGetPublishedVacancies() {

		$allVacancyList = TestDataService::loadObjectList('JobVacancy', $this->fixture, 'JobVacancy');
		$publishedActiveList = array($allVacancyList[0], $allVacancyList[2]);

		$vacancyDao = $this->getMock('VacancyDao');

		$vacancyDao->expects($this->once())
			->method('getPublishedVacancies')
			->will($this->returnValue($publishedActiveList));

		$this->vacancyService->setVacancyDao($vacancyDao);

		$readVacancyList = $this->vacancyService->getPublishedVacancies();
		$this->assertEquals($readVacancyList, $publishedActiveList);
	}
        
	/**
	 * Testing testGetVacancyList
	 */
	public function testSaveJobVacancy() {

		$jobVacancy = new JobVacancy();
		$jobVacancy->jobTitleCode = 'JOB002';
		$jobVacancy->name = "BA 2010";
		$jobVacancy->hiringManagerId = 2;
		$jobVacancy->noOfPositions = 2;
		$jobVacancy->description = "test";
		$jobVacancy->status = 1;
		$jobVacancy->definedTime = date('Y-m-d H:i:s');

		$vacancyDao = $this->getMock('VacancyDao');
		$vacancyDao->expects($this->once())
			->method('saveJobVacancy')
			->with($jobVacancy)
			->will($this->returnValue(true));

		$this->vacancyService->setVacancyDao($vacancyDao);

		$result = $this->vacancyService->saveJobVacancy($jobVacancy);
		$this->assertTrue($result);
	}

	public function testSearchVacancies() {

		$srchParams = array('jobTitle' => 'JOB002', 'jobVacancy' => '2', 'hiringManager' => '2', 'status' => '1');
		$vacancyList = TestDataService::fetchObject('JobVacancy', 2);


		$vacancyDao = $this->getMock('VacancyDao');

		$vacancyDao->expects($this->once())
			->method('searchVacancies')
			->with($srchParams)
			->will($this->returnValue($vacancyList));

		$this->vacancyService->setVacancyDao($vacancyDao);
		$result = $this->vacancyService->searchVacancies($srchParams);

		$this->assertEquals($result, $vacancyList);
	}
    
    /**
	 * Testing deleteVacancies with true arguments
	 */
    public function testDeleteVacanciesForTrue() {
        
        $vacancyIdsSet = array(0=>array(1, 3), 1=> array(2));

        foreach ($vacancyIdsSet as $vacancyIds) {
        
            $vacancyDao = $this->getMock('VacancyDao', array('deleteVacancies'));

            $vacancyDao->expects($this->once())
                       ->method('deleteVacancies')
                       ->with($vacancyIds)
                       ->will($this->returnValue(true));

            $this->vacancyService->setVacancyDao($vacancyDao);

            $result = $this->vacancyService->deleteVacancies($vacancyIds);

            $this->assertEquals(true, $result);
            
        }
        
    }
    
    /**
	 * Testing deleteVacancies with false arguments
	 */
    public function testDeleteVacanciesForFalse() {
        
        $vacancyIdsSet = array(0=>array(15));

        foreach ($vacancyIdsSet as $vacancyIds) {
        
            $vacancyDao = $this->getMock('VacancyDao', array('deleteVacancies'));

            $vacancyDao->expects($this->once())
                       ->method('deleteVacancies')
                       ->with($vacancyIds)
                       ->will($this->returnValue(false));

            $this->vacancyService->setVacancyDao($vacancyDao);

            $result = $this->vacancyService->deleteVacancies($vacancyIds);

            $this->assertEquals(false, $result);
            
        }
        
    }
    
    /**
	 * Testing deleteVacancies with null arguments
	 */
    public function testDeleteVacanciesForFalseWithNullArguments() {
        
        $vacancyIds = array();
        $result = $this->vacancyService->deleteVacancies($vacancyIds);
        $this->assertEquals(false, $result);
            
    }
    
    /**
	 * Testing searchVacanciesCount
	 */
    public function testSearchVacanciesCount() {

		$srchParams = array('jobTitle' => 'JOB002', 'jobVacancy' => '2', 'hiringManager' => '2', 'status' => '1');
		
		$vacancyDao = $this->getMock('VacancyDao', array('searchVacanciesCount'));

		$vacancyDao->expects($this->once())
			->method('searchVacanciesCount')
			->with($srchParams)
			->will($this->returnValue(1));

		$this->vacancyService->setVacancyDao($vacancyDao);
		$result = $this->vacancyService->searchVacanciesCount($srchParams);

		$this->assertEquals($result, 1);
        
	}
    
    /**
	 * Testing getVacancyById
	 */
    public function testGetVacancyById() {

		$vacancyId = 1;
        $allVacancyList = TestDataService::loadObjectList('JobVacancy', $this->fixture, 'JobVacancy');
		$returnedVacancy = $allVacancyList[0];
		
		$vacancyDao = $this->getMock('VacancyDao', array('getVacancyById'));

		$vacancyDao->expects($this->once())
			->method('getVacancyById')
			->with($vacancyId)
			->will($this->returnValue($returnedVacancy));

		$this->vacancyService->setVacancyDao($vacancyDao);
		$result = $this->vacancyService->getVacancyById($vacancyId);

		$this->assertTrue($result instanceof JobVacancy);
        
	}
    
    /**
	 * Testing getVacancyListForUserRole
	 */
    public function testGetVacancyListForUserRoleForCorrectObjects() {

        $allVacancyList = TestDataService::loadObjectList('JobVacancy', $this->fixture, 'JobVacancy');
		$allEmployeeList = TestDataService::loadObjectList('Employee', $this->fixture, 'Employee');
        
        $parameters = array('HIRING MANAGER', $allEmployeeList[0]);
		$returnedVacancy[0] = $allVacancyList[0];
		
		$vacancyDao = $this->getMock('VacancyDao', array('getVacancyListForUserRole'));

		$vacancyDao->expects($this->once())
			->method('getVacancyListForUserRole')
			->with($parameters[0], $parameters[1])
			->will($this->returnValue($returnedVacancy));

		$this->vacancyService->setVacancyDao($vacancyDao);
		$result = $this->vacancyService->getVacancyListForUserRole($parameters[0], $parameters[1]);

		$this->assertTrue($result[0] instanceof JobVacancy);
        
	}
    
    /**
	 * Testing getVacancyListForUserRole
	 */
    public function testGetVacancyListForUserRoleForCorrectNumberOfResults() {

        $allVacancyList = TestDataService::loadObjectList('JobVacancy', $this->fixture, 'JobVacancy');
		$allEmployeeList = TestDataService::loadObjectList('Employee', $this->fixture, 'Employee');
        
        $parameters = array('HIRING MANAGER', $allEmployeeList[0]);
		$returnedVacancy[0] = $allVacancyList[0];
		
		$vacancyDao = $this->getMock('VacancyDao', array('getVacancyListForUserRole'));

		$vacancyDao->expects($this->once())
			->method('getVacancyListForUserRole')
			->with($parameters[0], $parameters[1])
			->will($this->returnValue($returnedVacancy));

		$this->vacancyService->setVacancyDao($vacancyDao);
		$result = $this->vacancyService->getVacancyListForUserRole($parameters[0], $parameters[1]);

		$this->assertEquals(1, count($result));
        
	}

}

