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

class LeaveRequestService extends BaseService {

    private $leaveRequestDao ;
    private $leaveTypeService;
    private $leaveEntitlementService;
    private $leavePeriodService;
    private $holidayService;

    private $leaveNotificationService;
    private $leaveStateManager;

    const LEAVE_CHANGE_TYPE_LEAVE = 'change_leave';
    const LEAVE_CHANGE_TYPE_LEAVE_REQUEST = 'change_leave_request';

    /**
     *
     * @return LeaveRequestDao
     */
    public function getLeaveRequestDao() {
        if (!($this->leaveRequestDao instanceof LeaveRequestDao)) {
            $this->leaveRequestDao = new LeaveRequestDao();
        }
        return $this->leaveRequestDao;
    }

    /**
     *
     * @param LeaveRequestDao $leaveRequestDao
     * @return void
     */
    public function setLeaveRequestDao(LeaveRequestDao $leaveRequestDao) {
        $this->leaveRequestDao = $leaveRequestDao;
    }

    /**
     *
     * @return <type>
     */
    public function setLeaveNotificationService(LeaveNotificationService $leaveNotificationService) {
        $this->leaveNotificationService = $leaveNotificationService;
    }

    /**
     *
     * @return LeaveNotificationService
     */
    public function getLeaveNotificationService() {
        if(is_null($this->leaveNotificationService)) {
            $this->leaveNotificationService = new LeaveNotificationService();
        }
        return $this->leaveNotificationService;
    }

    /**
     *
     * @param LeaveRequest $leaveRequest
     * @param Leave $leave
     * @return boolean
     */
    public function saveLeaveRequest( LeaveRequest $leaveRequest , $leaveList) {

        $this->getLeaveRequestDao()->saveLeaveRequest( $leaveRequest, $leaveList);

        return true ;

    }

    /**
     * @return LeaveEntitlementService
     */
    public function getLeaveEntitlementService() {
        if(is_null($this->leaveEntitlementService)) {
            $this->leaveEntitlementService = new LeaveEntitlementService();
            $this->leaveEntitlementService->setLeaveEntitlementDao(new LeaveEntitlementDao());
        }
        return $this->leaveEntitlementService;
    }

    /**
     * @return LeaveTypeService
     */
    public function getLeaveTypeService() {
        if(is_null($this->leaveTypeService)) {
            $this->leaveTypeService = new LeaveTypeService();
            $this->leaveTypeService->setLeaveTypeDao(new LeaveTypeDao());
        }
        return $this->leaveTypeService;
    }

    /**
     * Sets LeaveEntitlementService
     * @param LeaveEntitlementService $leaveEntitlementService
     */
    public function setLeaveEntitlementService(LeaveEntitlementService $leaveEntitlementService) {
        $this->leaveEntitlementService = $leaveEntitlementService;
    }

    /**
     * Sets LeaveTypeService
     * @param LeaveTypeService $leaveTypeService
     */
    public function setLeaveTypeService(LeaveTypeService $leaveTypeService) {
        $this->leaveTypeService = $leaveTypeService;
    }

    /**
     * Returns LeavePeriodService
     * @return LeavePeriodService
     */
    public function getLeavePeriodService() {
        if(is_null($this->leavePeriodService)) {
            $this->leavePeriodService = new LeavePeriodService();
            $this->leavePeriodService->setLeavePeriodDao(new LeavePeriodDao());
        }
        return $this->leavePeriodService;
    }

    /**
     * Sets LeavePeriodService
     * @param LeavePeriodService $leavePeriodService
     */
    public function setLeavePeriodService(LeavePeriodService $leavePeriodService) {
        $this->leavePeriodService = $leavePeriodService;
    }

    /**
     * Returns HolidayService
     * @return HolidayService
     */
    public function getHolidayService() {
        if(is_null($this->holidayService)) {
            $this->holidayService = new HolidayService();
        }
        return $this->holidayService;
    }

    /**
     * Sets HolidayService
     * @param HolidayService $holidayService
     */
    public function setHolidayService(HolidayService $holidayService) {
        $this->holidayService = $holidayService;
    }

