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
require_once ROOT_PATH . '/lib/common/UniqueIDGenerator.php';

class PayPeriod {

	const TABLE_NAME = "hs_hr_payperiod";
	const DB_FIELD_PAYPERIOD_CODE = "payperiod_code";
	const DB_FIELD_PAYPERIOD_NAME = "payperiod_name";

	private $code;
	private $name;

	public function __construct() {
	}

	public function setCode($code) {
		$this->code = $code;
	}

	public function getCode() {
		return $this->code;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	/**
	 * Get list of pay periods defined in the system
	 * @return array Array of all pay periods defined in the system
	 */
	public static function getPayPeriodList() {

		$fields[0] = self::DB_FIELD_PAYPERIOD_NAME;
		$fields[1] = self::DB_FIELD_PAYPERIOD_CODE;

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = self::TABLE_NAME;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $fields;

		$sql = $sql_builder->queryAllInformation();

		$dbConnection = new DMLFunctions();
		$result = $dbConnection->executeQuery($sql);

		$periods = array();

		if ($result && mysql_num_rows($result) > 0) {
			while($line = mysql_fetch_assoc($result)) {;
				$period = new PayPeriod();
				$period->setCode($line[self::DB_FIELD_PAYPERIOD_CODE]);
				$period->setName($line[self::DB_FIELD_PAYPERIOD_NAME]);
				$periods[$period->getCode()] = $period;
			}
		}

		return $periods;
	}
}

?>
