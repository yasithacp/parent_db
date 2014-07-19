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
 * Localization form class
 */
class LocalizationForm extends BaseForm {

    private $configService;

    /**
     * to get confuguration service
     * @return <type>
     */
    public function getConfigService() {
        if (is_null($this->configService)) {
            $this->configService = new ConfigService();
            $this->configService->setConfigDao(new ConfigDao());
        }
        return $this->configService;
    }

    /**
     *  to set configuration service
     * @param ConfigService $configService
     */
    public function setConfigService(ConfigService $configService) {
        $this->configService = $configService;
    }

    /**
     * the configure method
     */
    public function configure() {

        //Setting widgets
        $this->setWidgets(array(
            'dafault_language' => new sfWidgetFormSelect(array('choices' => $this->getLanguages())),
            'use_browser_language' => new sfWidgetFormInputCheckbox(),
            'default_date_format' => new sfWidgetFormSelect(array('choices' => $this->__getDateFormats()))
        ));

        //Setting validators
        $this->setValidators(array(
            'dafault_language' => new sfValidatorString(array('required' => false)),
            'use_browser_language' => new sfValidatorString(array('required' => false)),
            'default_date_format' => new sfValidatorString(array('required' => false))
        ));

        $this->widgetSchema->setNameFormat('localization[%s]');

        $useBrowserLanguage = $this->getConfigService()->getAdminLocalizationUseBrowserLanguage();
        $useBrowserLanguage = ($useBrowserLanguage == "Yes") ? 1 : null;

        //set default values
        $this->setDefaults(array(
            'dafault_language' => $this->getConfigService()->getAdminLocalizationDefaultLanguage(),
            'use_browser_language' => $useBrowserLanguage,
            'default_date_format' => $this->getConfigService()->getAdminLocalizationDefaultDateFormat()
        ));
    }

    /**
     * this is used to get the posted widget values
     * @return <type>
     */
    public function getFormValues() {

        return array('defaultLanguage' => $this->getValue('dafault_language'),
            'setBrowserLanguage' => $this->getValue('use_browser_language'),
            'defaultDateFormat' => $this->getValue('default_date_format'));
    }

    /**
     * To make date format array
     * User can eneble any of the commented date formats below if someone is going to write
     * more date formats the key values should be in the format of php and the values should
     * be according to the jQuery datepicker date format
     * @return string
     */
    private function __getDateFormats() {

        $dateFormats = array(
            'Y-m-d' => 'yy-mm-dd ( '.date('Y-m-d').' )',
            'd-m-Y' => 'dd-mm-yy ( '.date('d-m-Y').' )',
            'm-d-Y' => 'mm-dd-yy ( '.date('m-d-Y').' )',
            'Y-d-m' => 'yy-dd-mm ( '.date('Y-d-m').' )',
            'm-Y-d' => 'mm-yy-dd ( '.date('m-Y-d').' )',
            'd-Y-m' => 'dd-yy-mm ( '.date('d-Y-m').' )',
            'Y/m/d' => 'yy/mm/dd ( '.date('Y/m/d').' )',
            'Y m d' => 'yy mm dd ( '.date('Y m d').' )',
            'Y-M-d' => 'yy-M-dd ( '.date('Y-M-d').' )',
            'l, d-M-Y' => 'DD, dd-M-yy ( '.date('l, d-M-Y').' )',
            'D, d M Y' => 'D, dd M yy ( '.date('D, d M Y').' )'
        );
        return $dateFormats;
    }

    /**
     * this is used to make language list from supported_languages.yml
     * @return <type>
     */
    public function getLanguages() {
        $localizationService = new LocalizationService();
        return $localizationService->getSupportedLanguageListFromYML();
    }

}
