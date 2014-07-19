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


 class PropertyReader {
 	private $path;
 	private $properties;
 	private $keys;

 	public function __construct($path) {
 		$this -> path = $path;
 		$this->init();
 	}

 	private function init() {
 		if(!isset($this->path)) {
 			return;
 		}
 		if(!is_file($this->path)) {
				throw new PropertyReaderException($this->path, PropertyReaderException::PROPERTY_FILE_NOT_FOUND);
		}

		$this->initProperties();
 	}

	private function initProperties() {
		$this->properties = array();
		$this->keys = array();

		try {
			$handle = fopen($this->path, "r");

			if($handle == false) {
				throw new PropertyReaderException('', PropertyReaderException::PROPERTY_FILE_NOT_FOUND);
			}

			while(!feof($handle)) {
				$line = fgets($handle);
				$isLine = preg_match('/^#/', $line);

				if($isLine == 0 ) {
					$temp = explode("=", $line);
					if(is_array($temp) && count($temp) == 2) {
						$this -> keys[] = trim($temp[0]);
						$this -> properties[] = trim($temp[1]);
					}
				}
			}
		}catch(Exception $e) {
			throw $e;
		}
	}

	public function getProperty($key) {
		if(!isset($this->properties)) {
			return null;
		}
		try {
			for($i = 0; $i < count($this->keys); $i = $i + 1) {
				if ($this -> keys[$i] == $key) {
					return $this->properties[$i];
				}
			}
		}catch(Exception $e) {

		}
		return null;
	}

	/**
	 * return value array with all the values where key is equal to param
	 */
	public function getPropertyArray($key) {
		if(!isset($this->properties)) {
			return null;
		}
		$values = array();
		try {
			for($i = 0; $i < count($this->keys); $i = $i + 1) {
				if ($this -> keys[$i] == $key) {
					$values[] = $this->properties[$i];
				}
			}
		}catch(Exception $e) {

		}
		return $values;

	}


 }

 class PropertyReaderException extends Exception {
 	const PROPERTY_FILE_NOT_FOUND = 0;
 }
?>
