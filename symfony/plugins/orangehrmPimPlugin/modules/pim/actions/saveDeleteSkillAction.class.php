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

class saveDeleteSkillAction extends basePimAction {
    
    /**
     * @param sfForm $form
     * @return
     */
    public function setSkillForm(sfForm $form) {
        if (is_null($this->skillForm)) {
            $this->skillForm = $form;
        }
    }
    
    public function execute($request) {

        $skill = $request->getParameter('skill');
        $empNumber = (isset($skill['emp_number']))?$skill['emp_number']:$request->getParameter('empNumber');
        
        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }
        
        $this->setSkillForm(new EmployeeSkillForm(array(), array('empNumber' => $empNumber), true));

        if ($request->isMethod('post')) {
            if ( $request->getParameter('option') == "save") {

                $this->skillForm->bind($request->getParameter($this->skillForm->getName()));

                if ($this->skillForm->isValid()) {
                    $skill = $this->getSkill($this->skillForm);
                    $this->getEmployeeService()->saveSkill($skill);
                    $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));
                } else {
                    $this->getUser()->setFlash('templateMessage', array('warning', __('Form Validation Failed')));
                }
            }

            //this is to delete 
            if ($request->getParameter('option') == "delete") {
                $deleteIds = $request->getParameter('delSkill');

                if(count($deleteIds) > 0) {
                    $this->getEmployeeService()->deleteSkill($empNumber, $request->getParameter('delSkill'));
                    $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::DELETE_SUCCESS)));
                }
            }
        }
        $this->getUser()->setFlash('qualificationSection', 'skill');
        $this->redirect('pim/viewQualifications?empNumber='. $empNumber . '#skill');
    }

    private function getSkill(sfForm $form) {

        $post = $form->getValues();

        $skill = $this->getEmployeeService()->getSkill($post['emp_number'], $post['code']);

        if(!$skill instanceof EmployeeSkill) {
            $skill = new EmployeeSkill();
        }

        $skill->emp_number = $post['emp_number'];
        $skill->skillId = $post['code'];
        $skill->years_of_exp = $post['years_of_exp'];
        $skill->comments = $post['comments'];

        return $skill;
    }
}
?>