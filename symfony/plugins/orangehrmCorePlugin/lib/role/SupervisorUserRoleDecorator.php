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

class SupervisorUserRoleDecorator extends UserRoleDecorator {
	const SUPERVISOR_USER = "SUPERVISOR";
	const VIEW_EMPLOYEE_TIMESHEET = "./symfony/web/index.php/time/viewEmployeeTimesheet";
	const EMPLOYEE_REPORT_LINK="./symfony/web/index.php/time/displayEmployeeReportCriteria?reportId=2";
	const VIEW_ATTENDANCE_RECORD_LINK="./symfony/web/index.php/attendance/viewAttendanceRecord";
	const ATTENDANCE_TOTAL_SUMMARY_REPORT_LINK="./symfony/web/index.php/time/displayAttendanceSummaryReportCriteria?reportId=4";
        const CSV_TIMESHEET_EXPORT ="./symfony/web/index.php/csvExport/viewTimesheetCsvExtract";
        const CSV_ATTENDANCE_EXPORT ="./symfony/web/index.php/time/viewAttendanceDataExtract";

	private $user;
	private $employeeService;
	private $timesheetService;

	public function __construct(User $user) {

		$this->user = $user;
		parent::setEmployeeNumber($user->getEmployeeNumber());
		parent::setUserId($user->getUserId());
		parent::setUserTimeZoneOffset($user->getUserTimeZoneOffset());
	}

	public function getTimesheetService() {

		if (is_null($this->timesheetService)) {

			$this->timesheetService = new TimesheetService();
		}

		return $this->timesheetService;
	}

	/**
	 * Set Timesheet Data Access Object
	 * @param TimesheetService $timesheetService
	 * @return void
	 */
	public function setTimesheetService(TimesheetService $timesheetService) {

		$this->timesheetService = $timesheetService;
	}

	/**
	 * Get the Employee Data Access Object
	 * @return EmployeeService
	 */
	public function getEmployeeService() {

		if (is_null($this->employeeService)) {
			$this->employeeService = new EmployeeService();
		}

		return $this->employeeService;
	}

	/**
	 * Set Employee Data Access Object
	 * @param EmployeeService $employeeService
	 * @return void
	 */
	public function setEmployeeService(EmployeeService $employeeService) {

		$this->EmployeeService = $employeeService;
	}

	public function getAccessibleTimeMenus() {

		$topMenuItemArray = $this->user->getAccessibleTimeMenus();

		$topMenuItem = new TopMenuItem();
		$topMenuItem->setDisplayName("Reports");
		$topMenuItem->setLink(SupervisorUserRoleDecorator::EMPLOYEE_REPORT_LINK);

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
		$topMenuItem->setDisplayName(__("Employee Timesheets"));
		$topMenuItem->setLink(SupervisorUserRoleDecorator::VIEW_EMPLOYEE_TIMESHEET);
		$topMenuItemArray = $this->__chkAndPutItemsToArray($topMenuItemArray, $topMenuItem);
        
		if ($this->isPluginAvailable('orangehrmTimesheetCsvExtractorPlugin')){       
		    $topMenuItem = new TopMenuItem();
		    $topMenuItem->setDisplayName(__("Export To CSV"));
		    $topMenuItem->setLink(SupervisorUserRoleDecorator::CSV_TIMESHEET_EXPORT);
		    $topMenuItemArray = $this->__chkAndPutItemsToArray($topMenuItemArray, $topMenuItem);

		}
		
                $topMenuItemArray = $this->__chkAndPutItemsToArray($topMenuItemArray, $topMenuItem);

                return $topMenuItemArray;
        }

	public function getAccessibleAttendanceSubMenus() {
		$topMenuItemArray = $this->user->getAccessibleAttendanceSubMenus();
		$topMenuItem = new TopMenuItem();
		$topMenuItem->setDisplayName(__("Employee Records"));
		$topMenuItem->setLink(SupervisorUserRoleDecorator::VIEW_ATTENDANCE_RECORD_LINK);
        

                $topMenuItemArray = $this->__chkAndPutItemsToArray($topMenuItemArray, $topMenuItem);
		
                if ($this->isPluginAvailable('orangehrmAttendanceDataExtractorPlugin')){
		    $topMenuItem = new TopMenuItem();
		    $topMenuItem->setDisplayName(__("Export To CSV"));
		    $topMenuItem->setLink(AdminUserRoleDecorator::CSV_ATTENDANCE_EXPORT);
		 }

                $topMenuItemArray = $this->__chkAndPutItemsToArray($topMenuItemArray, $topMenuItem);
		return $topMenuItemArray;
	}

