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
class ProjectServiceTest extends PHPUnit_Framework_TestCase {

	private $projectService;
	private $fixture;

	/**
	 * Set up method
	 */
	protected function setUp() {
		$this->projectService = new ProjectService();
		$this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/ProjectDao.yml';
		TestDataService::populate($this->fixture);
	}

	public function testGetProjectCount() {

		$projectList = TestDataService::loadObjectList('Project', $this->fixture, 'Project');

		$projectDao = $this->getMock('ProjectDao');
		$projectDao->expects($this->once())
			->method('getProjectCount')
			->with(false)
			->will($this->returnValue(count($projectList)));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->getProjectCount(false);
		$this->assertEquals($result, count($projectList));
	}

	public function testDeleteProject() {

		$projectDao = $this->getMock('ProjectDao');
		$projectDao->expects($this->once())
			->method('deleteProject')
			->with(1)
			->will($this->returnValue(true));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->deleteProject(1);
		$this->assertEquals($result, true);
	}

	public function testDeleteProjectActivities() {

		$projectDao = $this->getMock('ProjectDao');
		$projectDao->expects($this->once())
			->method('deleteProjectActivities')
			->with(1)
			->will($this->returnValue(true));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->deleteProjectActivities(1);
		$this->assertEquals($result, true);
	}

	public function testGetProjectById() {

		$projectList = TestDataService::loadObjectList('Project', $this->fixture, 'Project');

		$projectDao = $this->getMock('ProjectDao');
		$projectDao->expects($this->once())
			->method('getProjectById')
			->with(1)
			->will($this->returnValue($projectList[0]));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->getProjectById(1);
		$this->assertEquals($result, $projectList[0]);
	}

	public function testGetProjectActivityById() {

		$projectActivityList = TestDataService::loadObjectList('ProjectActivity', $this->fixture, 'ProjectActivity');

		$projectDao = $this->getMock('ProjectDao');
		$projectDao->expects($this->once())
			->method('getProjectActivityById')
			->with(1)
			->will($this->returnValue($projectActivityList[0]));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->getProjectActivityById(1);
		$this->assertEquals($result, $projectActivityList[0]);
	}

	public function testGetAllProjects() {

		$projectList = TestDataService::loadObjectList('Project', $this->fixture, 'Project');

		$projectDao = $this->getMock('ProjectDao');
		$projectDao->expects($this->once())
			->method('getAllProjects')
			->with(false)
			->will($this->returnValue($projectList));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->getAllProjects(false);
		$this->assertEquals($result, $projectList);
	}

	public function testGetActivityListByProjectId() {

		$projectActivityList = TestDataService::loadObjectList('ProjectActivity', $this->fixture, 'ProjectActivity');

		$projectDao = $this->getMock('ProjectDao');
		$projectDao->expects($this->once())
			->method('getActivityListByProjectId')
			->with(1)
			->will($this->returnValue($projectActivityList));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->getActivityListByProjectId(1);
		$this->assertEquals($result, $projectActivityList);
	}

	public function testHasActivityGotTimesheetItems() {

		$projectDao = $this->getMock('ProjectDao');
		$projectDao->expects($this->once())
			->method('hasActivityGotTimesheetItems')
			->with(1)
			->will($this->returnValue(true));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->hasActivityGotTimesheetItems(1);
		$this->assertEquals($result, true);
	}

	public function testHasProjectGotTimesheetItems() {

		$projectDao = $this->getMock('ProjectDao');
		$projectDao->expects($this->once())
			->method('hasProjectGotTimesheetItems')
			->with(1)
			->will($this->returnValue(true));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->hasProjectGotTimesheetItems(1);
		$this->assertEquals($result, true);
	}

	public function testGetProjectsByCustomerId() {

		$projectList = TestDataService::loadObjectList('Project', $this->fixture, 'Project');
		$projectList = array($projectList[0], $projectList[1]);

		$projectDao = $this->getMock('ProjectDao');
		$projectDao->expects($this->once())
			->method('getProjectsByCustomerId')
			->with(1)
			->will($this->returnValue($projectList));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->getProjectsByCustomerId(1);
		$this->assertEquals($result, $projectList);
	}

