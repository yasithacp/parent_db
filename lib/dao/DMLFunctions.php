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


require_once ROOT_PATH . '/lib/dao/MySQLClass.php';
require_once ROOT_PATH . '/lib/confs/Conf.php';

class DMLFunctions {

	public $dbObject; // Databse connection
	private $conf; // Connection configuration
	private $auth;
    private $maxAllowedPacketSize = -1;

	/**
	 * The constructor will take the configuration variables
	 * from the Conf Class (conf.php) and return the reference of 
	 * database connection object
	 */
	public function __construct() {
		$this->conf = new Conf();
		$this->dbObject = new MySQLClass($this->conf);
	}

	/**
	 * This method will take in a SQL Query and execute it using
	 * the sqlQuery() method of the database connection object 
	 * @param String $sql SQL statement
	 * @return ResultResource If query execution is successful the 
	 * result will return otherwise boolean false will return
	 */
	public function executeQuery($sql) {
		$sql = $this->_formatQuery($sql);

		if ($this->dbObject->dbConnect()) {
			$result = $this->dbObject->sqlQuery($sql);
			return $result;
		}

		return false;
	}

	public function getMaxAllowedPacketSize() {

        if ($this->maxAllowedPacketSize == -1) {
            try {
                $result = $this->dbObject->sqlQuery("show variables like 'max_allowed_packet'");
                if ($result && mysql_num_rows($result) == 1) {
                    $dataRow = mysql_fetch_array($result);
                    if (isset($dataRow[1])) {
                        $this->maxAllowedPacketSize = $dataRow[1];        
                    }
                }
            } catch (Exception $e) {
                // ignore if cannot get max_allowed_packet.                
            }
        }

        return $this->maxAllowedPacketSize;
    }

	/**
	 * This method will correct and query when encryption is enabled
	 * to call the encryption methods correctly
	 * @param String $query SQL query to be formatted
	 * @return String Formatted (corrected) SQL query
	 */
	private function _formatQuery($query) {
		if (preg_match('/\'AES_[ED][NE]CRYPT\(/', $query)) {
			$query = preg_replace(array ("/^'AES_ENCRYPT\(/", "/\)'/"), array ('AES_ENCRYPT(', ')'), $query);
		}
		return $query;
	}
}

