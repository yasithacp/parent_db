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
 * this action is used to set languages and the different date formats for the OrangeHRM
 */
class localizationAction extends sfAction {

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
     * to set Localization form
     * @param sfForm $form
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    /**
     * execute function
     * @param <type> $request
     */
    public function execute($request) {

        $this->setForm(new LocalizationForm());
        $languages = $this->getRequest()->getLanguages();
        $this->browserLanguage = $languages[0];

         if ($this->getUser()->hasFlash('templateMessage')) {
             $this->templateMessage = $this->getUser()->getFlash('templateMessage');
         }

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                
                // For reloading main menu (index.php)
                $_SESSION['load.admin.localization'] = true;

                $formValues = $this->form->getFormValues();               
                $defaultLanguage = $formValues['defaultLanguage'];
                $setBrowserLanguage = !empty($formValues['setBrowserLanguage']) ? "Yes" : "No";
                $supprotedLanguages = $this->form->getLanguages();
                if($setBrowserLanguage == "Yes" && in_array($languages[0], $supprotedLanguages)){
                   $defaultLanguage = $languages[0];
                }
                $this->getUser()->setCulture($defaultLanguage);
                $this->getConfigService()->setAdminLocalizationDefaultLanguage($formValues['defaultLanguage']);
                $this->getConfigService()->setAdminLocalizationUseBrowserLanguage($setBrowserLanguage);
                $this->getUser()->setDateFormat($formValues['defaultDateFormat']);
                $this->getConfigService()->setAdminLocalizationDefaultDateFormat($formValues['defaultDateFormat']);

                $this->getUser()->setFlash('templateMessage', array('SUCCESS', __(TopLevelMessages::SAVE_SUCCESS)));
                $this->redirect("admin/localization");
            }
        }
    }

}

