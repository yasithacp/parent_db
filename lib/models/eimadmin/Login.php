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

class Login {

		var $username;
		var $password;
		var $employeeIdLength;

		function Login() {
			$tmpSysConf = new sysConf();

			$this->employeeIdLength = $tmpSysConf->getEmployeeIdLength();
		}

function filterUser($userName) {
			$sql_builder = new SQLQBuilder();
			$dbConnection = new DMLFunctions();

			$this->username=mysql_real_escape_string($userName);
			$tableName = 'HS_HR_USERS a LEFT JOIN HS_HR_EMPLOYEE b ON (a.EMP_NUMBER = b.EMP_NUMBER)';
			$arrFieldList[0] = 'a.USER_NAME';
			$arrFieldList[1] = 'a.USER_PASSWORD';
			$arrFieldList[2] = 'IFNULL(b.EMP_FIRSTNAME, a.USER_NAME)';
			$arrFieldList[3] = 'a.ID';
			$arrFieldList[4] = 'a.USERG_ID';
			$arrFieldList[5] = 'a.STATUS';
			$arrFieldList[6] = 'LPAD(a.`EMP_NUMBER`, '.$this->employeeIdLength.', 0)';
			$arrFieldList[7] = 'a.IS_ADMIN';
			$arrFieldList[8] = 'b.EMP_STATUS';
			$arrFieldList[9] = 'a.EMP_NUMBER';

			$sql_builder->table_name = $tableName;
			$sql_builder->flg_select = 'true';
			$sql_builder->arr_select = $arrFieldList;

			$sqlQString = $sql_builder->selectOneRecordFiltered($this->username);

			//echo $sqlQString;
			$message2 = $dbConnection -> executeQuery($sqlQString); //Calling the addData() function

			if ( ($message2) && (mysql_num_rows($message2)!=0) ) {
				$i=0;
				while ($line = mysql_fetch_array($message2, MYSQL_NUM)) {

					$arrayDispList[$i][0] = $line[0];
					$arrayDispList[$i][1] = $line[1];
					$arrayDispList[$i][2] = $line[2];
					$arrayDispList[$i][3] = $line[3];
					$arrayDispList[$i][4] = $line[4];
					$arrayDispList[$i][5] = $line[5];
					$arrayDispList[$i][6] = $line[6];
					$arrayDispList[$i][7] = $line[7];
					$arrayDispList[$i][8] = $line[8];
					$arrayDispList[$i][9] = $line[9];
					$i++;
				}
			return $arrayDispList;

			 } else return NULL;
			}
}
?>