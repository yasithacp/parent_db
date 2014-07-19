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

require_once ROOT_PATH . '/lib/models/hrfunct/EmpRepTo.php';
require_once ROOT_PATH . '/lib/models/hrfunct/EmpInfo.php';
require_once ROOT_PATH . '/lib/models/eimadmin/JobTitle.php';
require_once ROOT_PATH . '/lib/models/eimadmin/ProjectAdminGateway.php';

/**
 * Assigns roles at login tells the rest
 * of the world if authorized.
 *
 */
class authorize {

    /**
     * Class constants
     *
     */
    public $roleAdmin = "Admin";
    public $roleSupervisor = "Supervisor";
    public $roleESS = "ESS";
    public $roleProjectAdmin = "ProjectAdmin";
    public $roleHiringManager = "HiringManager";
    public $roleInterviewer = "Interviewer";
    private static $currentUserId = null;

    const AUTHORIZE_ROLE_ADMIN = 'Admin';
    const AUTHORIZE_ROLE_SUPERVISOR = 'Supervisor';
    const AUTHORIZE_ROLE_ESS = 'ESS';
    const AUTHORIZE_ROLE_PROJECT_ADMIN = "ProjectAdmin";

    const YES = 'Yes';
    const NO = 'No';

    /**
     * class atributes
     *
     */
    private $employeeID;
    private $isAdmin;
    private $roles;

    public function setEmployeeId($employeeId) {
        $this->employeeID = $employeeId;
    }

    public function getEmployeeId() {
        return $this->employeeID;
    }

    public function setIsAdmin($isAdmin) {
        $this->isAdmin = $isAdmin;
    }

    public function getIsAdmin() {
        return $this->isAdmin;
    }

    public function setRoles($roles) {
        $this->roles = $roles;
    }

    public function getRoles() {
        return $this->roles;
    }

    public static function getCurrentUserId() {
        if (empty(self::$currentUserId)) {
            self::$currentUserId = @$_SESSION['user'];
        }
        return self::$currentUserId;
    }

    /**
     * Class contructor
     *
     * @param String $employeeId
     * @param String $isAdmin
     */
    public function __construct($employeeId, $isAdmin) {
        $this->setEmployeeId($employeeId);
        $this->setIsAdmin($isAdmin);

        $this->setRoles($this->_roles());
    }

    /**
     * Constructs roles
     *
     * @return boolean[]
     */
    private function _roles() {
        $roles = null;
        $isAdmin = $this->getIsAdmin();
        $empId = (int) $this->getEmployeeId();

        if ($isAdmin === authorize::YES) {
            $roles[$this->roleAdmin] = true;
        } else {
            $roles[$this->roleAdmin] = false;
        }

        $roles[$this->roleSupervisor] = $this->_checkIsSupervisor();
        $roles[$this->roleProjectAdmin] = $this->_checkIsProjectAdmin();
        $roles[$this->roleHiringManager] = $this->_checkIsHiringManager();
        $roles[$this->roleInterviewer] = $this->_checkIsInterviewer();

        if (!empty($empId)) {
            $roles[$this->roleESS] = true;
        } else {
            $roles[$this->roleESS] = false;
        }

        return $roles;
    }

    /**
     * Check whether there are any subordinates
     *
     * @return boolean
     */
    private function _checkIsSupervisor() {

        $id = $this->getEmployeeId();

        $objReportTo = new EmpRepTo();

        $subordinates = $objReportTo->getEmpSub($id);

        if (isset($subordinates[0]) && is_array($subordinates[0])) {
            return true;
        }

        return false;
    }

    /**
     * Check whether the user is a project admin
     *
     * @param int $projectId Project for which to check. If not given, all projects are checked.
     * @return boolean
     */
    private function _checkIsProjectAdmin($projectId = null) {

        $projectAdmin = false;
        $id = $this->getEmployeeId();

        if (!empty($id)) {
            $gw = new ProjectAdminGateway();
            $projectAdmin = $gw->isAdmin($id, $projectId);
        }

        return $projectAdmin;
    }

