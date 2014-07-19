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

class ProjectAdminUserRoleDecorator extends UserRoleDecorator {
	const PROJECT_ADMIN_USER = "PROJECT ADMIN";
	const PROJECT_REPORT_LINK="./symfony/web/index.php/time/displayProjectReportCriteria?reportId=1";

	private $user;
	private $projectService;

	public function __construct(User $user) {

		$this->user = $user;
		parent::setEmployeeNumber($user->getEmployeeNumber());
		parent::setUserId($user->getUserId());
		parent::setUserTimeZoneOffset($user->getUserTimeZoneOffset());
	}

	public function getProjectService() {

		if (is_null($this->projectService)) {

			$this->projectService = new ProjectService();
		}

		return $this->projectService;
	}

	/**
	 * Set Project Data Access Object
	 * @param ProjectService $projectService
	 * @return void
	 */
	public function setProjectService(ProjectService $projectService) {

		$this->projectService = $projectService;
	}

	public function getAccessibleTimeMenus() {

		$topMenuItemArray = $this->user->getAccessibleTimeMenus();

		$topMenuItem = new TopMenuItem();
		$topMenuItem->setDisplayName("Reports");
		$topMenuItem->setLink(ProjectAdminUserRoleDecorator::PROJECT_REPORT_LINK);

		$itemIsInArray = false;
		foreach ($topMenuItemArray as $item) {
			if ($topMenuItem->getDisplayName() == $item->getDisplayName()) {
				$itemIsInArray = true;
				break;
			}
		}

		if (!$itemIsInArray) {
			array_push($topMenuItemArray, $topMenuItem);
		}

		return $topMenuItemArray;
	}

	public function getAccessibleTimeSubMenus() {

		$tempArray = $this->user->getAccessibleTimeSubMenus();

		return $tempArray;
	}

	public function getAccessibleAttendanceSubMenus() {

		$tempArray = $this->user->getAccessibleAttendanceSubMenus();

		return $tempArray;
	}

	public function getAccessibleReportSubMenus() {

		$topMenuItemArray = $this->user->getAccessibleReportSubMenus();

		$topMenuItem = new TopMenuItem();
		$topMenuItem->setDisplayName(__("Project Reports"));
		$topMenuItem->setLink(AdminUserRoleDecorator::PROJECT_REPORT_LINK);


		if (!in_array($topMenuItem, $topMenuItemArray)) {
			array_push($topMenuItemArray, $topMenuItem);
		}

		return $topMenuItemArray;
	}

	public function getEmployeeList() {
		return $this->user->getEmployeeList();
	}

	public function getEmployeeListForAttendanceTotalSummaryReport() {
		return $this->user->getEmployeeListForAttendanceTotalSummaryReport();
	}

	/**
	 * Get actions that this user can perform on a perticular workflow with the current state
	 * @param int $workFlow
	 * @param string $state
	 * @return string[]
	 */
	public function getAllowedActions($workFlow, $state) {

		$accessFlowStateMachineService = new AccessFlowStateMachineService();
		$allowedActionsForEssUser = $accessFlowStateMachineService->getAllowedActions($workFlow, $state, ProjectAdminUserRoleDecorator::PROJECT_ADMIN_USER);

		$existingAllowedActions = $this->user->getAllowedActions($workFlow, $state);

		if (is_null($allowedActionsForEssUser)) {
			return $existingAllowedActions;
		}

		$allowedActionsList = array_unique(array_merge($allowedActionsForEssUser, $existingAllowedActions));

		return $allowedActionsList;
	}

	/**
	 * Get next state given workflow, state and action for this user
	 * @param int $workFlow
	 * @param string $state
	 * @param int $action
	 * @return string
	 */
	public function getNextState($workFlow, $state, $action) {

		$accessFlowStateMachineService = new AccessFlowStateMachineService();
		$tempNextState = $accessFlowStateMachineService->getNextState($workFlow, $state, ProjectAdminUserRoleDecorator::PROJECT_ADMIN_USER, $action);

		$temp = $this->user->getNextState($workFlow, $state, $action);

		if (is_null($tempNextState)) {
			return $temp;
		}

		return $tempNextState;
	}

	public function getAllAlowedRecruitmentApplicationStates($flow) {
		return $this->user->getAllAlowedRecruitmentApplicationStates($flow);
	}

	public function getActionableTimesheets() {
		return $this->user->getActionableTimesheets();
	}

	public function getActionableAttendanceStates($actions) {


		$accessFlowStateMachinService = new AccessFlowStateMachineService();
		$actionableAttendanceStatesForProjectAdminUser = $accessFlowStateMachinService->getActionableStates(PluginWorkflowStateMachine::FLOW_ATTENDANCE, ProjectAdminUserRoleDecorator::PROJECT_ADMIN_USER, $actions);


		$actionableAttendanceStates = $this->user->getActionableAttendanceStates($actions);

		if (is_null($actionableAttendanceStatesForProjectAdminUser)) {
			return $actionableAttendanceStates;
		}

		$actionableAttendanceStatesList = array_unique(array_merge($actionableAttendanceStatesForProjectAdminUser, $actionableAttendanceStates));
		return $actionableAttendanceStatesList;
	}

	public function isAllowedToDefineTimeheetPeriod() {

		$isAllowed = $this->user->isAllowedToDefineTimeheetPeriod();

		return $isAllowed;
	}

	public function getActiveProjectList() {

		$activeProjectList = $this->getProjectService()->getProjectListByProjectAdmin($this->user->getEmployeeNumber());
		return $activeProjectList;
	}

	public function getActionableStates() {

		return $this->user->getActionableStates();
	}

	public function getAccessibleConfigurationSubMenus() {

		return $this->user->getAccessibleConfigurationSubMenus();
	}

	public function getAllowedCandidateList() {

		return $this->user->getAllowedCandidateList();
	}

	public function getAllowedCandidateListToDelete() {

		return $this->user->getAllowedCandidateListToDelete();
	}

	public function getAllowedVacancyList() {
		return $this->user->getAllowedVacancyList();
	}

	public function getAllowedCandidateHistoryList($candidateId) {

		return $this->user->getAllowedCandidateHistoryList($candidateId);
	}

	public function getAccessibleRecruitmentMenus() {
		return $this->user->getAccessibleRecruitmentMenus();
	}

	public function getAllowedProjectList() {

		$accessFlowStateMachineService = new AccessFlowStateMachineService();
		$allowedProjectIdList = $accessFlowStateMachineService->getAllowedProjectList(ProjectAdminUserRoleDecorator::PROJECT_ADMIN_USER, $this->getEmployeeNumber());
		$existingIdList = $this->user->getAllowedProjectList();
		if (is_null($allowedProjectIdList)) {
			return $existingIdList;
		} else {
			$allowedProjectIdList = array_unique(array_merge($allowedProjectIdList, $existingIdList));
			return $allowedProjectIdList;
		}
	}

	public function isAdmin() {
		return $this->user->isAdmin();
	}

	public function isHiringManager() {
		return $this->user->isHiringManager();
	}

	public function isProjectAdmin() {
		return true;
	}

	public function isInterviewer() {
		return $this->user->isInterviewer();
	}

}