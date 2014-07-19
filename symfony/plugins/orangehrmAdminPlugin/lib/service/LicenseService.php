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

class LicenseService extends BaseService {
    
    private $licenseDao;
    
    /**
     * @ignore
     */
    public function getLicenseDao() {
        
        if (!($this->licenseDao instanceof LicenseDao)) {
            $this->licenseDao = new LicenseDao();
        }
        
        return $this->licenseDao;
    }

    /**
     * @ignore
     */
    public function setLicenseDao($licenseDao) {
        $this->licenseDao = $licenseDao;
    }
    
    /**
     * Saves a license
     * 
     * Can be used for a new record or updating.
     * 
     * @version 2.6.12 
     * @param License $license 
     * @return NULL Doesn't return a value
     */
    public function saveLicense(License $license) {        
        $this->getLicenseDao()->saveLicense($license);        
    }
    
    /**
     * Retrieves a license by ID
     * 
     * @version 2.6.12 
     * @param int $id 
     * @return License An instance of License or NULL
     */    
    public function getLicenseById($id) {
        return $this->getLicenseDao()->getLicenseById($id);
    }
    
    /**
     * Retrieves a license by name
     * 
     * Case insensitive
     * 
     * @version 2.6.12 
     * @param string $name 
     * @return License An instance of License or false
     */    
    public function getLicenseByName($name) {
        return $this->getLicenseDao()->getLicenseByName($name);
    }     
  
    /**
     * Retrieves all licenses ordered by name
     * 
     * @version 2.6.12 
     * @return Doctrine_Collection A doctrine collection of License objects 
     */        
    public function getLicenseList() {
        return $this->getLicenseDao()->getLicenseList();
    }
    
    /**
     * Deletes licenses
     * 
     * @version 2.6.12 
     * @param array $toDeleteIds An array of IDs to be deleted
     * @return int Number of records deleted
     */    
    public function deleteLicenses($toDeleteIds) {
        return $this->getLicenseDao()->deleteLicenses($toDeleteIds);
    }

    /**
     * Checks whether the given license name exists
     *
     * Case insensitive
     *
     * @version 2.6.12
     * @param string $licenseName License name that needs to be checked
     * @return boolean
     */
    public function isExistingLicenseName($licenseName) {
        return $this->getLicenseDao()->isExistingLicenseName($licenseName);
    }
    
}