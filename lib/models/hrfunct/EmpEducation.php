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

class EmpEducation {

	var $empID;
	var $eduCode;
	var $eduMajor;
	var $eduYear;
	var $eduGPA;
	var $eduStartDate;
	var $eduEndDate;

	function EmpEducation() {
	}

	function setEmpID($empID) {
		$this->empID = $empID;
	}

	function setEduCode($eduCode) {
		$this->eduCode = $eduCode;
	}

	function setEduMajor($eduMajor) {
		$this->eduMajor = $eduMajor;
	}

	function setEduYear($eduYear) {
		$this->eduYear = $eduYear;
	}

	function setEduGPA($eduGPA) {
		$this->eduGPA = $eduGPA;
	}

	function setEduStartDate($eduStartDate) {
		$this->eduStartDate = $eduStartDate;
	}

	function setEduEndDate($eduEndDate) {
		$this->eduEndDate = $eduEndDate;
	}

	function getEmpID() {
		return $this->empID;
	}

	function getEduCode() {
		return $this->eduCode;
	}

	function getEduMajor() {
		return $this->eduMajor;
	}

	function getEduYear() {
		return $this->eduYear;
	}

	function getEduGPA() {
		return $this->eduGPA;
	}

	function getEduStartDate() {
		return $this->eduStartDate;
	}

	function getEduEndDate() {
		return $this->eduEndDate;
	}

	function getListofEmpEducation($page,$str,$mode) {

		$tableName = 'HS_HR_EMP_EDUCATION';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->field = 'EMP_NUMBER';

		$sqlQString = $sql_builder->selectEmployee($page,$str,$mode);

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

	function countEmpEducation($str,$mode) {

		$tableName = 'HS_HR_EMP_EDUCATION';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->field = 'EMP_NUMBER';

		$sqlQString = $sql_builder->countEmployee($str,$mode);

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$line = mysql_fetch_array($message2, MYSQL_NUM);

	    	return $line[0];
	}

	function delEmpEducation($arrList) {

		$tableName = 'HS_HR_EMP_EDUCATION';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'EDU_CODE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_delete = 'true';
		$sql_builder->arr_delete = $arrFieldList;

		$sqlQString = $sql_builder->deleteRecord($arrList);

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;
	}

	function addEmpEducation() {

		$arrFieldList[0] = "'". $this->getEmpID()         . "'";
		$arrFieldList[1] = "'". $this->getEduCode()  . "'";
		$arrFieldList[2] = "'". $this->getEduMajor() . "'";
		$arrFieldList[3] = $this->getEduYear();
		$arrFieldList[4] = "'". $this->getEduGPA()   . "'";
		$arrFieldList[5] = $this->getEduStartDate(); // Quotes removed to accept null values
		$arrFieldList[6] = $this->getEduEndDate();

		$tableName = 'HS_HR_EMP_EDUCATION';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_insert = 'true';
		$sql_builder->arr_insert = $arrFieldList;

		$sqlQString = $sql_builder->addNewRecordFeature1();

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;

	}

	function updateEmpEducation() {

		$arrRecordsList[0] = "'". $this->getEmpId() . "'";
		$arrRecordsList[1] = "'". $this->getEduCode() . "'";
		$arrRecordsList[2] = "'". $this->getEduMajor() . "'";
		$arrRecordsList[3] = $this->getEduYear();
		$arrRecordsList[4] = "'". $this->getEduGPA() . "'";
		$arrRecordsList[5] = $this->getEduStartDate(); // Quotes removed to accept null values
		$arrRecordsList[6] = $this->getEduEndDate();

		$tableName = 'HS_HR_EMP_EDUCATION';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'EDU_CODE';
		$arrFieldList[2] = 'EDU_MAJOR';
		$arrFieldList[3] = 'EDU_YEAR';
		$arrFieldList[4] = 'EDU_GPA';
		$arrFieldList[5] = 'EDU_START_DATE';
		$arrFieldList[6] = 'EDU_END_DATE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_update = 'true';
		$sql_builder->arr_update = $arrFieldList;
		$sql_builder->arr_updateRecList = $arrRecordsList;

		$sqlQString = $sql_builder->addUpdateRecord1(1);
	//echo  $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;
	}


	function filterEmpEducation($getID) {

		$tableName = 'HS_HR_EMP_EDUCATION';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'EDU_CODE';
		$arrFieldList[2] = 'EDU_MAJOR';
		$arrFieldList[3] = 'EDU_YEAR';
		$arrFieldList[4] = 'EDU_GPA';
		$arrFieldList[5] = 'EDU_START_DATE';
		$arrFieldList[6] = 'EDU_END_DATE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->selectOneRecordFiltered($getID,1);

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

	function getAssEmpEducation($getID) {

		$this->getID = $getID;

		$tableName = 'HS_HR_EMP_EDUCATION';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'EDU_CODE';
		$arrFieldList[2] = 'EDU_MAJOR';
		$arrFieldList[3] = 'EDU_YEAR';
		$arrFieldList[4] = 'EDU_GPA';
		$arrFieldList[5] = 'EDU_START_DATE';
		$arrFieldList[6] = 'EDU_END_DATE';

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

	function getUnAssEduCodes($id) {

		$sql_builder = new SQLQBuilder();
		$tableName = 'HS_HR_EDUCATION';
		$arrFieldList[0] = 'EDU_CODE';
		$arrFieldList[1] = 'EDU_DEG';
		$arrFieldList[2] = 'EDU_UNI';

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;
		$sql_builder->field = 'EDU_CODE';
		$sql_builder->table2_name = 'HS_HR_EMP_EDUCATION';

		$arr[0][0]='EMP_NUMBER';
		$arr[0][1]=$id;
		$sqlQString = $sql_builder->selectFilter($arr, 1, 1);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {

	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];
	    	$arrayDispList[$i][2] = $line[2];
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