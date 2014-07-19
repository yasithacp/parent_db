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


require_once ROOT_PATH . '/lib/dao/DMLFunctions.php';
require_once ROOT_PATH . '/lib/dao/SQLQBuilder.php';
require_once ROOT_PATH . '/lib/confs/sysConf.php';
require_once ROOT_PATH . '/lib/common/LocaleUtil.php';
require_once ROOT_PATH . '/lib/common/CommonFunctions.php';
require_once ROOT_PATH . '/lib/models/eimadmin/export/CSVExportPlugin.php';
require_once ROOT_PATH . '/lib/models/eimadmin/export/CustomizableCSVExport.php';
require_once ROOT_PATH . '/lib/models/eimadmin/CustomExport.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmpDirectDebit.php';


/**
 * Class representing a CSV field used by CSV export
 */
class CSVField {

	const FIELD_TYPE_DIRECT = 1;
	const FIELD_TYPE_DATE = 2;
	const FIELD_TYPE_FROMMAP = 3;
	const FIELD_TYPE_DIRECT_DEBIT = 4;

	private $name;
	private $type;
	private $map;

	public function __construct($name, $type, $map = null) {
		$this->name = $name;
		$this->type = $type;

		if (($type == self::FIELD_TYPE_FROMMAP) && !is_array($map)) {
			throw new Exception("Map should be defined for FIELD_TYPE_FROMMAP");
		}
		$this->map = $map;
	}

	public function getValue($row, $ddList) {

		$value = "";

		switch ($this->type) {
			case self::FIELD_TYPE_DIRECT :
				if (isset($row[$this->name])) {
					$valueFromDb = $row[$this->name];
					$value = CSVField::escape($valueFromDb);
				}
				break;

			case self::FIELD_TYPE_DATE :
				if (isset($row[$this->name])) {
					$valueFromDb = $row[$this->name];
					$value = CSVField::escape(LocaleUtil::getInstance()->formatDate($valueFromDb));
				}
				break;

			case self::FIELD_TYPE_FROMMAP :
				if (isset($row[$this->name])) {
					$valueFromDb = $row[$this->name];
					$value = CSVField::escape(CSVField::getValueFromMap($this->map, $valueFromDb));
				}
				break;

			case self::FIELD_TYPE_DIRECT_DEBIT :
				$value = CSVField::escape($this->_getDDValue($row, $ddList));
				break;
		}
		return $value;

	}

	/**
	 * Get the direct deposit account related value based on
	 * passed parameters
	 *
	 * @param array $row Array of data from database
	 * @param array $ddList Array of direct deposit objects of this employee
	 *
	 * @return string Value for this field from database
	 */
	private function _getDDValue($row, $ddList) {
		$value = '';
		$parts = explode('_', $this->name);

		if (count($parts) == 2) {
			$ddNum = str_replace('DD', '', $parts[0]);
			$ddField = $parts[1];

			if (intval($ddNum) > 0) {

				if (count($ddList) >= $ddNum) {
					$directDeposit = $ddList[$ddNum - 1];

					switch ($ddField) {

						case CustomizableCSVExport::DD_ROUTING:
							$value = $directDeposit->getRoutingNumber();
							break;

						case CustomizableCSVExport::DD_ACCOUNT:
							$value = $directDeposit->getAccount();
							break;

						case CustomizableCSVExport::DD_AMOUNT:
							$value = $directDeposit->getAmount();
							break;

						case CustomizableCSVExport::DD_AMOUNTCODE:

							$transactionTypes = array (
								EmpDirectDebit::TRANSACTION_TYPE_BLANK => 'Blank',
								EmpDirectDebit::TRANSACTION_TYPE_PERCENTAGE => '%',
								EmpDirectDebit::TRANSACTION_TYPE_FLAT => 'Flat',
								EmpDirectDebit::TRANSACTION_TYPE_FLAT_MINUS => 'Flat-'
							);
							$value = CSVField::getValueFromMap($transactionTypes, $directDeposit->getTransactionType());
							break;

						case CustomizableCSVExport::DD_CHECKING:
							$accountTypes = array (
								EmpDirectDebit::ACCOUNT_TYPE_CHECKING => "Y",
								EmpDirectDebit::ACCOUNT_TYPE_SAVINGS => ""
							);

							$value = CSVField::getValueFromMap($accountTypes, $directDeposit->getAccountType());
							break;
						default:
							throw new Exception("Invalid direct deposit field");
					}
				}
			} else {
				throw new Exception("Invalid Direct deposit field number");
			}

		} else {
			throw Exception("Invalid Direct Deposit field definition");
		}

		return $value;
	}

	/**
	 * Escape the value properly for inclusion in CSV file
	 *
	 * @param $value Value to escape
	 * @return escaped value
	 */
	public static function escape($value) {

		$escapedValue = $value;
		$escaped = false;
		if (strpos($escapedValue, '"') !== false) {
			$escapedValue = str_replace('"', '""', $escapedValue);
			$escaped = true;
		}

		if ($escaped || (strpos($escapedValue, ",") !== false)) {
			$escapedValue = '"' . $escapedValue . '"';
		}

		return $escapedValue;
	}

	/**
	 * Get the value for the given key from the map (array)
	 *
	 * @param array $map Associative array
	 * @param string $key Key to look for
	 *
	 * @return string value for given key from array or an empty string if not found.
	 */
	public static function getValueFromMap($map, $key) {

		$value = "";
		if (array_key_exists($key, $map)) {
			$value = $map[$key];
		}
		return $value;
	}

}
?>
