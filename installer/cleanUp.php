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



// Cleaning up
function connectDB() {

	if(!@mysql_connect($_SESSION['dbInfo']['dbHostName'].':'.$_SESSION['dbInfo']['dbHostPort'], 		$_SESSION['dbInfo']['dbUserName'], $_SESSION['dbInfo']['dbPassword'])) {
		$_SESSION['error'] =  'Database Connection Error!';
		return false;
	}
return true;
}

function cleanUp() {
	
	if ($_SESSION['cMethod'] == 'new' || $_SESSION['dbCreateMethod'] == 'new') {

		if (!connectDB()) {
			return false;
		}
	
		if (isset($_SESSION['dbInfo']['dbOHRMUserName'])) {
			$query = dropUser();
		}
	
		$query[0] = dropDB();
	
		$sucExec = $query;
		$overall = true;
	
		for ($i=0;  $i < count($query); $i++) {
			$sucExec[$i] = mysql_query($query[$i]);
	
			if (!$sucExec[$i]) {
				$overall = false;
			}
		}
	
		if (!$overall) {
			connectDB();
			for ($i=0;  $i < count($query); $i++) {
				if (!$sucExec[$i]) {
					$sucExec[$i] = mysql_query($query[$i]);
				}
	
				if (!$sucExec[$i]) {
					$overall = false;
				}
			}
		}
	
	}

	$sucExec[] = delConf();

return $sucExec;
}

function dropDB() {
	$query = "DROP DATABASE ". $_SESSION['dbInfo']['dbName'];
return $query;
}

function dropUser() {
	$tables = array('`user`', '`db`', '`tables_priv`', '`columns_priv`');

	foreach ($tables as $table) {
		$query[] = "DELETE FROM $table WHERE `User` = '".$_SESSION['dbInfo']['dbOHRMUserName']."' AND (`Host` = 'localhost' OR `Host` = '%')";
	}

return $query;
}

function delConf() {
	$filename = ROOT_PATH . '/lib/confs/Conf.php';

return @unlink($filename);
}


$_SESSION['cleanProgress'] = cleanUp();

if (isset($_SESSION['UNISTALL']) && $_SESSION['cleanProgress']) {
	unset($_SESSION['UNISTALL']);

}

?>
