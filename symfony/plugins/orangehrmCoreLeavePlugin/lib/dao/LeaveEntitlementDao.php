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

class LeaveEntitlementDao extends BaseDao{
	
	/**
	 * Get Employee Leave Entitlement
	 * @param $empId
	 * @param $leaveTypeId
	 * @return EmployeeLeaveEntitlement
	 */
	public function getEmployeeLeaveEntitlement( $empId, $leaveTypeId,$leavePeriodId){
		try {
				$q = Doctrine_Query::create()
				    ->from('EmployeeLeaveEntitlement ele')
                    ->where('ele.employee_id = ?',$empId)
                    ->andWhere('ele.leave_type_id = ?',$leaveTypeId)
                    ->andWhere('ele.leave_period_id = ?',$leavePeriodId);

			   $employeeLeaveEntitlement = $q->fetchOne();
			   
			   return $employeeLeaveEntitlement;
			   
		} catch( Exception $e) {
            throw new DaoException( $e->getMessage());
        }
	}
	
	/**
	 * 
	 * @param int $employeeId
	 * @param string $leaveTypeId
	 * @param int $leavePeriodId
	 * @param int $adjustment Number of days to adjust as integer (eg: 1, 5, -1, -7, etc) 
	 * @return boolean Returns true if the operation is successfult
	 */
	public function adjustEmployeeLeaveEntitlement($employeeId, $leaveTypeId, $leavePeriodId, $adjustment) {
		$q = Doctrine_Query::create()
			->update('EmployeeLeaveEntitlement le')
			->set('le.leave_taken', "(le.leave_taken + ({$adjustment}))")
			->where('le.leave_type_id = ?', $leaveTypeId)
			->andWhere('le.leave_period_id = ?', $leavePeriodId)
			->andWhere('le.employee_id = ?', $employeeId);
		return $q->execute();
	}
	
	/**
	 * 
	 * @param int $employeeId
	 * @param string $leaveTypeId
	 * @param int $leavePeriodId
	 * @param int $adjustment Number of days to adjust as integer (eg: 1, 5, -1, -7, etc) 
	 * @return boolean Returns true if the operation is successfult
	 */
	public function overwriteEmployeeLeaveEntitlement($employeeId, $leaveTypeId, $leavePeriodId, $adjustment) {
		try {

            if ($this->_isValidAdjustment($adjustment)) {

                $q = Doctrine_Query::create()
                    ->update('EmployeeLeaveEntitlement le')
                    ->set('le.no_of_days_allotted', "({$adjustment})")
                    ->where('le.leave_type_id = ?', $leaveTypeId)
                    ->andWhere('le.leave_period_id = ?', $leavePeriodId)
                    ->andWhere('le.employee_id = ?', $employeeId);
                $q->execute();

            }
			
			return true ;
		} catch( Exception $e) {
            throw new DaoException( $e->getMessage());
        }
	}

    private function _isValidAdjustment($adjustment) {

        if (!is_numeric($adjustment)) {
            return false;
        } elseif ($adjustment > 365) {
            return false;
        }

        return true;

    }
	
	/**
	 * 
	 * @param int $employeeId
	 * @param string $leaveTypeId
	 * @param int $leavePeriodId
	 * @param int $adjustment Number of days to adjust as integer (eg: 1, 5, -1, -7, etc) 
	 * @return boolean Returns true if the operation is successfult
	 */
	public function updateEmployeeLeaveEntitlement($employeeId, $leaveTypeId, $leavePeriodId, $adjustment) {
		try {
			$q = Doctrine_Query::create()
				->update('EmployeeLeaveEntitlement le')
				->set('le.no_of_days_allotted',"(le.no_of_days_allotted + ({$adjustment}))")
				->where('le.leave_type_id = ?', $leaveTypeId)
				->andWhere('le.leave_period_id = ?', $leavePeriodId)
				->andWhere('le.employee_id = ?', $employeeId);
			$q->execute();
			
			return true ;
		} catch( Exception $e) {
            throw new DaoException( $e->getMessage());
        }
	}
	
