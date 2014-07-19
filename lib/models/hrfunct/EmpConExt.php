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

class EmpConExt {

	var $tableName = 'HS_HR_EMP_CONTRACT_EXTEND';
	
	var $empId;
	var $empConExtId;
	var $empConExtStartDat;
	var $empConExtEndDat;
	var $arrayDispList;
	var $singleField;
	
	function EmpConExt() {
		
	}
	
	function setEmpId($empId) {
	
	$this->empId=$empId;
	}
	
	function setEmpConExtId($empConExtId) {
	
	$this->empConExtId=$empConExtId;
	}
	
	function setEmpConExtStartDat($empConExtStartDat) {
	
	$this->empConExtStartDat=$empConExtStartDat;
	}
	
	function setEmpConExtEndDat($empConExtEndDat) {
	
	$this->empConExtEndDat=$empConExtEndDat;
	}
	
	function getEmpId() {
	
	return $this->empId;
	}
	
	function getEmpConExtId() {
	return $this->empConExtId;
	}
	
	function getEmpConExtStartDat() {
	return $this->empConExtStartDat;
	}
	
	function getEmpConExtEndDat() {
	return $this->empConExtEndDat;
	}
////
	

	function getListofConExt($page,$str,$mode) {
		
		$tableName = 'HS_HR_EMP_CONTRACT_EXTEND';

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

	function countConExt($str,$mode) {
		
		$tableName = 'HS_HR_EMP_CONTRACT_EXTEND';

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

	function delConExt($arrList) {

		$tableName = 'HS_HR_EMP_CONTRACT_EXTEND';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'ECON_EXTEND_ID';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_delete = 'true';
		$sql_builder->arr_delete = $arrFieldList;

		$sqlQString = $sql_builder->deleteRecord($arrList);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

	}

	function addConExt() {
		
		$this->getEmpId();
		$arrFieldList[0] = "'". $this->getEmpId() . "'";
		$arrFieldList[1] = "'". $this->getEmpConExtId() . "'";
		$arrFieldList[2] = $this->getEmpConExtStartDat(); // Quotes were removed to allow null values.
		$arrFieldList[3] = $this->getEmpConExtEndDat();

		$tableName = 'HS_HR_EMP_CONTRACT_EXTEND';
	
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
	
	function updateConExt() {
		
		$arrRecordsList[0] = "'". $this->getEmpId() . "'";
		$arrRecordsList[1] = "'". $this->getEmpConExtId() . "'";
		$arrRecordsList[2] = $this->getEmpConExtStartDat();
		$arrRecordsList[3] = $this->getEmpConExtEndDat();

		$tableName = 'HS_HR_EMP_CONTRACT_EXTEND';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'ECON_EXTEND_ID';
		$arrFieldList[2] = 'ECON_EXTEND_START_DATE';
		$arrFieldList[3] = 'ECON_EXTEND_END_DATE';

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
	
	
	function filterConExt($getID) {
		
		$this->getID = $getID;
		$tableName = 'HS_HR_EMP_CONTRACT_EXTEND';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'ECON_EXTEND_ID';
		$arrFieldList[2] = 'ECON_EXTEND_START_DATE';
		$arrFieldList[3] = 'ECON_EXTEND_END_DATE';

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

	function getAssConExt($getID) {

		$this->getID = $getID;
		$tableName = 'HS_HR_EMP_CONTRACT_EXTEND';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'ECON_EXTEND_ID';
		$arrFieldList[2] = 'ECON_EXTEND_START_DATE';
		$arrFieldList[3] = 'ECON_EXTEND_END_DATE';

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

	function getLastRecord($str) {
		
		$sql_builder = new SQLQBuilder();
		$tableName = 'HS_HR_EMP_CONTRACT_EXTEND';
		$arrFieldList[0] = 'ECON_EXTEND_ID';
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
