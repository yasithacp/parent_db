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

require_once ROOT_PATH . '/lib/dao/DMLFunctions.php';
require_once ROOT_PATH . '/lib/dao/SQLQBuilder.php';
require_once ROOT_PATH . '/plugins/Plugin.php';
require_once ROOT_PATH . '/plugins/PlugInFactoryException.php';
class PlugInFactory
{
	private function __construct(){
	}
	
	/**
	 * Get plugin object
	 *
	 * @return new plugin object
	 */
    public static function factory($plugInName){
		//Acces databse and get xmlpath
		$xmlPath = Plugin::fetchPlugin($plugInName);
		if($xmlPath){
			return PlugInFactory::readXMl($xmlPath);
		}else{
			throw new PlugInFactoryException(PlugInFactoryException::PLUGIN_INSTALL_ERROR);		
		}
		
		/* $temppluginObj = new Plugin();
		$temppluginObj->setPluginName(trim($plugInName));
		$tempPluginObj = $temppluginObj->fetchPlugin();
		
		if(is_object($tempPluginObj)){
			return PlugInFactory::readXMl($tempPluginObj);
		}else{
			throw new PlugInFactoryException(PlugInFactoryException::PLUGIN_INSTALL_ERROR);		
		} */
	}

	
	public function executePluginAction(){
		
	}
	
	/**
	 * Read plugins's install xml file
	 *
	 * @return new plugin object
	 */
	
	private static function readXMl($xmlPath){
		
		//Loading xml file through SimpleXML libarey
		if(is_file(ROOT_PATH .  "/" . $xmlPath)){
			$xmlObj = simplexml_load_file(ROOT_PATH .  "/" .  $xmlPath);
			if(is_file(ROOT_PATH . trim($xmlObj->initFile))){
					require_once(ROOT_PATH . trim($xmlObj->initFile));
					$pluginClassName = trim($xmlObj->initClass);
					$pluginClassNameNewObj = new $pluginClassName(); 
					foreach($xmlObj->authorizedRoles ->children() as $user){
			 	 		$authorizedRoles[trim($user)] = true;
			 		}
			 		$pluginClassNameNewObj->setAuthorizedRoles($authorizedRoles);
					foreach($xmlObj->authorizeModules ->children() as $module){							
		 	 			$authorizeModules[trim($module)] = true;
			 		}
			 		$pluginClassNameNewObj->setAuthorizeModules($authorizeModules);
					return  $pluginClassNameNewObj;
			}else{
				throw new PlugInFactoryException(PlugInFactoryException::PLUGIN_INSTALL_ERROR);
			}
		}else{
			return FALSE ;
		}
	}
}
?>