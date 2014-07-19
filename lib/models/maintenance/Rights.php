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

class Rights {
	
		var $userGroupID;
		var $moduleID;
		var $addRight;
		var $editRight;
		var $deleteRight;
		var $viewRight;
		
		var $arrRights;
		
	function Rights() {
		$this->sql_builder = new SQLQBuilder();
		$this->dbConnection = new DMLFunctions();		
	}
	
	function setUserGroupID($userGroupID){
		$this->userGroupID = $userGroupID;
	}
	
	function setModuleID($moduleID){
		$this->moduleID = $moduleID;
	}
	
	function setRightAdd($addRight) {
		$this->addRight=$addRight;
	}
	
	function setRightEdit($editRight) {
		$this->editRight=$editRight;
	}
	
	function setRightDelete($deleteRight) {
		$this->deleteRight=$deleteRight;
	}
	
	function setRightView($viewRight) {
		$this->viewRight=$viewRight;
	}
	
	function getUserGroupID() {
		return $this->userGroupID;
	}
	
	function getModuleID(){
		return $this->moduleID;
	}
	
	function getRightAdd() {
		return $this->addRight;
	}
	
	function getRightEdit() {
		return $this->editRight;
	}
	
	function getRightDelete() {
		return $this->deleteRight;
	}
	
	function getRightView() {
		return $this->viewRight;
	}

	function addRights(){
		$arrFieldList[0] = "'". $this->getUserGroupID() . "'";
		$arrFieldList[1] = "'". $this->getModuleID() . "'";		
		$arrFieldList[2] = "'". $this->getRightAdd() . "'";
		$arrFieldList[3] = "'". $this->getRightEdit() . "'";
		$arrFieldList[4] = "'". $this->getRightDelete() . "'";
		$arrFieldList[5] = "'". $this->getRightView() . "'";
				
		$arrRecordsList[0] = 'USERG_ID';
		$arrRecordsList[1] = 'MOD_ID';
		$arrRecordsList[2] = 'ADDITION';
		$arrRecordsList[3] = 'EDITING';
		$arrRecordsList[4] = 'DELETION';
		$arrRecordsList[5] = 'VIEWING';
								
		$this->sql_builder->table_name = 'HS_HR_RIGHTS';
		$this->sql_builder->flg_insert = 'true';
		$this->sql_builder->arr_insertfield = $arrRecordsList;
		$this->sql_builder->arr_insert = $arrFieldList;		
	
		$sqlQString = $this->sql_builder->addNewRecordFeature2();
		
		$message2 = $this->dbConnection -> executeQuery($sqlQString); //Calling the addData() function
		
		 return $message2;
		 echo $message2;
	}
	
	function updateRights() {
		$arrFieldList[0] = "'". $this->getUserGroupID() . "'";
		$arrFieldList[1] = "'". $this->getModuleID() . "'";		
		$arrFieldList[2] = "'". $this->getRightAdd() . "'";
		$arrFieldList[3] = "'". $this->getRightEdit() . "'";
		$arrFieldList[4] = "'". $this->getRightDelete() . "'";
		$arrFieldList[5] = "'". $this->getRightView() . "'";
		
		if($this->getUserGroupID() == $_SESSION['userGroup'])
			return false;
			
		$arrRecordsList[0] = 'USERG_ID';
		$arrRecordsList[1] = 'MOD_ID';
		$arrRecordsList[2] = 'ADDITION';
		$arrRecordsList[3] = 'EDITING';
		$arrRecordsList[4] = 'DELETION';
		$arrRecordsList[5] = 'VIEWING';
		
		$this->sql_builder->table_name = 'HS_HR_RIGHTS';
		$this->sql_builder->flg_update = 'true';
		$this->sql_builder->arr_update = $arrRecordsList;	
		$this->sql_builder->arr_updateRecList = $arrFieldList;	
	
		$sqlQString = $this->sql_builder->addUpdateRecord1(1);
		
		$message2 = $this->dbConnection -> executeQuery($sqlQString); //Calling the addData() function
		
		return $message2;
	}
	
	function clearRights($ugID) {
		
		if($ugID == $_SESSION['userGroup'])
			return false;

		$sqlQString = "UPDATE hs_hr_rights SET addition = 0, editing = 0, deletion = 0, viewing = 0 WHERE userg_id = '$ugID'";

		$message2 = $this->dbConnection -> executeQuery($sqlQString); //Calling the addData() function
		
		return $message2;
	}
	
	function filterRights($getID) {
		
		$this->ID = $getID;
		$arrFieldList[0] = 'USERG_ID';
		$arrFieldList[1] = 'MOD_ID';
		$arrFieldList[2] = 'ADDITION';
		$arrFieldList[3] = 'EDITING';
		$arrFieldList[4] = 'DELETION';
		$arrFieldList[5] = 'VIEWING';
						
		$this->sql_builder->table_name = 'HS_HR_RIGHTS';
		$this->sql_builder->flg_select = 'true';
		$this->sql_builder->arr_select = $arrFieldList;		
			
		$sqlQString = $this->sql_builder->selectOneRecordFiltered($this->ID,1);
		
		$message2 = $this->dbConnection -> executeQuery($sqlQString); //Calling the addData() function
		
		$i=0;
		
		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {
		 	
	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];
	    	$arrayDispList[$i][2] = $line[2];
	    	$arrayDispList[$i][3] = $line[3];
	    	$arrayDispList[$i][4] = $line[4];
	    	$arrayDispList[$i][5] = $line[5];
	    	
	    	$i++;
	    	
	     }
	     
