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
 * configPimAction
 *
 */
class configurePimAction extends basePimAction {

    /**
     * @param sfForm $form
     * @return
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    public function execute($request) {
        //authentication
        if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] != 'Yes') {
            $this->forward('leave', 'viewMyLeaveList');
        }

        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }

        $param = array('orangeconfig' => OrangeConfig::getInstance());


        $this->setForm(new ConfigPimForm(array(), $param, false));
        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $post = $this->form->getValues();

                $this->_saveConfigValue($post, 'chkDeprecateFields', ConfigService::KEY_PIM_SHOW_DEPRECATED);
                $this->_saveConfigValue($post, 'chkShowSSN', ConfigService::KEY_PIM_SHOW_SSN);
                $this->_saveConfigValue($post, 'chkShowSIN', ConfigService::KEY_PIM_SHOW_SIN);
                $this->_saveConfigValue($post, 'chkShowTax', ConfigService::KEY_PIM_SHOW_TAX_EXEMPTIONS);

                $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));
                $this->redirect('pim/configurePim');
            }
        }
    }

    /**
     *
     * @param type $post array of POST variables
     * @param type $postVar Post variable containing config value
     * @param type $configKey Key used in config table
     */
    private function _saveConfigValue($post, $postVar, $configKey) {

        $value = false;
        if (isset($post[$postVar]) && $post[$postVar] == 'on') {
            $value = true;
        }
        OrangeConfig::getInstance()->setAppConfValue($configKey, $value);
    }

}

?>
