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
 * listCustomFields action
 */
class listCustomFieldsAction extends sfAction {

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
     * List Custom fields
     * @param sfWebRequest $request
     * @return void
     */
    public function execute($request) {

        $admin = $this->getUser()->hasCredential(Auth::ADMIN_ROLE);
        
        if (!$admin) {
            $this->forward("auth", "unauthorized");
        } else {        
            if ($this->getUser()->hasFlash('templateMessage')) {
                list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
            } else if ($request->hasParameter('message')) {
                $message = $request->getParameter('message');
                
                if ($message == 'UPDATE_SUCCESS') {
                    $this->messageType = 'success';
                    $this->message = __(TopLevelMessages::UPDATE_SUCCESS);
                }
            }
            
            $this->form = new CustomFieldForm(array(), array(), true);
            $this->deleteForm = new CustomFieldDeleteForm(array(), array(), true);
            $customFieldsService = $this->getCustomFieldService();
            $this->sorter = new ListSorter('propoerty.sort', 'admin_module', $this->getUser(), array('field_num', ListSorter::ASCENDING));

            $sortBy = 'name';
            $sortOrder = 'ASC';
            
            if ($request->getParameter('sort')) {
                $sortBy = $request->getParameter('sort');
                $sortOrder = $request->getParameter('order');                
            }
            $this->sorter->setSort(array($sortBy, $sortOrder));
            $this->listCustomField = $customFieldsService->getCustomFieldList(null, $sortBy, $sortOrder);            
        }
    }

}