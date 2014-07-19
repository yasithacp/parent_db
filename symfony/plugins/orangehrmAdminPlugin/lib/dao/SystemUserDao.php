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

class SystemUserDao extends BaseDao {

    /**
     * Save System User
     * 
     * @param SystemUser $systemUser 
     * @return void
     */
    public function saveSystemUser(SystemUser $systemUser) {
        try {
            $systemUser->clearRelated('Employee');
            $systemUser->save();
            return $systemUser;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Check is existing user according to user name
     * 
     * @param type $userName 
     * @return mixed , false if user not exist  , otherwise it returns SystemUser object
     */
    public function isExistingSystemUser($userName, $userId = null) {
        try {
            $query = Doctrine_Query:: create()->from('SystemUser u')
                    ->andWhere('u.user_name = ?', $userName);
            if (!empty($userId)) {
                $query->andWhere('u.id != ?', $userId);
            }
            //print($query->getSqlQuery());
            return $query->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get System User for given User Id
     * 
     * @param type $userId
     * @return SystemUser  
     */
    public function getSystemUser($userId) {
        try {
            return Doctrine :: getTable('SystemUser')->find($userId);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get System Users
     * 
     * @return Doctrine_Collection 
     */
    public function getSystemUsers() {
        try {
            $query = Doctrine_Query:: create()->from('SystemUser u')
                    ->where('u.deleted=?', 0);

            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Delete System Users
     * @param array $deletedIds 
     * 
     */
    public function deleteSystemUsers(array $deletedIds) {
        try {
            $query = Doctrine_Query :: create()
                    ->update('SystemUser u')
                    ->set('u.deleted', 1)
                    ->whereIn('u.id', $deletedIds);
            $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get System Users
     * 
     * @return Doctrine_Collection 
     */
    public function getAssignableUserRoles() {
        try {
            $query = Doctrine_Query:: create()->from('UserRole ur')
                    ->whereIn('ur.is_assignable', 1);

            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }
    
    public function getUserRole($roleName) {
        try {
            $query = Doctrine_Query:: create()->from('UserRole ur')
                    ->where('ur.name = ?', $roleName);

            return $query->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get Count of Search Query 
     * 
     * @param type $searchClues
     * @return type 
     */
    public function getSearchSystemUsersCount($searchClues) {
        try {
            $q = $this->_buildSearchQuery($searchClues);
            return $q->count();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Search System Users 
     * 
     * @param type $searchClues
     * @return type 
     */
    public function searchSystemUsers($searchClues) {
        try {
            
            // Set defaults to sort order and limits           
            $sortField = empty($searchClues['sortField']) ? 'user_name' : $searchClues['sortField'];
            $sortOrder = empty($searchClues['sortOrder']) ? 'ASC' : $searchClues['sortOrder'];
            $offset = empty($searchClues['offset']) ? 0 : $searchClues['offset'];
            $limit = empty($searchClues['limit']) ? SystemUser::NO_OF_RECORDS_PER_PAGE : $searchClues['limit'];

            $q = $this->_buildSearchQuery($searchClues);

            $q->orderBy($sortField . ' ' . $sortOrder)
                    ->offset($offset)
                    ->limit($limit);

            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     *
     * @param type $searchClues
     * @return Doctrine Query 
     */
    private function _buildSearchQuery($searchClues) {

        $query = Doctrine_Query:: create()->from('SystemUser u');

        if (!empty($searchClues['userName'])) {
            $query->addWhere('u.user_name = ?', $searchClues['userName']);
        }
        if (!empty($searchClues['userType'])) {
            if (is_array($searchClues['userType'])) {
                $query->andWhereIn('u.user_role_id', $searchClues['userType']);
            } else {
                $query->addWhere('u.user_role_id = ?', $searchClues['userType']);
            }
        }
        if (!empty($searchClues['employeeId'])) {
            $query->addWhere('u.emp_number = ?', $searchClues['employeeId']);
        }
        if ($searchClues['status'] != '') {
            $query->addWhere('u.status = ?', $searchClues['status']);
        }

        if ($searchClues['location'] && $searchClues['location'] != '-1') {
            $query->leftJoin('u.Employee e');
            $query->leftJoin('e.EmpLocations l');
            $query->whereIn('l.location_id', explode(',', $searchClues['location']));
        }
        
        if (isset($searchClues['user_ids']) && is_array($searchClues['user_ids'])) {   
            $query->whereIn('u.id', $searchClues['user_ids']);
        }

        $query->addWhere('u.deleted=?', 0);

        return $query;
    }
    
    public function getAdminUserCount($enabledOnly=true, $undeletedOnly=true) {
        
        $q = Doctrine_Query::create()->from('SystemUser')
                                     ->where('user_role_id = ?', SystemUser::ADMIN_USER_ROLE_ID);
        
        if ($enabledOnly) {
            $q->addWhere('status = ?', SystemUser::ENABLED);
        }
        
        if ($undeletedOnly) {
            $q->addWhere('deleted = ?', SystemUser::UNDELETED);
        }
        
        return $q->count();
        
    }
    
    public function updatePassword($userId, $password) {
        
        try {
            
            $q = Doctrine_Query::create()
                               ->update('SystemUser')
                               ->set('user_password', '?', $password)
                               ->where('id = ?', $userId);
            
            return $q->execute();
            
        } catch (Exception $e) {
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
        
    }
    
}