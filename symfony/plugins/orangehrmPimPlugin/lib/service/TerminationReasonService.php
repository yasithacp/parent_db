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

class TerminationReasonService extends BaseService {
    
    private $terminationReasonDao;
    
    /**
     * @ignore
     */
    public function getTerminationReasonDao() {
        
        if (!($this->terminationReasonDao instanceof TerminationReasonDao)) {
            $this->terminationReasonDao = new TerminationReasonDao();
        }
        
        return $this->terminationReasonDao;
    }

    /**
     * @ignore
     */
    public function setTerminationReasonDao($terminationReasonDao) {
        $this->terminationReasonDao = $terminationReasonDao;
    }
    
    /**
     * Saves a termination reason
     * 
     * Can be used for a new record or updating.
     * 
     * @version 2.6.12 
     * @param TerminationReason $terminationReason 
     * @return NULL Doesn't return a value
     */
    public function saveTerminationReason(TerminationReason $terminationReason) {        
        $this->getTerminationReasonDao()->saveTerminationReason($terminationReason);        
    }
    
    /**
     * Retrieves a termination reason by ID
     * 
     * @version 2.6.12 
     * @param int $id 
     * @return TerminationReason An instance of TerminationReason or NULL
     */    
    public function getTerminationReasonById($id) {
        return $this->getTerminationReasonDao()->getTerminationReasonById($id);
    }
    
    /**
     * Retrieves a termination reason by name
     * 
     * Case insensitive
     * 
     * @version 2.6.12 
     * @param string $name 
     * @return TerminationReason An instance of TerminationReason or false
     */    
    public function getTerminationReasonByName($name) {
        return $this->getTerminationReasonDao()->getTerminationReasonByName($name);
    }      
  
    /**
     * Retrieves all termination reasons ordered by name
     * 
     * @version 2.6.12 
     * @return Doctrine_Collection A doctrine collection of TerminationReason objects 
     */        
    public function getTerminationReasonList() {
        return $this->getTerminationReasonDao()->getTerminationReasonList();
    }
    
    /**
     * Deletes termination reasons
     * 
     * @version 2.6.12 
     * @param array $toDeleteIds An array of IDs to be deleted
     * @return int Number of records deleted
     */    
    public function deleteTerminationReasons($toDeleteIds) {
        return $this->getTerminationReasonDao()->deleteTerminationReasons($toDeleteIds);
    }

    /**
     * Checks whether the given termination reason name exists
     *
     * Case insensitive
     *
     * @version 2.6.12
     * @param string $terminationReasonName Termination reason name that needs to be checked
     * @return boolean
     */
    public function isExistingTerminationReasonName($terminationReasonName) {
        return $this->getTerminationReasonDao()->isExistingTerminationReasonName($terminationReasonName);
    }
    
    /**
     * Checks whether the given IDs have been assigned to any employee
     * 
     * @param array $idArray Reason IDs
     * @return boolean 
     */
    public function isReasonInUse($idArray) {
        return $this->getTerminationReasonDao()->isReasonInUse($idArray);
    }
    
}