    /**
     * Set leave state manager. Only use for unit testing.
     * 
     * @param LeaveStateManager $leaveStateManager
     */
    public function setLeaveStateManager(LeaveStateManager $leaveStateManager) {
        $this->leaveStateManager = $leaveStateManager;
    }

    public function getLeaveStateManager() {
        if(is_null($this->leaveStateManager)) {
            $this->leaveStateManager = LeaveStateManager::instance();
        }
        return $this->leaveStateManager;
    }

    /**
     *
     * @param Employee $employee
     * @return LeaveType Collection
     */
    public function getEmployeeAllowedToApplyLeaveTypes(Employee $employee) {

        try {
            $leavePeriodService = $this->getLeavePeriodService();
            $leavePeriod = $leavePeriodService->getCurrentLeavePeriod();

            $leaveEntitlementService    = $this->getLeaveEntitlementService();
            $leaveTypeService           = $this->getLeaveTypeService();

            $leaveTypes     = $leaveTypeService->getLeaveTypeList();
            $leaveTypeList  = array();

            foreach($leaveTypes as $leaveType) {
                $entitlementDays = $leaveEntitlementService->getLeaveBalance($employee->getEmpNumber(), $leaveType->getLeaveTypeId(),$leavePeriod->getLeavePeriodId());

                if($entitlementDays > 0) {
                    array_push($leaveTypeList, $leaveType);
                }
            }
            return $leaveTypeList;
        } catch(Exception $e) {
            throw new LeaveServiceException($e->getMessage());
        }
    }

    /**
     *
     * @param date $leaveStartDate
     * @param date $leaveEndDate
     * @param int $empId
     * @return Leave List
     * @todo Parameter list is too long. Refactor to use LeaveParameterObject
     */
    public function getOverlappingLeave($leaveStartDate, $leaveEndDate ,$empId, $startTime = '00:00', $endTime='59:00', $hoursPerday = '8') {

        return $this->getLeaveRequestDao()->getOverlappingLeave($leaveStartDate, $leaveEndDate ,$empId,  $startTime, $endTime, $hoursPerday);

    }

    /**
     *
     * @param LeaveType $leaveType
     * @return boolean
     */
    public function isApplyToMoreThanCurrent(LeaveType $leaveType){
		try{
			$leaveRuleEligibilityProcessor	=	new LeaveRuleEligibilityProcessor();
			return $leaveRuleEligibilityProcessor->allowApplyToMoreThanCurrent($leaveType);

		}catch( Exception $e){
			throw new LeaveServiceException($e->getMessage());
		}
	}

    /**
     *
     * @param $empId
     * @param $leaveTypeId
     * @return int
     */
    public function getNumOfLeave($empId, $leaveTypeId) {

        return $this->getLeaveRequestDao()->getNumOfLeave($empId, $leaveTypeId);

    }

    /**
     *
     * @param $empId
     * @param $leaveTypeId 
     * @param $$leavePeriodId
     * @return int
     */
    public function getNumOfAvaliableLeave($empId, $leaveTypeId, $leavePeriodId = null) {
        
        return $this->getLeaveRequestDao()->getNumOfAvaliableLeave($empId, $leaveTypeId, $leavePeriodId);
        
    }

