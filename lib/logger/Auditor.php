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


require_once 'PropertyReader.php';

class Auditor{
	private $name;
	private $level;

	const LOG = 'logger';
	const LOG_NAME = 'name';
	const LOG_TYPE = 'type';
	const LOG_LEVEL ='level';

	const DEFAULT_LEVEL = 1;

	const LEVE_INFO ='info';
	const LEVEL_DEBUG = 'debug';
	const LEVEL_WARNING ='warn';
	const LEVEL_ERROR = 'error';

	const INFO = 0;
	const DEBUG = 1;
	const WARNING = 2;
	const ERROR = 3;

	const TYPE_FILELOGGER = 'fileLogger';

	public function __construct($name, $propertyReader) {
		$this -> name = $name;
		$this -> initProperties($propertyReader);
	}

	private function initProperties($propertyReader) {
		//set level
		if(isset($propertyReader)) {
			$level = $propertyReader->getProperty(self::LOG . '.' . $this->name . '.' . self::LOG_LEVEL);

			if(isset($level) && $level != '') {
				$this->setLevel($level);
			}else {
				$this -> level = self::DEFAULT_LEVEL;
			}
		}else {
			$this -> level = self::DEFAULT_LEVEL;
		}
	}

	private function setLevel($level) {
		switch($level) {
			case self::LEVE_INFO :
				$this->level = self::INFO;
				break;
			case self::LEVEL_DEBUG :
				$this->level = self::DEBUG;
				break;
			case self::LEVEL_WARNING :
				$this->level = self::WARNING;
				break;
			case self::LEVEL_ERROR :
				$this->level = self::ERROR;
				break;
			default :
				$this->level = self::DEBUG;
				break;
		}
	}

	public static function getType($name, $propertyReader) {
 		try {
			$type = $propertyReader-> getProperty(self::LOG . '.' . $name . '.' . self::LOG_TYPE);
			return $type;
 		}catch(Exception $e) {

 		}
 		return null;
 	}

	public function getName() {
		return $this->name;
	}

	public function getLevel() {
		return $this->level;
	}

	public function info($obj) {
		return false;
	}

	public function debug($obj) {
		return false;
	}

	public function warning($obj) {
		return false;
	}

	public function error($obj) {
		return false;
	}
}
?>
