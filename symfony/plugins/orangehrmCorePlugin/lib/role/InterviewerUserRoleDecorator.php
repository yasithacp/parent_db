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

class InterviewerUserRoleDecorator extends UserRoleDecorator {
	const INTERVIEWER = "INTERVIEWER";
	const VIEW_CANDIDATES = "./symfony/web/index.php/recruitment/viewCandidates";

	private $user;

	public function __construct(User $user) {

		$this->user = $user;
		parent::setEmployeeNumber($user->getEmployeeNumber());
		parent::setUserId($user->getUserId());
		parent::setUserTimeZoneOffset($user->getUserTimeZoneOffset());
	}

	public function getAccessibleTimeMenus() {
		return $this->user->getAccessibleTimeMenus();
	}

	public function getAccessibleTimeSubMenus() {
		return $this->user->getAccessibleTimeSubMenus();
	}

	public function getAccessibleAttendanceSubMenus() {
		return $this->user->getAccessibleAttendanceSubMenus();
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

	/**
	 * Get actions that this user can perform on a perticular workflow with the current state
	 * @param int $workFlow
	 * @param string $state
	 * @return string[]
	 */
	public function getAllowedActions($workFlow, $state) {

		$accessFlowStateMachineService = new AccessFlowStateMachineService();
		$allowedActionsForInterviewer = $accessFlowStateMachineService->getAllowedActions($workFlow, $state, InterviewerUserRoleDecorator::INTERVIEWER);
		$existingAllowedActions = $this->user->getAllowedActions($workFlow, $state);
		if (is_null($allowedActionsForInterviewer)) {
			return $existingAllowedActions;
		} else {

			$allowedActionsList = array_unique(array_merge($allowedActionsForInterviewer, $existingAllowedActions));

			return $allowedActionsList;
		}
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
		$tempNextState = $accessFlowStateMachineService->getNextState($workFlow, $state, InterviewerUserRoleDecorator::INTERVIEWER, $action);

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

		$accessFlowStateMachineService = new AccessFlowStateMachineService();
		$applicationStates = $accessFlowStateMachineService->getAllAlowedRecruitmentApplicationStates($flow, InterviewerUserRoleDecorator::INTERVIEWER);
		$existingStates = $this->user->getAllAlowedRecruitmentApplicationStates($flow);
		if (is_null($applicationStates)) {
			return $existingStates;
		} else {
			$applicationStates = array_unique(array_merge($applicationStates, $existingStates));
			return $applicationStates;
		}
	}

	public function getActionableTimesheets() {
		return $this->user->getActionableTimesheets();
	}

	public function getActionableAttendanceStates($actions) {

		return $this->user->getActionableAttendanceStates($actions);
	}

	public function isAllowedToDefineTimeheetPeriod() {
		return $this->user->isAllowedToDefineTimeheetPeriod();
	}

	public function getActiveProjectList() {

		return $this->user->getActiveProjectList();
	}

	public function getActionableStates() {

		return $this->user->getActionableStates();
	}

	public function getAccessibleConfigurationSubMenus() {

		return $this->user->getAccessibleConfigurationSubMenus();
	}

	public function getAllowedCandidateList() {
		$accessFlowStateMachineService = new AccessFlowStateMachineService();
		$allowedCandidateIdList = $accessFlowStateMachineService->getAllowedCandidateList(InterviewerUserRoleDecorator::INTERVIEWER, $this->getEmployeeNumber());
		$existingIdList = $this->user->getAllowedCandidateList();
		if (is_null($allowedCandidateIdList)) {
			return $existingIdList;
		} else {
			$allowedCandidateIdList = array_unique(array_merge($allowedCandidateIdList, $existingIdList));
			return $allowedCandidateIdList;
		}
	}

	public function getAllowedCandidateListToDelete() {
		return $this->user->getAllowedCandidateListToDelete();
	}

	public function getAllowedVacancyList() {

		$accessFlowStateMachineService = new AccessFlowStateMachineService();
		$allowedVacancyIdList = $accessFlowStateMachineService->getAllowedVacancyList(InterviewerUserRoleDecorator::INTERVIEWER, $this->getEmployeeNumber());
		$existingIdList = $this->user->getAllowedVacancyList();
		if (is_null($allowedVacancyIdList)) {
			return $existingIdList;
		} else {
			$allowedVacancyIdList = array_unique(array_merge($allowedVacancyIdList, $existingIdList));
			return $allowedVacancyIdList;
		}
	}

	public function getAllowedCandidateHistoryList($candidateId) {

		$accessFlowStateMachineService = new AccessFlowStateMachineService();
		$allowedCandidateHistoryIdList = $accessFlowStateMachineService->getAllowedCandidateHistoryList(InterviewerUserRoleDecorator::INTERVIEWER, $this->getEmployeeNumber(), $candidateId);
		$existingIdList = $this->user->getAllowedCandidateHistoryList($candidateId);
		if (is_null($allowedCandidateHistoryIdList)) {
			return $existingIdList;
		} else {
			$allowedCandidateHistoryIdList = array_unique(array_merge($allowedCandidateHistoryIdList, $existingIdList));
			return $allowedCandidateHistoryIdList;
		}
	}

	public function getAccessibleRecruitmentMenus() {

		$topMenuItem = new TopMenuItem();
		$topMenuItem->setDisplayName(__("Candidates"));
		$topMenuItem->setLink(InterviewerUserRoleDecorator::VIEW_CANDIDATES);
		$tempArray = $this->user->getAccessibleRecruitmentMenus();
		$tempArray = $this->__chkAndPutItemsToArray($tempArray, $topMenuItem);

		return $tempArray;
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

	public function getAllowedProjectList() {
		return $this->user->getAllowedProjectList();
	}

	public function isAdmin() {
		return $this->user->isAdmin();
	}

	public function isProjectAdmin() {
		return $this->user->isProjectAdmin();
	}

	public function isHiringManager() {
		return $this->user->isHiringManager();
	}

	public function isInterviewer() {
		return true;
	}

}

