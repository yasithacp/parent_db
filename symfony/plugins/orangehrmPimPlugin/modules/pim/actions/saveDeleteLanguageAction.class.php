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

class saveDeleteLanguageAction extends basePimAction {
    
    /**
     * @param sfForm $form
     * @return
     */
    public function setLanguageForm(sfForm $form) {
        if (is_null($this->languageForm)) {
            $this->languageForm = $form;
        }
    }
    
    public function execute($request) {

        $language = $request->getParameter('language');
        $empNumber = (isset($language['emp_number']))?$language['emp_number']:$request->getParameter('empNumber');

        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
        
        $this->setLanguageForm(new EmployeeLanguageForm(array(), array('empNumber' => $empNumber), true));

        if ($request->isMethod('post')) {
            if ( $request->getParameter('option') == "save") {

                $this->languageForm->bind($request->getParameter($this->languageForm->getName()));

                if ($this->languageForm->isValid()) {
                    $language = $this->getLanguage($this->languageForm);
                    $this->getEmployeeService()->saveLanguage($language);
                    $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));
                } else {
                    $this->getUser()->setFlash('templateMessage', array('warning', __('Form Validation Failed')));
                }
            }

            //this is to delete 
            if ($request->getParameter('option') == "delete") {
                $deleteIds = $request->getParameter('delLanguage');
                $languagesToDelete = array();
                
                foreach ($deleteIds as $value) {
                    $parts = explode("_", $value, 2);
                    if (count($parts) == 2) {
                        $languagesToDelete[$parts[0]] = $parts[1]; 
                    }
                }

                if (count($languagesToDelete) > 0) {

                    $this->getEmployeeService()->deleteLanguage($empNumber, $languagesToDelete);
                    $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::DELETE_SUCCESS)));
                }
            }
        }
        $this->getUser()->setFlash('qualificationSection', 'language');
        $this->redirect('pim/viewQualifications?empNumber='. $empNumber . '#language');
    }

    private function getLanguage(sfForm $form) {

        $post = $form->getValues();

        $language = $this->getEmployeeService()->getLanguage($post['emp_number'], $post['code'], $post['lang_type']);

        if(!$language instanceof EmployeeLanguage) {
            $language = new EmployeeLanguage();
        }

        $language->empNumber = $post['emp_number'];
        $language->langId = $post['code'];
        $language->fluency = $post['lang_type'];
        $language->competency = $post['competency'];
        $language->comments = $post['comments'];

        return $language;
    }
}
?>