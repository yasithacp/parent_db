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
class ProjectDaoTest extends PHPUnit_Framework_TestCase {

	private $projectDao;
	protected $fixture;

	/**
	 * Set up method
	 */
	protected function setUp() {

		$this->projectDao = new ProjectDao();
		$this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/ProjectDao.yml';
		TestDataService::populate($this->fixture);
	}

	public function testSearchProjectsForNullArray() {
		$srchClues = array();
		$allowedProjectList = array(1, 2);
		$result = $this->projectDao->searchProjects($srchClues, $allowedProjectList);
		$this->assertEquals(count($result), 2);
	}

	public function testSearchProjectsForProjectName() {
		$srchClues = array(
		    'project' => 'development'
		);
		$allowedProjectList = array(1);
		$result = $this->projectDao->searchProjects($srchClues, $allowedProjectList);
		$this->assertEquals(count($result), 1);
		$this->assertEquals($result[0]->getProjectId(), 1);
	}

	public function testSearchProjectsForCustomerName() {
		$srchClues = array(
		    'customer' => 'Xavier'
		);
		$allowedProjectList = array(1, 4);
		$result = $this->projectDao->searchProjects($srchClues, $allowedProjectList);
		$this->assertEquals(2, count($result));
		$this->assertEquals('Xavier', $result[0]->getCustomerName());
	}

	public function testSearchProjectsForProjectAdmin() {
		$srchClues = array(
		    'projectAdmin' => 'Kayla Abbey'
		);
		$allowedProjectList = array(1);
		$result = $this->projectDao->searchProjects($srchClues, $allowedProjectList);
		$this->assertEquals(count($result), 1);
		$this->assertEquals($result[0]->getProjectId(), 1);
	}

	public function testGetProjectCountWithActiveOnly() {
		$result = $this->projectDao->getProjectCount();
		$this->assertEquals(3, $result);
	}
	
	public function testGetProjectCount() {
		$result = $this->projectDao->getProjectCount(false);
		$this->assertEquals(4, $result);
	}

	public function testDeleteProject() {
		$this->projectDao->deleteProject(1);
		$result = $this->projectDao->getProjectById(1);
		$this->assertEquals($result->getIsDeleted(), 1);
	}

	public function testGetProjectActivityById() {
		$result = $this->projectDao->getProjectActivityById(1);
		$this->assertEquals($result->getName(), 'project activity 1');
	}

	public function testGetProjectById() {
		$result = $this->projectDao->getProjectById(1);
		$this->assertEquals($result->getName(), 'development');
	}

	public function testGetAllActiveProjectsWithActiveOnly() {
		$result = $this->projectDao->getAllProjects();
		$this->assertEquals(3, count($result));
	}
	
	public function testGetAllActiveProjects() {
		$result = $this->projectDao->getAllProjects(false);
		$this->assertEquals(4, count($result));
	}

//	public function testGetActivityListByProjectId() {
//		$result = $this->projectDao->getActivityListByProjectId(1);
//		$this->assertEquals(count($result), 2);
//		$this->assertEquals($result[0], 'project activity 1');
//	}

	public function testGetSearchProjectListCount() {
		$srchClues = array(
		    'projectAdmin' => 'Kayla Abbey'
		);
		$allowedProjectList = array(1);
		$result = $this->projectDao->getSearchProjectListCount($srchClues,$allowedProjectList);
		$this->assertEquals($result, 1);
	}

	public function testGetActiveProjectList() {

		$activeProjects = $this->projectDao->getActiveProjectList();
		$this->assertTrue($activeProjects[0] instanceof Project);
		$this->assertEquals(3, count($activeProjects));
	}
        
        public function testGetActiveProjectsOrderedByCustomer() {
            $sortedProjects = $this->projectDao->getActiveProjectsOrderedByCustomer();
            $this->assertEquals(3, count($sortedProjects));
            
            $this->assertTrue($sortedProjects[0] instanceof Project);
            $this->assertEquals(2, $sortedProjects[0]->getProjectId()); // Av Ltd - Engineering
           
            $this->assertTrue($sortedProjects[1] instanceof Project);
            $this->assertEquals(1, $sortedProjects[1]->getProjectId()); // Xavier - development
            
            $this->assertTrue($sortedProjects[2] instanceof Project);
            $this->assertEquals(4, $sortedProjects[2]->getProjectId()); // Xavier - Training            
        }

	public function testGetProjectsByProjectIdsWithActiveOnly() {

		$projectIdArray = array(1, 2);
		$activeProjects = $this->projectDao->getProjectsByProjectIds($projectIdArray);
		$this->assertTrue($activeProjects[0] instanceof Project);
		$this->assertEquals(2, count($activeProjects));
	}
	
	public function testGetActiveProjectsByProjectIds() {

		$projectIdArray = array(1, 2);
		$activeProjects = $this->projectDao->getProjectsByProjectIds($projectIdArray);
		$this->assertTrue($activeProjects[0] instanceof Project);
		$this->assertEquals(2, count($activeProjects));
	}

	public function testGetProjectAdminRecordsByEmpNo() {

		$empNo = 1;
		$projectAdmin = $this->projectDao->getProjectAdminByEmpNumber($empNo);
		$this->assertTrue($projectAdmin[0] instanceof ProjectAdmin);
		$this->assertEquals(1, count($projectAdmin));
	}
	
	public function testGetProjectAdminByProjectId() {

		$projectAdmin = $this->projectDao->getProjectAdminByProjectId(1);
		$this->assertTrue($projectAdmin[0] instanceof ProjectAdmin);
		$this->assertEquals(2, count($projectAdmin));
	}
	
	public function testDeleteProjectActivities() {

		$this->projectDao->deleteProjectActivities(1);
		$projectActivity = $this->projectDao->getProjectActivityById(1);
		$this->assertEquals($projectActivity->getIsDeleted(), 1);
	}

	public function testHasProjectGotTimesheetItems() {

		$result = $this->projectDao->hasProjectGotTimesheetItems(2);
		$this->assertTrue($result);
	}
	
	public function testHasActivityGotTimesheetItems() {

		$result = $this->projectDao->hasActivityGotTimesheetItems(1);
		$this->assertTrue($result);
	}
	
	public function testGetProjectsByCustomerId() {

		$result = $this->projectDao->getProjectsByCustomerId(1);
		$this->assertEquals(count($result), 2);
		$this->assertTrue($result[0] instanceof Project);
	}
	
	public function testGetProjectListForUserRole() {

		$result = $this->projectDao->getProjectListForUserRole(AdminUserRoleDecorator::ADMIN_USER, null);
		$this->assertEquals(4, count($result));
	}

}
