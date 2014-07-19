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

class saveDeleteWorkExperienceAction extends basePimAction {
    
    /**
     * @param sfForm $form
     * @return
     */
    public function setWorkExperienceForm(sfForm $form) {
        if (is_null($this->workExperienceForm)) {
            $this->workExperienceForm = $form;
        }
    }
    
    public function execute($request) {

        $experience = $request->getParameter('experience');
        $empNumber = (isset($experience['emp_number']))?$experience['emp_number']:$request->getParameter('empNumber');

        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
        
        $this->setWorkExperienceForm(new WorkExperienceForm(array(), array('empNumber' => $empNumber), true));

        //this is to save work experience
        if ($request->isMethod('post')) {
            if ( $request->getParameter('option') == "save") {

                $this->workExperienceForm->bind($request->getParameter($this->workExperienceForm->getName()));

                if ($this->workExperienceForm->isValid()) {
                    $workExperience = $this->getWorkExperience($this->workExperienceForm);
                    $this->setOperationName(($workExperience->getSeqno() == '') ? 'ADD WORK EXPERIENCE' : 'CHANGE WORK EXPERIENCE');
                    $this->getEmployeeService()->saveWorkExperience($workExperience);
                    $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));
                } else {
                    $this->getUser()->setFlash('templateMessage', array('warning', __('Form Validation Failed.')));
                }
            }

            //this is to delete work experience
            if ($request->getParameter('option') == "delete") {
                $deleteIds = $request->getParameter('delWorkExp');

                if(count($deleteIds) > 0) {
                    $this->setOperationName('DELETE WORK EXPERIENCE');
                    $this->getEmployeeService()->deleteWorkExperience($empNumber, $request->getParameter('delWorkExp'));
                    $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::DELETE_SUCCESS)));
                }
            }
        }
        $this->getUser()->setFlash('qualificationSection', 'workexperience');
        $this->redirect('pim/viewQualifications?empNumber='. $empNumber . '#workexperience');
    }

    private function getWorkExperience(sfForm $form) {

        $post = $form->getValues();

        $workExperience = $this->getEmployeeService()->getWorkExperience($post['emp_number'], $post['seqno']);

        if(!$workExperience instanceof EmpWorkExperience) {
            $workExperience = new EmpWorkExperience();
        }

        $workExperience->emp_number = $post['emp_number'];
        $workExperience->seqno = $post['seqno'];
        $workExperience->employer = $post['employer'];
        $workExperience->jobtitle = $post['jobtitle'];
        $workExperience->from_date = $post['from_date'];
        $workExperience->to_date = $post['to_date'];
        $workExperience->comments = $post['comments'];

        return $workExperience;
    }
}
?>