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

class ProjectService extends BaseService {

	private $projectDao;

	/**
	 * Construct
	 */
	public function __construct() {
		$this->projectDao = new ProjectDao();
	}

	/**
	 *
	 * @return <type>
	 */
	public function getProjectDao() {
		return $this->projectDao;
	}

	/**
	 *
	 * @param UbCoursesDao $UbCoursesDao
	 */
	public function setProjectDao(ProjectDao $projectDao) {
		$this->projectDao = $projectDao;
	}

	/**
	 *
	 * @param ProjectDao $projectDao 
	 */
	public function setTimesheetDao(ProjectDao $projectDao) {

		$this->projectDao = $projectDao;
	}

	/**
	 *
	 * get Project count
	 * 
	 * Get Active Project count in default. Can get all project count by passing $activeOnly as false.
	 * 
	 * @param type $activeOnly
	 * @return type 
	 */
	public function getProjectCount($activeOnly) {
		return $this->projectDao->getProjectCount($activeOnly);
	}

	/**
	 *
	 * Delete project
	 * 
	 * Set project's is_deleted flag to 1. This will handled the deleting of corresponding
	 * project activities and project admins under deleted project.
	 * 
	 * @param type $projectId
	 * @return type 
	 */
	public function deleteProject($projectId) {
		return $this->projectDao->deleteProject($projectId);
	}

	/**
	 *
	 * Delete project activity
	 * 
	 * Set project activity's is_deleted flag to 1.
	 * 
	 * @param type $activityId
	 * @return type 
	 */
	public function deleteProjectActivities($activityId) {
		return $this->projectDao->deleteProjectActivities($activityId);
	}

	/**
	 *
	 * Gret project by id.
	 * 
	 * @param type $projectId
	 * @return type 
	 */
	public function getProjectById($projectId) {
		return $this->projectDao->getProjectById($projectId);
	}

	/**
	 * 
	 * Get project activity by id.
	 * 
	 * @param type $projectId
	 * @return type 
	 */
	public function getProjectActivityById($projectId) {
		return $this->projectDao->getProjectActivityById($projectId);
	}

	/**
	 *
	 * Get all projects
	 * 
	 * Get all active projects as default. Can get all projects by passing $activeOnly parameter as false.
	 * 
	 * @return type 
	 */
	public function getAllProjects($activeOnly) {
		return $this->projectDao->getAllProjects($activeOnly);
	}

	/**
	 *
	 * Get active activity list for a project.
	 * 
	 * @param type $projectId
	 * @return type 
	 */
	public function getActivityListByProjectId($projectId) {
		return $this->projectDao->getActivityListByProjectId($projectId);
	}

	/**
	 * Will return wheather the activity has any timesheet records related.
	 * 
	 * @param type $activityId
	 * @return type 
	 */
	public function hasActivityGotTimesheetItems($activityId) {
		return $this->projectDao->hasActivityGotTimesheetItems($activityId);
	}

	/**
	 *
	 * Will return wheather the project has any timesheet records related.
	 * 
	 * @param type $projectId
	 * @return type 
	 */
	public function hasProjectGotTimesheetItems($projectId) {
		return $this->projectDao->hasProjectGotTimesheetItems($projectId);
	}

	/**
	 *
	 * Get active project list for a customer.
	 * 
	 * @param type $customerId
	 * @return type 
	 */
	public function getProjectsByCustomerId($customerId) {
		return $this->projectDao->getProjectsByCustomerId($customerId);
	}

	/**
	 * Get project list for login user
	 * 
	 * @param type $role
	 * @param type $empNumber
	 * @return type 
	 */
	public function getProjectListForUserRole($role, $empNumber) {
		return $this->projectDao->getProjectListForUserRole($role, $empNumber);
	}

	/**
	 * Gets project name with customer name given project id.
	 * 
	 * @param integer $projectId
	 * @return string
	 */
	public function getProjectNameWithCustomerName($projectId, $glue = " - ") {

		$project = $this->getProjectById($projectId);
		$projectName = $project->getCustomer()->getName() . $glue . $project->getName();

		return $projectName;
	}

	/**
	 * Get active project list
	 * 
	 * @return type 
	 */
	public function getActiveProjectList() {
		return $this->getProjectDao()->getActiveProjectList();
	}
        
        /**
        *Get list of active projects, ordered by customer name, project name.
        * 
        * @return Doctrine_Collection of Project objects. Empty collection if no
        *         active projects available.
        */
	public function getActiveProjectsOrderedByCustomer() {
		return $this->getProjectDao()->getActiveProjectsOrderedByCustomer();
	}        

	/**
	 * Get project list for a project admin
	 * 
	 * @param type $empNo
	 * @param type $emptyIfNotAprojectAdmin
	 * @return type 
	 */
	public function getProjectListByProjectAdmin($empNo, $emptyIfNotAprojectAdmin = false) {

		$projectAdmins = $this->getProjectDao()->getProjectAdminByEmpNumber($empNo);

		$projectIdArray = array();

		if (!is_null($projectAdmins)) {
			foreach ($projectAdmins as $projectAdmin) {
				$projectIdArray[] = $projectAdmin->getProjectId();
			}
		}

		if (empty($projectIdArray)) {
			return array();
		}

		$projectList = $this->getProjectDao()->getProjectsByProjectIds($projectIdArray);

		return $projectList;
	}

	/**
	 * Check wheather the user is a project admin
	 * 
	 * @param int $empNumber 
	 * @return boolean
	 */
	public function isProjectAdmin($empNumber) {
		$projects = $this->getProjectListByProjectAdmin($empNumber, true);
		return (count($projects) > 0);
	}

	/**
	 * Get project admin list
	 * 
	 * @return type 
	 */
	public function getProjectAdminList() {
		return $this->getProjectDao()->getProjectAdminList();
	}

	/**
	 * 
	 * Search project by project name, customer name and project admin.
	 * 
	 * @param type $srchClues
	 * @param type $allowedProjectList
	 * @return type 
	 */
	public function searchProjects($srchClues, $allowedProjectList) {
		return $this->getProjectDao()->searchProjects($srchClues, $allowedProjectList);
	}

	/**
	 *
	 * Get project count of the search results.
	 * 
	 * @param type $srchClues
	 * @param type $allowedProjectList
	 * @return type 
	 */
	public function getSearchProjectListCount($srchClues, $allowedProjectList) {
		return $this->getProjectDao()->getSearchProjectListCount($srchClues, $allowedProjectList);
	}

}
