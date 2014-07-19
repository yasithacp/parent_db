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


/**
 * Description of CompanyService
 *
 * @author orange
 */
class UserService extends BaseService {
	
   /**
     * Get Skill List
     * @return Skill 
     */
    public function getUserGroupList( $orderField='userg_id',$orderBy='ASC' )
    {
    	try
        {
	    	$q = Doctrine_Query::create()
			    ->from('UserGroup')
			    ->orderBy($orderField.' '.$orderBy);
			
			$userGroupList = $q->execute();
			   
			return  $userGroupList ;
			
        }catch( Exception $e)
        {
            throw new AdminServiceException($e->getMessage());
        }
    } 
    
   /**
     * Save Skill
     * @param Skill $skill
     * @return void
     */
    public function saveUserGroup(UserGroup $userGroup)
    {
    	try
        {
        	if( $userGroup->getUsergId() == '')
        	{
	        	$idGenService	=	new IDGeneratorService();
				$idGenService->setEntity($userGroup);
				$userGroup->setUsergId( $idGenService->getNextID() );
        	}
        	$userGroup->save();
			
        }catch( Exception $e)
        {
            throw new AdminServiceException($e->getMessage());
        }
    }
    
   /**
     * Delete Skill
     * @param $skillList
     * @return unknown_type
     */
    public function deleteUserGroup( $userGroupList )
    {
   	 	try
        {
	    	if(is_array( $userGroupList ))
	    	{
	        	$q = Doctrine_Query::create()
					    ->delete('UserGroup')
					    ->whereIn('userg_id', $skillList  );
	
					   
				$numDeleted = $q->execute();
	    	}
        }catch( Exception $e)
        {
            throw new AdminServiceException($e->getMessage());
        }
    }
    
    /**
     * Search Skill
     * @param $saleryGradeList
     * @return unknown_type
     */
  	public function searchUserGroup( $searchMode, $searchValue )
    {
    	try
        {
	    	$searchValue	=	trim($searchValue);
        	$q 				= 	Doctrine_Query::create( )
				   				 ->from('UserGroup') 
				    			 ->where("$searchMode = '$searchValue'");
				    
			$userGroupList = $q->execute();
			
			return $userGroupList;
			   
        }catch( Exception $e)
        {
            throw new AdminServiceException($e->getMessage());
        }
    }
    
   /**
     * Read Skill
     * @return Skill
     */
    public function readUserGroup( $id )
    {
   	 	try
        {
	    	$userGroup = Doctrine::getTable('UserGroup')->find($id);
	    	return $userGroup;
	    	
        }catch( Exception $e)
        {
            throw new AdminServiceException($e->getMessage());
     
        }
    } 
    
   /**
     * Get User List
     * @return Skill 
     */
    public function getUsersList( $isAdmin='Yes' ,$orderField='id',$orderBy='ASC' )
    {
    	try
        {
	    	
        	$q = Doctrine_Query::create()
			    ->from('Users u')
			    ->where('u.is_admin=?',$isAdmin)
			    ->orderBy($orderField.' '.$orderBy);
			
			
			$userList = $q->execute();
			  
			return  $userList ;
			
        }catch( Exception $e)
        {
            throw new AdminServiceException($e->getMessage());
        }
    } 
    
   /**
     * Save User
     * @param Language $language
     * @return void
     */
    public function saveUser(Users $user)
    {
    	try
        {
        	if( $user->getId() == '')
        	{
	        	$idGenService	=	new IDGeneratorService();
				$idGenService->setEntity($user);
				$user->setId( $idGenService->getNextID() );
        	}
        	
        	$user->save();
			
        }catch( Exception $e)
        {
            throw new AdminServiceException($e->getMessage());
        }
    }

    public function getUserByUserName($userName) {

    	try {
        	$q = Doctrine_Query::create( )
				   				 ->from('Users u')
				    			 ->where('u.user_name=?', $userName);

			return $q->fetchOne();

        } catch( Exception $e) {
            throw new AdminServiceException($e->getMessage());
        }

    }

   /**
     * Delete User
     * @param $skillList
     * @return unknown_type
     */
    public function deleteUser( $userList )
    {
   	 	try
        {
	    	if(is_array($userList ))
	    	{
	        	$q = Doctrine_Query::create()
					    ->delete('Users')
					    ->whereIn('id', $userList );
	
					   
				$numDeleted = $q->execute();
	    	}
        }catch( Exception $e)
        {
            throw new AdminServiceException($e->getMessage());
        }
    }
    
    /**
     * Search User
     * @return unknown_type
     */
  	public function searchUsers( $isAdmin=1,$searchMode, $searchValue )
    {
    	try
        {
	    	$searchValue	=	trim($searchValue);
        	$q 				= 	Doctrine_Query::create( )
				   				 ->from('Users u') 
				    			 ->where("$searchMode = '$searchValue' AND u.is_admin=?",$isAdmin);
				    
			
			$userList = $q->execute();
			
			return $userList;
			   
        }catch( Exception $e)
        {
            throw new AdminServiceException($e->getMessage());
        }
    }
    
   /**
     * Read User
     * @return Language
     */
    public function readUser( $id )
    {
   	 	try
        {
	    	$user = Doctrine::getTable('Users')->find($id);
	    	return $user;
	    	
        }catch( Exception $e)
        {
            throw new AdminServiceException($e->getMessage());
     
        }
    } 
    
     /**
     * Get User List
     * @return Skill 
     */
    public function getModuleList(  UserGroup $userGrop )
    {
    	try
        {
        	$existingModule		=	array();
	    	$existingModules	=	$this->getUserGroupModelRights($userGrop);
        	foreach( $existingModules as $right)
        	{
        		array_push($existingModule,$right->getModule()->getModId());
        	}
	    	
        	$q = Doctrine_Query::create()
			    ->from('Module m')
			    ->whereNotIn('m.mod_id',implode(',',$existingModule))
			    ->orderBy('mod_id ASC');
			
			
			$moduleList = $q->execute();
			  
			return  $moduleList ;
			
        }catch( Exception $e)
        {
            throw new AdminServiceException($e->getMessage());
        }
    } 
    
   /**
     * Get User List
     * @return Skill 
     */
    public function getUserGroupModelRights( UserGroup $userGrop)
    {
   	 	try
        {
	    	$q = Doctrine_Query::create()
			    ->from('ModuleRights')
			    ->where("userg_id='".$userGrop->getUsergId()."'");
			
			$rightList = $q->execute();
			  
			return  $rightList ;
			
        }catch( Exception $e)
        {
            throw new AdminServiceException($e->getMessage());
        }
    }
    
    /**
     * Save Grop module rights
     * @return Skill 
     */
    public function saveUserGroupModelRights( ModuleRights $moduleRights)
    {
   	 	try
        {
        	$moduleRights->save();
			
        }catch( Exception $e)
        {
            throw new AdminServiceException($e->getMessage());
        }
    }
    
     /**
     * Delete User group model rights
     * @return Skill 
     */
    public function deleteUserGroupModelRights( UserGroup $userGrop )
    {
    	try
        {
        	$q = Doctrine_Query::create()
				    ->delete('ModuleRights')
				    ->where("userg_id='".$userGrop->getUsergId()."'");

				   
			$numDeleted = $q->execute();
	    	
        }catch( Exception $e)
        {
            throw new AdminServiceException($e->getMessage());
        }
    }
}
