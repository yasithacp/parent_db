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

require_once 'key.php';

class LicenceExpiryService{
	
	CONST LICENCE_EXPIRY_URL	=	'le/le_template.php';
	
	private $redirectUrl;
	
	/**
	 * Check licence Key expiry
	 * @param $url
	 * @return String $url
	 */
	public function doLicenceExpiry( $url ){
		//Check Licence Skipp
		try{
			if($this->hasSkipedLicenceExpiry()){
				$this->redirectUrl			= $_SESSION['expiredUrl'];
				$_SESSION['skipExpiry']		=	true;
			}else{
				if(isset($_SESSION['skipExpiry']) && $_SESSION['skipExpiry']){
					$this->redirectUrl = $url;
				}else{
					if($this->isLicenceExpired()){
						$this->redirectUrl = self::LICENCE_EXPIRY_URL; 
						$_SESSION['expiredUrl']	=	$url;
					}else{
						$this->redirectUrl = $url;	
					}
				}
			}
			return $this->redirectUrl;
		}catch( Exception $e){
			$this->redirectUrl = self::LICENCE_EXPIRY_URL;
			$_SESSION['errorLicenceExpiry'] = $e->getMessage();
			$_SESSION['errorCodeLicenceExpiry'] = $e->getCode();
			return $this->redirectUrl;
		}
		
	}
	
	/**
	 * Check expiry key skipped
	 * @return unknown_type
	 */
	private function hasSkipedLicenceExpiry(){
		return (isset( $_GET['skip_le']) && $_GET['skip_le']==1)?true:false;
	}
	
	/**
	 * Check validity of licence key
	 * @return unknown_type
	 */
	private function isLicenceExpired( ){
		$key 	=	new Key();
		
    	$decryptKey = $key->decryptKey(dirname(__FILE__).'/../ohrmpublic.key',dirname(__FILE__).'/../licence.key');
    	$expiryDate = $key->getExpiryDate();
    	if(strtotime("now") >= strtotime($expiryDate)){
    		return true;
    	}else{
    		return false;
    	}
		
	}

	
}