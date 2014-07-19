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

class EducationService extends BaseService {
    
    private $educationDao;
    
    /**
     * @ignore
     */
    public function getEducationDao() {
        
        if (!($this->educationDao instanceof EducationDao)) {
            $this->educationDao = new EducationDao();
        }
        
        return $this->educationDao;
    }

    /**
     * @ignore
     */
    public function setEducationDao($educationDao) {
        $this->educationDao = $educationDao;
    }
    
    /**
     * Saves an education object
     * 
     * Can be used for a new record or updating.
     * 
     * @version 2.6.12 
     * @param Education $education 
     * @return NULL Doesn't return a value
     */
    public function saveEducation(Education $education) {        
        $this->getEducationDao()->saveEducation($education);        
    }
    
    /**
     * Retrieves an education object by ID
     * 
     * @version 2.6.12 
     * @param int $id 
     * @return Education An instance of Education or NULL
     */    
    public function getEducationById($id) {
        return $this->getEducationDao()->getEducationById($id);
    }
    
    /**
     * Retrieves an education object by name
     * 
     * Case insensitive
     * 
     * @version 2.6.12 
     * @param string $name 
     * @return Education An instance of Education or false
     */    
    public function getEducationByName($name) {
        return $this->getEducationDao()->getEducationByName($name);
    }    
  
    /**
     * Retrieves all education records ordered by name
     * 
     * @version 2.6.12 
     * @return Doctrine_Collection A doctrine collection of Education objects 
     */        
    public function getEducationList() {
        return $this->getEducationDao()->getEducationList();
    }
    
    /**
     * Deletes education records
     * 
     * @version 2.6.12 
     * @param array $toDeleteIds An array of IDs to be deleted
     * @return int Number of records deleted
     */    
    public function deleteEducations($toDeleteIds) {
        return $this->getEducationDao()->deleteEducations($toDeleteIds);
    }

    /**
     * Checks whether the given education name exists
     *
     * Case insensitive
     *
     * @version 2.6.12
     * @param string $educationName Education name that needs to be checked
     * @return boolean
     */
    public function isExistingEducationName($educationName) {
        return $this->getEducationDao()->isExistingEducationName($educationName);
    }
    
}