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


define('LANG_DIR','/language');

 class  Language {

 	var $availLang;

 	function Language(){

 		$dh  = opendir(ROOT_PATH . LANG_DIR);

 		while (false !== ($entry = readdir($dh))) {
 			if($entry != '.' && $entry != '..')
    			$dir[] = $entry;
 		}

		sort($dir);
 		$this->availLang = $dir;
 	}

 	function pmaLangDetect($str = '', $envType = '') {

 			$availLang = $this->availLang;

            for($c=0;count($availLang)>$c;$c++) {
                // $envType =  1 for the 'HTTP_ACCEPT_LANGUAGE' environment variable,
                //             2 for the 'HTTP_USER_AGENT' one
                if (($envType == 1 && eregi('^(' . $availLang[$c] . ')(;q=[0-9]\\.[0-9])?$', $str))
                    || ($envType == 2 && eregi('(\(|\[|;[[:space:]])(' . $availLang[$c] . ')(;|\]|\))', $str))) {
                   $lang     = $availLang[$c];
                  // echo $lang;
                  return $lang;
                }
            }
        } // end of the 'PMA


        /////////////////////////////

 //$lang_tables = array('home.php','first.php','second.php');


 function getLangPath($template) {

 	if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']))
    	$HTTP_ACCEPT_LANGUAGE = $_SERVER['HTTP_ACCEPT_LANGUAGE'];

	if (!empty($_SERVER['HTTP_USER_AGENT']))
	    $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];


	/**
	 * Do the work!
	 */

	/*// If '$lang' is defined, ensure this is a valid translation
	if (!empty($lang) && empty($available_languages[$lang])) {
	    $lang = '';
	}*/

	// Language is not defined yet :
	// 1. try to findout users language by checking it's HTTP_ACCEPT_LANGUAGE
	//    variable
	if (empty($lang) && !empty($HTTP_ACCEPT_LANGUAGE)) {
	    $accepted    = explode(',', $HTTP_ACCEPT_LANGUAGE);
	    $acceptedCnt = count($accepted);
	    reset($accepted);
	   // echo $lang;
	    for ($i = 0; $i < $acceptedCnt && empty($lang); $i++) {
	      //echo $accepted[$i];
	     $lang = $this ->pmaLangDetect($accepted[$i], 1);
	    }
	}
	// 2. try to findout users language by checking it's HTTP_USER_AGENT variable
	if (empty($lang) && !empty($HTTP_USER_AGENT)) {
		$lang = $this ->pmaLangDetect($HTTP_USER_AGENT, 2);
	}

	// 3. Didn't catch any valid lang : we use the default settings
	if (empty($lang)) {
	    $lang = 'default';
	}
	//echo $lang;

	// include '../Language1/'.$lang.'/lang_'.$lang.'.php' ;
	$dirName = dirname($template);

	if (isset($dirName) && ($dirName == ".")) {
		$dirName = "";
	} else {
		$dirName .= "/";
	}

	$path = ROOT_PATH . LANG_DIR .'/'.$lang .'/'.$dirName.'lang_'.$lang.'_'.basename($template); //dirname(__FILE__)


	    return $path;
	      // else include('../Language1/'.$lang.'/lang_'.$lang.'_'.$lang_tables[$tc]);

}
        /////////////////////////////////
 }
?>