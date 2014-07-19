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


class LeaveSummaryService extends BaseService {

    private $leaveSummaryDao;

    /**
     *
     * @return LeaveSummaryDao
     */
    public function getLeaveSummaryDao() {
        return $this->leaveSummaryDao;
    }

    /**
     *
     * @param LeaveSummaryDao $leaveSummaryDao
     * @return void
     */
    public function setLeaveSummaryDao(LeaveSummaryDao $leaveSummaryDao) {
        $this->leaveSummaryDao = $leaveSummaryDao;
    }

    public function fetchRawLeaveSummaryRecords($clues, $offset, $limit) {
        
        $includeTerminated = false;        
        if($clues['cmbWithTerminated'] != 0) {
            $includeTerminated = true;
        }
        
        $recordsResult = $this->getLeaveSummaryDao()->fetchRawLeaveSummaryRecords($clues, $offset, $limit, $includeTerminated);
        $recordsCount = $this->fetchRawLeaveSummaryRecordsCount($clues, $includeTerminated);

        $leaveEntitlementService = new LeaveEntitlementService();
        $leaveEntitlementService->setLeaveEntitlementDao(new LeaveEntitlementDao());

        $leavePeriodService = new LeavePeriodService();
        $leavePeriodService->setLeavePeriodDao(new LeavePeriodDao());

        $summaryListArray = Array();
        if ($recordsCount > 0) {

            $i = 0;

            while ($row = $recordsResult->fetch()) {

                $employeeName = $row['empFirstName'].' '.$row['empLastName'];
                $employeeId = $row['empNumber'];
                $employeeStatus = $row['empStatus'];
                $leaveType = $row['leaveTypeName'];
                $leaveTypeId = $row['leaveTypeId'];
                $leavePeriodId = $clues['cmbLeavePeriod']?$clues['cmbLeavePeriod']:$leavePeriodService->getCurrentLeavePeriod();

                $leaveEntitlementObj = $leaveEntitlementService->readEmployeeLeaveEntitlement($employeeId, $leaveTypeId, $leavePeriodId);

                if ($leaveEntitlementObj instanceof EmployeeLeaveEntitlement) {
                    $leaveEntitled = $leaveEntitlementObj->getNoOfDaysAllotted();
                    $leaveBroughtForward = $leaveEntitlementObj->getLeaveBroughtForward();
                    $leaveCarryForward = $leaveEntitlementObj->getLeaveCarriedForward();
                } else {
                    $leaveEntitled = '0.00';
                    $leaveBroughtForward = '0.00';
                    $leaveCarryForward = '0.00';
                }

                $leaveRequestService = new LeaveRequestService();
                $leaveRequestService->setLeaveRequestDao(new LeaveRequestDao());

                $leaveTaken = $leaveRequestService->getTakenLeaveSum($employeeId, $leaveTypeId, $leavePeriodId);
                $leaveTaken = empty($leaveTaken)?'0.00':$leaveTaken;

                //$leaveScheduled = $this->_getLeaveScheduled($employeeId, $leaveTypeId, $leavePeriodId);
                $leaveScheduled = $leaveRequestService->getScheduledLeavesSum($employeeId, $leaveTypeId, $leavePeriodId);
                $leaveScheduled = empty($leaveScheduled)?'0.00':$leaveScheduled;

                $leaveRemaining = ($leaveEntitled + $leaveBroughtForward) - ($leaveTaken + $leaveScheduled + $leaveCarryForward);
                $leaveRemaining = number_format($leaveRemaining, 2);

                $rowDisplayFlag = false;
                $deletedFlag = false;
                //show active leave types
                if($row['availableFlag'] == 1) {
                    $rowDisplayFlag = true;
                }

                //show inactive leave types if any leaveEntitled, leaveTaken, leaveScheduled of them above 0
                if(($row['availableFlag'] != 1) && ($leaveEntitled > 0 || $leaveTaken > 0 || $leaveScheduled > 0)) {
                    $rowDisplayFlag = true;
                    $deletedFlag = true;
                }

                if($rowDisplayFlag) {

                    $summaryListRow = Array();
                    $employeeLeaveEntitlementObject = new EmployeeLeaveEntitlement();

                    // If readonly value is 1, force read only
                    if (isset($row['readonly']) && $row['readonly'] == 1) {
                        $employeeLeaveEntitlementObject->setForceReadOnly(true);
                    }
                    
                    $employeeLeaveEntitlementObject->setEmployeeId($employeeId);
                    $employeeLeaveEntitlementObject->setEmployeeStatus($employeeStatus);
                    $employeeLeaveEntitlementObject->setLeaveTypeId($leaveTypeId);
                    $employeeLeaveEntitlementObject->setNoOfDaysAllotted($leaveEntitled);
                    $employeeLeaveEntitlementObject->setLeaveBroughtForward($leaveBroughtForward);
                    $employeeLeaveEntitlementObject->setLeaveCarriedForward($leaveCarryForward);

                    $employeeLeaveEntitlementObject->setLeavePeriodId($leavePeriodId);

                    $summaryListArray[] = $employeeLeaveEntitlementObject;
                    
                    $i++;
                }
            }
        }

        return $summaryListArray;
    }

    public function fetchRawLeaveSummaryRecordsCount($clues, $includeTerminated = false) {
        return $this->getLeaveSummaryDao()->fetchRawLeaveSummaryRecordsCount($clues, $includeTerminated);
    }
    
    /**
     * @tutorial this is a performance imporoved version of fetchRawLeaveSummaryRecords method
     * @param array $clues
     * @return array 
     */
    public function fetchRawLeaveSummaryRecordsImproved($clues) {
        return $this->getLeaveSummaryDao()->fetchRawLeaveSummaryRecordsImproved($clues);        
    }
    
}