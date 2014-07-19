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

class ReportingMethodService extends BaseService {
    
    private $reportingMethodDao;
    
    /**
     * @ignore
     */
    public function getReportingMethodDao() {
        
        if (!($this->reportingMethodDao instanceof ReportingMethodDao)) {
            $this->reportingMethodDao = new ReportingMethodDao();
        }
        
        return $this->reportingMethodDao;
    }

    /**
     * @ignore
     */
    public function setReportingMethodDao($reportingMethodDao) {
        $this->reportingMethodDao = $reportingMethodDao;
    }
    
    /**
     * Saves a reportingMethod
     * 
     * Can be used for a new record or updating.
     * 
     * @version 2.6.12 
     * @param ReportingMethod $reportingMethod 
     * @return NULL Doesn't return a value
     */
    public function saveReportingMethod(ReportingMethod $reportingMethod) {        
        return $this->getReportingMethodDao()->saveReportingMethod($reportingMethod);        
    }
    
    /**
     * Retrieves a reportingMethod by ID
     * 
     * @version 2.6.12 
     * @param int $id 
     * @return ReportingMethod An instance of ReportingMethod or NULL
     */    
    public function getReportingMethodById($id) {
        return $this->getReportingMethodDao()->getReportingMethodById($id);
    }
    
    /**
     * Retrieves a reporting method by name
     * 
     * Case insensitive
     * 
     * @version 2.6.12 
     * @param string $name 
     * @return ReportingMethod An instance of ReportingMethod or false
     */    
    public function getReportingMethodByName($name) {
        return $this->getReportingMethodDao()->getReportingMethodByName($name);
    }     
  
    /**
     * Retrieves all reportingMethods ordered by name
     * 
     * @version 2.6.12 
     * @return Doctrine_Collection A doctrine collection of ReportingMethod objects 
     */        
    public function getReportingMethodList() {
        return $this->getReportingMethodDao()->getReportingMethodList();
    }
    
    /**
     * Deletes reportingMethods
     * 
     * @version 2.6.12 
     * @param array $toDeleteIds An array of IDs to be deleted
     * @return int Number of records deleted
     */    
    public function deleteReportingMethods($toDeleteIds) {
        return $this->getReportingMethodDao()->deleteReportingMethods($toDeleteIds);
    }

    /**
     * Checks whether the given reportingMethod name exists
     *
     * Case insensitive
     *
     * @version 2.6.12
     * @param string $reportingMethodName ReportingMethod name that needs to be checked
     * @return boolean
     */
    public function isExistingReportingMethodName($reportingMethodName) {
        return $this->getReportingMethodDao()->isExistingReportingMethodName($reportingMethodName);
    }
    
}