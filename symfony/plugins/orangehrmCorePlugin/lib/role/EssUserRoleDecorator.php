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

class EssUserRoleDecorator extends UserRoleDecorator {
	const ESS_USER = "ESS USER";
	const VIEW_MY_TIMESHEET = "./symfony/web/index.php/time/viewMyTimesheet";
	const PUNCH_ATTENDANCE_RECORD = "./symfony/web/index.php/attendance/punchIn";
	const VIEW_ATTENDANCE_RECORD_LINK="./symfony/web/index.php/attendance/viewMyAttendanceRecord";
	private $user;

	public function __construct(User $user) {

		$this->user = $user;
		parent::setEmployeeNumber($user->getEmployeeNumber());
		parent::setUserId($user->getUserId());
		parent::setUserTimeZoneOffset($user->getUserTimeZoneOffset());
	}

	public function getAccessibleTimeMenus() {

		$topMenuItemArray = $this->user->getAccessibleTimeMenus();

		$topMenuItem = new TopMenuItem();
		$topMenuItem->setDisplayName("Timesheets");
		$topMenuItem->setLink(EssUserRoleDecorator::VIEW_MY_TIMESHEET);

		$topMenuItemArray = $this->__chkAndPutItemsToArray($topMenuItemArray, $topMenuItem);

		$topMenuItem = new TopMenuItem();
		$topMenuItem->setDisplayName("Attendance");
		$topMenuItem->setLink(EssUserRoleDecorator::PUNCH_ATTENDANCE_RECORD);

		$topMenuItemArray = $this->__chkAndPutItemsToArray($topMenuItemArray, $topMenuItem);

		return $topMenuItemArray;
	}

	private function __chkAndPutItemsToArray($topMenuItemArray, $topMenuItem) {
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
		$topMenuItemArray = $this->user->getAccessibleTimeSubMenus();
		$topMenuItem = new TopMenuItem();
		$topMenuItem->setDisplayName(__("My Timesheets"));
		$topMenuItem->setLink(EssUserRoleDecorator::VIEW_MY_TIMESHEET);
		if (!in_array($topMenuItem, $topMenuItemArray)) {
			array_push($topMenuItemArray, $topMenuItem);
		}
		return $topMenuItemArray;
	}

	public function getAccessibleAttendanceSubMenus() {
		$topMenuItemArray = $this->user->getAccessibleAttendanceSubMenus();
		$topMenuItem = new TopMenuItem();
		$topMenuItem->setDisplayName(__("My Records"));
		$topMenuItem->setLink(EssUserRoleDecorator::VIEW_ATTENDANCE_RECORD_LINK);
		if (!in_array($topMenuItem, $topMenuItemArray)) {
			array_push($topMenuItemArray, $topMenuItem);
		}

		$topMenuItem = new TopMenuItem();
		$topMenuItem->setDisplayName(__("Punch In/Out"));
		$topMenuItem->setLink(EssUserRoleDecorator::PUNCH_ATTENDANCE_RECORD);

		if (!in_array($topMenuItem, $topMenuItemArray)) {
			array_push($topMenuItemArray, $topMenuItem);
		}
		return $topMenuItemArray;
	}

	public function getAccessibleReportSubMenus() {
		return $this->user->getAccessibleReportSubMenus();
	}

	public function getEmployeeList() {
		return $this->user->getEmployeeList();
	}

	public function getEmployeeListForAttendanceTotalSummaryReport() {
		return $this->user->getEmployeeListForAttendanceTotalSummaryReport();
	}

	public function getAllowedActions($workFlow, $state) {

		$accessFlowStateMachineService = new AccessFlowStateMachineService();
		$allowedActionsForEssUser = $accessFlowStateMachineService->getAllowedActions($workFlow, $state, EssUserRoleDecorator::ESS_USER);

		$existingAllowedActions = $this->user->getAllowedActions($workFlow, $state);

		if (is_null($allowedActionsForEssUser)) {
			return $existingAllowedActions;
		}

		$allowedActionsList = array_unique(array_merge($allowedActionsForEssUser, $existingAllowedActions));

		return $allowedActionsList;
	}

	public function getNextState($workFlow, $state, $action) {

		$accessFlowStateMachineService = new AccessFlowStateMachineService();
		$tempNextState = $accessFlowStateMachineService->getNextState($workFlow, $state, EssUserRoleDecorator::ESS_USER, $action);
		$temp = $this->user->getNextState($workFlow, $state, $action);

		if (is_null($tempNextState)) {

			return $temp;
		}

		return $tempNextState;
	}

	/**
	 * Get previous states given workflow, action for this user
	 * @param int $workFlow
	 * @param int $action
	 * @return string
	 */
	public function getAllAlowedRecruitmentApplicationStates($flow) {
		return $this->user->getAllAlowedRecruitmentApplicationStates($flow);
	}

	public function getActionableTimesheets() {
		return $this->user->getActionableTimesheets();
	}

	public function getActionableAttendanceStates($actions) {

		$accessFlowStateMachinService = new AccessFlowStateMachineService();
		$actionableAttendanceStatesForEssUser = $accessFlowStateMachinService->getActionableStates(PluginWorkflowStateMachine::FLOW_ATTENDANCE, EssUserRoleDecorator::ESS_USER, $actions);


		$actionableAttendanceStates = $this->user->getActionableAttendanceStates($actions);

		if (is_null($actionableAttendanceStatesForEssUser)) {
			return $actionableAttendanceStates;
		}

		$actionableAttendanceStatesList = array_unique(array_merge($actionableAttendanceStatesForEssUser, $actionableAttendanceStates));
		return $actionableAttendanceStatesList;
	}

	public function isAllowedToDefineTimeheetPeriod() {
		return $this->user->isAllowedToDefineTimeheetPeriod();
	}

	public function getActiveProjectList() {

		$activeProjectList = $this->user->getActiveProjectList();
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
		return $this->user->getAllowedProjectList();
	}

	public function isAdmin() {
		return $this->user->isAdmin();
	}

	public function isHiringManager() {
		return $this->user->isHiringManager();
	}

	public function isProjectAdmin() {
		return $this->user->isProjectAdmin();
	}

	public function isInterviewer() {
		return $this->user->isInterviewer();
	}

}
