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


class SkillForm extends BaseForm {
    
    private $skillService;
    
    public function getSkillService() {
        
        if (!($this->skillService instanceof SkillService)) {
            $this->skillService = new SkillService();
        }
        
        return $this->skillService;
    }

    public function setSkillService($skillService) {
        $this->skillService = $skillService;
    }

    public function configure() {

        $this->setWidgets(array(
            'id' => new sfWidgetFormInputHidden(),
            'name' => new sfWidgetFormInputText(),
            'description' => new sfWidgetFormTextArea(array(),array('rows'=>5,'cols'=>10)),
        ));

        $this->setValidators(array(
            'id' => new sfValidatorNumber(array('required' => false)),
            'name' => new sfValidatorString(array('required' => true, 'max_length' => 120)),
            'description' => new sfValidatorString(array('required' => false, 'max_length' => 250)),
        ));

        $this->widgetSchema->setNameFormat('skill[%s]');

        $this->setDefault('id', '');
	}
    
    public function save() {
        
        $id = $this->getValue('id');
        
        if (empty($id)) {
            $skill = new Skill();
            $message = array('SUCCESS', __(TopLevelMessages::SAVE_SUCCESS));
        } else {
            $skill = $this->getSkillService()->getSkillById($id);
            $message = array('SUCCESS', __(TopLevelMessages::UPDATE_SUCCESS));
        }
        
        $skill->setName($this->getValue('name'));
        $skill->setDescription($this->getValue('description'));            
        $this->getSkillService()->saveSkill($skill);        
        
        return $message;
        
    }
    
    public function getSkillListAsJson() {

        $list = array();
        $skillList = $this->getSkillService()->getSkillList();
        foreach ($skillList as $skill) {
            $list[] = array('id' => $skill->getId(), 'name' => $skill->getName());
        }
        return json_encode($list);
    }

}

?>
