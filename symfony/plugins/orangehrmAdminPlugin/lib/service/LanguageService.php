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

class LanguageService extends BaseService {
    
    private $languageDao;
    
    /**
     * @ignore
     */
    public function getLanguageDao() {
        
        if (!($this->languageDao instanceof LanguageDao)) {
            $this->languageDao = new LanguageDao();
        }
        
        return $this->languageDao;
    }

    /**
     * @ignore
     */
    public function setLanguageDao($languageDao) {
        $this->languageDao = $languageDao;
    }
    
    /**
     * Saves a language
     * 
     * Can be used for a new record or updating.
     * 
     * @version 2.6.12 
     * @param Language $language 
     * @return NULL Doesn't return a value
     */
    public function saveLanguage(Language $language) {        
        $this->getLanguageDao()->saveLanguage($language);        
    }
    
    /**
     * Retrieves a language by ID
     * 
     * @version 2.6.12 
     * @param int $id 
     * @return Language An instance of Language or NULL
     */    
    public function getLanguageById($id) {
        return $this->getLanguageDao()->getLanguageById($id);
    }
    
    /**
     * Retrieves a language by name
     * 
     * Case insensitive
     * 
     * @version 2.6.12 
     * @param string $name 
     * @return Language An instance of Language or false
     */    
    public function getLanguageByName($name) {
        return $this->getLanguageDao()->getLanguageByName($name);
    }        
  
    /**
     * Retrieves all languages ordered by name
     * 
     * @version 2.6.12 
     * @return Doctrine_Collection A doctrine collection of Language objects 
     */        
    public function getLanguageList() {
        return $this->getLanguageDao()->getLanguageList();
    }
    
    /**
     * Deletes languages
     * 
     * @version 2.6.12 
     * @param array $toDeleteIds An array of IDs to be deleted
     * @return int Number of records deleted
     */    
    public function deleteLanguages($toDeleteIds) {
        return $this->getLanguageDao()->deleteLanguages($toDeleteIds);
    }

    /**
     * Checks whether the given language name exists
     *
     * Case insensitive
     *
     * @version 2.6.12
     * @param string $languageName Language name that needs to be checked
     * @return boolean
     */
    public function isExistingLanguageName($languageName) {
        return $this->getLanguageDao()->isExistingLanguageName($languageName);
    }
    
}