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
 * Actions class for PIM module updateMembership
 */

class updateMembershipAction extends basePimAction {

    /**
     * Add / update employee membership
     *
     * @param int $empNumber Employee number
     *
     * @return boolean true if successfully assigned, false otherwise
     */
    public function execute($request) {

        $memberships = $request->getParameter('memberships');
        $empNumber = (isset($memberships['empNumber']))?$memberships['empNumber']:$request->getParameter('empNumber');
        $this->empNumber = $empNumber;


        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
        
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        $adminMode = $this->getUser()->hasCredential(Auth::ADMIN_ROLE);
        $essMode = !$adminMode && !empty($loggedInEmpNum) && ($empNumber == $loggedInEmpNum);
        $param = array('empNumber' => $empNumber, 'ESS' => $essMode);

        $this->form = new EmployeeMembershipForm(array(), $param, true);

        if ($this->getRequest()->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $this->form->save();
                $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));
            }
        }

        $empNumber = $request->getParameter('empNumber');

        $this->redirect('pim/viewMemberships?empNumber='. $empNumber);
    }

}
