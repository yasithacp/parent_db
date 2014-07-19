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

class EmpLicenses {

	var $tableName = 'HS_HR_EMP_LICENSES';

	var $empId;
	var $empLicCode;
	var $empLicDat;
	var $empLicrenewalDat;


	var $arrayDispList;
	var $singleField;

	function EmpLicenses() {

	}

	function setEmpId($empId) {

	$this->empId=$empId;
	}

	function setEmpLicCode($empLicCode) {

	$this->empLicCode=$empLicCode;
	}

	function setempLicDat($empLicDat) {

	$this->empLicDat=$empLicDat;
	}

	function setempLicrenewalDat($empLicrenewalDat) {

	$this->empLicrenewalDat=$empLicrenewalDat;
	}



	function getEmpId() {

	return $this->empId;
	}

	function getEmpLicCode() {

	return $this->empLicCode;
	}

	function getempLicDat() {

	return $this->empLicDat;
	}

	function getempLicrenewalDat() {

	return $this->empLicrenewalDat;
	}




function getListofEmpLicenses($page,$str,$mode) {

		$tableName = 'HS_HR_EMP_LICENSES';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->field = 'EMP_NUMBER';

		$sqlQString = $sql_builder->selectEmployee($page,$str,$mode);

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

	function countEmpLicenses($str,$mode) {

		$tableName = 'HS_HR_EMP_LICENSES';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->field = 'EMP_NUMBER';

		$sqlQString = $sql_builder->countEmployee($str,$mode);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$line = mysql_fetch_array($message2, MYSQL_NUM);
		echo mysql_error();
	    	return $line[0];
	}

	function delEmpLicenses($arrList) {

		$tableName = 'HS_HR_EMP_LICENSES';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'LICENSES_CODE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_delete = 'true';
		$sql_builder->arr_delete = $arrFieldList;

		$sqlQString = $sql_builder->deleteRecord($arrList);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

	}

	function addEmpLicenses() {

		$this->getEmpId();
		$arrFieldList[0]  = "'". $this->getEmpId() . "'";
		$arrFieldList[1]  = "'". $this->getEmpLicCode() . "'";
		$arrFieldList[2]  = $this->getempLicDat(); // Quotes removed to accept null values
		$arrFieldList[3]  = $this->getempLicrenewalDat();


		$tableName = 'HS_HR_EMP_LICENSES';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_insert = 'true';
		$sql_builder->arr_insert = $arrFieldList;


		$sqlQString = $sql_builder->addNewRecordFeature1();
	//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		 return $message2;
		 echo $message2;

	}

	function updateEmpLicenses() {

		$arrRecordsList[0]  = "'". $this->getEmpId() . "'";
		$arrRecordsList[1]  = "'". $this->getEmpLicCode() . "'";
		$arrRecordsList[2]  = $this->getempLicDat(); // Quotes removed to accept null values
		$arrRecordsList[3]  = $this->getempLicrenewalDat();


		$tableName = 'HS_HR_EMP_LICENSES';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'LICENSES_CODE';
		$arrFieldList[2] = 'LICENSES_DATE';
		$arrFieldList[3] = 'LICENSES_RENEWAL_DATE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_update = 'true';
		$sql_builder->arr_update = $arrFieldList;
		$sql_builder->arr_updateRecList = $arrRecordsList;

		$sqlQString = $sql_builder->addUpdateRecord1(1);

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;
	}

	function filterEmpLicenses($getID) {

		$this->getID = $getID;
		$tableName = 'HS_HR_EMP_LICENSES';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'LICENSES_CODE';
		$arrFieldList[2] = 'LICENSES_DATE';
		$arrFieldList[3] = 'LICENSES_RENEWAL_DATE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->selectOneRecordFiltered($this->getID,1);

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



	function getAssEmpLicenses($getID) {

		$this->getID = $getID;
		$tableName = 'HS_HR_EMP_LICENSES';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'LICENSES_CODE';
		$arrFieldList[2] = 'LICENSES_DATE';
		$arrFieldList[3] = 'LICENSES_RENEWAL_DATE';

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



}

?>
