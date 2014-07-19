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


require_once ROOT_PATH . '/lib/common/Language.php';
require_once ROOT_PATH . '/lib/common/CommonFunctions.php';

class FormCreator
{
	var $getArr;
	var $postArr;
	var $popArr;
	var $formPath;

	function FormCreator($getArr,$postArr = null) {

        /**
         * Escape any html in GET variables, making them safer
         */
        foreach($getArr as $key=>$val) {
            if (is_string($val)) {
                $getArr[$key] = CommonFunctions::escapeHtml($val);
            }
        }

		$this->getArr = $getArr;
		if($postArr != null)
			$this->postArr = $postArr;

		$this->popArr = array();

	}

	function display() {
		@ob_clean();
		$str = ROOT_PATH . $this->formPath;

		require_once ROOT_PATH . '/lib/common/xajax/xajax.inc.php';
		require_once ROOT_PATH . '/lib/common/xajax/xajaxElementFiller.php';
		require_once ROOT_PATH . '/language/default/lang_default_full.php';

		$lan = new Language();
		require_once($lan->getLangPath("full.php"));
		$fileName = pathinfo($this->formPath, PATHINFO_BASENAME);

		$styleSheet = CommonFunctions::getTheme();

		if (preg_match('/view\.php$/', $fileName) == 1) {
			require_once(ROOT_PATH . '/language/default/lang_default_' .$fileName);
		}

		require_once(ROOT_PATH.$this->formPath);
	}
}
?>