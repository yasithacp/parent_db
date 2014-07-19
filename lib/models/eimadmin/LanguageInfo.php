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

class LanguageInfo {

	var $tableName = 'HS_HR_LANGUAGE';
	var $languageId;
	var $languageDesc;
	var $arrayDispList;
	var $singleField;


	function LanguageInfo() {
	}

	function setLanguageInfoId($languageId) {
		$this->languageId = $languageId;
	}

	function setLanguageInfoDesc($languageDesc) {
		$this->languageDesc = $languageDesc;
	}

	function getLanguageInfoId() {
		return $this->languageId;
	}

	function getLanguageInfoDesc() {
		return $this->languageDesc;
	}

	function getListofLanguageInfo($pageNO,$schStr,$mode, $sortField = 0, $sortOrder = 'ASC') {

		$tableName = 'HS_HR_LANGUAGE';
		$arrFieldList[0] = 'LANG_CODE';
		$arrFieldList[1] = 'LANG_NAME';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->passResultSetMessage($pageNO,$schStr,$mode, $sortField, $sortOrder);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {
	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];
	    	$i++;
	     }

	    if (isset($arrayDispList)) {
	     	return $arrayDispList;
		} else {
			$arrayDispList = '';
			return $arrayDispList;
		}
	}

	function countLanguageInfo($schStr,$mode) {

		$tableName = 'HS_HR_LANGUAGE';
		$arrFieldList[0] = 'LANG_CODE';
		$arrFieldList[1] = 'LANG_NAME';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->countResultset($schStr,$mode);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$line = mysql_fetch_array($message2, MYSQL_NUM);

	    return $line[0];
	}

	function delLanguageInfo($arrList) {

    	$tableName = 'HS_HR_LANGUAGE';
		$arrFieldList[0] = 'LANG_CODE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_delete = 'true';
		$sql_builder->arr_delete = $arrFieldList;

		$sqlQString = $sql_builder->deleteRecord($arrList);

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;
	}


	function addLanguageInfo() {

		if ($this->_isDuplicateName()) {
			throw new LanguageInfoException("Duplicate name", 1);
		}

		$tableName = 'hs_hr_language';

		$this->languageId = UniqueIDGenerator::getInstance()->getNextID($tableName, 'lang_code', 'LAN');
		$arrFieldList[0] = "'". $this->getLanguageInfoId() . "'";
		$arrFieldList[1] = "'". $this->getLanguageInfoDesc() . "'";

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_insert = 'true';
		$sql_builder->arr_insert = $arrFieldList;

		$sqlQString = $sql_builder->addNewRecordFeature1();

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;
	}

	function updateLanguageInfo() {

		if ($this->_isDuplicateName(true)) {
			throw new LanguageInfoException("Duplicate name", 1);
		}

		$this->getLanguageInfoId();
		$arrRecordsList[0] = "'". $this->getLanguageInfoId() . "'";
		$arrRecordsList[1] = "'". $this->getLanguageInfoDesc() . "'";

		$arrFieldList[0] = 'LANG_CODE';
		$arrFieldList[1] = 'LANG_NAME';


		$tableName = 'HS_HR_LANGUAGE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_update = 'true';
		$sql_builder->arr_update = $arrFieldList;
		$sql_builder->arr_updateRecList = $arrRecordsList;

		$sqlQString = $sql_builder->addUpdateRecord1();

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;
	}


	function filterLanguageInfo($getID) {

		$this->getID = $getID;
		$tableName = 'HS_HR_LANGUAGE';
		$arrFieldList[0] = 'LANG_CODE';
		$arrFieldList[1] = 'LANG_NAME';


		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->selectOneRecordFiltered($this->getID);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {
	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];

	    	$i++;
	     }

	     if (isset($arrayDispList)) {
			return $arrayDispList;
		} else {
			$arrayDispList = '';
			return $arrayDispList;

		}

	}

	function getLang() {
		$tableName = 'HS_HR_LANGUAGE';
		$arrFieldList[0] = 'LANG_CODE';
		$arrFieldList[1] = 'LANG_NAME';


		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->passResultSetMessage(0, '', -1, 1);

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection->executeQuery($sqlQString); //Calling the addData() function

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {
	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];

	    	$i++;
	     }

	     if (isset($arrayDispList)) {
	     	return $arrayDispList;
		} else {
			$arrayDispList = '';
			return $arrayDispList;
		}
	}

	function getUnAssLangCodes($eno) {

		$sql_builder = new SQLQBuilder();
		$tableName = 'HS_HR_LANGUAGE';
		$arrFieldList[0] = 'LANG_CODE';
		$arrFieldList[1] = 'LANG_NAME';


		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;
		$sql_builder->field='MEMBSHIP_CODE';
		$sql_builder->table2_name= 'HS_HR_EMP_MEMBER_DETAIL';
		$arr1[0][0]='EMP_NUMBER';
		$arr1[0][1]=$eno;

		$sqlQString = $sql_builder->selectFilter($arr1);

		$dbConnection = new DMLFunctions();
       		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$common_func = new CommonFunctions();

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {
	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];


	    	$i++;
	     }

	     if (isset($arrayDispList)) {
	       	return $arrayDispList;
	     }
	}

	private function _isDuplicateName($update=false) {
		$languages = $this->getListofLanguageInfo(0, $this->getLanguageInfoDesc(), 1);

		if (is_array($languages)) {
			if ($update) {
				if ($languages[0][0] == $this->getLanguageInfoId()) {
					return false;
				}
			}
			return true;
		}

		return false;
	}
}

class LanguageInfoException extends Exception {
}
?>
