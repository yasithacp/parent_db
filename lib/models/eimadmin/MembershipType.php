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

class MembershipType {

	var $tableName = 'hs_hr_membership_type';
	var $memId;
	var $memDesc;
	var $arrayDispList;
	var $singleField;


	function MembershipType() {

	}

	function setMemId($memId) {

		$this->memId = $memId;

	}

	function setMemDescription($memDesc) {

		$this->memDesc = $memDesc;

	}


	function getMemId() {

		return $this->memId;

	}

	function getMemDescription() {

		return $this->memDesc;

	}

	function getListofMembershipType($pageNO,$schStr,$mode, $sortField = 0, $sortOrder = 'ASC') {

		$tableName = 'HS_HR_MEMBERSHIP_TYPE';
		$arrFieldList[0] = 'MEMBTYPE_CODE';
		$arrFieldList[1] = 'MEMBTYPE_NAME';

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

	function countMembershipType($schStr,$mode) {

		$tableName = 'HS_HR_MEMBERSHIP_TYPE';
		$arrFieldList[0] = 'MEMBTYPE_CODE';
		$arrFieldList[1] = 'MEMBTYPE_NAME';

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

	function delMembershipType($arrList) {

		$tableName = 'HS_HR_MEMBERSHIP_TYPE';
		$arrFieldList[0] = 'MEMBTYPE_CODE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_delete = 'true';
		$sql_builder->arr_delete = $arrFieldList;

		$sqlQString = $sql_builder->deleteRecord($arrList);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;
	}

	function filterGetMembershipTypeInfo($getID) {

		$this->getID = $getID;
		$tableName = 'HS_HR_MEMBERSHIP_TYPE';
		$arrFieldList[0] = 'MEMBTYPE_CODE';
		$arrFieldList[1] = 'MEMBTYPE_NAME';

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

	    	$arrayDispList[$i][0] = $line[0]; // Province Code
	    	$arrayDispList[$i][1] = $line[1]; // Provicne Name

	    	$i++;

	     }

	     if (isset($arrayDispList)) {

			return $arrayDispList;

		} else {

			$arrayDispList = '';
			return $arrayDispList;

		}

	}

	function filterNotEqualMembershipInfo($getID) {

		$this->getID = $getID;

		$tableName = 'HS_HR_MEMBERSHIP_TYPE';
		$arrFieldList[0] = 'MEMBTYPE_CODE';
		$arrFieldList[1] = 'MEMBTYPE_NAME';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->filterNotEqualRecordSet($this->getID);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {

	    	$arrayDispList[$i][0] = $line[0]; // Province Code
	    	$arrayDispList[$i][1] = $line[1]; // Provicne Name

	    	$i++;

	     }

	     if (isset($arrayDispList)) {

			return $arrayDispList;

		} else {

			$arrayDispList = '';
			return $arrayDispList;

		}

	}

	function getMembershipTypeCodes() {

		$tableName = 'HS_HR_MEMBERSHIP_TYPE';
		$arrFieldList[0] = 'MEMBTYPE_CODE';
		$arrFieldList[1] = 'MEMBTYPE_NAME';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->passResultSetMessage(0, '', -1, 1);

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

	function addMembershipType() {

		if ($this->_isDuplicateName()) {
			throw new MembershipTypeException("Duplicate name", 1);
		}
		
		$tableName = 'hs_hr_membership_type';

		$this->getMemId();
		$this->memId = UniqueIDGenerator::getInstance()->getNextID($tableName, 'membtype_code', 'MEM');
		$arrFieldList[0] = "'". $this->getMemId() . "'";
		$arrFieldList[1] = "'". $this->getMemDescription() . "'";


		//$arrFieldList[0] = 'MEMBTYPE_CODE';
		//$arrFieldList[1] = 'MEMBTYPE_NAME';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_insert = 'true';
		$sql_builder->arr_insert = $arrFieldList;

		$sqlQString = $sql_builder->addNewRecordFeature1();

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		 return $message2;

	}

	function updateMembershipType() {

		if ($this->_isDuplicateName(true)) {
			throw new MembershipTypeException("Duplicate name", 1);
		}
		
		$this->getMemId();
		$arrRecordsList[0] = "'". $this->getMemId() . "'";
		$arrRecordsList[1] = "'". $this->getMemDescription() . "'";
		$arrFieldList[0] = 'MEMBTYPE_CODE';
		$arrFieldList[1] = 'MEMBTYPE_NAME';

		$tableName = 'HS_HR_MEMBERSHIP_TYPE';

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


	function filterMembershipType($getID) {

		$this->getID = $getID;
		$tableName = 'HS_HR_MEMBERSHIP_TYPE';
		$arrFieldList[0] = 'MEMBTYPE_CODE';
		$arrFieldList[1] = 'MEMBTYPE_NAME';

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

	private function _isDuplicateName($update=false) {
		$membershipTypes = $this->filterExistingMembershipTypes();

		if (is_array($membershipTypes)) {
			if ($update) {
				if ($membershipTypes[0][0] == $this->getMemId()){
					return false;
				}
			}
			return true;
		}

		return false;
	}
	
	public function filterExistingMembershipTypes() {

		$selectFields[] ='`membtype_code`'; 
        $selectFields[] = '`membtype_name`';  
	    $selectTable = $this->tableName;

	    $sqlBuilder = new SQLQBuilder();
	    $description = $sqlBuilder->quoteCorrectString($this->getMemDescription());
        $selectConditions[] = "`membtype_name` = {$description}";

        $query = $sqlBuilder->simpleSelect($selectTable, $selectFields, $selectConditions);
         
        $dbConnection = new DMLFunctions();
        $result = $dbConnection->executeQuery($query);

        $cnt = 0;

        while ($row = mysql_fetch_array($result, MYSQL_NUM)){
            $existingMembershipTypes[$cnt++] = $row;
        }

        if (isset( $existingMembershipTypes)) {
            return   $existingMembershipTypes;
        } else {
             $existingMembershipTypes = '';
            return   $existingMembershipTypes;
        }
	}
}
class MembershipTypeException extends Exception {
}
?>
