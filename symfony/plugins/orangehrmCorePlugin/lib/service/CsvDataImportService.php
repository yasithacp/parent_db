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

class CsvDataImportService extends BaseService {

	public function import($file, $importType) {

		$factory = new CsvDataImportFactory();
		$instance = $factory->getImportClassInstance($importType);
		$rowsImported = 0;
		$row = 1;
		if (($handle = fopen($file->getTempName(), "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				$temp = array();
				$row++;
				if ($row != 2) {
					for ($c = 0; $c < $num; $c++) {
						$temp[] = $data[$c];
					}
					$result = $instance->import($temp);
					if($result) {
						$rowsImported++;
					}
				}
			}
			fclose($handle);
		}
		return $rowsImported;
	}

}

?>
