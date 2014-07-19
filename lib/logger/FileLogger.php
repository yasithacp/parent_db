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
 require_once 'Auditor.php';


 class FileLogger extends Auditor {
	private $filePath;
	private $rolling;

	const DEFAULT_FILE_PATH = '';
	const DEFAULT_FILE = 'traceLogger.log';

	const ROLLING_NEVER = 'never';
	const ROLLING_DAILY = 'daily';
	const ROLLING_MONTHLY = 'monthly';

	const FILE_PATH = 'path';
	const FILE_NAME = 'fileName';
	const ROLLING = 'rolling';

	const DEFAULT_ROLLING = self::ROLLING_NEVER;

	public function __construct($name, $propertyReader) {
		parent::__construct($name, $propertyReader);
		$this->initProperties($propertyReader);
	}

	private function initProperties($propertyReader) {

		$filePath = $propertyReader->getProperty(Auditor::LOG . '.' . parent::getName() . '.' . self::FILE_PATH);
		if(isset($filePath) && is_dir($filePath)) {
			$this->filePath = $filePath;
		}else {
			$this->filePath = dirname(__FILE__) . '/' . self::DEFAULT_FILE_PATH;
		}

		$rolling = $propertyReader->getProperty(Auditor::LOG . '.' . parent::getName() . '.' . self::ROLLING);

		if(isset($rolling) && $rolling != '') {
			$this->rolling = $rolling;
		}else {
			$this->rolling = self::DEFAULT_ROLLING;
		}

	}

	private function getFileName() {
		switch($this->rolling) {
			case self::ROLLING_NEVER :
				return $this->filePath . self::DEFAULT_FILE;
				break;
			case self::ROLLING_DAILY :
				$fileName = date('Y-m-d', time()) . '-' . self::DEFAULT_FILE;
				return $this->filePath. $fileName;
				break;
			case self::ROLLING_MONTHLY :
				$fileName = date('Y-m', time()) . '-' . self::DEFAULT_FILE;
				return $this->filePath . $fileName;
				break;
			default :
				return $this->filePath . self::DEFAULT_FILE;
				break;
		}
	}

	private function getFileHandler() {
		$fileName = $this->getFileName();
		if(is_file($fileName)) {
			try {
				return @fopen($fileName, "a");
			}catch (Exception $e) {

			}

		}else {
			try {
				return @fopen($fileName, "a");
			}catch(Exception $e) {

			}
		}
	}

	public function info($obj) {
		if(parent::getLevel() > self::INFO) {
			return false;
		}

		$msg = "\r\nINFO - " . date('Y-m-d G:i', time()) . " - " . $obj;

		try {
			$handler = $this->getFileHandler();
			@fwrite($handler, $msg);
			@fclose($handler);
			return true;
		}catch(Exception $e) {
			return false;
		}
	}

	public function debug($obj) {
		if(parent::getLevel() > self::DEBUG) {
			return false;
		}

		$msg = "\r\nDEBUG - " . date('Y-m-d G:i', time()) . " - " . $obj;

		try {
			$handler = $this->getFileHandler();
			@fwrite($handler, $msg);
			@fclose($handler);
			return true;
		}catch(Exception $e) {
			return false;
		}
	}

	public function warning($obj) {
		if(parent::getLevel() > self::WARNING) {
			return false;
		}

		$msg = "\r\nWARN - " . date('Y-m-d G:i', time()) . " - " . $obj;

		try {
			$handler = $this->getFileHandler();
			@fwrite($handler, $msg);
			@fclose($handler);
			return true;
		}catch(Exception $e) {
			return false;
		}
	}

	public function error($obj) {
		if(parent::getLevel() > self::ERROR) {
			return false;
		}

		$msg = "\r\nWARN - " . date('Y-m-d G:i', time()) . " - " . $obj;

		try {
			$handler = $this->getFileHandler();
			@fwrite($handler, $msg);
			@fclose($handler);
			return true;
		}catch(Exception $e) {
			return false;
		}
	}
 }
?>
