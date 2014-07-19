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


class LeaveTypeService extends BaseService {

    private $leaveTypeDao;

    public function getLeaveTypeDao() {
        if (!($this->leaveTypeDao instanceof LeaveTypeDao)) {
            $this->leaveTypeDao = new LeaveTypeDao();
        }
        return $this->leaveTypeDao;
    }

    public function setLeaveTypeDao(LeaveTypeDao $leaveTypeDao) {
        $this->leaveTypeDao = $leaveTypeDao;
    }

    /**
     *
     * @param LeaveType $leaveType
     * @return boolean
     */
    public function saveLeaveType(LeaveType $leaveType) {

        $this->getLeaveTypeDao()->saveLeaveType($leaveType);

        return true;
    }

    /**
     * Delete Leave Type
     * @param array $leaveTypeList
     * @returns boolean
     * @throws LeaveServiceException
     */
    public function deleteLeaveType($leaveTypeList) {

        return $this->getLeaveTypeDao()->deleteLeaveType($leaveTypeList);
    }

    /**
     *
     * @return LeaveType Collection
     */
    public function getLeaveTypeList($operationalCountryId = null) {

        return $this->getLeaveTypeDao()->getLeaveTypeList($operationalCountryId);
    }

    /**
     *
     * @return LeaveType
     */
    public function readLeaveType($leaveTypeId) {

        return $this->getLeaveTypeDao()->readLeaveType($leaveTypeId);
    }

    public function readLeaveTypeByName($leaveTypeName) {

        return $this->getLeaveTypeDao()->readLeaveTypeByName($leaveTypeName);
    }

    public function undeleteLeaveType($leaveTypeId) {

        return $this->getLeaveTypeDao()->undeleteLeaveType($leaveTypeId);
    }

    public function getDeletedLeaveTypeList($operationalCountryId = null) {

        return $this->getLeaveTypeDao()->getDeletedLeaveTypeList($operationalCountryId);
    }
    
    /**
     *
     * @return array
     */
    public function getActiveLeaveTypeNamesArray($operationalCountryId = null) {

        $activeLeaveTypes = $this->getLeaveTypeList($operationalCountryId);

        $activeTypeNamesArray = array();

        foreach ($activeLeaveTypes as $activeLeaveType) {
            $activeTypeNamesArray[] = $activeLeaveType->getLeaveTypeName();
        }

        return $activeTypeNamesArray;
    }
    
    public function getDeletedLeaveTypeNamesArray($operationalCountryId = null) {

        $deletedLeaveTypes = $this->getDeletedLeaveTypeList($operationalCountryId);

        $deletedTypeNamesArray = array();

        foreach ($deletedLeaveTypes as $deletedLeaveType) {

            $deletedLeaveTypeObject = new stdClass();
            $deletedLeaveTypeObject->id = $deletedLeaveType->getLeaveTypeId();
            $deletedLeaveTypeObject->name = $deletedLeaveType->getLeaveTypeName();
            $deletedTypeNamesArray[] = $deletedLeaveTypeObject;
        }

        return $deletedTypeNamesArray;
    }

}