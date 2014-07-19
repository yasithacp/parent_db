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

class EmpWorkExp {

	var $tableName = 'HS_HR_EMP_WORK_EXPERIENCE';

	var $empId;
	var $empExpSeqNo;
	var $empExpEmployer;
	var $empExpJobTitle;
	var $empExpFromDate;
	var $empExpToDate;
	var $empExpComments;


	var $arrayDispList;
	var $singleField;

	function EmpWorkExp() {

	}

	function setEmpId($empId) {

	$this->empId=$empId;
	}

	function setEmpExpSeqNo($empExpSeqNo) {

	$this->empExpSeqNo=$empExpSeqNo;
	}

	function setEmpExpEmployer($empExpEmployer) {

		$this->empExpEmployer = $empExpEmployer;
	}

	function setEmpExpJobTitle($empExpJobTitle) {

		$this->empExpJobTitle = $empExpJobTitle;
	}

	function setEmpExpFromDate($empExpFromDate) {

		$this->empExpFromDate = $empExpFromDate;
	}

	function setEmpExpToDate($empExpToDate) {

		$this->empExpToDate = $empExpToDate;
	}

	function setEmpExpComments($empExpComments) {

		$this->empExpComments = $empExpComments;
	}

	function setEmpExpInternal($empExpInternal) {

		$this->empExpInternal = $empExpInternal;
	}

	function getEmpId() {

	return $this->empId;
	}

	function getEmpExpSeqNo() {

	return $this->empExpSeqNo;
	}

	function getEmpExpEmployer() {

		return $this->empExpEmployer;
	}

	function getEmpExpJobTitle() {

		return $this->empExpJobTitle;
	}

	function getEmpExpFromDate() {

		return $this->empExpFromDate;
	}

	function getEmpExpToDate() {

		return $this->empExpToDate;
	}

	function getEmpExpComments() {

		return $this->empExpComments;
	}

	function getEmpExpInternal() {

		return $this->empExpInternal;
	}

function getListofEmpWorkExp($page,$str,$mode) {

		$tableName = 'HS_HR_EMP_WORK_EXPERIENCE';

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

	function countEmpWorkExp($str,$mode) {

		$tableName = 'HS_HR_EMP_WORK_EXPERIENCE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->field = 'EMP_NUMBER';

		$sqlQString = $sql_builder->countEmployee($str,$mode);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$line = mysql_fetch_array($message2, MYSQL_NUM);

	    	return $line[0];
	}

	function delEmpWorkExp($arrList) {

		$tableName = 'HS_HR_EMP_WORK_EXPERIENCE';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'EEXP_SEQNO';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_delete = 'true';
		$sql_builder->arr_delete = $arrFieldList;

		$sqlQString = $sql_builder->deleteRecord($arrList);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

	}

	function addEmpWorkExp() {

		$arrFieldList[0]  = "'". $this->getEmpId() . "'";
		$arrFieldList[1]  = "'". $this->getEmpExpSeqNo() . "'";
		$arrFieldList[2]  = "'". $this->getEmpExpEmployer() . "'";
		$arrFieldList[3]  = "'". $this->getEmpExpJobTitle() . "'";
		$arrFieldList[4]  = $this->getEmpExpFromDate(); // Quotes removed to allow null values
		$arrFieldList[5]  = $this->getEmpExpToDate();
		$arrFieldList[6]  = "'". $this->getEmpExpComments() . "'";
		$arrFieldList[7]  = "'". $this->getEmpExpInternal() . "'";

		$tableName = 'HS_HR_EMP_WORK_EXPERIENCE';

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

	function updateEmpWorkExp() {

		$arrRecordsList[0]  = "'". $this->getEmpId() . "'";
		$arrRecordsList[1]  = "'". $this->getEmpExpSeqNo() . "'";
		$arrRecordsList[2]  = "'". $this->getEmpExpEmployer() . "'";
		$arrRecordsList[3]  = "'". $this->getEmpExpJobTitle() . "'";
		$arrRecordsList[4]  =  $this->getEmpExpFromDate() ; // Quotes removed to allow null values
		$arrRecordsList[5]  =  $this->getEmpExpToDate() ;
		$arrRecordsList[6]  = "'". $this->getEmpExpComments() . "'";
		$arrRecordsList[7]  = "'". $this->getEmpExpInternal() . "'";

		$tableName = 'HS_HR_EMP_WORK_EXPERIENCE';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'EEXP_SEQNO';
		$arrFieldList[2] = 'EEXP_EMPLOYER';
		$arrFieldList[3] = 'EEXP_JOBTIT';
		$arrFieldList[4] = 'EEXP_FROM_DATE';
		$arrFieldList[5] = 'EEXP_TO_DATE';
		$arrFieldList[6] = 'EEXP_COMMENTS';
		$arrFieldList[7] = 'EEXP_INTERNAL';

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

	function filterEmpWorkExp($getID) {

		$this->getID = $getID;
		$tableName = 'HS_HR_EMP_WORK_EXPERIENCE';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'EEXP_SEQNO';
		$arrFieldList[2] = 'EEXP_EMPLOYER';
		$arrFieldList[3] = 'EEXP_JOBTIT';
		$arrFieldList[4] = 'EEXP_FROM_DATE';
		$arrFieldList[5] = 'EEXP_TO_DATE';
		$arrFieldList[6] = 'EEXP_COMMENTS';
		$arrFieldList[7] = 'EEXP_INTERNAL';

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

	function getAssEmpWorkExp($getID) {

		$this->getID = $getID;
		$tableName = 'HS_HR_EMP_WORK_EXPERIENCE';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'EEXP_SEQNO';
		$arrFieldList[2] = 'EEXP_EMPLOYER';
		$arrFieldList[3] = 'EEXP_JOBTIT';
		$arrFieldList[4] = 'EEXP_FROM_DATE';
		$arrFieldList[5] = 'EEXP_TO_DATE';
		$arrFieldList[6] = 'EEXP_COMMENTS';
		$arrFieldList[7] = 'EEXP_INTERNAL';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->selectOneRecordFiltered($this->getID, null, 4, 'DESC');

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

	function getLastRecord($str) {

		$sql_builder = new SQLQBuilder();
		$tableName = 'HS_HR_EMP_WORK_EXPERIENCE';
		$arrFieldList[0] = 'EEXP_SEQNO';
		$arrFieldList[1] = 'EMP_NUMBER';

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$arrSel[0]=$str;
		$sqlQString = $sql_builder->selectOneRecordOnly(1,$arrSel);

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$common_func = new CommonFunctions();

		if (isset($message2)) {

			$i=0;

		while ($line = mysql_fetch_array($message2, MYSQL_ASSOC)) {
			foreach ($line as $col_value) {
			$this->singleField = $col_value;
			}
		}

		$lastrec=((int)$this->singleField)+1;
		return $lastrec;

		}

	}


}

?>
