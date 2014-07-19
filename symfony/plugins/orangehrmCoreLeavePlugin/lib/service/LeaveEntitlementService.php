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


/**
 * Leave Period Service
 */
class LeaveEntitlementService extends BaseService {

    private $leaveEntitlementDao;
    private $leaveRequestService;

    public function getLeaveEntitlementDao() {
        if (!($this->leaveEntitlementDao instanceof LeaveEntitlementDao)) {
            $this->leaveEntitlementDao = new LeaveEntitlementDao();
        }
        return $this->leaveEntitlementDao ;
    }

    public function setLeaveEntitlementDao(LeaveEntitlementDao $leaveEntitlementDao) {
        $this->leaveEntitlementDao	=	$leaveEntitlementDao;
    }

    public function getLeaveRequestService() {

        if (is_null($this->leaveRequestService)) {
            $this->leaveRequestService = new LeaveRequestService();
            $this->leaveRequestService->setLeaveRequestDao(new LeaveRequestDao());
        }

        return $this->leaveRequestService;

    }

    public function setLeaveRequestService(LeaveRequestService $leaveRequestService) {
        $this->leaveRequestService = $leaveRequestService;
    }

    /**
     *
     * @param int $empId
     * @param int $leaveTypeId
     * @return int
     */
    public function getEmployeeLeaveEntitlementDays( $empId, $leaveTypeId,$leavePeriodId) {

        $entitlementdays	=	0 ;

        $employeeLeaveEntitlement	=	$this->getLeaveEntitlementDao()->getEmployeeLeaveEntitlement( $empId, $leaveTypeId ,$leavePeriodId);

        if ($employeeLeaveEntitlement != null) {
            $entitlementdays = $employeeLeaveEntitlement->getNoOfDaysAllotted();
        }

        return $entitlementdays;

    }

    /**
     *
     * @param LeaveRequest $leaveRquest
     * @param int $adjustment
     * @return boolean True if the operation is successful
     */
    public function adjustEmployeeLeaveEntitlement($leave, $adjustment) {

        $leaveRquest = $leave->getLeaveRequest();

        return $this->getLeaveEntitlementDao()->adjustEmployeeLeaveEntitlement($leaveRquest->getEmployeeId(), $leaveRquest->getLeaveTypeId(), $leaveRquest->getLeavePeriodId(), $adjustment);

    }

    /**
     *
     * @param int $employeeId
     * @param String $leaveTypeId
     * @param int $leavePeriodId
     * @param int $entitlment
     * @return boolean Returns true if the operation is successfuly
     */
    public function saveEmployeeLeaveEntitlement( $employeeId, $leaveTypeId, $leavePeriodId , $entitlment,$overWrite = false) {

        $employeeLeaveEntitlement	=	$this->getLeaveEntitlementDao()->readEmployeeLeaveEntitlement($employeeId ,$leaveTypeId, $leavePeriodId);

        if($employeeLeaveEntitlement != null) {

            if($overWrite) {
                $this->getLeaveEntitlementDao()->overwriteEmployeeLeaveEntitlement($employeeId ,$leaveTypeId, $leavePeriodId, $entitlment);
            } else {
                $this->getLeaveEntitlementDao()->updateEmployeeLeaveEntitlement($employeeId ,$leaveTypeId, $leavePeriodId, $entitlment);
            }

        } else {
            $this->getLeaveEntitlementDao()->saveEmployeeLeaveEntitlement($employeeId ,$leaveTypeId, $leavePeriodId, $entitlment);
        }

        return true ;

    }

    public function readEmployeeLeaveEntitlement($employeeId, $leaveTypeId, $leavePeriodId) {

        return $this->getLeaveEntitlementDao()->readEmployeeLeaveEntitlement($employeeId, $leaveTypeId, $leavePeriodId);

    }


    /**
     * Save employee leave carried forward for given period
     * @param int $employeeId
     * @param int $leaveTypeId
     * @param int $leavePeriodId
     * @param float $carriedForwardLeaveLength
     * @return boolean
     */
    public function saveEmployeeLeaveCarriedForward( $employeeId, $leaveTypeId, $leavePeriodId, $carriedForwardLeaveLength) {

        return $this->getLeaveEntitlementDao()->saveEmployeeLeaveCarriedForward($employeeId, $leaveTypeId, $leavePeriodId, $carriedForwardLeaveLength);

    }

    /**
     * Save employee leave brought forward for given period
     * @param int $employeeId
     * @param int $leaveTypeId
     * @param int $leavePeriodId
     * @param float $broughtForwardLeaveLength
     * @return boolean
     */
    public function saveEmployeeLeaveBroughtForward( $employeeId, $leaveTypeId, $leavePeriodId, $broughtForwardLeaveLength) {

        return $this->getLeaveEntitlementDao()->saveEmployeeLeaveBroughtForward($employeeId, $leaveTypeId, $leavePeriodId, $broughtForwardLeaveLength);

    }

    public function getLeaveBalance($employeeId, $leaveTypeId, $leavePeriodId) {

        $leaveEntitlementObj = $this->
            readEmployeeLeaveEntitlement(
            $employeeId, $leaveTypeId, $leavePeriodId);

        if ($leaveEntitlementObj instanceof EmployeeLeaveEntitlement) {
            $leaveEntitled = $leaveEntitlementObj->getNoOfDaysAllotted();
            $leaveBroughtForward = $leaveEntitlementObj->getLeaveBroughtForward();
            $leaveCarryForward = $leaveEntitlementObj->getLeaveCarriedForward();
        } else {
            $leaveEntitled = '0.00';
            $leaveBroughtForward = '0.00';
            $leaveCarryForward = '0.00';
        }

        $leaveRequestService = $this->getLeaveRequestService();

        $leaveTaken = $leaveRequestService->getTakenLeaveSum($employeeId, $leaveTypeId, $leavePeriodId);
        $leaveTaken = empty($leaveTaken) ? '0.00' : $leaveTaken;

        //$leaveScheduled = $this->_getLeaveScheduled($employeeId, $leaveTypeId, $leavePeriodId);
        $leaveScheduled = $leaveRequestService->getScheduledLeavesSum($employeeId, $leaveTypeId, $leavePeriodId);
        $leaveScheduled = empty($leaveScheduled) ? '0.00' : $leaveScheduled;

        $leaveRemaining = ($leaveEntitled + $leaveBroughtForward) - ($leaveTaken + $leaveScheduled + $leaveCarryForward);
        $leaveRemaining = number_format($leaveRemaining, 2);

        return $leaveRemaining;
    }

    /**
     * Check Whether the Employee is Allowed to Apply Requested Leave Days
     * @param array $requestedLeaveDays key => leave period id
     * @param LeaveRequest $leaveRequest
     * @return boolean 
     */
    public function isLeaveRequestNotExceededLeaveBalance($requestedLeaveDays, $leaveRequest) {

        foreach ($requestedLeaveDays as $leavePeriodId => $days) {
            $leaveQuota = $this->getEmployeeLeaveEntitlementDays($leaveRequest->getEmployeeId(), $leaveRequest->getLeaveTypeId(), $leavePeriodId);
            $acceptedLeaveDays = $this->getLeaveRequestService()->getNumOfAvaliableLeave($leaveRequest->getEmployeeId(), $leaveRequest->getLeaveTypeId(), $leavePeriodId);
            
            if ($days > ($leaveQuota - $acceptedLeaveDays)) {
                return false;
            }
        }

        return true;
    }

}