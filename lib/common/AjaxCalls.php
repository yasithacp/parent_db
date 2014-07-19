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

class AjaxCalls {

	const COMPARE_LEFT = 1;
	const COMPARE_RIGHT = 2;
	const COMPARE_MID = 3;

	const NON_XML_DEFAULT_MODE_DELIMITER = ',';
	const NON_XML_MULTI_LEVEL_MODE_DELIMITER = ':';
	const NON_XML_MULTI_LEVEL_MODE_LEFT_ENCASEMENT = '[';
	const NON_XML_MULTI_LEVEL_MODE_RIGHT_ENCASEMENT = ']';

	const NON_XML_DEFAULT_MODE = 1;
	const NON_XML_MULTI_LEVEL_MODE  = 2;

	private static $levels = 1;

	public static function sendResponse($values, $responseXML = true, $nonXMLMode = self::NON_XML_DEFAULT_MODE) {

		if ($responseXML) {
			$response = self::_fetchXMLResponse($values);
		} else {
			$response = self::_fetchNonXMLResponse($values, $nonXMLMode);
		}

		echo $response;
	}

	public static function fetchOptions($table, $valueField, $labelField, $descField, $filterKey, $joinTable = null, $joinCondition = null, $compareMethod = self::COMPARE_LEFT, $caseSensitive = false) {
		$selecteFields[] = $valueField;
		$selecteFields[] = $labelField;
		$selecteFields[] = $descField;
		
		$selectTables[] = $table;
		$selectTables[] = $joinTable; 
		
		$joinConditions[1] = $joinCondition;
		
		if (!$caseSensitive) {
				$labelField = "LOWER($labelField)";
				$filterKey = strtolower($filterKey);
		}

		switch ($compareMethod) {
			case self::COMPARE_LEFT :
				$selectConditions[] = "$labelField LIKE '$filterKey%'";
				break;
			
			case self::COMPARE_RIGHT :
				$selectConditions[] = "$labelField LIKE '%$filterKey'";
				break;
				
			case self::COMPARE_MID :
				$selectConditions[] = "$labelField LIKE '%$filterKey%'";
				break;
		}
		
		$orderCondition = $labelField;
		
		$sqlBuilder = new SQLQBuilder();
		$query = $sqlBuilder->selectFromMultipleTable($selecteFields, $selectTables, $joinConditions, $selectConditions, null, $orderCondition);

		$query = self::_formatQuery($query);

		$dbConnection = new DMLFunctions();
		$result = $dbConnection->executeQuery($query);
		
		if (mysql_error()) { echo mysql_error() + "\n" + $query; die;}
		
		$result = $dbConnection->executeQuery($query);

		while($row = mysql_fetch_array($result, MYSQL_NUM)) {
			$value = trim($row[0]);
			$label = trim($row[1]);
			$description = ($row[2] == '') ? '&nbsp;' : trim($row[2]);
			echo "$value,$label,$description\n";
		}
	}
	
	private static function _formatQuery($query) {
		$query = preg_replace("/\\\'/", "'", $query);
		
		return $query;
	}

	private static function _fetchXMLResponse($values) {

	}

	private static function _fetchNonXMLResponse($values, $mode) {

		switch ($mode) {
			case self::NON_XML_DEFAULT_MODE :
				$response = implode(self::NON_XML_DEFAULT_MODE_DELIMITER, $values);
				break;

			case self::NON_XML_MULTI_LEVEL_MODE :

				$response = self::_getMultiLevelResponseString($values);
				break;
		}

		return $response;

	}

	private static function _getMultiLevelResponseString($arrayElements) {

		static $level = 1;

		$str = '';
		$delimiter = self::getMultiLevelDelimiter($level);

		foreach ($arrayElements as $element) {
			
			if (is_array($element)) {
				$level++;
				$str .= self::_getMultiLevelResponseString($element);
				$level--;
			} else {
				$str .= $element;
			}

			$str .= $delimiter; 

		}

		$str = substr($str, 0, strlen($str) - strlen($delimiter));

		return $str;

	}

	public static function getMultiLevelDelimiter($level) {

		$str = self::NON_XML_MULTI_LEVEL_MODE_LEFT_ENCASEMENT;
		$str .= str_repeat(self::NON_XML_MULTI_LEVEL_MODE_DELIMITER, $level);
		$str .= self::NON_XML_MULTI_LEVEL_MODE_RIGHT_ENCASEMENT;

		return $str;

	}

	public static function getDelimiterLevelsArray($level) {

		$arrLevels = array();

		for($i = 0; $i < $level; $i++) {
			$arrLevels[$i] = self::getMultiLevelDelimiter($i + 1);
		}

		return $arrLevels;

	}
}
?>
