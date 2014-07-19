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

class JobTitEmpStat {

	var $tableName = 'HS_HR_JOBTIT_EMPSTAT';

	var $jobTitId;
	var $empStatId;
	var $arrayDispList;
	var $singleField;

	function JobTitEmpStat() {
	}

	function setJobTitId($jobTitId) {
		$this->jobTitId = $jobTitId;
	}

	function setEmpStatId($empStatId) {
		$this->empStatId = $empStatId;
	}

	function getJobTitId() {
		return $this->jobTitId;
	}

	function getEmpStatId() {
		return $this->empStatId;
	}

	function delJobTitEmpStat($arrList) {

		$tableName = 'HS_HR_JOBTIT_EMPSTAT';
		$arrFieldList[0] = 'JOBTIT_CODE';
		$arrFieldList[1] = 'ESTAT_CODE';

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

	function addJobTitEmpStat() {

		$arrFieldList[0] = "'". $this->getJobTitId() . "'";
		$arrFieldList[1] = "'". $this->getEmpStatId() . "'";

		$tableName = 'HS_HR_JOBTIT_EMPSTAT';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_insert = 'true';
		$sql_builder->arr_insert = $arrFieldList;

		$sqlQString = $sql_builder->addNewRecordFeature1();

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		 return $message2;
	}

	function updateJobTitEmpStat() {

		$arrRecordsList[0] = "'". $this->getJobTitId() . "'";
		$arrRecordsList[1] = "'". $this->getEmpStatId() . "'";

		$arrFieldList[0] = 'JOBTIT_CODE';
		$arrFieldList[1] = 'ESTAT_CODE';

		$tableName = 'HS_HR_JOBTIT_EMPSTAT';

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

	function filterJobTitEmpStat($getID) {

		$arrFieldList[0] = 'JOBTIT_CODE';
		$arrFieldList[1] = 'ESTAT_CODE';

		$tableName = 'HS_HR_JOBTIT_EMPSTAT';

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

	function getUnAssEmpStat($jobtit) {

		$sql_builder = new SQLQBuilder();

		$tableName = 'hs_hr_empstat';
		$arrFieldList[0] = 'ESTAT_CODE';
		$arrFieldList[1] = 'ESTAT_NAME';

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;
		$sql_builder->field = 'ESTAT_CODE';
		$sql_builder->table2_name= 'HS_HR_JOBTIT_EMPSTAT';
		$arr[0][0]= 'JOBTIT_CODE';
		$arr[0][1]= $jobtit;

		$sqlQString = $sql_builder->selectFilter($arr);

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

	function getAssEmpStat($jobtit) {

		$sql_builder = new SQLQBuilder();

		$sqlQString = $sql_builder->getAssEmpStat($jobtit);

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

	public function getAllEmpStats($value) {

        $tableName="`hs_hr_jobtit_empstat` a,`hs_hr_empstat` b";                
        $arrFieldList[0] = "a.`estat_code`";
        $arrFieldList[1] = "b.`estat_name`";
		
		$sql_builder = new SQLQBuilder();
		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

        $selectConditions[] = "a.`jobtit_code`='" . mysql_real_escape_string($value) . "'";
        $selectConditions[] = "a.`estat_code`=b.`estat_code`";
         $sqlQString='SELECT ' . $arrFieldList[0] . ',' . $arrFieldList[1] . ' FROM ' . $tableName . ' WHERE '
                 . $selectConditions[0] . ' AND ' . $selectConditions[1] ;

         $dbConnection = new DMLFunctions();
         $result = $dbConnection -> executeQuery($sqlQString);
         $num=$dbConnection->dbObject->numberOfRows($result);
				
                if ($num<1) { // if job title already selected

                    $tableName = 'hs_hr_empstat';
                    $arrFieldList[0] = 'ESTAT_CODE';
                    $arrFieldList[1] = 'ESTAT_NAME';

                    $sqlQString = 'SELECT ' . $arrFieldList[0] . ', ' . $arrFieldList[1] . ' FROM ' . $tableName. ' ORDER BY '.$arrFieldList[1]. ' ASC';
                    $result = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function
                
                }
				
		$i=0;

		while ($line = $dbConnection->dbObject->getArray($result)) {

                    $arrayDispList[$i][0] = $line[0];
                    $arrayDispList[$i][1] = $line[1];
                    $i++;
                }

                if (isset($arrayDispList)) {

                    return $arrayDispList;

                } else {
	     	
                }
	}
}
?>