	     if (isset($arrayDispList)) {
	     
			return $arrayDispList;
			
		} else {
		
			$arrayDispList = '';
			return $arrayDispList;
			
		}
				
	}
	
	function delRights($arrList) {

		$arrFieldList[0] = 'USERG_ID';
		$arrFieldList[1] = 'MOD_ID';

		$this->sql_builder->table_name = 'HS_HR_RIGHTS';
		$this->sql_builder->flg_delete = 'true';
		$this->sql_builder->arr_delete = $arrFieldList;

		$delFlag = false;
		for($c=0;count($arrList[0])>$c;$c++) 
			if('USG001' == $arrList[0][$c])
				$delFlag = true;
		
		if($delFlag) {
			return false;
		}

		$sqlQString = $this->sql_builder->deleteRecord($arrList);

		$message2 = $this->dbConnection -> executeQuery($sqlQString); //Calling the addData() function

	}
		
	function getAllModules(){
		
		$arrFieldList[0] = 'MOD_ID';
		$arrFieldList[1] = 'NAME';
	
		
		$this->sql_builder->table_name = 'HS_HR_MODULE';
		$this->sql_builder->flg_select = 'true';
		$this->sql_builder->arr_select = $arrFieldList;
		
		$sqlQString =$this->sql_builder->passResultSetMessage();
		
		$message2 = $this->dbConnection -> executeQuery($sqlQString);
		
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

	function getModuleCodes($id) {
		
		$tableName = 'HS_HR_MODULE';			
		$arrFieldList[0] = 'MOD_ID';
		$arrFieldList[1] = 'NAME';
		
		$sql_builder = new SQLQBuilder();
		
		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;		
		$sql_builder->field = 'MOD_ID';		
		$sql_builder->table2_name = 'HS_HR_RIGHTS';
			
		$arr[0][0]='USERG_ID';
		$arr[0][1]=$id;
		$sqlQString = $sql_builder->selectFilter($arr,1);
		
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
	
	function getAssRights($getID) {
		
		$this->ID = $getID;
		$arrFieldList[0] = 'USERG_ID';
		$arrFieldList[1] = 'MOD_ID';
		$arrFieldList[2] = 'ADDITION';
		$arrFieldList[3] = 'EDITING';
		$arrFieldList[4] = 'DELETION';
		$arrFieldList[5] = 'VIEWING';
						
		$this->sql_builder->table_name = 'HS_HR_RIGHTS';
		$this->sql_builder->flg_select = 'true';
		$this->sql_builder->arr_select = $arrFieldList;		
			
		$sqlQString = $this->sql_builder->selectOneRecordFiltered($this->ID);
		
		$message2 = $this->dbConnection -> executeQuery($sqlQString); //Calling the addData() function
		
		$i=0;
		
		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {
		 	
	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];
	    	$arrayDispList[$i][2] = $line[2];
	    	$arrayDispList[$i][3] = $line[3];
	    	$arrayDispList[$i][4] = $line[4];
	    	$arrayDispList[$i][5] = $line[5];
	    	
	    	$i++;
	    	
	     }
	     
	     if (isset($arrayDispList)) {
	     
			return $arrayDispList;
			
		} else {
		
			$arrayDispList = '';
			return $arrayDispList;
			
		}
				
	}
	
function getRights($user,$module) {
			$tableName = 'HS_HR_RIGHTS';
			$arrFieldList[0] = 'USERG_ID';
			$arrFieldList[1] = 'MOD_ID';
			$arrFieldList[2] = 'ADDITION';
			$arrFieldList[3] = 'EDITING';
			$arrFieldList[4] = 'DELETION';
			$arrFieldList[5] = 'VIEWING';
	
			$sql_builder = new SQLQBuilder();
			
			$sql_builder->table_name = $tableName;
			$sql_builder->flg_select = 'true';
			$sql_builder->arr_select = $arrFieldList;		
				
			$arr[0]=$user;
			$arr[1]=$module;
			$sqlQString = $sql_builder->selectOneRecordFiltered($arr,1);
			
			//echo $sqlQString;		
			$dbConnection = new DMLFunctions();
			$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function
			
	
			if(mysql_num_rows($message2)!=0) {
				$i=0;
				while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {
					
					$arrRights['add'] = $line[2]==1 ? true : false;
					$arrRights['edit'] = $line[3]==1 ? true : false;
					$arrRights['delete'] = $line[4]==1 ? true : false;
					$arrRights['view'] = $line[5]==1 ? true : false;
					$i++;
				}
				
			 } else {
					$arrRights['add'] =  false;
					$arrRights['edit'] = false;
					$arrRights['delete'] = false;
					$arrRights['view'] = false;
			 }

			 return $arrRights;

		}

}
?>