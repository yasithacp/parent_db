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

class OrangeI18NFilter extends sfFilter {

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

    public function execute($filterChain) {

        $languages = $this->getContext()->getRequest()->getLanguages();
        $userCulture = $this->getConfigService()->getAdminLocalizationDefaultLanguage();
        $localizationService = new LocalizationService();
        $languageToSet = (!empty($languages[0]) && $this->getConfigService()->getAdminLocalizationUseBrowserLanguage() == "Yes" && key_exists($languages[0], $localizationService->getSupportedLanguageListFromYML())) ? $languages[0] : $userCulture;
        $datePattern = $this->getContext()->getUser()->getDateFormat();
        $datePattern = isset($datePattern) ? $datePattern : $this->getConfigService()->getAdminLocalizationDefaultDateFormat();

        $user = $this->getContext()->getUser();
        $user->setCulture($languageToSet);
        $user->setDateFormat($datePattern);

        // Execute next filter in filter chain
        $filterChain->execute();
    }

}