    /**
     * Check whether the user is an HiringManager that can approve job offers
     *
     * @return boolean True if an hiring manager, false otherwise
     */
    private function _checkIsHiringManager() {

        $isHiringManager = false;
        $id = $this->getEmployeeId();

        if (!empty($id)) {
            $empInfo = new EmpInfo();
            $isHiringManager = $empInfo->isHiringManager($id);
        }

        return $isHiringManager;
    }

    /**
     * Check whether the user is an Interviewer who can interview candidates
     *
     * @return boolean True if an interviewer, false otherwise
     */
    private function _checkIsInterviewer() {

        $isInterviewer = false;
        $id = $this->getEmployeeId();

        if (!empty($id)) {
            $empInfo = new EmpInfo();
            $isInterviewer = $empInfo->isInterviewer($id);
        }

        return $isInterviewer;
    }

    /**
     * Checks whether an admin
     *
     * @return boolean
     */
    public function isAdmin() {
        return $this->_chkRole($this->roleAdmin);
    }

    /**
     * Checks whether an supervisor
     *
     * @return boolean
     */
    public function isSupervisor() {
        return $this->_chkRole($this->roleSupervisor);
    }

    /**
     * Checks whether a project admin
     *
     * @return boolean true if a project admin. False otherwise
     */
    public function isProjectAdmin() {
        return $this->_chkRole($this->roleProjectAdmin);
    }

    /**
     * Checks whether an HiringManager
     *
     * @return boolean true if an HiringManager. False otherwise
     */
    public function isHiringManager() {
        return $this->_chkRole($this->roleHiringManager);
    }

    /**
     * Checks whether an Interviewer
     *
     * @return boolean true if an Interviewer. False otherwise
     */
    public function isInterviewer() {
        return $this->_chkRole($this->roleInterviewer);
    }

    /**
     * Checks whether an ESS
     *
     * @return boolean
     */
    public function isESS() {
        return $this->_chkRole($this->roleESS);
    }

    /**
     * Checks whether the particular employee is
     * the supervisor of the subordinate concerned
     *
     * @param unknown_type $subordinateId
     * @return boolean
     */
    public function isTheSupervisor($subordinateId) {
        $id = $this->getEmployeeId();

        $objReportTo = new EmpRepTo();

        $subordinates = $objReportTo->getEmpSub($id);

        for ($i = 0; $i < count($subordinates); $i++) {
            if (isset($subordinates[$i]) && is_array($subordinates[$i]) && ($subordinateId == $subordinates[$i][1])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether employee is a project admin of the
     * given project.
     *
     * @param int $projectId The project id
     * @return bool true if a project admin, false otherwise
     */
    public function isProjectAdminOf($projectId) {
        return $this->_checkIsProjectAdmin($projectId);
    }

    /**
     * Test whether element at pos of the array is equal to match
     *
     * @param Array array
     * @param String match
     * @param int pos
     */
    private function searchArray($array, $match, $pos) {
        if ($array[$pos] == $match) {
            return true;
        }
        return false;
    }

    /**
     * Delegates all checks for all is<Role>
     * functions
     *
     * @param String $role
     * @return boolean
     */
    private function _chkRole($role) {
        $roles = $this->getRoles();

        if (isset($roles[$role]) && $roles[$role]) {
            return true;
        }

        return false;
    }

    /**
     * Returns the first role out of the array of
     * roles sent
     *
     * @param String[] $roleArr
     * @return String/boolean
     */
    public function firstRole($roleArr) {
        for ($i = 0; $i < count($roleArr); $i++) {
            if ($this->_chkRole($roleArr[$i])) {
                return $roleArr[$i];
            }
        }

        return false;
    }

    public function isActionPermitted($action) {

        $permitted = false;

        switch ($action) {

            case 'TCP' : if (!$this->isAdmin() && !$this->isSupervisor()) {
                    $permitted = false;
                } else {
                    $permitted = true;
                }
                break;

            case 'CST' : if (!$this->isAdmin() && !$this->isSupervisor()) {
                    $permitted = false;
                } else {
                    $permitted = true;
                }
                break;

            case 'PAC' : if (!$this->isAdmin() && !$this->isProjectAdmin()) {
                    $permitted = false;
                } else {
                    $permitted = true;
                }
                break;

            default : if ($this->isAdmin()) {
                    $permitted = true;
                } else {
                    $permitted = false;
                }
                break;
        }

        return $permitted;
    }

}

?>
