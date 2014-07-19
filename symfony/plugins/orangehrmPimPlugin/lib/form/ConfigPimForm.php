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
 * ConfigPimForm
 *
 */
class ConfigPimForm extends sfForm {

    private $formWidgets = array();

    public function configure() {
        $orangeConfig = $this->getOption('orangeconfig');
        
        $showDeprecatedFields = $orangeConfig->getAppConfValue(ConfigService::KEY_PIM_SHOW_DEPRECATED);
        $showSSN = $orangeConfig->getAppConfValue(ConfigService::KEY_PIM_SHOW_SSN);
        $showSIN = $orangeConfig->getAppConfValue(ConfigService::KEY_PIM_SHOW_SIN);
        $showTax = $orangeConfig->getAppConfValue(ConfigService::KEY_PIM_SHOW_TAX_EXEMPTIONS);
        
        $this->formWidgets['chkDeprecateFields'] = new sfWidgetFormInputCheckbox();
        $this->formWidgets['chkShowSSN'] = new sfWidgetFormInputCheckbox();
        $this->formWidgets['chkShowSIN'] = new sfWidgetFormInputCheckbox();
        $this->formWidgets['chkShowTax'] = new sfWidgetFormInputCheckbox();
        
        
        if ($showDeprecatedFields) {
            $this->formWidgets['chkDeprecateFields']->setAttribute('checked', 'checked');
        }
        if ($showSSN) {
            $this->formWidgets['chkShowSSN']->setAttribute('checked', 'checked');
        }
        if ($showSIN) {
            $this->formWidgets['chkShowSIN']->setAttribute('checked', 'checked');
        }
        if ($showTax) {
            $this->formWidgets['chkShowTax']->setAttribute('checked', 'checked');
        }
            
        $this->setWidgets($this->formWidgets);

        $this->setValidators(array(
                'chkDeprecateFields' => new sfValidatorString(array('required' => false)),
                'chkShowSSN' => new sfValidatorString(array('required' => false)),
                'chkShowSIN' => new sfValidatorString(array('required' => false)),
                'chkShowTax' => new sfValidatorString(array('required' => false)),            
            ));

        $this->widgetSchema->setNameFormat('configPim[%s]');
    }
}
?>