	/**
	 * 
	 * @param int $employeeId
	 * @param String $leaveTypeId
	 * @param int $leavePeriodId
	 * @param int $entitlment
	 * @return boolean Returns true if the operation is successfuly
	 */
	public function saveEmployeeLeaveEntitlement( $employeeId, $leaveTypeId, $leavePeriodId, $entitlment){
		try {
			$employeeLeaveEntitlement	=	new EmployeeLeaveEntitlement();
			$employeeLeaveEntitlement->setLeaveTypeId($leaveTypeId);
			$employeeLeaveEntitlement->setEmployeeId($employeeId);
			$employeeLeaveEntitlement->setLeavePeriodId($leavePeriodId);
			$employeeLeaveEntitlement->setNoOfDaysAllotted($entitlment);
			
			$employeeLeaveEntitlement->save();
			  
			return true ;
		} catch( Exception $e) {
            throw new DaoException( $e->getMessage());
        }
	}
	
	/**
	 * Read Leave Entitlment
	 * @param int $employeeId
	 * @param String $leaveTypeId
	 * @param int $leavePeriodId
	 * @return boolean Returns true if the operation is successfuly
	 */
	public function readEmployeeLeaveEntitlement( $employeeId, $leaveTypeId, $leavePeriodId){
		try {
			
			$q = Doctrine_Query::create()
			->from('EmployeeLeaveEntitlement ele')
			->where("ele.leave_type_id = '$leaveTypeId'")
			->andWhere("ele.employee_id = '$employeeId'")
			->andWhere("ele.leave_period_id = '$leavePeriodId'");
			
			$record = $q->fetchOne();
			return $record;
			   
		} catch( Exception $e) {
            throw new DaoException( $e->getMessage());
        }
	}
        
           /**
	 * Save employee leave carried forward for given period
	 * @param int $employeeId
         * @param int $leaveTypeId
         * @param int $leavePeriodId
         * @param float $carriedForwardLeaveLength
	 * @return boolean
	 */
	public function saveEmployeeLeaveCarriedForward( $employeeId, $leaveTypeId, $leavePeriodId, $carriedForwardLeaveLength){
            try {

                $leaveEntitlement = $this->readEmployeeLeaveEntitlement($employeeId, $leaveTypeId, $leavePeriodId);
                if(!$leaveEntitlement instanceof EmployeeLeaveEntitlement){
                    $leaveEntitlement = new EmployeeLeaveEntitlement();
                    $leaveEntitlement->setLeaveTypeId($leaveTypeId);
                    $leaveEntitlement->setLeavePeriodId($leavePeriodId);
                    $leaveEntitlement->setEmployeeId($employeeId);

                }
                $leaveEntitlement->setLeaveCarriedForward($carriedForwardLeaveLength);
                $leaveEntitlement->save();
                return true ;

            } catch( Exception $e) {
                throw new DaoException( $e->getMessage());
            }
	}

        /**
	 * Save employee leave brought forward for given period
	 * @param int $employeeId
         * @param int $leaveTypeId
         * @param int $leavePeriodId
         * @param float $broughtForwardLeaveLength
	 * @return boolean
	 */
	public function saveEmployeeLeaveBroughtForward( $employeeId, $leaveTypeId, $leavePeriodId, $broughtForwardLeaveLength){
            try {

                $leaveEntitlement = $this->readEmployeeLeaveEntitlement($employeeId, $leaveTypeId, $leavePeriodId);
                
                if(!$leaveEntitlement instanceof EmployeeLeaveEntitlement){
                   $leaveEntitlement = new EmployeeLeaveEntitlement();
                   $leaveEntitlement->setLeaveTypeId($leaveTypeId);
                   $leaveEntitlement->setLeavePeriodId($leavePeriodId);
                   $leaveEntitlement->setEmployeeId($employeeId);
                   $leaveEntitlement->setNoOfDaysAllotted(0);
                }
                
                $leaveEntitlement->setLeaveBroughtForward($broughtForwardLeaveLength);
                $leaveEntitlement->save();
                
                return true ;

            } catch( Exception $e) {
                throw new DaoException( $e->getMessage());
            }
	}

}