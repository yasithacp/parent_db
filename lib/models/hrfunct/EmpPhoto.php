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

class EmpPicture {

	var $tableName = 'HS_HR_EMP_PICTURE';

	var $empId;
	var $empPicture;
	var $empPicFilename;
	var $empPicSize;
	var $empPicType;
	var $arrayDispList;
	var $singleField;

	function EmpPicture() {

	}

	function setEmpId($empId) {

	$this->empId=$empId;
	}

	function setEmpPicture($empPicture) {

		$this->empPicture = $empPicture;
	}

	function setEmpFilename($empPicFilename) {

		$this->empPicFilename = $empPicFilename;
	}

	function setEmpPicType($empPicType) {

		$this->empPicType = $empPicType;
	}

	function setEmpPicSize($empPicSize) {

		$this->empPicSize = $empPicSize;
	}

	function getEmpId() {

	return $this->empId;
	}

	function getEmpPicture() {

		return $this->empPicture;
	}

	function getEmpFilename() {

		return $this->empPicFilename;
	}

	function getEmpPicType() {

		return $this->empPicType;
	}

	function getEmpPicSize() {

		return $this->empPicSize;
	}

////

	function delEmpPic($arrList) {

		$tableName = 'HS_HR_EMP_PICTURE';
		$arrFieldList[0] = 'EMP_NUMBER';

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

	function addEmpPic() {

        $dbConnection = new DMLFunctions();

        $this->_escapeFields();
		$arrFieldList[0] = "'". $this->getEmpId() . "'";
		$arrFieldList[1] = "'". $this->getEmpPicture() . "'";
		$arrFieldList[2] = "'". $this->getEmpFilename() . "'";
		$arrFieldList[3] = "'". $this->getEmpPicType() . "'";
		$arrFieldList[4] = "'". $this->getEmpPicSize() . "'";

		$tableName = 'HS_HR_EMP_PICTURE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_insert = 'true';
		$sql_builder->arr_insert = $arrFieldList;

        /* We pass false to $quoteCorrect field, since it can corrupt the picture binary data */
		$sqlQString = $sql_builder->addNewRecordFeature1(false);

		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		 return $message2;
	}

	function updateEmpPic() {

        $dbConnection = new DMLFunctions();

        $this->_escapeFields();
		$arrRecordsList[0] = "'". $this->getEmpId() . "'";
		$arrRecordsList[1] = "'". $this->getEmpPicture() . "'";
		$arrRecordsList[2] = "'". $this->getEmpFilename() . "'";
		$arrRecordsList[3] = "'". $this->getEmpPicType() . "'";
		$arrRecordsList[4] = "'". $this->getEmpPicSize() . "'";

		$tableName = 'HS_HR_EMP_PICTURE';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'EPIC_PICTURE';
		$arrFieldList[2] = 'EPIC_FILENAME';
		$arrFieldList[3] = 'EPIC_TYPE';
		$arrFieldList[4] = 'EPIC_FILE_SIZE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_update = 'true';
		$sql_builder->arr_update = $arrFieldList;
		$sql_builder->arr_updateRecList = $arrRecordsList;

        /* We pass false to $quoteCorrect field, since it can corrupt the picture binary data */
		$sqlQString = $sql_builder->addUpdateRecord1(0, false);

		$dbConnection = new DMLFunctions();
		$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

		return $message2;
	}


	function filterEmpPic($getID) {

		$tableName = 'HS_HR_EMP_PICTURE';
		$arrFieldList[0] = 'EMP_NUMBER';
		$arrFieldList[1] = 'EPIC_PICTURE';
		$arrFieldList[2] = 'EPIC_FILENAME';
		$arrFieldList[3] = 'EPIC_TYPE';
		$arrFieldList[4] = 'EPIC_FILE_SIZE';

		$sql_builder = new SQLQBuilder();

		$sql_builder->table_name = $tableName;
		$sql_builder->flg_select = 'true';
		$sql_builder->arr_select = $arrFieldList;

		$sqlQString = $sql_builder->selectOneRecordFiltered($getID);

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

    private function _escapeFields() {

        /* The other fields are not user entered, so no need to strip slashes even
         * if magic_quotes is set
         */
        if (get_magic_quotes_gpc()) {
            $this->empPicFilename = stripslashes($this->empPicFilename);
            $this->empId = stripslashes($this->empId);
        }

        /* escape all fields, in case there is a way to inject values */
        $this->empPicture = mysql_real_escape_string($this->empPicture);
        $this->empPicFilename = mysql_real_escape_string($this->empPicFilename);
        $this->empPicSize = mysql_real_escape_string($this->empPicSize);
        $this->empPicType = mysql_real_escape_string($this->empPicType);
    }
}

?>
