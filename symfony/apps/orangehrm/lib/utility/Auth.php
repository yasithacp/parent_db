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
 * OrangeHRM Authentication & Authorization library. Taken from insurance branch.
 */
class Auth {

    /** Original OrangeHRM modules. We may need to change this once OrangeHRM is modularized */
    // TODO: consider reading these dynamically from database. (BUT then these constants will not be available)
    const ADMIN_MODULE = 'MOD001';
    const PIM_MODULE = 'MOD002';
    const REPORT_MODULE = 'MOD004';
    const LEAVE_MODULE = 'MOD005';
    const TIME_MODULE = 'MOD006';
    const BENEFIT_MODULE = 'MOD007';
    const RECRUITMENT_MODULE = 'MOD008';
    const PERFORMANCE_MODULE = 'MOD009';

    /** Roles defined in OrangeHRM */
    const ADMIN_ROLE = 'Admin';
    const SUPERVISOR_ROLE = 'Supervisor';
    const ESSUSER_ROLE = 'ESS';
    const PROJECTADMIN_ROLE = 'ProjectAdmin';
    const MANAGER_ROLE = 'Manager';
    const DIRECTOR_ROLE = 'Director';
    const INTERVIEWER = 'Interviewer'; // Someone involved with interview events (called ACCEPTOR in authorize.php)
    const HIRINGMANAGER_ROLE = 'Offerer'; // Called Offerer in authorize.php		 

    /** Is current user logged in */
    private $loggedIn = false;
	
    /** Current user's roles */
    private $roles = array ();

    /** Permission array for current user */
    private $permissions = array ();

    // Authclass singleton
    private static $instance = null;

    // employee number
    private $empNumber = null;

    // User Id
    private $loggedInUserId;
    /** TODO: userg_repdef field in hs_hr_user_group doesn't seem to be used at all: Remove
     */
    /**
     * Private constructor. Use instance() method to get singleton instance
     */
    private function __construct() {

    	if (isset($_SESSION['user'])) {
    		$this->loggedIn = true;
                $this->empNumber = $_SESSION['empID']; 
                $this->loggedInUserId = $_SESSION['user'];
	        $orangeAuth = new AuthorizeService($this->empNumber, $_SESSION['isAdmin']);
	        
	        $roleList = $orangeAuth->getRoles();
	        if (!empty($roleList)) {
	            foreach ($roleList as $role=>$inRole) {
	                if ($inRole) {
	                    $this->roles[] = $role;
	                }
	            }
	        }	        
	
	        // If an admin, get admin group permissions
	        if ($this->hasRole(Auth :: ADMIN_ROLE)) {
	            $permissions = $this->_getRightsForUserGroup($_SESSION['userGroup']);
	        }
    	} else {
    	    $this->loggedIn = false;
    	}
    }

    public function getEmployeeNumber() {
        $empNumber = $this->empNumber;

        // trim leading zeros
        $empNumber = ltrim($empNumber, '0');
        return intval($empNumber);
    }

    public function getLoggedInUserId() {
        return $this->loggedInUserId;
    }

    /**
     * Get singleton instance of Auth class
     * @return Auth
     */
    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new Auth();
        }

        return self::$instance;
    }

    /**
     * Get rights that the the currently logged in user has
     * 
     * Note: 
     * Due to the current OrangeHRM authorization system, actions (edit/view etc.) are 
     * only considered for admin users. This is done by checking the permissions of the admin user
     * group for the currently logged in user.
     * 
     * @param String $module One of module constants defined in this class (eg Auth::BENEFIT_MODULE)
     * @param Array $allowedRoles One of the roles allowed to do this action (eg: Auth::SUPERVISOR_ROLE)
     * 
     * @return Rights Rights object
     */
    public function getRights($module, $allowedRoles) {
		
		$rights = new Rights();
		
		// find roles that match
		$matchingRoles = array_intersect($allowedRoles, $this->roles);
		if (!empty($matchingRoles)) {
		    // Additional check for action if in ADMIN Role only
		    if ((count($matchingRoles) === 1) && in_array(self::ADMIN_ROLE,$matchingRoles)) {
		    	if (isset($this->permissions[$module])) {
		    	    $rights = $this->permissions[$module];
		    	}    
		    } else {
		    	
		    	// All actions allowed
		        $rights->edit = $rights->view = $rights->add = $rights->delete = true;
		    }
		}
		
		return $rights;		
    }
    
    /**
     * Is current user logged in?
     * @param boolean $redirect Should use be redirected to login page. Defaults to true
     * 
     * @return boolean true if logged in, false otherwise
     */
    public function isLoggedIn(/*$redirect = true*/) {
    	
    	if (!$this->loggedIn) {
    		/*if ($redirect) {
    			$url = (empty($_SERVER['HTTPS']) OR $_SERVER['HTTPS'] === 'off') ? 'http://' : 'https://';
    			$url .= $_SERVER['SERVER_NAME'];
    			if (isset($_SERVER['SERVER_PORT']) && ($_SERVER['SERVER_PORT'] != '180')) {
    			    $url .= ':' . $_SERVER['SERVER_PORT'];
    			}
    			$url .= url::base();
    			$url .= Kohana::config('auth.loginpage'); 
    			url::redirect($url);
    		}*/
    	    return false;
    	}
		return true;
    }

    /**
     * Is current user in the given Role
     * 
     * @param int $roles One of the role constants defined in this class
     * @return boolean true if the user is in that role, false otherwise
     */
    public function hasRole($role) {
		return in_array($role, $this->roles);
    }

    /**
     * Function used to get user group rights. Should be later moved to the Rights class in OrangeHRM (Rights.php)
     */
    public function _getRightsForUserGroup($group) {

        /*$db = Doctrine:: :: instance();
        $query = $db->where('userg_id', $group)->get('hs_hr_rights');

        if ($query->count() > 0) {        	
            foreach ($query->result() as $row) {

                $rights = new Rights();
                $rights->view = ($row->viewing == 1);
				$rights->edit =  ($row->editing == 1);
				$rights->add = ($row->addition == 1);
				$rights->delete = ($row->deletion == 1);

                $this->permissions[$row->mod_id] = $rights;
            }
        }*/
    }
}

class Rights {
    public $edit = false;
    public $save = false;
    public $view = false;
    public $delete = false;        
}
