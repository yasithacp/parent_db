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

class SalCurDet {

	var $salGrdId;
	var $currId;
	var $minSal;
	var $maxSal;
	var $stepSal;

	var $arrayDispList;
	var $singleField;

	function SalCurDet() {

	}

	function setSalGrdId($salGrdId) {

	$this->salGrdId=$salGrdId;
	}

	function setCurrId($currId) {

	$this->currId=$currId;
	}

	function setMinSal($minSal) {

	$this->minSal=$minSal;
	}

	function setMaxSal($maxSal) {

	$this->maxSal=$maxSal;
	}

	function setStepSal($stepSal) {

	$this->stepSal=$stepSal;
	}

	function getSalGrdId() {

	return $this->salGrdId;
	}

	function getCurrId() {

	return $this->currId;
	}

	function getMinSal() {

	return $this->minSal;
	}

	function getMaxSal() {

	return $this->maxSal;
	}

	function getStepSal() {

	return $this->stepSal;
	}

	function delSalCurDet($arrList) {

		$tableName = 'HS_PR_SALARY_CURRENCY_DETAIL';
		$arrFieldList1[0] = 'SAL_GRD_CODE';
		$arrFieldList1[1] = 'CURRENCY_ID';

		$sql_builder1 = new SQLQBuilder();

		$sql_builder1->table_name = $tableName;
		$sql_builder1->flg_delete = 'true';
		$sql_builder1->arr_delete = $arrFieldList1;

		$sqlDelString = $sql_builder1->deleteRecord($arrList);

		//echo $sqlQString;
		$dbConnection1 = new DMLFunctions();
		$message2 = $dbConnection1 -> executeQuery($sqlDelString); //Calling the addData() function
	}

	function addSalCurDet() {

		$arrFieldList[0] = 'SAL_GRD_CODE';
		$arrFieldList[1] = 'CURRENCY_ID';
		$arrFieldList[2] = 'SALCURR_DTL_MINSALARY';
		$arrFieldList[3] = 'SALCURR_DTL_MAXSALARY';
		$arrFieldList[4] = 'SALCURR_DTL_STEPSALARY';

		$arrRecordList[0] = "'". $this->getSalGrdId() . "'";
		$arrRecordList[1] = "'". $this->getCurrId() . "'";
		$minSal = $this->getMinSal() == '' ? 'null' : $this->getMinSal();
		$arrRecordList[2] = $minSal;
		$maxSal = $this->getMaxSal() == '' ? 'null' : $this->getMaxSal();
		$arrRecordList[3] = $maxSal;
		$stepSal = $this->getStepSal() == '' ? 'null' : $this->getStepSal();
		$arrRecordList[4] = $stepSal;


		$tableName = 'HS_PR_SALARY_CURRENCY_DETAIL';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_insert = 'true';
		$sql_builder->arr_insert = $arrRecordList;
		$sql_builder->arr_insertfield = $arrFieldList;

		$sqlQString = $sql_builder->addNewRecordFeature2();

		$dbConnection = new DMLFunctions();

		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		 return $message2;
	}

	function updateSalCurDet() {

		$arrRecordsList[0] = "'". $this->getSalGrdId() . "'";
		$arrRecordsList[1] = "'". $this->getCurrId() . "'";
		$minSal = $this->getMinSal() == '' ? 'null' : $this->getMinSal();
		$arrRecordsList[2] = $minSal;
		$maxSal = $this->getMaxSal() == '' ? 'null' : $this->getMaxSal();
		$arrRecordsList[3] = $maxSal;
		$stepSal = $this->getStepSal() == '' ? 'null' : $this->getStepSal();
		$arrRecordsList[4] = $stepSal;

		$tableName = 'HS_PR_SALARY_CURRENCY_DETAIL';
        	$arrFieldList[0] = 'SAL_GRD_CODE';
		$arrFieldList[1] = 'CURRENCY_ID';
		$arrFieldList[2] = 'SALCURR_DTL_MINSALARY';
		$arrFieldList[3] = 'SALCURR_DTL_MAXSALARY';
		$arrFieldList[4] = 'SALCURR_DTL_STEPSALARY';

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

	function filterSalCurDet($getID) {

		$this->getID = $getID;
		$tableName = 'HS_PR_SALARY_CURRENCY_DETAIL';
        $arrFieldList[0] = 'SAL_GRD_CODE';
		$arrFieldList[1] = 'CURRENCY_ID';
		$arrFieldList[2] = 'SALCURR_DTL_MINSALARY';
		$arrFieldList[3] = 'SALCURR_DTL_MAXSALARY';
		$arrFieldList[4] = 'SALCURR_DTL_STEPSALARY';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->selectOneRecordFiltered($this->getID,1);

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {

			for($c=0;count($arrFieldList)>$c;$c++)
			   $arrayDispList[$i][$c] = $line[$c];

            $arrayDispList[$i][5] = $this->getMinSalPg($getID);
            $arrayDispList[$i][6] = $this->getMaxSalPg($getID);

	    	$i++;

	     }

	     if (isset($arrayDispList)) {

			return $arrayDispList;

		} else {

			$arrayDispList = '';
			return $arrayDispList;

		}

	}

function getUnAssSalCurDet($salgrd) {

		$sql_builder = new SQLQBuilder();
		$tableName = 'HS_HR_CURRENCY_TYPE';
		$arrFieldList[0] = 'CURRENCY_ID';
		$arrFieldList[1] = 'CURRENCY_NAME';

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;
		$sql_builder->field = 'CURRENCY_ID';
		$sql_builder->table2_name= 'HS_PR_SALARY_CURRENCY_DETAIL';
		$arr[0][0]= 'SAL_GRD_CODE';
		$arr[0][1]= $salgrd;

		$sqlQString = $sql_builder->selectFilter($arr,'',1);

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

function getAssSalCurDet($salgrd) {

		$sql_builder = new SQLQBuilder();

		$sqlQString = $sql_builder->getCurrencyAssigned($salgrd);

		//echo $sqlQString;
		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		$i=0;

		 while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {

	    	$arrayDispList[$i][0] = $line[0];
	    	$arrayDispList[$i][1] = $line[1];
	    	$arrayDispList[$i][2] = $line[2];
	    	$arrayDispList[$i][3] = $line[3];
	    	$arrayDispList[$i][4] = $line[4];

	    	$i++;
	    }

	    if (isset($arrayDispList)) {

			return $arrayDispList;

		} else {

			$arrayDispList = '';
			return $arrayDispList;

		}

	}

    public function getMaxSalPg($getID){

        $sal_grd_code = $getID[0];
        $currency_id = $getID[1];

        $sql = "SELECT MAX(`ebsal_basic_salary`) " .
                "FROM `hs_hr_emp_basicsalary` " .
                "WHERE `sal_grd_code`='$sal_grd_code' AND `currency_id` = '$currency_id' ";

        $dbConnection = new DMLFunctions();
        $message = $dbConnection->executeQuery($sql);
        $res = mysql_fetch_array($message, MYSQL_NUM);
        return $res[0];
    }

    public function getMinSalPg($getID){

        $sal_grd_code = $getID[0];
        $currency_id = $getID[1];

        $sql = "SELECT MIN(`ebsal_basic_salary`) " .
                "FROM `hs_hr_emp_basicsalary` " .
                "WHERE `sal_grd_code`='$sal_grd_code' AND `currency_id` = '$currency_id' ";

        $dbConnection = new DMLFunctions();
        $message = $dbConnection->executeQuery($sql);
        $res = mysql_fetch_array($message, MYSQL_NUM);
        return $res[0];
    }
}

?>
