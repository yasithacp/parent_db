<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
require_once 'phpseclib/Math/BigInteger.php';
require_once 'phpseclib/Crypt/Random.php';
require_once 'phpseclib/Crypt/Hash.php';
require_once 'phpseclib/Crypt/RSA.php';

class Key{
	
	private $customerSerialNumber ;
	private $licenceExpiryDate ;
	private $decryptText ;
	
	/**
	 * Get Customer Serial number
	 * @return String
	 */
	public function getCustomerSerialNumber(){
		return $this->customerSerialNumber;
	}
	
	/**
	 * Set Customer Serial Number
	 * @param $customerSerialNumber
	 * @return void
	 */
	public function setCustomerSerialNumber( $customerSerialNumber){
		$this->customerSerialNumber = $customerSerialNumber;
	}
	
	/**
	 * Get Licence Expiry date
	 * @return Date
	 */
	public function getLicenceExpiryDate(){
		return $this->licenceExpiryDate;
	}
	
	public function getDecryptText(){
		return $this->decryptText;
	}
	
	
	public function setDecryptText( $decryptText){
		$this->decryptText = $decryptText;
	}
	
	/**
	 * Set Licence Expiry date
	 * @param $licenceExpiryDate
	 * @return void
	 */
	public function setLicenceExpiryDate( $licenceExpiryDate ){
		$this->licenceExpiryDate = $licenceExpiryDate;
	}
	
	
	/**
	 * 
	 * @param $publicKeyPath
	 * @param $licenceKeyPath
	 * @return String
	 */
	public function decryptKey($publicKeyPath , $licenceKeyPath){
		try{
			if(!file_exists($publicKeyPath))
				throw new Exception('can not open public key file',100);

			if(!file_exists($licenceKeyPath))
				throw new Exception('can not open licence key file',101);
				
			$rsa 		= new Crypt_RSA();
			$publicKey 	= $this->loadKeyFile($publicKeyPath);
			$rsa->loadKey($publicKey);
		
			$this->setDecryptText($rsa->decrypt($this->loadKeyFile($licenceKeyPath)));
		}catch( Exception $e){
			throw new Exception($e->getMessage(),$e->getCode());
		}
		
	}
	
	/**
	 * Get Expiry Date
	 * @param $encryptText
	 * @return unknown_type
	 */
	public function getExpiryDate( ){
		try{
			if($this->getDecryptText() == '')
				throw new Exception('Empty Encrypt string',102);
			
			$list	=	explode('|',$this->getDecryptText());
			if(!isset($list[1]))
				throw new Exception('Expiry date can not extract from the key',103);
				
			return $list[1];
			
		}catch( Exception $e){
			throw new Exception($e->getMessage(),$e->getCode());
		}
	}
	
	/**
	 * Load Key file
	 * @param $file
	 * @return String
	 */
	private function loadKeyFile( $file ){
		$handle 	= fopen($file, "rb");
		$contents 	= fread($handle, filesize($file));
		fclose($handle);
		
		return $contents;
	}
	
	
}