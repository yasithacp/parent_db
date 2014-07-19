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
require_once ROOT_PATH . '/lib/logs/LogFileWriter.php';

class EmpDependents {

	var $tableName = 'HS_HR_EMP_DEPENDENTS';
	
	var $empId;
	var $empDSeqNo;
	var $empDepName;
	var $empDepRel;
	
	function EmpDependents() {
		
	}
	
	function setEmpId($empId) {
	
	$this->empId=$empId;
	}

	function setEmpDSeqNo($empDSeqNo) {
	/*$logw = new LogFileWriter();
	$logw->writeLogDB($empDSeqNo.'hhh');*/
	$this->empDSeqNo=$empDSeqNo;
	}

	function setEmpDepName($empDepName) {
	
	$this->empDepName=$empDepName;
	}
	
	function setEmpDepRel($empDepRel) {
	
	$this->empDepRel=$empDepRel;
	}
	
		
	function getEmpId() {
	
	return $this->empId;
	}
	
	function getEmpDSeqNo(){
		
	return $this->empDSeqNo;
	}
	
	function getEmpDepName() {
	
	return $this->empDepName;
	}
	
	function getEmpDepRel() {
	
	return $this->empDepRel;
	}
				
	
////
	function getListofEmpDep($str,$mode) {
		
		$tableName = 'HS_HR_EMP_DEPENDENTS';

		$sql_builder = new SQLQBuilder();
		
		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->field = 'EMP_NUMBER';

		$sqlQString = $sql_builder->selectEmployee($str,$mode);
		
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

	function delEmpDep($arrList) {

		$tableName = 'HS_HR_EMP_DEPENDENTS';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'ED_SEQNO';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_delete = 'true';
		$sql_builder->arr_delete = $arrFieldList;

		$sqlQString = $sql_builder->deleteRecord($arrList);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

	}

	function addEmpDep() {
		
		//$this->getEmpId();
		
		$arrFieldList[0] = "'". $this->getEmpId() . "'";
		$arrFieldList[1] = "'". $this->getEmpDSeqNo() . "'";
		$arrFieldList[2] = "'". mysql_real_escape_string($this->getEmpDepName()) . "'";
		$arrFieldList[3] = "'". mysql_real_escape_string($this->getEmpDepRel()) . "'";
		
			
		$tableName = 'HS_HR_EMP_DEPENDENTS';
	
		$sql_builder = new SQLQBuilder();
		
		$sql_builder->table_name = $tableName;
		$sql_builder->flg_insert = 'true';
		$sql_builder->arr_insert = $arrFieldList;		
			
		$sqlQString = "";
		$sqlQString = $sql_builder->addNewRecordFeature1();		
	
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection->executeQuery($sqlQString); //Calling the addData() function
		
	return $message2;		
	}
	
	function updateEmpDep() {
		
		$arrRecordsList[0] = "'". $this->getEmpId() . "'";
		$arrRecordsList[1] = "'". $this->getEmpDSeqNo() . "'";
		$arrRecordsList[2] = "'". mysql_real_escape_string($this->getEmpDepName()) . "'";
		$arrRecordsList[3] = "'". mysql_real_escape_string($this->getEmpDepRel()) . "'";
				

		$tableName = 'HS_HR_EMP_DEPENDENTS';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'ED_SEQNO';
		$arrFieldList[2] = 'ED_NAME';
		$arrFieldList[3] = 'ED_RELATIONSHIP';
		

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
	
	
	function filterEmpDep($getID) {
		
		$this->getID = $getID;
		$tableName = 'HS_HR_EMP_DEPENDENTS';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'ED_SEQNO';
		$arrFieldList[2] = 'ED_NAME';
		$arrFieldList[3] = 'ED_RELATIONSHIP';
		

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

	function getLastRecord($str) {
		
		$sql_builder = new SQLQBuilder();
		$tableName = 'HS_HR_EMP_DEPENDENTS';
		$arrFieldList[0] = 'ED_SEQNO';
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
	
	function getAssEmpDep($getID) {
		
		$this->getID = $getID;
		$tableName = 'HS_HR_EMP_DEPENDENTS';
		
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'ED_SEQNO';
		$arrFieldList[2] = 'ED_NAME';
		$arrFieldList[3] = 'ED_RELATIONSHIP';
		
		
		$sql_builder = new SQLQBuilder();
		
		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;		
			
		$sqlQString = $sql_builder->selectOneRecordFiltered($this->getID);
		/*$exception_handler = new ExceptionHandler();
	  	 	$exception_handler->logW($sqlQString);*/
		//$sqlQString="SELECT EMP_NUMBER, EP_SEQNO, EP_PASSPORT_NUM, EP_PASSPORTISSUEDDATE, EP_PASSPORTEXPIREDATE, EP_COMMENTS, EP_PASSPORT_TYPE_FLG, EP_I9_STATUS, EP_I9_REVIEW_DATE, COU_CODE  FROM HS_HR_EMP_DEPENDENTS WHERE EMP_NUMBER='EMP010'";
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
