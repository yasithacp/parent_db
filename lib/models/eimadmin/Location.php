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

class models_eimadmin_Location {

	var $tableName = 'HS_HR_LOCATION';

	var $locationId;
	var $locationDescription;
	var $locationCountry;
	var $locationState;
	var $locationCity;
	var $locationAddress;
	var $locationZIP;
	var $locationPhone;
	var $locationFax;
	var $locationComments;

	var $arrayDispList;
	var $singleField;


	function setLocationId($locationID) {

		$this->locationId = $locationID;
	}

	function setLocationName($locationName) {

		$this->locationName = $locationName;
	}

	function setLocationCountry($locationCountry) {
		$this->locationCountry = $locationCountry;
	}

	function setLocationState($locationState) {
		$this->locationState = $locationState;
	}

	function setLocationCity($locationCity) {
		$this->locationCity = $locationCity;
	}

	function setLocationAddress($locationAddress) {
		$this->locationAddress = $locationAddress;
	}

	function setLocationZIP($locationZIP) {
		$this->locationZIP = $locationZIP;
	}

	function setLocationPhone($locationPhone) {
		$this->locationPhone = $locationPhone;
	}

	function setLocationFax($locationFax) {
		$this->locationFax = $locationFax;
	}

	function setLocationComments($locationComments) {
		$this->locationComments = $locationComments;
	}

	function getLocationId() {

		return $this->locationId;
	}

	function getLocationName() {

		return $this->locationName;
	}

	function getLocationCountry() {
		return $this->locationCountry;
	}

	function getLocationState() {
		return $this->locationState;
	}

	function getLocationCity() {
		return $this->locationCity;
	}

	function getLocationAddress() {
		return $this->locationAddress;
	}

	function getLocationZIP() {
		return $this->locationZIP;
	}

	function getLocationPhone() {
		return $this->locationPhone;
	}

	function getLocationFax() {
		return $this->locationFax;
	}

	function getLocationComments() {
		return $this->locationComments;
	}

	function getListofLocations($pageNO,$schStr,$mode, $sortField = 0, $sortOrder = 'ASC') {

		$tableName = 'HS_HR_LOCATION';
		$arrFieldList[0] = 'LOC_CODE';
		$arrFieldList[1] = 'LOC_NAME';
		$arrFieldList[2] = 'LOC_CITY';

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

	    	for($c=0;count($arrFieldList)>$c;$c++)
			   $arrayDispList[$i][$c] = $line[$c];

	    	$i++;

	     }

	     if (isset($arrayDispList)) {

			return $arrayDispList;

		} else {

			$arrayDispList = '';
			return $arrayDispList;
		}
	}

	function countLocations($schStr,$mode) {

		$tableName = 'HS_HR_LOCATION';
		$arrFieldList[0] = 'LOC_CODE';
		$arrFieldList[1] = 'LOC_NAME';
		$arrFieldList[2] = 'LOC_CITY';

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

	function delLocation($arrList) {

		$tableName = 'HS_HR_LOCATION';
		$arrFieldList[0] = 'LOC_CODE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_delete = 'true';
		$sql_builder->arr_delete = $arrFieldList;

		$sqlQString = $sql_builder->deleteRecord($arrList);

		$dbConnection = new DMLFunctions();

		$sqlQString1 = sprintf("SELECT * FROM `hs_hr_compstructtree` WHERE `loc_code` = '%s'",
		implode("' OR `loc_code` = '", $arrList[0]));

		$message1 = $dbConnection -> executeQuery($sqlQString1);

		if (mysql_num_rows($message1) == 0) {
			$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function
			return true;
		}

	}

	function addLocation() {

		$tableName = 'HS_HR_LOCATION';

		$this->locationId = UniqueIDGenerator::getInstance()->getNextID($tableName, 'LOC_CODE', 'LOC');

		$arrFieldList[0] = "'". $this->getLocationId() . "'";
		$arrFieldList[1] = "'". $this->getLocationName() . "'";
		$arrFieldList[2] = "'". $this->getLocationCountry() . "'";
		$arrFieldList[3] = "'". $this->getLocationState() . "'";
		$arrFieldList[4] = "'". $this->getLocationCity() . "'";
		$arrFieldList[5] = "'". $this->getLocationAddress() . "'";
		$arrFieldList[6] = "'". $this->getLocationZIP() . "'";
		$arrFieldList[7] = "'". $this->getLocationPhone() . "'";
		$arrFieldList[8] = "'". $this->getLocationFax() . "'";
		$arrFieldList[9] = "'". $this->getLocationComments() . "'";

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_insert = 'true';
		$sql_builder->arr_insert = $arrFieldList;


		$sqlQString = $sql_builder->addNewRecordFeature1();

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		 return $message2;
		 echo $message2;

	}

	function updateLocation() {

		$this->getLocationId();
		$arrRecordsList[0] = "'". $this->getLocationId() . "'";
		$arrRecordsList[1] = "'". $this->getLocationName() . "'";
		$arrRecordsList[2] = "'". $this->getLocationCountry() . "'";
		$arrRecordsList[3] = "'". $this->getLocationState() . "'";
		$arrRecordsList[4] = "'". $this->getLocationCity() . "'";
		$arrRecordsList[5] = "'". $this->getLocationAddress() . "'";
		$arrRecordsList[6] = "'". $this->getLocationZIP() . "'";
		$arrRecordsList[7] = "'". $this->getLocationPhone() . "'";
		$arrRecordsList[8] = "'". $this->getLocationFax() . "'";
		$arrRecordsList[9] = "'". $this->getLocationComments() . "'";
		$arrFieldList[0] = 'LOC_CODE';
		$arrFieldList[1] = 'LOC_NAME';
		$arrFieldList[2] = 'LOC_COUNTRY';
		$arrFieldList[3] = 'LOC_STATE';
		$arrFieldList[4] = 'LOC_CITY';
		$arrFieldList[5] = 'LOC_ADD';
		$arrFieldList[6] = 'LOC_ZIP';
		$arrFieldList[7] = 'LOC_PHONE';
		$arrFieldList[8] = 'LOC_FAX';
		$arrFieldList[9] = 'LOC_COMMENTS';

		$tableName = 'HS_HR_LOCATION';

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

	function filterLocation($getID) {

		$this->getID = $getID;
		$tableName = 'HS_HR_LOCATION';
		$arrFieldList[0] = 'LOC_CODE';
		$arrFieldList[1] = 'LOC_NAME';
		$arrFieldList[2] = 'LOC_COUNTRY';
		$arrFieldList[3] = 'LOC_STATE';
		$arrFieldList[4] = 'LOC_CITY';
		$arrFieldList[5] = 'LOC_ADD';
		$arrFieldList[6] = 'LOC_ZIP';
		$arrFieldList[7] = 'LOC_PHONE';
		$arrFieldList[8] = 'LOC_FAX';
		$arrFieldList[9] = 'LOC_COMMENTS';

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

			for($c=0;count($arrFieldList)>$c;$c++)
			   $arrayDispList[$i][$c] = $line[$c];

	    	$i++;
	     }

	     if (isset($arrayDispList)) {
			return $arrayDispList;

		} else {
			$arrayDispList = '';
			return $arrayDispList;
		}
	}

	function getLocCodes() {

		$sql_builder = new SQLQBuilder();
		$tableName = 'HS_HR_LOCATION';
		$arrFieldList[0] = 'LOC_CODE';
		$arrFieldList[1] = 'LOC_NAME';
		$arrFieldList[2] = 'LOC_CITY';

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->passResultSetMessage();

		//echo $sqlQString;

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
	     } else {
			return false;
	     }
	}
}
?>
