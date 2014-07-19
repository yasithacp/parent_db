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


class LeaveTypeDao extends BaseDao {

    /**
     * Get Logger instance. Creates if not already created.
     *
     * @return Logger
     */
    protected function getLogger() {
        if (is_null($this->logger)) {
            $this->logger = Logger::getLogger('leave.LeaveTypeDao');
        }

        return($this->logger);
    }

    /**
     *
     * @param LeaveType $leaveType
     * @return boolean
     */
    public function saveLeaveType(LeaveType $leaveType) {
        try {
            if ($leaveType->getLeaveTypeId() == '') {

                $idGenService = new IDGeneratorService();
                $idGenService->setEntity($leaveType);
                $leaveType->setLeaveTypeId($idGenService->getNextID());
            }

            $leaveType->save();

            return true;
        } catch (Exception $e) {
            $this->getLogger()->error("Exception in saveLeaveType:" . $e);
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Delete Leave Type
     * @param array leaveTypeList
     * @returns boolean
     * @throws DaoException
     */
    public function deleteLeaveType($leaveTypeList) {

        try {

            $q = Doctrine_Query::create()
                            ->update('LeaveType lt')
                            ->set('lt.availableFlag', '?', '0')
                            ->whereIn('lt.leaveTypeId', $leaveTypeList);
            $numDeleted = $q->execute();
            if ($numDeleted > 0) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            $this->getLogger()->error("Exception in deleteLeaveType:" . $e);
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Get Leave Type list
     * @param mixed $operationalCountryId 
     * @return LeaveType Collection
     */
    public function getLeaveTypeList($operationalCountryId = null) {
        try {
            $q = Doctrine_Query::create()
                            ->from('LeaveType lt')
                            ->where('lt.availableFlag = 1')
                            ->orderBy('lt.leaveTypeName');
            
            if (!is_null($operationalCountryId)) {
                if (is_array($operationalCountryId)) {
                    $q->andWhereIn('lt.operationalCountryId', $operationalCountryId);
                } else {
                    $q->andWhere('lt.operationalCountryId = ? ', $operationalCountryId);
                }
            }
            
            $leaveTypeList = $q->execute();

            return $leaveTypeList;
        } catch (Exception $e) {
            $this->getLogger()->error("Exception in getLeaveTypeList:" . $e);
            throw new DaoException($e->getMessage());
        }
    }

    public function getDeletedLeaveTypeList($operationalCountryId = null) {
        try {
            $q = Doctrine_Query::create()
                            ->from('LeaveType lt')
                            ->where('lt.availableFlag = 0')
                            ->orderBy('lt.leaveTypeId');

            if (!is_null($operationalCountryId)) {
                $q->andWhere('lt.operationalCountryId = ? ', $operationalCountryId);
            }
            
            $leaveTypeList = $q->execute();

            return $leaveTypeList;
        } catch (Exception $e) {
            $this->getLogger()->error("Exception in getDeletedLeaveTypeList:" . $e);
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Read Leave Type
     * @return LeaveType
     */
    public function readLeaveType($leaveTypeId) {
        try {
            return Doctrine::getTable('LeaveType')->find($leaveTypeId);
        } catch (Exception $e) {
            $this->getLogger()->error("Exception in readLeaveType:" . $e);
            throw new DaoException($e->getMessage());
        }
    }

    public function readLeaveTypeByName($leaveTypeName) {
        try {
            $q = Doctrine_Query::create()
                            ->from('LeaveType lt')
                            ->where("lt.leaveTypeName = ?", $leaveTypeName)
                            ->andWhere('lt.availableFlag = 1');

            $leaveTypeCollection = $q->execute();

            return $leaveTypeCollection[0];
        } catch (Exception $e) {
            $this->getLogger()->error("Exception in readLeaveTypeByName:" . $e);
            throw new DaoException($e->getMessage());
        }
    }

    public function undeleteLeaveType($leaveTypeId) {

        try {

            $q = Doctrine_Query::create()
                            ->update('LeaveType lt')
                            ->set('lt.availableFlag', '1')
                            ->where("lt.leaveTypeId = '" . $leaveTypeId . "'");

            $numUpdated = $q->execute();

            if ($numUpdated > 0) {
                return true;
            }

            return false;
        } catch (Exception $e) {
            $this->getLogger()->error("Exception in undeleteLeaveType:" . $e);
            throw new DaoException($e->getMessage());
        }
    }

}