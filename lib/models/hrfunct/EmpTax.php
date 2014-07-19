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


require_once ROOT_PATH . '/lib/confs/Conf.php';
require_once ROOT_PATH . '/lib/dao/DMLFunctions.php';
require_once ROOT_PATH . '/lib/dao/SQLQBuilder.php';
require_once ROOT_PATH . '/lib/common/CommonFunctions.php';

class EmpTax {

	const EMPLOYEE_TABLE_NAME = 'hs_hr_employee';

	const EMP_TAX_TABLE_EMP_NUMBER = 'emp_number';
	const EMP_TAX_TABLE_NAME = 'hs_hr_emp_us_tax';
	const EMP_TAX_FEDERAL_STATUS = 'tax_federal_status';
	const EMP_TAX_FEDERAL_EXCEPTIONS = 'tax_federal_exceptions';
	const EMP_TAX_STATE = 'tax_state';
	const EMP_TAX_STATE_STATUS = 'tax_state_status';
	const EMP_TAX_STATE_EXCEPTIONS = 'tax_state_exceptions';
	const EMP_TAX_UNEMP_STATE = 'tax_unemp_state';
	const EMP_TAX_WORK_STATE = 'tax_work_state';

	const TAX_STATUS_MARRIED = "M";
	const TAX_STATUS_SINGLE = "S";
	const TAX_STATUS_NONRESIDENTALIEN = "NRA";
	const TAX_STATUS_NOTAPPLICABLE = "NA";

	private $empNumber;

	private $federalTaxStatus;
	private $federalTaxExceptions;
	private $taxState;
	private $stateTaxStatus;
	private $stateTaxExceptions;
	private $taxUnemploymentState;
	private $taxWorkState;

	public function setEmpNumber($empNumber) {
		$this->empNumber = $empNumber;
	}

	public function getEmpNumber() {
		return $this->empNumber;
	}

	public function setFederalTaxStatus($status) {
		$this->federalTaxStatus = $status;
	}

	public function setFederalTaxExceptions($taxExceptions) {
		$this->federalTaxExceptions = $taxExceptions;
	}

	public function setTaxState($taxState) {
		$this->taxState = $taxState;
	}

	public function setStateTaxStatus($status) {
		$this->stateTaxStatus = $status;
	}

	public function setStateTaxExceptions($exceptions) {
		$this->stateTaxExceptions = $exceptions;
	}

	public function setTaxUnemploymentState($state) {
		$this->taxUnemploymentState = $state;
	}

	public function setTaxWorkState($state) {
		$this->taxWorkState = $state;
	}

	public function getFederalTaxStatus() {
		return $this->federalTaxStatus;
	}

	public function getFederalTaxExceptions() {
		return $this->federalTaxExceptions;
	}

	public function getTaxState() {
		return $this->taxState;
	}

	public function getStateTaxStatus() {
		return $this->stateTaxStatus;
	}

	public function getStateTaxExceptions() {
		return $this->stateTaxExceptions;
	}

	public function getTaxUnemploymentState() {
		return $this->taxUnemploymentState;
	}

	public function getTaxWorkState() {
		return $this->taxWorkState;
	}


	/**
	 * Gets the tax information for the given employee
	 *
	 * @param string $empNumber The employee number
	 * @return array array containing employee tax information
	 */
	function getEmployeeTaxInfo($empNumber) {

		$this->empNumber = $empNumber;

		$arrFieldList[0] = self::EMP_TAX_TABLE_EMP_NUMBER;
		$arrFieldList[1] = self::EMP_TAX_FEDERAL_STATUS;
		$arrFieldList[2] = self::EMP_TAX_FEDERAL_EXCEPTIONS;
		$arrFieldList[3] = self::EMP_TAX_STATE;
		$arrFieldList[4] = self::EMP_TAX_STATE_STATUS;
		$arrFieldList[5] = self::EMP_TAX_STATE_EXCEPTIONS;
		$arrFieldList[6] = self::EMP_TAX_UNEMP_STATE;
		$arrFieldList[7] = self::EMP_TAX_WORK_STATE;

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = self :: EMP_TAX_TABLE_NAME;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->selectOneRecordFiltered($this->empNumber);

		$dbConnection = new DMLFunctions();
		$results = $dbConnection->executeQuery($sqlQString);

		if (mysql_num_rows($results) === 1) {
			$taxInfo = mysql_fetch_array($results, MYSQL_ASSOC);
		} else {
			$taxInfo = array(self::EMP_TAX_TABLE_EMP_NUMBER=>$empNumber, self::EMP_TAX_FEDERAL_STATUS => null,
					self::EMP_TAX_FEDERAL_EXCEPTIONS => null, self::EMP_TAX_STATE => null,
					self::EMP_TAX_STATE_STATUS => null, self::EMP_TAX_STATE_EXCEPTIONS => null,
					self::EMP_TAX_UNEMP_STATE => null, self::EMP_TAX_WORK_STATE => null);
		}

		return $taxInfo;
	}

	/**
	 * Inserts or updates tax information to the employee tax table.
	 * Note that the tax information is kept separate from the employee table
	 * since it can be country specific.
	 */
	function updateEmpTax() {

		$arrRecordsList[0] = "'" . $this->getEmpNumber() . "'";
		$arrRecordsList[1] = "'" . $this->getFederalTaxStatus() . "'";
		$arrRecordsList[2] = (trim($this->getFederalTaxExceptions()) != '') ? "'" . $this->getFederalTaxExceptions() . "'" : "'0'";
		$arrRecordsList[3] = "'" . $this->getTaxState() . "'";
		$arrRecordsList[4] = "'" . $this->getStateTaxStatus() . "'";
		$arrRecordsList[5] = (trim($this->getStateTaxExceptions()) != '') ? "'" . $this->getStateTaxExceptions() . "'" : "'0'";
		$arrRecordsList[6] = "'" . $this->getTaxUnemploymentState() . "'";
		$arrRecordsList[7] = "'" . $this->getTaxWorkState() . "'";

		$arrFieldList[0] = self :: EMP_TAX_TABLE_EMP_NUMBER;
		$arrFieldList[1] = self :: EMP_TAX_FEDERAL_STATUS;
		$arrFieldList[2] = self :: EMP_TAX_FEDERAL_EXCEPTIONS;
		$arrFieldList[3] = self :: EMP_TAX_STATE;
		$arrFieldList[4] = self :: EMP_TAX_STATE_STATUS;
		$arrFieldList[5] = self :: EMP_TAX_STATE_EXCEPTIONS;
		$arrFieldList[6] = self :: EMP_TAX_UNEMP_STATE;
		$arrFieldList[7] = self :: EMP_TAX_WORK_STATE;

		$sql_builder = new SQLQBuilder();
		$sql_builder->table_name = self :: EMP_TAX_TABLE_NAME;
		$sql_builder->flg_insert = 'true';
		$sql_builder->arr_insert = $arrRecordsList;
		$sql_builder->arr_insertfield = $arrFieldList;

		$sqlQString = $sql_builder->addNewRecordFeature2(true, true);
		$dbConnection = new DMLFunctions();
		$result = $dbConnection->executeQuery($sqlQString);

		return $result;
	}

}
?>
