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

class LeavePeriodDao extends BaseDao{

	/**
	 * Saves the leave period
	 *
	 * @param LeavePeriod $leavePeriod
	 * @return boolean
	 */
	public function saveLeavePeriod (LeavePeriod $leavePeriod) {
		try {
			if ($leavePeriod->getLeavePeriodId() == '') {

				$idGenService = new IDGeneratorService();
				$idGenService->setEntity($leavePeriod);
				$leavePeriod->setLeavePeriodId($idGenService->getNextID());
			}

			$leavePeriod->save();

			return true ;

		} catch( Exception $e) {
			throw new DaoException( $e->getMessage());
		}
	}

	/**
	 * Returns an instance of LeavePeriod to which the passed timestamp belogs to
	 *
	 * @param int $timestamp
	 * @return LeavePeriod Object of LeavePeriod to which the passed timestamp belogs to
	 */
	public function filterByTimestamp($timestamp) {
		$date = date('Y-m-d', $timestamp);
		$q = Doctrine_Query::create()
		->select("*")
		->from("LeavePeriod lp")
		->where("lp.leave_period_start_date <= ?", $date)
		->andWhere("lp.leave_period_end_date >= ?", $date);

		$result = $q->fetchOne();
      if(!$result instanceof LeavePeriod) {
         return null;
      }
      return $result;
	}

	public function findLastLeavePeriod($date = null) {
		$date = empty($date) ? date('Y-m-d', time()) : $date;
		$q = Doctrine_Query::create()
		->select("*")
		->from("LeavePeriod lp")
		->where("lp.leave_period_end_date < ?", $date);

		
		$result = $q->execute();
		
		if ($result->count() > 0) {
			return $result->end();
		} else {
			return null;
		}
	}




	/**
	 * Get Leave Period list
	 * @return LeavePeriod Collection
	 */
	public function getLeavePeriodList() {

		try {

            $q = Doctrine_Query::create()
            ->from('LeavePeriod lp');

            return $q->execute();

        } catch( Exception $e) {
            throw new DaoException( $e->getMessage());
        }

	}

    public function readLeavePeriod($leavePeriodId) {

        try {
         return Doctrine::getTable('LeavePeriod')->find($leavePeriodId);
        } catch(Exception $e) {
         throw new DaoException($e->getMessage());
        }
        
    }



}
