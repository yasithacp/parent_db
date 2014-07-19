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


require_once ROOT_PATH . '/lib/models/eimadmin/encryption/KeyHandlerOld.php';

class CryptoQuery {

	public static function isEncTable($table) {

		if (strtolower($table) == "hs_hr_employee" || strtolower($table) == "hs_hr_emp_basicsalary") {
		    return true;
		} else {
		    return false;
		}

	}

	public static function prepareDecryptFields($decryptFieldsArray) {
		$encOn = KeyHandlerOld::KeyExists();
		foreach ($decryptFieldsArray as $field) { 
			if ($encOn && self::isEncField($field)) {
				$key = KeyHandlerOld::readKey();
			    $fieldsArray[] = "AES_DECRYPT(`$field`, '$key')";
			} else {
			    $fieldsArray[] = $field;
			}
		}
		return $fieldsArray;
	}
	
	public static function prepareEncryptFields($encryptFieldsArray, $encryptValuesArray) {
		$encOn = KeyHandlerOld::KeyExists();
		
		$valuesArray = array();
		
		$encryptFieldsArrayCount = count($encryptFieldsArray);
		
		for ($i = 0; $i < $encryptFieldsArrayCount; $i++) { 
			if ($encOn && self::isEncField($encryptFieldsArray[$i])) {
				
				$key = KeyHandlerOld::readKey();
				
				if ($encryptValuesArray[$i] == null)
					$valuesArray[$i] = null;
				else
				    $valuesArray[$i] = "AES_ENCRYPT($encryptValuesArray[$i], '$key')";
			    
			} else {
			    $valuesArray[$i] = $encryptValuesArray[$i];
			}
		}
		return $valuesArray;
	}	

	public static function isEncField($field) {
	    if (strtolower($field) == "emp_ssn_num" || strtolower($field) == "ebsal_basic_salary") {
			return true;
	    } else {
			return false;
	    }
	}

}

?>
