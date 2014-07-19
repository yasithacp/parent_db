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

class saveDeleteLicenseAction extends basePimAction {
    
    /**
     * @param sfForm $form
     * @return
     */
    public function setLicenseForm(sfForm $form) {
        if (is_null($this->licenseForm)) {
            $this->licenseForm = $form;
        }
    }
    
    public function execute($request) {

        $license = $request->getParameter('license');
        $empNumber = (isset($license['emp_number']))?$license['emp_number']:$request->getParameter('empNumber');

        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
        
        $this->setLicenseForm(new EmployeeLicenseForm(array(), array('empNumber' => $empNumber), true));

        if ($request->isMethod('post')) {
            if ( $request->getParameter('option') == "save") {

                $this->licenseForm->bind($request->getParameter($this->licenseForm->getName()));

                if ($this->licenseForm->isValid()) {
                    $license = $this->getLicense($this->licenseForm);
                    $this->getEmployeeService()->saveLicense($license);
                    $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));
                } else {
                    $this->getUser()->setFlash('templateMessage', array('warning', __('Form Validation Failed')));
                }
            }

            //this is to delete 
            if ($request->getParameter('option') == "delete") {
                $deleteIds = $request->getParameter('delLicense');

                if(count($deleteIds) > 0) {
                    $this->getEmployeeService()->deleteLicense($empNumber, $request->getParameter('delLicense'));
                    $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::DELETE_SUCCESS)));
                }
            }
        }
        $this->getUser()->setFlash('qualificationSection', 'license');
        $this->redirect('pim/viewQualifications?empNumber='. $empNumber . '#license');
    }

    private function getLicense(sfForm $form) {

        $post = $form->getValues();

        $license = $this->getEmployeeService()->getLicense($post['emp_number'], $post['code']);

        if(!$license instanceof EmployeeLicense) {
            $license = new EmployeeLicense();
        }

        $license->empNumber = $post['emp_number'];
        $license->licenseId = $post['code'];
        $license->licenseNo = $post['license_no'];
        $license->licenseIssuedDate = $post['date'];
        $license->licenseExpiryDate = $post['renewal_date'];

        return $license;
    }
}
?>