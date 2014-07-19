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
 * Actions class for PIM module updateDependentAction
 */

class updateCustomFieldsAction extends basePimAction {

    /**
     * Add / update employee customFields
     *
     * @param int $empNumber Employee number
     *
     * @return boolean true if successfully assigned, false otherwise
     */
    public function execute($request) {
        
        // this should probably be kept in session?
        $screen = $request->getParameter('screen');

        $customFieldsService = new CustomFieldsService();
        $customFieldList = $customFieldsService->getCustomFieldList($screen);

        $this->form = new EmployeeCustomFieldsForm(array(), array('customFields'=>$customFieldList), true);

        if ($this->getRequest()->isMethod('post')) {


            // Handle the form submission
            $this->form->bind($request->getPostParameters());

            if ($this->form->isValid()) {

                $empNumber = $this->form->getValue('EmpID');
                if (!$this->IsActionAccessible($empNumber)) {
                    $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
                }

                $this->form->save();
                $this->getUser()->setFlash('customFieldsMessage', array('success', __(TopLevelMessages::UPDATE_SUCCESS)));                
            } else {
                $this->getUser()->setFlash('customFieldsMessage', array('warning', __('Failed to Save: Length Exceeded')));
            }
        }                    

                    
        $this->redirect($this->getRequest()->getReferer() . '#custom');
    }

}
