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


class KeyHandler {

	private static $filePath = '/lib/confs/cryptokeys/key.ohrm';
	private static $key;
	private static $keySet = false;

    public static function createKey() {

		if (self::keyExists()) {
			throw new KeyHandlerException('Key already exists', KeyHandlerException::KEY_ALREADY_EXISTS);
		}

		// Creating the key
		try {

			$cryptKey = '';

			for($i = 0; $i < 4; $i++) {
				$cryptKey .= md5(rand(10000000, 99999999));
			}

			$cryptKey = str_shuffle($cryptKey);

			$handle = fopen(ROOT_PATH . self::$filePath, 'w');
			fwrite($handle, $cryptKey, 128) or die('error');
		    fclose($handle);

		} catch (Exception $e) {

			throw new KeyHandlerException('Failed to create the key file', KeyHandlerException::KEY_CREATION_FAILIURE);

		}

		if (self::keyExists()) {
			return true;
		} else {
		    return false;
		}

    }

    public static function readKey() {

		if (!self::keyExists()) {

			throw new KeyHandlerException('Key file does not exist', KeyHandlerException::KEY_DOES_NOT_EXIST);

		}

		if (!is_readable(ROOT_PATH . self::$filePath)) {

			throw new KeyHandlerException('Key is not readable', KeyHandlerException::KEY_NOT_READABLE);

		}

		if (!self::$keySet) {
	    	self::$key = trim(file_get_contents(ROOT_PATH . self::$filePath));
			self::$keySet = true;
		}

		return self::$key;

    }

    public static function deleteKey() {

		if (!self::keyExists()) {
			throw new KeyHandlerException('Key does not exist', KeyHandlerException::KEY_DOES_NOT_EXIST);
		}

		// Deleting
		try {
			@unlink(ROOT_PATH . self::$filePath);
		} catch (Exception $e) {
			throw new KeyHandlerException('Failed to delete the key file', KeyHandlerException::KEY_DELETION_FAILIURE);
		}

		if (!self::keyExists()) {
			return true;
		} else {
		    return false;
		}

    }

    public static function keyExists() {

		return (file_exists(ROOT_PATH . self::$filePath));

    }

}

class KeyHandlerException extends Exception {

	const KEY_DOES_NOT_EXIST		= 1;
	const KEY_NOT_READABLE			= 2;
	const KEY_ALREADY_EXISTS		= 3;
	const KEY_CREATION_FAILIURE	= 4;
	const KEY_DELETION_FAILIURE	= 5;

}
?>
