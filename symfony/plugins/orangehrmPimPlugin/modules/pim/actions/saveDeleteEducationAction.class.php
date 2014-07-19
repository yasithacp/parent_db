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

class saveDeleteEducationAction extends basePimAction {
    
    /**
     * @param sfForm $form
     * @return
     */
    public function setEducationForm(sfForm $form) {
        if (is_null($this->educationForm)) {
            $this->educationForm = $form;
        }
    }
    
    public function execute($request) {

        $education = $request->getParameter('education');
        $empNumber = (isset($education['emp_number']))?$education['emp_number']:$request->getParameter('empNumber');

        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
        
        $this->setEducationForm(new EmployeeEducationForm(array(), array('empNumber' => $empNumber), true));

        if ($request->isMethod('post')) {
            if ( $request->getParameter('option') == "save") {

                $this->educationForm->bind($request->getParameter($this->educationForm->getName()));

                if ($this->educationForm->isValid()) {
                    $education = $this->getEducation($this->educationForm);
                    $this->getEmployeeService()->saveEducation($education);
                    $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));
                } else {
                    $this->getUser()->setFlash('templateMessage', array('warning', __('Form Validation Failed')));
                }
            }

            //this is to delete 
            if ($request->getParameter('option') == "delete") {
                $deleteIds = $request->getParameter('delEdu');

                if(count($deleteIds) > 0) {
                    $this->getEmployeeService()->deleteEducation($empNumber, $request->getParameter('delEdu'));
                    $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::DELETE_SUCCESS)));
                }
            }
        }
        $this->getUser()->setFlash('qualificationSection', 'education');
        $this->redirect('pim/viewQualifications?empNumber='. $empNumber . '#education');
    }

    private function getEducation(sfForm $form) {

        $post = $form->getValues(); 
        
        if (!empty($post['id'])) {
            $education = $this->getEmployeeService()->getEducation($post['id']);
        } 
        
        if(!$education instanceof EmployeeEducation) {
            $education = new EmployeeEducation();
        }        

        $education->empNumber = $post['emp_number'];
        $education->educationId = $post['code'];
        $education->institute = $post['institute'];
        $education->major = $post['major'];
        $education->year = $post['year'];
        $education->score = $post['gpa'];
        $education->startDate = $post['start_date'];
        $education->endDate = $post['end_date'];
        
        return $education;
    }
}
?>