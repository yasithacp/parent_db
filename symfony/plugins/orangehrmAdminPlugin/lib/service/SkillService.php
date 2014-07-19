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

class SkillService extends BaseService {
    
    private $skillDao;
    
    /**
     * @ignore
     */
    public function getSkillDao() {
        
        if (!($this->skillDao instanceof SkillDao)) {
            $this->skillDao = new SkillDao();
        }
        
        return $this->skillDao;
    }

    /**
     * @ignore
     */
    public function setSkillDao($skillDao) {
        $this->skillDao = $skillDao;
    }
    
    /**
     * Saves a skill
     * 
     * Can be used for a new record or updating.
     * 
     * @version 2.6.12 
     * @param Skill $skill 
     * @return NULL Doesn't return a value
     */
    public function saveSkill(Skill $skill) {        
        $this->getSkillDao()->saveSkill($skill);        
    }
    
    /**
     * Retrieves a skill by ID
     * 
     * @version 2.6.12 
     * @param int $id 
     * @return Skill An instance of Skill or NULL
     */    
    public function getSkillById($id) {
        return $this->getSkillDao()->getSkillById($id);
    }
    
    /**
     * Retrieves a skill by name
     * 
     * Case insensitive
     * 
     * @version 2.6.12 
     * @param string $name 
     * @return Skill An instance of Skill or false
     */    
    public function getSkillByName($name) {
        return $this->getSkillDao()->getSkillByName($name);
    }    
  
    /**
     * Retrieves all skills ordered by name
     * 
     * @version 2.6.12 
     * @return Doctrine_Collection A doctrine collection of Skill objects 
     */        
    public function getSkillList() {
        return $this->getSkillDao()->getSkillList();
    }
    
    /**
     * Deletes skills
     * 
     * @version 2.6.12 
     * @param array $toDeleteIds An array of IDs to be deleted
     * @return int Number of records deleted
     */    
    public function deleteSkills($toDeleteIds) {
        return $this->getSkillDao()->deleteSkills($toDeleteIds);
    }

    /**
     * Checks whether the given skill name exists
     * 
     * Case insensitive
     * 
     * @version 2.6.12 
     * @param string $skillName Skill name that needs to be checked
     * @return boolean
     */    
    public function isExistingSkillName($skillName) {
        return $this->getSkillDao()->isExistingSkillName($skillName);
    }
    

}