	public function testGetProjectListForUserRole() {

		$role = "ADMIN_USER";
		$empNumber = null;
		$projectList = TestDataService::loadObjectList('Project', $this->fixture, 'Project');

		$projectDao = $this->getMock('ProjectDao');
		$projectDao->expects($this->once())
			->method('getProjectListForUserRole')
			->with($role, $empNumber)
			->will($this->returnValue($projectList));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->getProjectListForUserRole($role, $empNumber);
		$this->assertEquals($result, $projectList);
	}

	public function testGetProjectNameWithCustomerName() {

		$projectName = "Xavier - development";
		$result = $this->projectService->getProjectNameWithCustomerName(1);
		$this->assertEquals($result, $projectName);
	}

	public function testGetActiveProjectList() {
		
		$projectList = TestDataService::loadObjectList('Project', $this->fixture, 'Project');
		$projectList = array($projectList[0], $projectList[1]);
		
		$projectDao = $this->getMock('ProjectDao');
		$projectDao->expects($this->once())
			->method('getActiveProjectList')
			->will($this->returnValue($projectList));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->getActiveProjectList();
		$this->assertEquals($result, $projectList);
	}
	
	public function testGetProjectListByProjectAdmin() {
		
		$projectList = TestDataService::loadObjectList('Project', $this->fixture, 'Project');
		$employee = TestDataService::loadObjectList('ProjectAdmin', $this->fixture, 'ProjectAdmin');
		
		$projectId = array(1);
		
		$projectDao = $this->getMock('ProjectDao',array('getProjectAdminByEmpNumber', 'getProjectsByProjectIds'));
		$projectDao->expects($this->once())
			->method('getProjectAdminByEmpNumber')
			->with(1)
			->will($this->returnValue(array($employee[0])));
		
		$projectDao->expects($this->once())
			->method('getProjectsByProjectIds')
			->with($projectId)
			->will($this->returnValue(array($projectList[0])));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->getProjectListByProjectAdmin(1);
		$this->assertEquals($result[0]->getProjectId(), $projectList[0]->getProjectId());
	}
	
	public function testIsProjectAdmin() {

		$result = $this->projectService->isProjectAdmin(1);
		$this->assertEquals($result, true);
	}
	
	public function testSearchProjects() {

		$project = TestDataService::loadObjectList('Project', $this->fixture, 'Project');
		$srchClues = array(
		    'project' => 'development'
		);
		$allowedProjectList = array(1);
		
		$projectDao = $this->getMock('ProjectDao');
		$projectDao->expects($this->once())
			->method('searchProjects')
			->with($srchClues, $allowedProjectList)
			->will($this->returnValue($project[0]));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->searchProjects($srchClues, $allowedProjectList);
		$this->assertEquals($result, $project[0]);
	}
	
	public function testGetSearchProjectListCount() {

		$project = TestDataService::loadObjectList('Project', $this->fixture, 'Project');
		$srchClues = array(
		    'project' => 'development'
		);
		$allowedProjectList = array(1);
		
		$projectDao = $this->getMock('ProjectDao');
		$projectDao->expects($this->once())
			->method('getSearchProjectListCount')
			->with($srchClues, $allowedProjectList)
			->will($this->returnValue(1));

		$this->projectService->setProjectDao($projectDao);

		$result = $this->projectService->getSearchProjectListCount($srchClues, $allowedProjectList);
		$this->assertEquals($result, 1);
	}
        
        public function testGetActiveProjectsOrderedByCustomer() {
            // Verify that service calls DAO and returns object returned by dao method.
            $project = new Project();
            $expected = array($project);

            $projectDao = $this->getMock('ProjectDao');
            $projectDao->expects($this->once())
                    ->method('getActiveProjectsOrderedByCustomer')
                    ->will($this->returnValue($expected));

            $this->projectService->setProjectDao($projectDao);

            $result = $this->projectService->getActiveProjectsOrderedByCustomer();
            $this->assertEquals($expected, $result);            
        }
	
}

?>
