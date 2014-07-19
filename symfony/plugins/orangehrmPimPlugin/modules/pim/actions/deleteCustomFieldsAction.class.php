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
 * deleteCustomFieldsAction action
 */
class deleteCustomFieldsAction extends sfAction {

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
     * Delete Customer fields
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function execute($request) {
        
        $admin = $this->getUser()->hasCredential(Auth::ADMIN_ROLE);
        
        if (!$admin) {
            $this->forward("auth", "unauthorized");
        } else {
            $this->form = new CustomFieldDeleteForm(array(), array(), true);
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                if (count($request->getParameter('chkLocID')) > 0) {
                    $customFieldsService = $this->getCustomFieldService();
                    $customFieldsService->deleteCustomField($request->getParameter('chkLocID'));
                    $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::DELETE_SUCCESS)));
                } else {
                    $this->getUser()->setFlash('templateMessage', array('notice', __(TopLevelMessages::SELECT_RECORDS)));
                }
            }
            $this->redirect('pim/listCustomFields');
        }
    }

}

