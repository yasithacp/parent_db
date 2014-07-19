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


require_once ROOT_PATH.'/lib/dao/DMLFunctions.php';
require_once ROOT_PATH.'/lib/dao/SQLQBuilder.php';
require_once ROOT_PATH.'/lib/confs/sysConf.php';
require_once ROOT_PATH.'/lib/common/CommonFunctions.php';
require_once ROOT_PATH.'/lib/models/eimadmin/export/CSVExportPlugin.php';
require_once ROOT_PATH.'/lib/models/eimadmin/export/CustomizableCSVExport.php';

class CSVExport {

	/**
	 * Class Attributes
	 */
	private $exportPlugins;
	private $pluginDir;

	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		$this->pluginDir = ROOT_PATH . '/lib/models/eimadmin/export/plugins';
		$this->exportPlugins = $this->_getListOfAvailablePlugins();

		/* Get user defined exports - defined via the UI.*/
		$customExports = CustomExport::getCustomExportList();
		foreach ($customExports as $export) {

			/* We don't check for any conflicts in key since, plugins have the class name as key*/
			$this->exportPlugins[$export->getId()] = $export->getName();
		}
	}

	/**
	 * Get defined export types
	 *
	 */
	public function getDefinedExportTypes() {
		return $this->exportPlugins;
	}

	/**
	 * Do the data export
	 *
	 * @param string $type Export type
	 */
	 public function exportData($type) {

		$exportPlugin = $this->_getPlugin($type);

		$fileName = $exportPlugin->getName();
		$csvContents = $exportPlugin->getHeader() . "\n" . $exportPlugin->getCSVData();

		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false);
		header("Content-Type: text/csv");
		header('Content-Disposition: attachment; filename="' . $fileName . '.csv";');
		header("Content-Transfer-Encoding: none");
		//header("Content-Length: " .strlen($csvContents));

		echo $csvContents;

	 }

	 /**
	  * Get list of available csv export plugins
	  */
	 protected function _getListOfAvailablePlugins() {

		$plugins = array();

		if (is_dir($this->pluginDir)) {

			$handle = @opendir($this->pluginDir);
			if ($handle) {

				$oldDir = getcwd();
				chdir($this->pluginDir);

				while (false !== ($file = readdir($handle))) {

					if (is_file($file)) {
						$fileInfo = pathinfo($file);
						$className = $fileInfo['basename'];
						$extension = $fileInfo['extension'];
						if (!empty($extension)) {
							$className = str_replace("." . $extension, "", $className);
						}

						/* Skip any unit test classes (ending with Test) */
						if (!(strrpos($className, "Test") === strlen($className) - 4)) {

							require_once $this->pluginDir . "/" . $file;
							$object = new $className;

							if ($object instanceof CSVExportPlugin) {
								$pluginName = $object->getName();
								$plugins[$className] = $pluginName;
							}
						}
					}
    			}
				closedir($handle);
				chdir($oldDir);
			}
		}
		return $plugins;
	 }

	 private function _getPlugin($type) {

		/* If the type is an ID, get the customizable CSV Export class */
		if (CommonFunctions::isValidId($type)) {
			$object = new CustomizableCSVExport($type);
		} else {
			require_once $this->pluginDir . "/" . $type . ".php";
			$object = new $type;
		}
		return $object;
	 }
}
?>
