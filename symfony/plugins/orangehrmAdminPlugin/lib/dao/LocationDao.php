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

class LocationDao extends BaseDao {

	/**
	 *
	 * @param type $locationId
	 * @return type 
	 */
	public function getLocationById($locationId) {

		try {
			return Doctrine :: getTable('Location')->find($locationId);
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	/**
	 *
	 * @param type $srchClues
	 * @return type 
	 */
	public function getSearchLocationListCount($srchClues) {

		try {
			$q = $this->_buildSearchQuery($srchClues);
			return $q->count();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	/**
	 *
	 * @param type $srchClues
	 * @return type 
	 */
	public function searchLocations($srchClues) {

                if (!isset($srchClues['sortField'])) {
                    $srchClues['sortField'] = 'name';
                }
                
                if (!isset($srchClues['sortOrder'])) {
                    $srchClues['sortOrder'] = 'ASC';
                }
                
                if (!isset($srchClues['offset'])) {
                    $srchClues['offset'] = 0;
                }
                
                if (!isset($srchClues['limit'])) {
                    $srchClues['limit'] = 50;
                }
                
		$sortField = ($srchClues['sortField'] == "") ? 'name' : $srchClues['sortField'];
		$sortOrder = ($srchClues['sortOrder'] == "") ? 'ASC' : $srchClues['sortOrder'];
		$offset = ($srchClues['offset'] == "") ? 0 : $srchClues['offset'];
		$limit = ($srchClues['limit'] == "") ? 50 : $srchClues['limit'];

		try {
			$q = $this->_buildSearchQuery($srchClues);
			$q->orderBy($sortField . ' ' . $sortOrder)
				->offset($offset)
				->limit($limit);                        
			return $q->execute();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	/**
	 *
	 * @param type $srchClues
	 * @return type 
	 */
	private function _buildSearchQuery($srchClues) {

		$q = Doctrine_Query::create()
			->from('Location');

		if (!empty($srchClues['name'])) {
			$q->addWhere('name LIKE ?', "%" . trim($srchClues['name']) . "%");
		}
		if (!empty($srchClues['city'])) {
			$q->addWhere('city LIKE ?', "%" . trim($srchClues['city']) . "%");
		}
		if (!empty($srchClues['country'])) {
                    if (is_array($srchClues['country'])) {
                        $q->andWhereIn('country_code', $srchClues['country']);
                    } else {
			$q->addWhere('country_code = ?', $srchClues['country']);
                    }
		}
		return $q;
	}

	/**
	 *
	 * @param type $locationId
	 * @return type 
	 */
	public function getNumberOfEmplyeesForLocation($locationId) {

		try {
			$q = Doctrine_Query :: create()
				->from('EmpLocations')
				->where('location_id = ?', $locationId);
			return $q->count();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	/**
	 *
	 * @return type 
	 */
	public function getLocationList() {
		
		try {
			$q = Doctrine_Query :: create()
				->from('Location l')
                                ->orderBy('l.name ASC');
			return $q->execute();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}
}

?>
