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

class EmailNotificationDao extends BaseDao {

	public function getEmailNotificationList() {
		try {
			$q = Doctrine_Query :: create()
				->from('EmailNotification');
			return $q->execute();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	public function updateEmailNotification($toBeEnabledIds) {
		try {
			$this->disableEmailNotification($toBeEnabledIds);				
			if (!empty($toBeEnabledIds)) {
				$this->enableEmailNotification($toBeEnabledIds);
			}
			return true;
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	private function disableEmailNotification($toBeEnabledIds) {
		try {
			$q = Doctrine_Query :: create()->update('EmailNotification')
				->set('isEnable', '?', EmailNotification::DISABLED);
			if (!empty($toBeEnabledIds)) {
				$q->whereNotIn('id', $toBeEnabledIds);
			}
			return $q->execute();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	private function enableEmailNotification($toBeEnabledIds) {
		try {
			$q = Doctrine_Query :: create()->update('EmailNotification')
				->set('isEnable', '?', EmailNotification::ENABLED)
				->whereIn('id', $toBeEnabledIds);
			return $q->execute();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	public function getEnabledEmailNotificationIdList() {
		try {
			$q = Doctrine_Query :: create()->select('id')
				->from('EmailNotification')
				->where('isEnable = ?', EmailNotification::ENABLED);
			return $q->execute();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	public function getSubscribersByNotificationId($emailNotificationId) {
		try {
			$q = Doctrine_Query :: create()
				->from('EmailSubscriber')
				->where('notificationId = ?', $emailNotificationId)
				->orderBy('name ASC');
			return $q->execute();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	public function getSubscriberById($subscriberId) {

		try {
			return Doctrine :: getTable('EmailSubscriber')->find($subscriberId);
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	public function deleteSubscribers($subscriberIdList) {
		try {
			$q = Doctrine_Query::create()
				->delete('EmailSubscriber')
				->whereIn('id', $subscriberIdList);

			return $q->execute();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

}