	public function getAccessibleReportSubMenus() {

		$topMenuItemArray = $this->user->getAccessibleReportSubMenus();

		$topMenuItem = new TopMenuItem();
		$topMenuItem->setDisplayName(__("Employee Reports"));
		$topMenuItem->setLink(SupervisorUserRoleDecorator::EMPLOYEE_REPORT_LINK);

		$topMenuItemArray = $this->__chkAndPutItemsToArray($topMenuItemArray, $topMenuItem);

		$topMenuItem = new TopMenuItem();
		$topMenuItem->setDisplayName(__("Attendance Summary"));
		$topMenuItem->setLink(AdminUserRoleDecorator::ATTENDANCE_TOTAL_SUMMARY_REPORT_LINK);

		$topMenuItemArray = $this->__chkAndPutItemsToArray($topMenuItemArray, $topMenuItem);

		return $topMenuItemArray;
	}

	public function getEmployeeList() {

		$employeeList = $this->getEmployeeService()->getSupervisorEmployeeChain($this->getEmployeeNumber(), true);
		return $employeeList;
	}

	public function getEmployeeListForAttendanceTotalSummaryReport() {

		$employeeList = $this->getEmployeeService()->getSupervisorEmployeeChain($this->getEmployeeNumber(), true);
		return $employeeList;
	}

	public function getAllowedActions($workFlow, $state) {

		$accessFlowStateMachineService = new AccessFlowStateMachineService();
		$allowedActionsForSupervisorUser = $accessFlowStateMachineService->getAllowedActions($workFlow, $state, SupervisorUserRoleDecorator::SUPERVISOR_USER);

		$existingAllowedActions = $this->user->getAllowedActions($workFlow, $state);

		if (is_null($allowedActionsForSupervisorUser)) {
			return $existingAllowedActions;
		}

		$allowedActionsList = array_unique(array_merge($allowedActionsForSupervisorUser, $existingAllowedActions));

		return $allowedActionsList;
	}

	public function getNextState($workFlow, $state, $action) {

		$accessFlowStateMachineService = new AccessFlowStateMachineService();
		$tempNextState = $accessFlowStateMachineService->getNextState($workFlow, $state, SupervisorUserRoleDecorator::SUPERVISOR_USER, $action);

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
		$pendingApprovelTimesheets = null;
		$accessFlowStateMachinService = new AccessFlowStateMachineService();
		$action = array(PluginWorkflowStateMachine::TIMESHEET_ACTION_APPROVE, PluginWorkflowStateMachine::TIMESHEET_ACTION_REJECT);
		$actionableStatesList = $accessFlowStateMachinService->getActionableStates(PluginWorkflowStateMachine::FLOW_TIME_TIMESHEET, SupervisorUserRoleDecorator::SUPERVISOR_USER, $action);

		$subordinateListObjects = $this->getEmployeeService()->getSubordinateListForEmployee($this->getEmployeeNumber());
		$subordinateList = array();
		foreach ($subordinateListObjects as $subordinate) {
			$subordinateList[] = $subordinate->getSubordinate();
		}

		if ($actionableStatesList != null) {
			foreach ($subordinateList as $employee) {

				$timesheetList = $this->getTimesheetService()->getTimesheetByEmployeeIdAndState($employee->getEmpNumber(), $actionableStatesList);

				if ($timesheetList != null) {

					foreach ($timesheetList as $timesheet) {

						$pendingApprovelTimesheetArray["timesheetId"] = $timesheet->getTimesheetId();
						$pendingApprovelTimesheetArray["employeeFirstName"] = $employee->getFirstName();
						$pendingApprovelTimesheetArray["employeeLastName"] = $employee->getLastName();
						$pendingApprovelTimesheetArray["timesheetStartday"] = $timesheet->getStartDate();
						$pendingApprovelTimesheetArray["timesheetEndDate"] = $timesheet->getEndDate();
						$pendingApprovelTimesheetArray["employeeId"] = $employee->getEmpNumber();
						$pendingApprovelTimesheets[] = $pendingApprovelTimesheetArray;
					}
				}
			}
		}
		if ($pendingApprovelTimesheets[0] != null) {

			return $pendingApprovelTimesheets;
		} else {

			return $this->user->getActionableTimesheets();
		}
	}

	public function getActionableAttendanceStates($actions) {


		$accessFlowStateMachinService = new AccessFlowStateMachineService();
		$actionableAttendanceStatesForSupervisorUser = $accessFlowStateMachinService->getActionableStates(PluginWorkflowStateMachine::FLOW_ATTENDANCE, SupervisorUserRoleDecorator::SUPERVISOR_USER, $actions);


		$actionableAttendanceStates = $this->user->getActionableAttendanceStates($actions);

		if (is_null($actionableAttendanceStatesForSupervisorUser)) {
			return $actionableAttendanceStates;
		}

		$actionableAttendanceStatesList = array_unique(array_merge($actionableAttendanceStatesForSupervisorUser, $actionableAttendanceStates));
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

	public function isProjectAdmin() {
		return $this->user->isProjectAdmin();
	}

	public function isHiringManager() {
		return $this->user->isHiringManager();
	}

	public function isInterviewer() {
		return $this->user->isInterviewer();
	}

}