    /**
     *
     * @param $empId
     * @param $leaveTypeId
     * @return bool
     */
    public function isEmployeeHavingLeaveBalance( $empId, $leaveTypeId ,$leaveRequest,$applyDays) {
        try {
            $leaveEntitlementService = $this->getLeaveEntitlementService();
            $entitledDays	=	$leaveEntitlementService->getEmployeeLeaveEntitlementDays($empId, $leaveTypeId,$leaveRequest->getLeavePeriodId());
            $leaveDays		=	$this->getLeaveRequestDao()->getNumOfAvaliableLeave($empId, $leaveTypeId);

            $leaveEntitlement = $leaveEntitlementService->readEmployeeLeaveEntitlement($empId, $leaveTypeId, $leaveRequest->getLeavePeriodId());
            $leaveBoughtForward = 0;
            if($leaveEntitlement instanceof EmployeeLeaveEntitlement) {
                $leaveBoughtForward = $leaveEntitlement->getLeaveBroughtForward();
            }

            $leaveBalance = $leaveEntitlementService->getLeaveBalance(
                    $empId, $leaveTypeId,
                    $leaveRequest->getLeavePeriodId());

            $entitledDays += $leaveBoughtForward;

            if($entitledDays == 0)
                throw new Exception('Leave Entitlements Not Allocated',102);

            //this is for border period leave apply - days splitting
            $leavePeriodService = $this->getLeavePeriodService();

            //this would either create or returns the next leave period
            $currentLeavePeriod     = $leavePeriodService->getLeavePeriod(strtotime($leaveRequest->getDateApplied()));
            $leaveAppliedEndDateTimeStamp = strtotime("+" . $applyDays . " day", strtotime($leaveRequest->getDateApplied()));
            $nextLeavePeriod        = $leavePeriodService->createNextLeavePeriod(date("Y-m-d", $leaveAppliedEndDateTimeStamp));
            $currentPeriodStartDate = explode("-", $currentLeavePeriod->getStartDate());
            $nextYearLeaveBalance   = 0;

            if($nextLeavePeriod instanceof LeavePeriod) {
                $nextYearLeaveBalance = $leaveEntitlementService->getLeaveBalance(
                        $empId, $leaveTypeId,
                        $nextLeavePeriod->getLeavePeriodId());
                //this is to notify users are applying to the same leave period
                $nextPeriodStartDate    = explode("-", $nextLeavePeriod->getStartDate());
                if($nextPeriodStartDate[0] == $currentPeriodStartDate[0]) {
                    $nextLeavePeriod        = null;
                    $nextYearLeaveBalance   = 0;
                }
            }

            //this is only applicable if user applies leave during current leave period
            if(strtotime($currentLeavePeriod->getStartDate()) < strtotime($leaveRequest->getDateApplied()) &&
                    strtotime($currentLeavePeriod->getEndDate()) > $leaveAppliedEndDateTimeStamp) {
                if($leaveBalance < $applyDays) {
                    throw new Exception('Leave Balance Exceeded',102);
                }
            }

            //this is to verify whether leave applied within border period
            if($nextLeavePeriod instanceof LeavePeriod && strtotime($currentLeavePeriod->getStartDate()) < strtotime($leaveRequest->getDateApplied()) &&
                    strtotime($nextLeavePeriod->getEndDate()) > $leaveAppliedEndDateTimeStamp) {

                $endDateTimeStamp = strtotime($leavePeriodService->getCurrentLeavePeriod()->getEndDate());
                $borderDays = date("d", ($endDateTimeStamp - strtotime($leaveRequest->getDateApplied())));
                if($borderDays > $leaveBalance || $nextYearLeaveBalance < ($applyDays - $borderDays)) {
                    throw new Exception("Leave Balance Exceeded", 102);
                }
            }

            return true ;

        }catch( Exception $e) {
            throw new LeaveServiceException($e->getMessage());
        }
    }

    public function isLeaveRequestWithinLeaveBalance($employeeId, $leaveTypeId, $leaveList) {

        $currentLeavePeriod = $this->getLeavePeriodService()->getCurrentLeavePeriod();
        $currentLeavePeriodEndDate = $currentLeavePeriod->getEndDate();
        $currentLeavePeriodEndDateTimeStamp = strtotime($currentLeavePeriodEndDate);

        $leaveEntitlementService = $this->getLeaveEntitlementService();

        $leaveLengthOnCurrentLeavePeriod = 0;
        $leaveLengthOnNextLeavePeriod = 0;

        $canApplyForCurrentLeavePeriod = true;
        $canApplyForNextLeavePeriod = true;

        foreach ($leaveList as $leave) {

            if (strtotime($leave->getLeaveDate()) <= $currentLeavePeriodEndDateTimeStamp) {

                $leaveLengthOnCurrentLeavePeriod += $leave->getLeaveLengthDays();

            } else {

                $leaveLengthOnNextLeavePeriod += $leave->getLeaveLengthDays();

            }

        }

        if ($leaveLengthOnCurrentLeavePeriod > 0) {

            $currentLeaveBalance = $leaveEntitlementService->getLeaveBalance($employeeId, $leaveTypeId, $currentLeavePeriod->getLeavePeriodId());

            if ($leaveLengthOnCurrentLeavePeriod > $currentLeaveBalance) {

                $canApplyForCurrentLeavePeriod = false;

            }

        }

        if ($leaveLengthOnNextLeavePeriod > 0) {

            $nextLeavePeriod = $this->getLeavePeriodService()->getNextLeavePeriodByCurrentEndDate($currentLeavePeriodEndDate);

            if ($nextLeavePeriod instanceof LeavePeriod) {

                $nextLeaveBalance = $leaveEntitlementService->getLeaveBalance($employeeId, $leaveTypeId, $nextLeavePeriod->getLeavePeriodId());

                if ($leaveLengthOnNextLeavePeriod > $nextLeaveBalance) {

                    $canApplyForNextLeavePeriod = false;

                }

            } else {

                $canApplyForNextLeavePeriod = false;

            }

        }

        if ($canApplyForCurrentLeavePeriod && $canApplyForNextLeavePeriod) {
            return true;
        } else {
            return false;
        }

    }

