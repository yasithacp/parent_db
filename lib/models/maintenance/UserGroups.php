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

class UserGroups {
	var $tableName = 'hs_hr_user_group';

	var $userGroupID;
	var $userGroupName;
	var $userGroupRepDef;
	var $arrayDispList;


	function UserGroups() {
		$this->sql_builder = new SQLQBuilder();
		$this->dbConnection = new DMLFunctions();
	}

	function setUserGroupID($userGroupID){
		$this->userGroupID = $userGroupID;
	}

	function setUserGroupName($userGroupName){
		$this->userGroupName = $userGroupName;
	}

	function setUserGroupRepDef($userGroupRepDef) {
		$this->userGroupRepDef = $userGroupRepDef;
	}

	function getUserGroupID() {
		return $this->userGroupID;
	}

	function getUserGroupName(){
		return $this->userGroupName;
	}

	function getUserGroupRepDef() {
		return $this->userGroupRepDef;
	}

	function getListOfUserGroups($pageNO,$schStr,$mode, $sortField, $sortOrder){

		$arrFieldList[0] = 'userg_id';
		$arrFieldList[1] = 'userg_name';

		$this->sql_builder->table_name = $this->tableName;
		$this->sql_builder->flg_select = 'true';
		$this->sql_builder->arr_select = $arrFieldList;

		$sqlQString =$this->sql_builder->passResultSetMessage($pageNO,$schStr,$mode, $sortField, $sortOrder);

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

	function countUserGroups($schStr,$mode) {

		$arrFieldList[0] = 'userg_id';
		$arrFieldList[1] = 'userg_name';

		$sql_builder = new SQLQBuilder();
		$sql_builder->table_name = $this->tableName;

		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->countResultset($schStr,$mode);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$line = mysql_fetch_array($message2, MYSQL_NUM);

	    	return $line[0];
	}

	function addUserGroups(){

		if ($this->_isDuplicateName()) {
			throw new UserGroupsException("Duplicate name", 1);
		}
		
		$this->userGroupID = UniqueIDGenerator::getInstance()->getNextID($this->tableName, 'userg_id', 'USG');

		$arrFieldList[0] = "'". $this->getUserGroupID() . "'";
		$arrFieldList[1] = "'". $this->getUserGroupName() . "'";
		$arrFieldList[2] = "'". $this->getUserGroupRepDef() . "'";

		$arrRecordsList[0] = 'userg_id';
		$arrRecordsList[1] = 'userg_name';
		$arrRecordsList[2] = 'userg_repdef';

		$this->sql_builder->table_name = $this->tableName;
		$this->sql_builder->flg_insert = 'true';
		$this->sql_builder->arr_insertfield = $arrRecordsList;
		$this->sql_builder->arr_insert = $arrFieldList;

		$sqlQString = $this->sql_builder->addNewRecordFeature2();

		$message2 = $this->dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		 return $message2;
	}

	function updateUserGroups(){
		
		if ($this->_isDuplicateName(true)) {
			throw new UserGroupsException("Duplicate name", 1);
		}
		
		$arrFieldList[0] = "'". $this->getUserGroupID() . "'";
		$arrFieldList[1] = "'". $this->getUserGroupName() . "'";
		$arrFieldList[2] = "'". $this->getUserGroupRepDef() . "'";

	    $arrRecordsList[0] = 'userg_id';
		$arrRecordsList[1] = 'userg_name';
		$arrRecordsList[2] = 'userg_repdef';

		$this->sql_builder->table_name = $this->tableName;
		$this->sql_builder->flg_update = 'true';
		$this->sql_builder->arr_update = $arrRecordsList;
		$this->sql_builder->arr_updateRecList = $arrFieldList;

		$sqlQString = $this->sql_builder->addUpdateRecord1();

		$message2 = $this->dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;
	}

	function filterUserGroups($getID) {

		$this->ID = $getID;
		$arrFieldList[0] = 'userg_id';
		$arrFieldList[1] = 'userg_name';
		$arrFieldList[2] = 'userg_repdef';

		$this->sql_builder->table_name = $this->tableName;
		$this->sql_builder->flg_select = 'true';
		$this->sql_builder->arr_select = $arrFieldList;

		$sqlQString = $this->sql_builder->selectOneRecordFiltered($this->ID);

		$message2 = $this->dbConnection -> executeQuery($sqlQString); //Calling the addData() function

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

			return null;

		}

	}

	function delUserGroups($arrList) {

		$arrFieldList[0] = 'userg_id';

		$this->sql_builder->table_name = $this->tableName;
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

		return $message2;
	}
	
	private function _isDuplicateName($update=false) {
		$userGroups = $this->filterExistingAdminUserGroups();

		if (is_array($userGroups)) {
			if ($userGroups) {
				if ($userGroups[0][0] == $this->getUserGroupID()){
					return false;
				}
			}
			return true;
		}

		return false;
	}
	
	public function filterExistingAdminUserGroups() {

		$selectFields[] ='`userg_id`'; 
        $selectFields[] = '`userg_name`';  
	    $selectTable = $this->tableName;

        $selectConditions[] = "`userg_name` = '".$this->getUserGroupName()."'";	       
         
        $sqlBuilder = new SQLQBuilder();
        $query = $sqlBuilder->simpleSelect($selectTable, $selectFields, $selectConditions);
         
        $dbConnection = new DMLFunctions();
        $result = $dbConnection->executeQuery($query);

        $cnt = 0;

        while ($row = mysql_fetch_array($result, MYSQL_NUM)){
            $existingUserGroups[$cnt++] = $row;
        }

        if (isset($existingUserGroups)) {
            return $existingUserGroups;
        } else {
             $existingUserGroups = '';
            return $existingUserGroups;
        }
	}
}
class UserGroupsException extends Exception {
}
?>