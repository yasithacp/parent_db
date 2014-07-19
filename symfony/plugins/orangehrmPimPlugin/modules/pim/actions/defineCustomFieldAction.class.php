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
 * defineCustomFieldAction action
 */
class defineCustomFieldAction extends sfAction {

    protected $customFieldService;

    /**
     * Get CustomFieldsService
     * @returns CustomFieldsService
     */
    public function getCustomFieldService() {
        if (is_null($this->customFieldService)) {
            $this->customFieldService = new CustomFieldsService();
            $this->customFieldService->setCustomFieldsDao(new CustomFieldsDao());
        }
        return $this->customFieldService;
    }

    /**
     * Set Country Service
     */
    public function setCustomFieldService(CustomFieldsService $customFieldsService) {
        $this->customFieldService = $customFieldsService;
    }

    /**
     * Delete custom fields
     * @param $request
     * @return unknown_type
     */
    public function execute($request) {
        $admin = $this->getUser()->hasCredential(Auth::ADMIN_ROLE);
        
        if (!$admin) {
            $this->forward("auth", "unauthorized");
            return;
        } 
        
        $form = new CustomFieldForm(array(), array(), true);
        $customFieldsService = $this->getCustomFieldService();
        
        if ($request->isMethod('post')) {

            $form->bind($request->getParameter($form->getName()));
            if ($form->isValid()) {
                
                $fieldNum = $form->getValue('field_num');
                $customField = null;
                
                if (isset($fieldNum)) {
                    $customField = $customFieldsService->readCustomField($fieldNum);
                }
                
                if (empty($customField)) {
                    $customField = new CustomFields();
                }
                
                $customField->setName($form->getValue('name'));
                $customField->setType($form->getValue('type'));
                $customField->setScreen($form->getValue('screen'));
                $customField->setExtraData($form->getValue('extra_data'));
                $customFieldsService->saveCustomField($customField);
                $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));

            }
        }
        $this->redirect('pim/listCustomFields');        
    }

}