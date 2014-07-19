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

class PimCsvImportForm extends BaseForm {

	private $pimCsvDataImportService;
	
	public function getPimCsvDataImportService() {
		if (is_null($this->pimCsvDataImportService)) {
			$this->pimCsvDataImportService = new PimCsvDataImportService();
		}
		return $this->pimCsvDataImportService;
	}

	public function configure() {

		$this->setWidgets(array(
		    'csvFile' => new sfWidgetFormInputFile(),
		));

		$this->setValidators(array(
		    'csvFile' => new sfValidatorFile(array('required' => false)),
		));
		$this->widgetSchema->setNameFormat('pimCsvImport[%s]');
	}

	public function save() {

		$file = $this->getValue('csvFile');
		if (!empty($file)) {
			if (!($this->isValidResume($file))) {
				$resultArray['messageType'] = 'warning';
				$resultArray['message'] = __('Failed to Import: File Type Not Allowed');
				return $resultArray;
			}
			return $this->getPimCsvDataImportService()->import($file);
		}
	}

	public function isValidResume($file) {

		$validFile = false;
		$originalName = $file->getOriginalName();
		$fileType = $file->getType();
		$allowedImageTypes[] = "text/csv";
		$allowedImageTypes[] = 'text/comma-separated-values';
		$allowedImageTypes[] = "application/csv";
		if (($file instanceof sfValidatedFile) && $originalName != "") {
			if (in_array($fileType, $allowedImageTypes)) {
				$validFile = true;
			} else if ($file->getOriginalExtension() == '.csv') {
				$validFile = true;
			}
		}

		return $validFile;
	}

}

?>
