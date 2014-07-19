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

class TerminationReasonDao extends BaseDao {

    public function saveTerminationReason(TerminationReason $terminationReason) {
        
        try {
            $terminationReason->save();
            return $terminationReason;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }
    
    public function getTerminationReasonById($id) {
        
        try {
            return Doctrine::getTable('TerminationReason')->find($id);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }
    
    public function getTerminationReasonByName($name) {
        
        try {
            
            $q = Doctrine_Query::create()
                                ->from('TerminationReason')
                                ->where('name = ?', trim($name));
            
            return $q->fetchOne();
            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }    
    
    public function getTerminationReasonList() {
        
        try {
            
            $q = Doctrine_Query::create()->from('TerminationReason')
                                         ->orderBy('name');
            
            return $q->execute(); 
            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }        
        
    }
    
    public function deleteTerminationReasons($toDeleteIds) {
        
        try {
            
            $q = Doctrine_Query::create()->delete('TerminationReason')
                            ->whereIn('id', $toDeleteIds);

            return $q->execute();            
            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }        
        
    }
    
    public function isExistingTerminationReasonName($terminationReasonName) {
        
        try {
            
            $q = Doctrine_Query:: create()->from('TerminationReason rm')
                            ->where('rm.name = ?', trim($terminationReasonName));

            if ($q->count() > 0) {
                return true;
            }
            
            return false;
            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }       
        
    }
    
    public function isReasonInUse($idArray) {
        
        $q = Doctrine_Query::create()->from('Employee em')
                                     ->leftJoin('em.EmpTermination et')
                                     ->leftJoin('et.TerminationReason tr')
                                     ->whereIn('tr.id', $idArray);        
        
        $result = $q->fetchOne();
        
        if ($result instanceof Employee) {
            return true;
        }
        
        return false;
        
    }

}