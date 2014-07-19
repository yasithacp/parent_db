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

class EmpAttach {

	var $tableName = 'HS_HR_EMP_ATTACHMENT';
	
	var $empId;
	var $empAttId;
	var $empAttDesc;
	var $empAttFilename;
	var $empAttSize;
	var $empAttachment;
	var $empAttType;
	var $arrayDispList;
	var $singleField;
	
	function EmpAttach() {
		
	}
	
	function setEmpId($empId) {
	
	$this->empId=$empId;
	}

	function setEmpAttId($empAttId) {
		$this->empAttId=$empAttId;		
	}
	
	function setEmpAttDesc($empAttDesc) {
		$this->empAttDesc=$empAttDesc;
	}
	
	function setEmpAttFilename($empAttFilename) {
		$this->empAttFilename=$empAttFilename;	
	}
	
	function setEmpAttSize($empAttSize) {
		$this->empAttSize=$empAttSize;	
	}
	
	function setEmpAttachment($empAttachment) {
		$this->empAttachment=$empAttachment;
	}
	
	function setEmpAttType($empAttType) {
		$this->empAttType=$empAttType;
	}

	function getEmpId() {
	
	return $this->empId;
	}
	
	function getEmpAttId() {
		return $this->empAttId;		
	}
	
	function getEmpAttDesc() {
		return $this->empAttDesc;
	}
	
	function getEmpAttFilename() {
		return $this->empAttFilename;	
	}
	
	function getEmpAttSize() {
		return $this->empAttSize;	
	}
	
	function getEmpAttachment() {
		return $this->empAttachment;
	}
	
	function getEmpAttType() {
		return $this->empAttType;
	}

	
////
	function getListofEmpAtt($str,$mode) {
		
		$tableName = 'HS_HR_EMP_ATTACHMENT';

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

	function delEmpAtt($arrList) {

		$tableName = 'HS_HR_EMP_ATTACHMENT';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'EATTACH_ID';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_delete = 'true';
		$sql_builder->arr_delete = $arrFieldList;

		$sqlQString = $sql_builder->deleteRecord($arrList);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

	}

	function addEmpAtt() {
		
		$this->getEmpId();
		$arrFieldList[0] = "'". $this->getEmpId() . "'";
		$arrFieldList[1] = "'". $this->getEmpAttId() . "'";
		$arrFieldList[2] = "'". mysql_real_escape_string($this->getEmpAttDesc()) . "'";
		$arrFieldList[3] = "'". $this->getEmpAttFilename() . "'";
		$arrFieldList[4] = "'". $this->getEmpAttSize() . "'";
		$arrFieldList[5] = "'". $this->getEmpAttachment() . "'";
		$arrFieldList[6] = "'". $this->getEmpAttType() . "'";

		$tableName = 'HS_HR_EMP_ATTACHMENT';
	
		$sql_builder = new SQLQBuilder();
		
		$sql_builder->table_name = $tableName;
		$sql_builder->flg_insert = 'true';
		$sql_builder->arr_insert = $arrFieldList;		
			
	
		$sqlQString = $sql_builder->addNewRecordFeature1(false);
	
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function
		
		 return $message2;		 
				
	}
	
	function updateEmpAtt() {
		
		$arrRecordsList[0] = "'". $this->getEmpId() . "'";
		$arrRecordsList[1] = "'". $this->getEmpAttId() . "'";
		$arrRecordsList[2] = "'". mysql_real_escape_string($this->getEmpAttDesc()) . "'";
	
		$tableName = 'HS_HR_EMP_ATTACHMENT';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'EATTACH_ID';
		$arrFieldList[2] = 'EATTACH_DESC';
	
		$sql_builder = new SQLQBuilder();
		
		$sql_builder->table_name = $tableName;
		$sql_builder->flg_update = 'true';
		$sql_builder->arr_update = $arrFieldList;	
		$sql_builder->arr_updateRecList = $arrRecordsList;	
	
		$sqlQString = $sql_builder->addUpdateRecord1(1, false);
		
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function
		
		return $message2;
		 
				
	}
	
	
	function filterEmpAtt($getID) {
		
		$this->getID = $getID;
		$tableName = 'HS_HR_EMP_ATTACHMENT';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'EATTACH_ID';
		$arrFieldList[2] = 'EATTACH_DESC';
		$arrFieldList[3] = 'EATTACH_FILENAME';
		$arrFieldList[4] = 'EATTACH_SIZE';
		$arrFieldList[5] = 'EATTACH_ATTACHMENT';
		$arrFieldList[6] = 'EATTACH_TYPE';

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

	
	function getAssEmpAtt($getID) {
		
		$this->getID = $getID;
		$tableName = 'HS_HR_EMP_ATTACHMENT';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'EATTACH_ID';
		$arrFieldList[2] = 'EATTACH_DESC';
		$arrFieldList[3] = 'EATTACH_FILENAME';
		$arrFieldList[4] = 'EATTACH_SIZE';
		$arrFieldList[5] = 'EATTACH_ATTACHMENT';
		$arrFieldList[6] = 'EATTACH_TYPE';


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
		$tableName = 'HS_HR_EMP_ATTACHMENT';
		$arrFieldList[0] = 'EATTACH_ID';
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
