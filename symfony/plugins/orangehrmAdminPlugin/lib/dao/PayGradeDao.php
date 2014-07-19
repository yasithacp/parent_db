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

class PayGradeDao extends BaseDao {

	/**
	 *
	 * @param type $payGradeId
	 * @return type 
	 */
	public function getPayGradeById($payGradeId) {

		try {
			return Doctrine :: getTable('PayGrade')->find($payGradeId);
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	/**
	 *
	 * @return type 
	 */
	public function getPayGradeList($sortField='name', $sortOrder='ASC') {

		$sortField = ($sortField == "") ? 'name' : $sortField;
		$sortOrder = ($sortOrder == "") ? 'ASC' : $sortOrder;

		try {
			$q = Doctrine_Query :: create()
				->from('PayGrade')
				->orderBy($sortField . ' ' . $sortOrder);
			return $q->execute();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	public function getCurrencyListByPayGradeId($payGradeId) {

		try {
			$q = Doctrine_Query :: create()
				->from('PayGradeCurrency pgc')
                                ->leftJoin('pgc.CurrencyType ct')
				->where('pgc.pay_grade_id = ?', $payGradeId)
                                ->orderBy('ct.currency_name ASC');
			return $q->execute();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

	public function getCurrencyByCurrencyIdAndPayGradeId($currencyId, $payGradeId) {

		try {
			$q = Doctrine_Query :: create()
				->from('PayGradeCurrency')
				->where('pay_grade_id = ?', $payGradeId)
				->andWhere('currency_id = ?', $currencyId);
			return $q->fetchOne();
		} catch (Exception $e) {
			throw new DaoException($e->getMessage());
		}
	}

}

?>
