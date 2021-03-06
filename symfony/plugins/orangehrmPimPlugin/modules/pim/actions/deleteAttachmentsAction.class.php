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
 * Actions class for PIM module deleteAttachmentAction
 */
class deleteAttachmentsAction extends basePimAction {

    /**
     * Delete employee attachments
     *
     * @param int $empNumber Employee number
     *
     * @return boolean true if successfully deleted, false otherwise
     */
    public function execute($request) {
        $this->form = new EmployeeAttachmentDeleteForm(array(), array(), true);

        $this->form->bind($request->getParameter($this->form->getName()));
        if ($this->form->isValid()) {
            $empId = $request->getParameter('EmpID', false);
            if (!$empId) {
                throw new PIMServiceException("No Employee ID given");
            }
            
            if (!$this->IsActionAccessible($empId)) {
                $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
            }
            
            $attachmentsToDelete = $request->getParameter('chkattdel', array());
            if ($attachmentsToDelete) {
                $service = new EmployeeService();
                $service->deleteAttachments($empId, $attachmentsToDelete);
                $this->getUser()->setFlash('attachmentMessage', array('success', __(TopLevelMessages::DELETE_SUCCESS)));
            }
        }

        $this->redirect($this->getRequest()->getReferer(). '#attachments');
    }

}
