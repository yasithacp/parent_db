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
 require_once 'AuditorFactory.php';


 class Logger {
 	private static $instance;
	private $propertyReader;
	private $auditors;

	const DEFAULT_PROPERTY_FILE = '/logger.properties';

	private function __construct($propertyFilePath = null) {
		if(!isset($propertyFilePath) || $propertyFilePath == '') {
			$propertyFilePath = dirname(__FILE__) . self::DEFAULT_PROPERTY_FILE;
		}
		$this->init($propertyFilePath);
	}

	public static function getInstance($propertyFilePath = null) {
//		if(!isset($this->instance)) {
//			$this->instance = new Logger($propertyFilePath);
//		}

		return new Logger();
	}

	private function init($propertyFilePath) {
		$this->auditors = array();

		$this->propertyReader = new PropertyReader($propertyFilePath);
		$auditorNames = $this->propertyReader->getPropertyArray(Auditor::LOG . '.' . Auditor::LOG_NAME);
		$this->initAuditors($auditorNames);
	}

	private function initAuditors($auditorNames) {
		if(isset($auditorNames) && is_array($auditorNames)) {

			$factory = AuditorFactory::getInstance($this->propertyReader);

			foreach($auditorNames as $name) {
				$auditor = $factory->getAuditor($name);
				if(isset($auditor)) {
					$this->auditors[] = $auditor;
				}
			}
		}
	}

	public function info($obj) {
		if(!isset($this->auditors)) {
			return null;
		}

		foreach($this->auditors as $auditor) {
			$auditor -> info($obj);
		}
	}

	public function debug($obj) {
		if(!isset($this->auditors)) {
			return null;
		}

		foreach($this->auditors as $auditor) {
			$auditor -> debug($obj);
		}
	}

	public function warn($obj) {
		if(!isset($this->auditors)) {
			return null;
		}

		foreach($this->auditors as $auditor) {
			$auditor -> warn($obj);
		}
	}

	public function error($obj) {
		if(!isset($this->auditors)) {
			return null;
		}

		foreach($this->auditors as $auditor) {
			$auditor -> error($obj);
		}
	}
 }
?>