    /**
     *
     * @param ParameterObject $searchParameters
     * @param array $statuses
     * @return array
     */
    public function searchLeaveRequests($searchParameters, $page = 1, $isCSVPDFExport = false, $isMyLeaveList = false) {
        $result = $this->getLeaveRequestDao()->searchLeaveRequests($searchParameters, $page, $isCSVPDFExport, $isMyLeaveList);
        return $result;

    }

    /**
     * Get Leave Request Status
     * @param $day
     * @return unknown_type
     */
    public function getLeaveRequestStatus( $day ) {
        try {
            $holidayService = $this->getHolidayService();
            $holiday = $holidayService->readHolidayByDate($day);
            if ($holiday != null) {
                return Leave::LEAVE_STATUS_LEAVE_HOLIDAY;
            }

            return Leave::LEAVE_STATUS_LEAVE_PENDING_APPROVAL;

        } catch (Exception $e) {
            throw new LeaveServiceException($e->getMessage());
        }
    }

    /**
     *
     * @param int $leaveRequestId
     * @return array
     */
    public function searchLeave($leaveRequestId) {

        return $this->getLeaveRequestDao()->fetchLeave($leaveRequestId);

    }

    /**
     *
     * @param int $leaveId
     * @return array
     */
    public function readLeave($leaveId) {

        return $this->getLeaveRequestDao()->readLeave($leaveId);

    }

    public function saveLeave(Leave $leave) {
        return $this->getLeaveRequestDao()->saveLeave($leave);
    }

    /**
     * @param int $leaveRequestId
     */
    public function fetchLeaveRequest($leaveRequestId) {

        return $this->getLeaveRequestDao()->fetchLeaveRequest($leaveRequestId);

    }

    /**
     * Modify Over lap leaves
     * @param LeaveRequest $leaveRequest
     * @param $leaveList
     * @return unknown_type
     */
    public function modifyOverlapLeaveRequest(LeaveRequest $leaveRequest , $leaveList ) {

        return $this->getLeaveRequestDao()->modifyOverlapLeaveRequest($leaveRequest , $leaveList);

    }

    /**
     *
     * @param LeavePeriod $leavePeriod
     * @return boolean
     */
    public function adjustLeavePeriodOverlapLeaves(LeavePeriod $leavePeriod) {

        $overlapleaveList =	$this->getLeaveRequestDao()->getLeavePeriodOverlapLeaves($leavePeriod);

        if (count($overlapleaveList) > 0) {

            foreach($overlapleaveList as $leave) {

                $leaveRequest	=	$leave->getLeaveRequest();
                $leaveList		=	$this->getLeaveRequestDao()->fetchLeave($leaveRequest->getLeaveRequestId());
                $this->getLeaveRequestDao()->modifyOverlapLeaveRequest($leaveRequest,$leaveList,$leavePeriod);

            }

        }

    }

