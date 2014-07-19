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

class viewQualificationsAction extends basePimAction {
    
    /**
     * @param sfForm $form
     * @return
     */
    public function setWorkExperienceForm(sfForm $form) {
        if (is_null($this->workExperienceForm)) {
            $this->workExperienceForm = $form;
        }
    }

    /**
     * @param sfForm $form
     * @return
     */
    public function setEducationForm(sfForm $form) {
        if (is_null($this->educationForm)) {
            $this->educationForm = $form;
        }
    }
    
    /**
     * @param sfForm $form
     * @return
     */
    public function setSkillForm(sfForm $form) {
        if (is_null($this->skillForm)) {
            $this->skillForm = $form;
        }
    }    
    
    /**
     * @param sfForm $form
     * @return
     */
    public function setLanguageForm(sfForm $form) {
        if (is_null($this->languageForm)) {
            $this->languageForm = $form;
        }
    } 
    
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
        
        $this->showBackButton = false;
        $empNumber = $request->getParameter('empNumber');
        $this->empNumber = $empNumber;

        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
        
        $this->_setMessage();

        $this->setWorkExperienceForm(new WorkExperienceForm(array(), array('empNumber' => $empNumber), true));
        $this->setEducationForm(new EmployeeEducationForm(array(), array('empNumber' => $empNumber), true));
        $this->setSkillForm(new EmployeeSkillForm(array(), array('empNumber' => $empNumber), true));
        $this->setLanguageForm(new EmployeeLanguageForm(array(), array('empNumber' => $empNumber), true));
        $this->setLicenseForm(new EmployeeLicenseForm(array(), array('empNumber' => $empNumber), true));        
    }
    
    protected function _setMessage() {
        $this->section = '';
        $this->message = '';
        $this->messageType = '';
        
        if ($this->getUser()->hasFlash('templateMessage')) {
            if ($this->getUser()->hasFlash('qualificationSection')) {
                $this->section = $this->getUser()->getFlash('qualificationSection');
            }
            
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }        
    }
}
?>