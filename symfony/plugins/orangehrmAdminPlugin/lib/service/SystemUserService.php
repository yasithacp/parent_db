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

class SystemUserService extends BaseService{
    
    private $systemUserDao = null;
    
    /**
     * Constructor of System User Service class
     * 
     * Set System User Dao if object not intilaized
     */
    function __construct() {
        if( empty($this->systemUserDao)){
            $this->setSystemUserDao(new SystemUserDao());
        }
        
    }

    /**
     *
     * @return SystemUserDao
     */
    public function getSystemUserDao() {
        return $this->systemUserDao;
    }

    public function setSystemUserDao($systemUserDao) {
        $this->systemUserDao = $systemUserDao;
    }

    /**
     * Save System User
     * 
     * @param SystemUser $systemUser 
     * @return void
     */
    public function saveSystemUser(SystemUser $systemUser,$changePassword = false){
        
        try {
            
            if ($changePassword) {
                $systemUser->setUserPassword(md5($systemUser->getUserPassword()));
            }

            return $this->getSystemUserDao()->saveSystemUser($systemUser);
            
        } catch (Exception $e) {
            throw new ServiceException($e->getMessage(),$e->getCode(),$e);
        }
        
    }
    
    /**
     * Check is existing user according to user name
     * 
     * @param type $userName 
     * @param int $userId
     * @return mixed , false if user not exist  , otherwise it returns SystemUser object
     */
    public function isExistingSystemUser( $userName , $userId){
        try {
           return  $this->getSystemUserDao()->isExistingSystemUser( $userName , $userId);
        } catch (Exception $e) {
            throw new ServiceException($e->getMessage(),$e->getCode(),$e);
        }
    }
    
    /**
     * Get System User for given User Id
     * 
     * @param type $userId
     * @return SystemUser  
     */
    public function getSystemUser( $userId ){
        try {
            return $this->getSystemUserDao()->getSystemUser( $userId );
        } catch (Exception $e) {
            throw new ServiceException($e->getMessage(),$e->getCode(),$e);
        }
    }
    
    /**
     * Get System Users
     * 
     * @return Doctrine_Collection 
     */
    public function getSystemUsers(){
        try {
            return $this->getSystemUserDao()->getSystemUsers();
        } catch (Exception $e) {
            throw new ServiceException($e->getMessage(),$e->getCode(),$e);
        }
    }
    
   /**
     * Delete System Users
     * @param array $deletedIds 
     * 
     */
    public function deleteSystemUsers( array $deletedIds){
        try {
            $this->getSystemUserDao()->deleteSystemUsers($deletedIds);
        } catch (Exception $e) {
            throw new ServiceException($e->getMessage(),$e->getCode(),$e);
        }
    }
    
    /**
     * Get Pre Defined User Roles
     * 
     * @return Doctrine_Collection UserRoles 
     */
    public function getAssignableUserRoles(){
        try {
           return $this->getSystemUserDao()->getAssignableUserRoles();
        } catch (Exception $e) {
            throw new ServiceException($e->getMessage(),$e->getCode(),$e);
        }
    }
    
    /**
     * Get User role with given name
     * 
     * @param String $roleName Role Name
     * @return Doctrine_Collection UserRoles 
     */
    public function getUserRole($roleName){
        try {
           return $this->getSystemUserDao()->getUserRole($roleName);
        } catch (Exception $e) {
            throw new ServiceException($e->getMessage(),$e->getCode(),$e);
        }
    }    
    
    
   /**
     * Get Count of Search Query 
     * 
     * @param type $searchClues
     * @return int 
     */
    public function getSearchSystemUsersCount( $searchClues ){
        try {
           return $this->getSystemUserDao()->getSearchSystemUsersCount( $searchClues );
        } catch (Exception $e) {
            throw new ServiceException($e->getMessage(),$e->getCode(),$e);
        }
    }
    
    /**
     * Search System Users 
     * 
     * @param type $searchClues
     * @return type 
     */
     public function searchSystemUsers( $searchClues){
         try {
           return $this->getSystemUserDao()->searchSystemUsers( $searchClues );
        } catch (Exception $e) {
            throw new ServiceException($e->getMessage(),$e->getCode(),$e);
        }
     }
     
     public function isCurrentPassword($userId, $password) {
         
         $systemUser = $this->getSystemUserDao()->getSystemUser($userId);
         
         if (!($systemUser instanceof SystemUser)) {
             return false;
         }
         
         if ($systemUser->getUserPassword() == md5($password)) {
             return true;
         }
         
         return false;
         
     }
     
     /**
      * Updates the password of given user
      * 
      * @param int $userId User ID of the user
      * @param string $password Non-encrypted password
      * @return int 
      */     
     public function updatePassword($userId, $password) {
         return $this->getSystemUserDao()->updatePassword($userId, md5($password));
     }
    
}