    /**
     *
     * @param array $changes
     * @param string $changeType
     * @return boolean
     */
    public function changeLeaveStatus($changes, $changeType, $changeComments = null, $changedByUserType = null, $changedUserId = null) {
        if(is_array($changes)) {
            $approvalIds = array_keys(array_filter($changes, array($this, '_filterApprovals')));
            $rejectionIds = array_keys(array_filter($changes, array($this, '_filterRejections')));
            $cancellationIds = array_keys(array_filter($changes, array($this, '_filterCancellations')));

            $leaveNotificationService = $this->getLeaveNotificationService();

            if ($changeType == 'change_leave_request') {
                foreach ($approvalIds as $leaveRequestId) {
                    $approvals = $this->searchLeave($leaveRequestId);
                    $this->_approveLeave($approvals, $changeComments[$leaveRequestId]);

                    $leaveNotificationService->approve($approvals, $changedByUserType, $changedUserId, 'request');
                }

                foreach ($rejectionIds as $leaveRequestId) {
                    $rejections = $this->searchLeave($leaveRequestId);
                    $this->_rejectLeave($rejections, $changeComments[$leaveRequestId]);
                    $leaveNotificationService->reject($rejections, $changedByUserType, $changedUserId, 'request');
                }

                foreach ($cancellationIds as $leaveRequestId) {
                    $cancellations = $this->searchLeave($leaveRequestId);
                    $this->_cancelLeave($cancellations, $changedByUserType);
                    
                    if ($changedByUserType == SystemUser::USER_TYPE_EMPLOYEE) {
                        $leaveNotificationService->cancelEmployee($cancellations, $changedByUserType, $changedUserId, 'request');
                    } else {
                        $leaveNotificationService->cancel($cancellations, $changedByUserType, $changedUserId, 'request');
                    }                    
                }

            } elseif ($changeType == 'change_leave') {

                $approvals = array();
                foreach ($approvalIds as $leaveId) {
                    $approvals[] = $this->getLeaveRequestDao()->getLeaveById($leaveId);
                }
                $this->_approveLeave($approvals, $changeComments);

                foreach ($approvals as $approval) {
                    $leaveNotificationService->approve(array($approval), $changedByUserType, $changedUserId, 'single');
                }

                $rejections = array();
                foreach ($rejectionIds as $leaveId) {
                    $rejections[] = $this->getLeaveRequestDao()->getLeaveById($leaveId);
                }
                $this->_rejectLeave($rejections, $changeComments);

                foreach ($rejections as $rejection) {
                    $leaveNotificationService->reject(array($rejection), $changedByUserType, $changedUserId, 'single');
                }

                $cancellations = array();
                foreach ($cancellationIds as $leaveId) {
                    $cancellations[] = $this->getLeaveRequestDao()->getLeaveById($leaveId);
                }
                $this->_cancelLeave($cancellations, $changedByUserType);

                foreach ($cancellations as $cancellation) {

                    if ($changedByUserType == SystemUser::USER_TYPE_EMPLOYEE) {
                        $leaveNotificationService->cancelEmployee(array($cancellation), $changedByUserType, $changedUserId, 'single');
                    } else {
                        $leaveNotificationService->cancel(array($cancellation), $changedByUserType, $changedUserId, 'single');
                    }
                }

            } else {
                throw new LeaveServiceException('Wrong change type passed');
            }
        }else {
            throw new LeaveServiceException('Empty changes list');
        }

    }

    private function _approveLeave($leave, $comments, $changeType = null) {
        $leaveStateManager = $this->getLeaveStateManager();

        $leaveRequests = array();
        foreach ($leave as $approval) {
            $leaveRequestId = $approval->getLeaveRequest()->getLeaveRequestId();
            $leaveRequests[$leaveRequestId]['requestObj'] = $approval->getLeaveRequest();
            $leaveRequests[$leaveRequestId]['leaves'][] = $approval;

            $comment = is_array($comments) ? $comments[$approval->getLeaveId()] : $comments;

            $leaveStateManager->setLeave($approval);
            $leaveStateManager->setChangeComments($comment);
            $leaveStateManager->approve();
        }

    }

