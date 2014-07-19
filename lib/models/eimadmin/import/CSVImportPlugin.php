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


/**
 * Interface to be implemented by CSV import plugins
 */
interface CSVImportPlugin {


	/** Get descriptive name for this plugin */
	public function getName();

	/** Get number of header rows to skip */
	public function getNumHeaderRows();

	/** Get number of csv columns expected */
	public function getNumColumns();

	/**
	 * Import CSV data to the system
	 *
	 * @param array dataRow Array containing one row of CSV data
	 */
	public function importCSVData($dataRow);
}

class CSVImportException extends Exception {
	const IMPORT_DATA_NOT_RECEIVED = 0;
	const COMPULSARY_FIELDS_MISSING_DATA = 1;
	const MISSING_WORKSTATION = 2;
	const UNKNOWN_ERROR = 3;
	const DD_DATA_INCOMPLETE = 4;
	const INVALID_TYPE = 5;
	const DUPLICATE_EMPLOYEE_ID = 6;
	const DUPLICATE_EMPLOYEE_NAME = 7;
	const FIELD_TOO_LONG = 8;

}

?>
