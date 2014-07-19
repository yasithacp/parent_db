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

class Licenses {

	var $tableName = 'hs_hr_licenses';
	var $LicensesId;
	var $LicensesDesc;
	var $arrayDispList;
	var $singleField;


	function Licenses() {

	}

	function setLicensesId($LicensesId) {

		$this->LicensesId = $LicensesId;

	}

	function setLicensesDesc($LicensesDesc) {

		$this->LicensesDesc = $LicensesDesc;

	}


	function getLicensesId() {

		return $this->LicensesId;

	}

	function getLicensesDesc() {

		return $this->LicensesDesc;

	}


	function getListofLicenses($pageNO,$schStr,$mode,$sortField = 0, $sortOrder = 'ASC') {

		$tableName = 'HS_HR_LICENSES';
		$arrFieldList[0] = 'LICENSES_CODE';
		$arrFieldList[1] = 'LICENSES_DESC';

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

	function countLicenses($schStr,$mode) {

		$tableName = 'HS_HR_LICENSES';
		$arrFieldList[0] = 'LICENSES_CODE';
		$arrFieldList[1] = 'LICENSES_DESC';

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

	function delLicenses($arrList) {

		$tableName = 'HS_HR_LICENSES';
		$arrFieldList[0] = 'LICENSES_CODE';

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


	function addLicenses() {

		if ($this->_isDuplicateName()) {
			throw new LicensesException("Duplicate name", 1);
		}
		
		$tableName = 'HS_HR_LICENSES';

		$this->LicensesId = UniqueIDGenerator::getInstance()->getNextID($tableName, 'LICENSES_CODE', 'LIC');
		$arrFieldList[0] = "'". $this->getLicensesId() . "'";
		$arrFieldList[1] = "'". $this->getLicensesDesc() . "'";

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_insert = 'true';
		$sql_builder->arr_insert = $arrFieldList;

		$sqlQString = $sql_builder->addNewRecordFeature1();

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		 return $message2;

	}

	function updateLicenses() {

		if ($this->_isDuplicateName(true)) {
			throw new LicensesException("Duplicate name", 1);
		}
		
		$this->getLicensesId();
		$arrRecordsList[0] = "'". $this->getLicensesId() . "'";
		$arrRecordsList[1] = "'". $this->getLicensesDesc() . "'";


		$arrFieldList[0] = 'LICENSES_CODE';
		$arrFieldList[1] = 'LICENSES_DESC';

		$tableName = 'HS_HR_LICENSES';

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

	function getUnAssLicensesCodes($id) {

		$sql_builder = new SQLQBuilder();
		$tableName = 'HS_HR_LICENSES';
		$arrFieldList[0] = 'LICENSES_CODE';
		$arrFieldList[1] = 'LICENSES_DESC';

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;
		$sql_builder->field = 'LICENSES_CODE';
		$sql_builder->table2_name = 'HS_HR_EMP_LICENSES';

		$arr[0][0]='EMP_NUMBER';
		$arr[0][1]=$id;
		$sqlQString = $sql_builder->selectFilter($arr, 1 , 1);

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

			return false;

		}
	}

	function filterLicenses($getID) {

		$this->getID = $getID;
		$tableName = 'HS_HR_LICENSES';
		$arrFieldList[0] = 'LICENSES_CODE';
		$arrFieldList[1] = 'LICENSES_DESC';


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

	function getLicensesCodes () {

		$sql_builder = new SQLQBuilder();
		$tableName = 'HS_HR_LICENSES';
		$arrFieldList[0] = 'LICENSES_CODE';
		$arrFieldList[1] = 'LICENSES_DESC';

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->passResultSetMessage();

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

	     	//Handle Exceptions
	     	//Create Logs

	     }

	}
	
	private function _isDuplicateName($update=false) {
		$licenses = $this->filterExistingLicenses();

		if (is_array($licenses)) {
			if ($update) {
				if ($licenses[0][0] == $this->getLicensesId()){
					return false;
				}
			}
			return true;
		}

		return false;
	}
	
	public function filterExistingLicenses() {

		$selectFields[] ='`licenses_code`'; 
        $selectFields[] = '`licenses_desc`';  
	    $selectTable = $this->tableName;

	    $sqlBuilder = new SQLQBuilder();
	    
	    $description = $sqlBuilder->quoteCorrectString($this->getLicensesDesc());
        $selectConditions[] = "`licenses_desc` = {$description}";	       
        
        $query = $sqlBuilder->simpleSelect($selectTable, $selectFields, $selectConditions);

        $dbConnection = new DMLFunctions();
        $result = $dbConnection->executeQuery($query);

        $cnt = 0;

        while ($row = mysql_fetch_array($result, MYSQL_NUM)){
            $existingLicenses[$cnt++] = $row;
        }

        if (isset($existingLicenses)) {
            return  $existingLicenses;
        } else {
            $existingLicenses = '';
            return  $existingLicenses;
        }
	}
}
class LicensesException extends Exception {
}
?>
