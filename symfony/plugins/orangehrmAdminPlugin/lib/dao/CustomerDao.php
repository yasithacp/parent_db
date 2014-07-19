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

class CustomerDao extends BaseDao {

	/**
	 *
	 * @param type $limit
	 * @param type $offset
	 * @param type $sortField
	 * @param type $sortOrder
	 * @param type $activeOnly
	 * @return type 
	 */
	public function getCustomerList($limit=50, $offset=0, $sortField='name', $sortOrder='ASC', $activeOnly = true) {

		$sortField = ($sortField == "") ? 'name' : $sortField;
		$sortOrder = ($sortOrder == "") ? 'ASC' : $sortOrder;

		try {
			$q = Doctrine_Query :: create()
				->from('Customer');
			if ($activeOnly == true) {
				$q->addWhere('is_deleted = 0');
			}
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
	 * @param type $activeOnly
	 * @return type 
	 */
	public function getCustomerCount($activeOnly = true) {

		try {
			$q = Doctrine_Query :: create()
				->from('Customer');
			if ($activeOnly == true) {
				$q->addWhere('is_deleted = ?', 0);
			}
			$count = $q->execute()->count();
			return $count;
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	/**
	 *
	 * @param type $customerId
	 * @return type 
	 */
	public function getCustomerById($customerId) {

		try {
			return Doctrine :: getTable('Customer')->find($customerId);
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	/**
	 *
	 * @param type $customerId 
	 */
	public function deleteCustomer($customerId) {

		try {
			$customer = Doctrine :: getTable('Customer')->find($customerId);
			$customer->setIsDeleted(Customer::DELETED);
			$customer->save();
			$this->_deleteRelativeProjectsForCustomer($customerId);
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	private function _deleteRelativeProjectsForCustomer($customerId) {

		try {
			$q = Doctrine_Query :: create()
				->from('Project')
				->where('is_deleted = ?', Project::ACTIVE_PROJECT)
				->andWhere('customer_id = ?', $customerId);
			$projects = $q->execute();

			foreach ($projects as $project) {
				$project->setIsDeleted(Project::DELETED_PROJECT);
				$project->save();
				$this->_deleteRelativeProjectActivitiesForProject($project->getProjectId());
				$this->_deleteRelativeProjectAdminsForProject($project->getProjectId());
			}
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	private function _deleteRelativeProjectActivitiesForProject($projectId) {

		try {
			$q = Doctrine_Query :: create()
				->from('ProjectActivity')
				->where('is_deleted = ?', ProjectActivity::ACTIVE_PROJECT_ACTIVITY)
				->andWhere('project_id = ?', $projectId);
			$projectActivities = $q->execute();

			foreach ($projectActivities as $projectActivity) {
				$projectActivity->setIsDeleted(ProjectActivity::DELETED_PROJECT_ACTIVITY);
				$projectActivity->save();
			}
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	private function _deleteRelativeProjectAdminsForProject($projectId) {

		try {
			$q = Doctrine_Query :: create()
				->delete('ProjectAdmin pa')
				->where('pa.project_id = ?', $projectId);
			$q->execute();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	/**
	 *
	 * @param type $activeOnly
	 * @return type 
	 */
	public function getAllCustomers($activeOnly = true) {

		try {
			$q = Doctrine_Query :: create()
				->from('Customer');
			if ($activeOnly == true) {
				$q->where('is_deleted =?', 0);
			}
			return $q->execute();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	/**
	 *
	 * @param type $customerId
	 * @return type 
	 */
	public function hasCustomerGotTimesheetItems($customerId) {

		try {
			$q = Doctrine_Query :: create()
				->select("COUNT(*)")
				->from('TimesheetItem ti')
				->leftJoin('ti.Project p')
				->leftJoin('p.Customer c')
				->where('c.customerId = ?', $customerId);
			$count = $q->fetchOne(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
			return ($count > 0);
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

}

?>
