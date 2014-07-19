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
require_once ROOT_PATH . '/lib/models/hrfunct/EmpInfo.php';

class GenInfo {

	var $genInfoKeys;
	var $genInfoValues;

	function GenInfo() {

	}

	function setGenInfoKeys($genInfoKeys) {
		$this->genInfoKeys = $genInfoKeys;
	}

	function setGenInfoValues($genInfoValues) {
		$this->genInfoValues = $genInfoValues;
	}

	function getGenInfoKeys() {
		return $this->genInfoKeys;
	}

	function getGenInfoValues() {
		return $this->genInfoValues;
	}

	function updateGenInfo() {

		$tableName = 'HS_HR_GENINFO';

		$arrRecordsList[0] = "'001'";
		$arrRecordsList[1] = "'". $this->getGenInfoKeys() . "'";
		$arrRecordsList[2] = "'". $this->getGenInfoValues() . "'";

		$arrFieldList[0] = 'CODE';
		$arrFieldList[1] = 'GENINFO_KEYS';
		$arrFieldList[2] = 'GENINFO_VALUES';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_update = 'true';
		$sql_builder->arr_update = $arrFieldList;
		$sql_builder->arr_updateRecList = $arrRecordsList;

		$sqlQString = $sql_builder->addUpdateRecord1();
		$compStruct_newTitle = explode("|",$arrRecordsList[2]);
		$compStruct_newTitle = substr($compStruct_newTitle[0], 1);

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$compStructObj = new CompStruct();

		$compStructObj->setaddStr($compStruct_newTitle);
		$compStructObj->setid(1);
		$compStructObj->setlocation('');

		$compStructObj->updateCompStruct();

		return $message2;
	}

	function filterGenInfo() {

		$tableName = 'HS_HR_GENINFO';
		$arrFieldList[0] = 'CODE';
		$arrFieldList[1] = 'GENINFO_KEYS';
		$arrFieldList[2] = 'GENINFO_VALUES';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->selectOneRecordFiltered('001');



		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {

	    	$arrayDispList[$i][0] = $line[1];
	    	$arrayDispList[$i][1] = $line[2];
	    	$i++;

	     }

	     if (isset($arrayDispList)) {

			return $arrayDispList;

		} else {

			$arrayDispList = '';
			return $arrayDispList;

		}
	}

	/**
	 * Get value for given key from general info
	 * @param $key Key
	 * @return Value for the given key
	 */
	public function getValue($key) {
		$tabArr = $this->filterGenInfo();

		$genInfoKeys = explode('|',$tabArr[0][0]);
		$genInfoValues = explode('|',$tabArr[0][1]);

		/* Look for the key */
		$index = array_search($key, $genInfoKeys);

		if (($index !== false) && isset($genInfoValues[$index])) {
			$value = $genInfoValues[$index];
		} else {
			$value = null;
		}

		return $value;
	}
}
?>
