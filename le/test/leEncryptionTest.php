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

require_once '../key.php';

class leEncryptionTest extends PHPUnit_Framework_TestCase {
	
 	protected function setUp() {
        
    }

    protected function tearDown() {
        
    }
	
    public function testDecryptKey(){
    	$key 	=	new Key();
		
    	$key->decryptKey(dirname(__FILE__).'/../../ohrmpublic.key',dirname(__FILE__).'/../../licence.key');
    	$this->assertEquals($key->getDecryptText(),'1111|2012-01-01');
    }
    
	/**
     * @expectedException Exception
     */
    public function testDecryptKeyWithEmptyLicenceKey(){
    	$key 	=	new Key();
    	$licenceKey = $key->decryptKey(dirname(__FILE__).'/../../ohrmpublic.key','');
    }
    
    
	/**
     * @expectedException Exception
     */
    public function testDecryptKeyWithEmptyPublicKey(){
    	$key 	=	new Key();
    	$licenceKey = $key->decryptKey('',dirname(__FILE__).'/../../licence.key');
    }
    
    public function testGetExpiryDate(){
    	$key 	=	new Key();
		
    	$decryptKey = $key->decryptKey(dirname(__FILE__).'/../../ohrmpublic.key',dirname(__FILE__).'/../../licence.key');
    	$result = $key->getExpiryDate();
    	$this->assertEquals($result,'2012-01-01');
    }
    
    
}