    private function _rejectLeave($leave, $comments, $changeType = null) {
        $leaveStateManager = $this->getLeaveStateManager();

        $leaveRequests = array();
        foreach ($leave as $rejection) {
            $leaveRequestId = $rejection->getLeaveRequest()->getLeaveRequestId();
            $leaveRequests[$leaveRequestId]['requestObj'] = $rejection->getLeaveRequest();
            $leaveRequests[$leaveRequestId]['leaves'][] = $rejection;

            $comment = is_array($comments) ? $comments[$rejection->getLeaveId()] : $comments;

            $leaveStateManager->setLeave($rejection);
            $leaveStateManager->setChangeComments($comment);
            $leaveStateManager->reject();
        }

    }

    private function _cancelLeave($leave, $changeType = null) {
        $leaveStateManager = $this->getLeaveStateManager();

        $leaveRequests = array();
        foreach ($leave as $cancellation) {
            $leaveRequestId = $cancellation->getLeaveRequest()->getLeaveRequestId();
            $leaveRequests[$leaveRequestId]['requestObj'] = $cancellation->getLeaveRequest();
            $leaveRequests[$leaveRequestId]['leaves'][] = $cancellation;

            $leaveStateManager->setLeave($cancellation);
            $leaveStateManager->cancel();
        }

    }

    public function getScheduledLeavesSum($employeeId, $leaveTypeId, $leavePeriodId) {

        return $this->getLeaveRequestDao()->getScheduledLeavesSum($employeeId, $leaveTypeId, $leavePeriodId);

    }

    public function getTakenLeaveSum($employeeId, $leaveTypeId, $leavePeriodId) {

        return $this->getLeaveRequestDao()->getTakenLeaveSum($employeeId, $leaveTypeId, $leavePeriodId);

    }
    
    public function getLeaveRequestActions($request, $loggedUserId, $listMode) {
        $actions = array();

        if ($request->canApprove() && $listMode != LeaveListForm::MODE_MY_LEAVE_LIST && $request->getEmployeeId() != $loggedUserId) {
            $actions['markedForApproval'] = 'Approve';
            $actions['markedForRejection'] = 'Reject';
        }

        if ($request->canCancel(Auth::instance()->hasRole(Auth::ADMIN_ROLE))) {
            $actions['markedForCancellation'] = 'Cancel';
        }
        
        return $actions;
    }
    
    public function getLeaveActions($leave, $loggedUserId, $listMode) {
        $actions = array();

        if ($leave->canApprove() && $listMode != viewLeaveRequestAction::MODE_MY_LEAVE_DETAILED_LIST && $leave->getEmployeeId() != $loggedUserId) {
            $actions['markedForApproval'] = 'Approve';
            $actions['markedForRejection'] = 'Reject';
        }

        if ($leave->canCancel(Auth::instance()->hasRole(Auth::ADMIN_ROLE))) {
            $actions['markedForCancellation'] = 'Cancel';
        }
        
        return $actions;
    }

    /**
     *
     * @param string $element
     * @return boolean
     */
    private function _filterApprovals($element) {
        return ($element == 'markedForApproval');
    }

    /**
     *
     * @param unknown_type $element
     * @return boolean
     */
    private function _filterRejections($element) {
        return ($element == 'markedForRejection');
    }

    /**
     *
     * @param unknown_type $element
     * @return boolean
     */
    private function _filterCancellations($element) {
        return ($element == 'markedForCancellation');
    }
    

    /**
     *
     * @param type $employeeId
     * @param type $date
     * @return double
     */
    public function getTotalLeaveDuration($employeeId, $date){
        return $this->getLeaveRequestDao()->getTotalLeaveDuration($employeeId, $date);
    }

    public function getLeaveById($leaveId) {
        return $this->getLeaveRequestDao()->getLeaveById($leaveId);
    }
     /**
     *
     * @param ParameterObject $searchParameters
     * @param array $statuses
     * @return array
     */
    public function getLeaveRequestSearchResultAsArray($searchParameters) {
        return $this->getLeaveRequestDao()->getLeaveRequestSearchResultAsArray($searchParameters);
    }
    
     /**
     *
     * @param ParameterObject $searchParameters
     * @param array $statuses
     * @return array
     */
    public function getDetailedLeaveRequestSearchResultAsArray($searchParameters) {
        return $this->getLeaveRequestDao()->getDetailedLeaveRequestSearchResultAsArray($searchParameters);
    }

}
