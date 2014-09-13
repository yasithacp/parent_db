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

/* For logging PHP errors */
include_once('lib/confs/log_settings.php');

session_start();

/**
 * This if case checks whether the user is logged in. If so it will decorate User object with the user's user role.
 * This decorated user object is only used to determine menu accessibility. This decorated user object should not be
 * used for any other purposess. This if case will be dicarded when the whole system is converted to symfony.
 */
if (file_exists('symfony/config/databases.yml')) {
    if (isset($_SESSION['user'])) {

        define('SF_APP_NAME', 'orangehrm');
        define('SF_ENV', 'prod');
        define('SF_CONN', 'doctrine');


        require_once(dirname(__FILE__) . '/symfony/config/ProjectConfiguration.class.php');
        $configuration = ProjectConfiguration::getApplicationConfiguration(SF_APP_NAME, 'prod', true);
        new sfDatabaseManager($configuration);
        $context = sfContext::createInstance($configuration);

        if ($_SESSION['isAdmin'] == "Yes") {
            $userRoleArray['isAdmin'] = true;
        } else {
            $userRoleArray['isAdmin'] = false;
        }

        $userRoleArray['isSupervisor'] = $_SESSION['isSupervisor'];
        $userRoleArray['isProjectAdmin'] = $_SESSION['isProjectAdmin'];
        $userRoleArray['isHiringManager'] = $_SESSION['isHiringManager'];
        $userRoleArray['isInterviewer'] = $_SESSION['isInterviewer'];

        if ($_SESSION['empNumber'] == null) {
            $userRoleArray['isEssUser'] = false;
        } else {
            $userRoleArray['isEssUser'] = true;
        }

        $userObj = new User();

        $simpleUserRoleFactory = new SimpleUserRoleFactory();
        $decoratedUser = $simpleUserRoleFactory->decorateUserRole($userObj, $userRoleArray);
        $decoratedUser->setEmployeeNumber($_SESSION['empNumber']);
        $decoratedUser->setUserId($_SESSION['user']);

        $accessibleTimeMenuItems = $decoratedUser->getAccessibleTimeMenus();
        $accessibleTimeSubMenuItems = $decoratedUser->getAccessibleTimeSubMenus();
        $accessibleRecruitmentMenuItems = $decoratedUser->getAccessibleRecruitmentMenus();
        $attendanceMenus = $decoratedUser->getAccessibleAttendanceSubMenus();
        $reportsMenus = $decoratedUser->getAccessibleReportSubMenus();
        $recruitHomePage = './symfony/web/index.php/recruitment/viewCandidates';
        
        $i18n = $context->getI18N();
        $cultureElements = explode('_', $context->getUser()->getCulture()); // Used in <html> tag
                
    }
}

ob_start();

define('ROOT_PATH', dirname(__FILE__));

if (!is_file(ROOT_PATH . '/lib/confs/Conf.php')) {
    header('Location: ./install.php');
    exit();
}

if (!isset($_SESSION['user'])) {

    header("Location: ./symfony/web/index.php/auth/login");
    exit();
}

if (isset($_GET['ACT']) && $_GET['ACT'] == 'logout') {
    session_destroy();
    setcookie('Loggedin', '', time() - 3600, '/');
    header("Location: ./symfony/web/index.php/auth/login");
    exit();
}

/* Sanitising $_GET parameters: Begins */

if (!empty($_GET)) {
    
    $a = array();
    
    foreach ($_GET as $key => $value) {
        $a[$key] = htmlspecialchars($value);
    }
    
    $_GET = $a;
    
}

/* Sanitising $_GET parameters: Ends */

/* Loading disabled modules: Begins */

require_once ROOT_PATH . '/lib/common/ModuleManager.php';

$disabledModules = array();

if (isset($_SESSION['admin.disabledModules'])) {
    
    $disabledModules = $_SESSION['admin.disabledModules'];
    
} else {
    
    $moduleManager = new ModuleManager();    
    $disabledModules = $moduleManager->getDisabledModuleList();
    $_SESSION['admin.disabledModules'] = $disabledModules;    
    
}

/* Loading disabled modules: Ends */

define('Admin', 'MOD001');
define('PIM', 'MOD002');
define('MT', 'MOD003');
define('Report', 'MOD004');
define('Leave', 'MOD005');
define('TimeM', 'MOD006');
define('Benefits', 'MOD007');
define('Recruit', 'MOD008');
define('Perform', 'MOD009');
define('Parent', 'MOD010');

$arrRights = array('add' => false, 'edit' => false, 'delete' => false, 'view' => false);
$arrAllRights = array(Admin => $arrRights,
    PIM => $arrRights,
    MT => $arrRights,
    Report => $arrRights,
    Leave => $arrRights,
    TimeM => $arrRights,
    Benefits => $arrRights,
    Recruit => $arrRights,
    Perform => $arrRights);

require_once ROOT_PATH . '/lib/models/maintenance/Rights.php';
require_once ROOT_PATH . '/lib/models/maintenance/UserGroups.php';
require_once ROOT_PATH . '/lib/common/CommonFunctions.php';
require_once ROOT_PATH . '/lib/common/Config.php';
require_once ROOT_PATH . '/lib/common/authorize.php';

$_SESSION['path'] = ROOT_PATH;
?>
<?php
/* Default modules */
if (!isset($_GET['menu_no_top'])) {
    if ($_SESSION['isAdmin'] == 'Yes') {
        $_GET['menu_no_top'] = "hr";
    } else if ($_SESSION['isSupervisor']) {
        $_GET['menu_no_top'] = "ess";
    } else {
        $_GET['menu_no_top'] = "ess";
    }
}

/** Clean Get variables that are used in URLs in page */
$varsToClean = array('uniqcode', 'isAdmin', 'pageNo', 'id', 'repcode', 'reqcode', 'menu_no_top');

foreach ($varsToClean as $var) {
    if (isset($_GET[$var])) {
        $_GET[$var] = CommonFunctions::cleanAlphaNumericIdField($_GET[$var]);
    }
}


/* For checking TimesheetPeriodStartDaySet status : Begins */

//This should be change using $timesheetPeriodService->isTimesheetPeriodDefined() method to support symfony version of the timesheet period 
if (Config::getTimePeriodSet()) {
    $_SESSION['timePeriodSet'] = 'Yes';
} else {
    $_SESSION['timePeriodSet'] = 'No';
}
/* For checking TimesheetPeriodStartDaySet status : Ends */

if ($_SESSION['isAdmin'] == 'Yes') {
    $rights = new Rights();

    foreach ($arrAllRights as $moduleCode => $currRights) {
        $arrAllRights[$moduleCode] = $rights->getRights($_SESSION['userGroup'], $moduleCode);
    }

    $ugroup = new UserGroups();
    $ugDet = $ugroup->filterUserGroups($_SESSION['userGroup']);

    $arrRights['repDef'] = $ugDet[0][2] == '1' ? true : false;
} else {

    /* Assign supervisors edit and view rights to the PIM
     * They have PIM rights over their subordinates, but they cannot add/delete
     * employees. But they have add/delete rights in the employee details page.
     */
    if ($_SESSION['isSupervisor']) {
        $arrAllRights[PIM] = array('add' => false, 'edit' => true, 'delete' => false, 'view' => true);
    }

    /*
     * Assign Manager's access to recruitment module
     */
    if ($_SESSION['isHiringManager'] || $_SESSION['isInterviewer']) {
        $arrAllRights[Recruit] = array('view' => true);
    }
}

switch ($_GET['menu_no_top']) {
    case "eim":
        $arrRights = $arrAllRights[Admin];
        break;
    case "hr" :
        $arrRights = $arrAllRights[PIM];
        break;
    case "mt" :
        $arrRights = $arrAllRights[MT];
        break;
    case "rep" :
        $arrRights = $arrAllRights[Report];
        break;
    case "leave" :
        $arrRights = $arrAllRights[Leave];
        break;
    case "time" :
        $arrRights = $arrAllRights[TimeM];
        break;
    case "recruit" :
        $arrRights = $arrAllRights[Recruit];
        break;
    case "perform" :
        $arrRights = $arrAllRights[Perform];
        break;
}
$_SESSION['localRights'] = $arrRights;

$styleSheet = CommonFunctions::getTheme();

$authorizeObj = new authorize($_SESSION['empID'], $_SESSION['isAdmin']);

// Default leave home page
$leavePeriodDefined = Config::isLeavePeriodDefined();
if (!$leavePeriodDefined) {
    if ($authorizeObj->isAdmin()) {
        $leaveHomePage = './symfony/web/index.php/leave/defineLeavePeriod';
    } else {
        $leaveHomePage = './symfony/web/index.php/leave/showLeavePeriodNotDefinedWarning';
    }
} else {
    if ($authorizeObj->isAdmin()) {
        $leaveHomePage = './symfony/web/index.php/leave/viewLeaveList/reset/1';
    } else if ($authorizeObj->isSupervisor()) {
        if ($authorizeObj->isAdmin()) {
            $leaveHomePage = './symfony/web/index.php/leave/viewLeaveList/reset/1';
        } else {
            $leaveHomePage = './symfony/web/index.php/leave/viewLeaveList/reset/1';
        }
    } else if ($authorizeObj->isESS()) {
        $leaveHomePage = './symfony/web/index.php/leave/viewMyLeaveList/reset/1';
    }
}

// Time module default pages
if (!$authorizeObj->isAdmin() && $authorizeObj->isESS()) {
    if ($_SESSION['timePeriodSet'] == 'Yes') {
        $timeHomePage = './symfony/web/index.php/time/viewMyTimeTimesheet';
    } else {
        $timeHomePage = './symfony/web/index.php/time/defineTimesheetPeriod';
    }

} else {
    if ($_SESSION['timePeriodSet'] == 'Yes') {
        $timeHomePage = './symfony/web/index.php/time/viewEmployeeTimesheet';
    } else {
        $timeHomePage = './symfony/web/index.php/time/defineTimesheetPeriod';
    }

}






/* Disabling Benefits module: Begins 
if (!$authorizeObj->isAdmin() && $authorizeObj->isESS()) {
    $beneftisHomePage = 'benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=Benefits_Schedule_Select_Year';
    $empId = $_SESSION['empID'];
    $year = date('Y');
    $personalHspSummary = "benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=Search_Hsp_Summary&empId=$empId&year=$year";
} else {
    $beneftisHomePage = 'benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=Benefits_Schedule_Select_Year';
    $personalHspSummary = 'benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=Hsp_Summary_Select_Year_Employee_Admin';
}
   Disabling Benefits module: Ends */







if ($authorizeObj->isESS()) {
    if ($_SESSION['timePeriodSet'] == 'Yes') {
        $timeHomePage = './symfony/web/index.php/attendance/punchIn';
    } else {
        $timeHomePage = './symfony/web/index.php/time/defineTimesheetPeriod';
    }
}

// Default page in admin module is the Company general info page.
$defaultAdminView = "GEN";
$allowAdminView = false;

if ($_SESSION['isAdmin'] == 'No') {
    if ($_SESSION['isProjectAdmin']) {

        // Default page for project admins is the Project Activity page
        $defaultAdminView = "PAC";

        // Allow project admins to view PAC (Project Activity) page only (in the admin module)
        // If uniqcode is not set, the default view is Project activity
        if ((!isset($_GET['uniqcode'])) || ($_GET['uniqcode'] == 'PAC')) {
            $allowAdminView = true;
        }
    }
}

require_once ROOT_PATH . '/lib/common/Language.php';
require_once ROOT_PATH . '/lib/common/menu/MenuItem.php';

$lan = new Language();

require_once ROOT_PATH . '/language/default/lang_default_full.php';
require_once($lan->getLangPath("full.php"));

require_once ROOT_PATH . '/themes/' . $styleSheet . '/menu/Menu.php';
$menuObj = new Menu();

/* Create menu items */
/* TODO: Extract to separate class */
$menu = array();

/* View for Admin users */
if ($_SESSION['isAdmin'] == 'Yes' || $arrAllRights[Admin]['view']) {
    $menuItem = new MenuItem("admin", $i18n->__("Admin"), "#");
    $menuItem->setCurrent($_GET['menu_no_top'] == "eim");

    $subs = array();

//    $sub = new MenuItem("companyinfo", $i18n->__("Organization"), "#");
//    $subsubs = array();
//    $subsubs[] = new MenuItem("companyinfo", $i18n->__("General Information"), "./symfony/web/index.php/admin/viewOrganizationGeneralInformation");
//    $subsubs[] = new MenuItem("companyinfo", $i18n->__("Locations"), "./symfony/web/index.php/admin/viewLocations");
//    $subsubs[] = new MenuItem("companyinfo", $i18n->__("Structure"), "./symfony/web/index.php/admin/viewCompanyStructure");
//
//    $sub->setSubMenuItems($subsubs);
//
//
//    $subs[] = $sub;
//
//    $sub = new MenuItem("job", $i18n->__("Job"), "#");
//    $subsubs = array();
//    $subsubs[] = new MenuItem("job", $i18n->__("Job Titles"), "./symfony/web/index.php/admin/viewJobTitleList");
//    $subsubs[] = new MenuItem("job", $i18n->__("Pay Grades"), "./symfony/web/index.php/admin/viewPayGrades");
//    $subsubs[] = new MenuItem("job", $i18n->__("Employment Status"), "./symfony/web/index.php/admin/employmentStatus");
//    $subsubs[] = new MenuItem("job", $i18n->__("Job Categories"), "./symfony/web/index.php/admin/jobCategory");
//    $subsubs[] = new MenuItem("job", $i18n->__("Work Shifts"), "./symfony/web/index.php/admin/workShift");
//    $sub->setSubMenuItems($subsubs);
//    $subs[] = $sub;
//
//    $sub = new MenuItem("qualifications", $i18n->__("Qualification"), "#");
//    $subsubs = array();
//    $subsubs[] = new MenuItem("qualifications", $i18n->__("Skills"), "./symfony/web/index.php/admin/viewSkills");
//    $subsubs[] = new MenuItem("qualifications", $i18n->__("Education"), "./symfony/web/index.php/admin/viewEducation");
//    $subsubs[] = new MenuItem("qualifications", $i18n->__("Licenses"), "./symfony/web/index.php/admin/viewLicenses");
//    $subsubs[] = new MenuItem("qualifications", $i18n->__("Languages"), "./symfony/web/index.php/admin/viewLanguages");
//    $sub->setSubMenuItems($subsubs);
//    $subs[] = $sub;
//
//    $sub = new MenuItem("memberships", $i18n->__("Memberships"), "./symfony/web/index.php/admin/membership", "rightMenu");
//    $subs[] = $sub;
//
//    $sub = new MenuItem("nationalities", $i18n->__("Nationalities"), "./symfony/web/index.php/admin/nationality", "rightMenu");
//    $subs[] = $sub;

    $sub = new MenuItem("users", $i18n->__("Users"), "./symfony/web/index.php/admin/viewSystemUsers", "rightMenu");
    $subsubs = array();

//    if (is_dir(ROOT_PATH . '/symfony/plugins/orangehrmSecurityAuthenticationPlugin') && $arrAllRights[Admin]['edit']) {
//        $subsubs[] = new MenuItem('users', $i18n->__("Configure Security Authentication"), './symfony/web/index.php/securityAuthentication/securityAuthenticationConfigure', 'rightMenu');
//    }

    $sub->setSubMenuItems($subsubs);
    $subs[] = $sub;

    $sub = new MenuItem("email", $i18n->__("Email Notifications"), "#");
    $subsubs = array();
    $subsubs[] = new MenuItem("email", $i18n->__("Configuration"), "./symfony/web/index.php/admin/listMailConfiguration");
//    $subsubs[] = new MenuItem("email", $i18n->__("Subscribe"), "./symfony/web/index.php/admin/viewEmailNotification");
    $sub->setSubMenuItems($subsubs);
    $subs[] = $sub;

//    $sub = new MenuItem("project", $i18n->__("Project Info"), "#");
//    $subsubs = array();
//    $subsubs[] = new MenuItem("project", $i18n->__("Customers"), "./symfony/web/index.php/admin/viewCustomers");
//    $subsubs[] = new MenuItem("project", $i18n->__("Projects"), "./symfony/web/index.php/admin/viewProjects");
//
//    $sub->setSubMenuItems($subsubs);
//    $subs[] = $sub;
//
//    $sub = new MenuItem("configuration", $i18n->__("Configuration"), "#");
//    $subsubs = array();
//    $subsubs[] = new MenuItem("configuration", $i18n->__("Localization"), "./symfony/web/index.php/admin/localization");
//    $subsubs[] = new MenuItem("configuration", $i18n->__("Modules"), "./symfony/web/index.php/admin/viewModules");
//    $sub->setSubMenuItems($subsubs);
//    $subs[] = $sub;
//
//    if (is_dir(ROOT_PATH . '/symfony/plugins/orangehrmAuditTrailPlugin') && $arrAllRights[Admin]['view']) {
//        $subs[] = new MenuItem('audittrail', $i18n->__("Audit Trail"), './symfony/web/index.php/audittrail/viewAuditTrail', 'rightMenu');
//    }
//
//    if (is_dir(ROOT_PATH . '/symfony/plugins/orangehrmLDAPAuthenticationPlugin') && $arrAllRights[Admin]['edit']) {
//        $subs[] = new MenuItem('ldap', $i18n->__("LDAP Configuration"), './symfony/web/index.php/ldapAuthentication/configureLDAPAuthentication', 'rightMenu');
//    }

    $menuItem->setSubMenuItems($subs);
    $menu[] = $menuItem;
} else {
    
    $subs = array();
    
//    if ($_SESSION['isProjectAdmin']) {
//        $subs[] = new MenuItem("project", $i18n->__("Projects"), "./symfony/web/index.php/admin/viewProjects", 'rightMenu');
//    }
    
    if (count($subs) > 0) {
        $menuItem = new MenuItem("admin", $i18n->__("Admin"), '#', 'rightMenu');
        $menuItem->setCurrent($_GET['menu_no_top'] == "eim");
        $menuItem->setSubMenuItems($subs);
        $menu[] = $menuItem;        
    }
}

define('PIM_MENU_TYPE', 'left');
$_SESSION['PIM_MENU_TYPE'] = PIM_MENU_TYPE;

/* PIM menu start */
if (($_SESSION['isAdmin'] == 'Yes' || $_SESSION['isSupervisor']) && $arrAllRights[PIM]['view']) {

    $menuItem = new MenuItem("pim", $i18n->__("PIM"), "./index.php?menu_no_top=hr&reset=1");
    $menuItem->setCurrent($_GET['menu_no_top'] == "hr");
    $enablePimMenu = false;
    if ((isset($_GET['menu_no_top'])) && ($_GET['menu_no_top'] == "hr") && isset($_GET['reqcode']) && $arrRights['view']) {
        $enablePimMenu = true;
    }
    $subs = array();
    if ($_SESSION['isAdmin'] == 'Yes') {

//        $sub = new MenuItem("configure", __("Configuration"), "#");
//        $subsubs = array();
//        $subsubs[] = new MenuItem("pimconfig", $i18n->__("Optional Fields"), "./symfony/web/index.php/pim/configurePim", "rightMenu");
//        $subsubs[] = new MenuItem("customfields", $i18n->__("Custom Fields"), "./symfony/web/index.php/pim/listCustomFields");
//        $subsubs[] = new MenuItem("customfields", $i18n->__("Data Import"), "./symfony/web/index.php/admin/pimCsvImport");
//        $subsubs[] = new MenuItem("customfields", $i18n->__("Reporting Methods"), "./symfony/web/index.php/pim/viewReportingMethods");
//        $subsubs[] = new MenuItem("customfields", $i18n->__("Termination Reasons"), "./symfony/web/index.php/pim/viewTerminationReasons");
//        $sub->setSubMenuItems($subsubs);
//        $subs[] = $sub;
    }

    $subs[] = new MenuItem("emplist", $i18n->__("Employee List"), "./symfony/web/index.php/pim/viewEmployeeList/reset/1", "rightMenu");
    if ($arrAllRights[PIM]['add']) {
        $subs[] = new MenuItem("empadd", $i18n->__("Add Employee"), "./symfony/web/index.php/pim/addEmployee", "rightMenu");
    }

//    if ($_SESSION['isAdmin'] == 'Yes') {
//        $subs[] = new MenuItem("reports", $i18n->__("Reports"), "./symfony/web/index.php/core/viewDefinedPredefinedReports/reportGroup/3/reportType/PIM_DEFINED", "rightMenu");
//    }

    $menuItem->setSubMenuItems($subs);

    $menu[] = $menuItem;
}

/* Start leave menu */
if (($_SESSION['empID'] != null) || $arrAllRights[Leave]['view']) {
//    $menuItem = new MenuItem("leave", $i18n->__("Leave"), "./index.php?menu_no_top=leave&reset=1");
//    $menuItem->setCurrent($_GET['menu_no_top'] == "leave");
//
//    $subs = array();
//    $subsubs = array();
//
//    if ($authorizeObj->isAdmin() && $arrAllRights[Leave]['view']) {
//
//        $sub = new MenuItem("leavesummary", $i18n->__("Configure"), "#");
//
//        $subsubs[] = new MenuItem("leaveperiod", $i18n->__("Leave Period"), './symfony/web/index.php/leave/defineLeavePeriod', 'rightMenu');
//        $subsubs[] = new MenuItem("leavetypes", $i18n->__("Leave Types"), './symfony/web/index.php/leave/leaveTypeList');
//        $subsubs[] = new MenuItem("daysoff", $i18n->__("Work Week"), "./symfony/web/index.php/leave/defineWorkWeek");
//        $subsubs[] = new MenuItem("daysoff", $i18n->__("Holidays"), "./symfony/web/index.php/leave/viewHolidayList");
//
//        $sub->setSubMenuItems($subsubs);
//        $subs[] = $sub;
//    }
//
//    $subs[] = new MenuItem("leavesummary", $i18n->__("Leave Summary"), "./symfony/web/index.php/leave/viewLeaveSummary", 'rightMenu');
//
//    if ($authorizeObj->isSupervisor() && !$authorizeObj->isAdmin()) {
//        $subs[] = new MenuItem("leavelist", $i18n->__("Leave List"), './symfony/web/index.php/leave/viewLeaveList/reset/1', 'rightMenu');
//    }
//    if ($authorizeObj->isAdmin() && $arrAllRights[Leave]['view']) {
//        $subs[] = new MenuItem("leavelist", $i18n->__("Leave List"), './symfony/web/index.php/leave/viewLeaveList/reset/1', 'rightMenu');
//    }
//
//    if (($authorizeObj->isAdmin() && $arrAllRights[Leave]['add']) || $authorizeObj->isSupervisor()) {
//        $subs[] = new MenuItem("assignleave", $i18n->__("Assign Leave"), "./symfony/web/index.php/leave/assignLeave", 'rightMenu');
//    }
//
//    if ($authorizeObj->isESS()) {
//        $subs[] = new MenuItem("leavelist", $i18n->__("My Leave"), './symfony/web/index.php/leave/viewMyLeaveList/reset/1', 'rightMenu');
//        $subs[] = new MenuItem("applyLeave", $i18n->__("Apply"), "./symfony/web/index.php/leave/applyLeave", 'rightMenu');
//    }
//
//    if (file_exists('symfony/plugins/orangehrmLeaveCalendarPlugin/config/orangehrmLeaveCalendarPluginConfiguration.class.php')) {//if plugin is installed
//        $subs[] = new MenuItem("leavelist", $i18n->__("Leave Calendar"), './symfony/web/index.php/leavecalendar/showLeaveCalendar', 'rightMenu');
//    }
//    /* Emptying the leave menu items if leave period is not defined */
//    if (!$leavePeriodDefined) {
//        $subs = array();
//    }
//
//    $menuItem->setSubMenuItems($subs);
//    $menu[] = $menuItem;
}

/* Start time menu */
if (($_SESSION['empID'] != null) || $arrAllRights[TimeM]['view']) {
//    $menuItem = new MenuItem("time", $i18n->__("Time"), "./index.php?menu_no_top=time");
//    $menuItem->setCurrent($_GET['menu_no_top'] == "time");
//
//    /* Only show rest of menu if time period set */
//    if ($_SESSION['timePeriodSet'] == "Yes" && file_exists('symfony/config/databases.yml')) {
//        $subs = array();
//
//        // modified under restructure time menu story
//
//        $subsubs = array();
//        $subsubs0 = array();
//        $subsubs1 = array();
//        if ($accessibleTimeMenuItems != null) {
//            foreach ($accessibleTimeMenuItems as $ttt) {
//
//                $sub = new MenuItem("timesheets", __($ttt->getDisplayName()), $ttt->getLink(), 'rightMenu');
//
//                if ($ttt->getDisplayName() == "Timesheets") {
//
//                    foreach ($accessibleTimeSubMenuItems as $ctm) {
//
//                        $subsubs[] = new MenuItem("timesheets", __($ctm->getDisplayName()), $ctm->getLink());
//                    }
//
//                    $sub->setSubMenuItems($subsubs);
//                }
//                if ($ttt->getDisplayName() == "Attendance") {
//
//                    foreach ($attendanceMenus as $ptm) {
//                        $subsubs0[] = new MenuItem("timesheets", __($ptm->getDisplayName()), $ptm->getLink());
//                    }
//
//                    $sub->setSubMenuItems($subsubs0);
//                }
//
//                if ($ttt->getDisplayName() == "Reports") {
//
//                    foreach ($reportsMenus as $ptm) {
//                        $subsubs1[] = new MenuItem("timesheets", __($ptm->getDisplayName()), $ptm->getLink());
//                    }
//
//                    $sub->setSubMenuItems($subsubs1);
//                }
//
//                $subs[] = $sub;
//            }
//        }
//
//        $menuItem->setSubMenuItems($subs);
//    }
//    $menu[] = $menuItem;
}

/* Start recruitment menu */

if ($arrAllRights[Recruit]['view']) {

//
//    $menuItem = new MenuItem("recruit", $i18n->__("Recruitment"), "./index.php?menu_no_top=recruit");
//    $menuItem->setCurrent($_GET['menu_no_top'] == "recruit");
//
//    if (file_exists('symfony/config/databases.yml')) {
//        $subs = array();
//        foreach ($accessibleRecruitmentMenuItems as $tttt) {
//            $subs[] = new MenuItem("recruit", $tttt->getDisplayName(), $tttt->getLink(), "rightMenu");
//        }
//    }
//    $menuItem->setSubMenuItems($subs);
//    $menu[] = $menuItem;
}

/* Performance menu start */
//
//$menuItem = new MenuItem("perform", $i18n->__("Performance"), "index.php?uniqcode=KPI&menu_no_top=performance&uri=performance/viewReview/mode/new");
//$menuItem->setCurrent($_GET['menu_no_top'] == "perform");
//$enablePerformMenu = false;
//if ((isset($_GET['menu_no_top'])) && ($_GET['menu_no_top'] == "perform") && isset($_GET['reqcode']) && $arrRights['view']) {
//    $enablePerformMenu = true;
//}
//$subs = array();
//
//if ($arrAllRights[Perform]['add'] && ($_SESSION['isAdmin'] == 'Yes')) {
//    $subs[] = new MenuItem('definekpi', $i18n->__("KPI List"), "index.php?uniqcode=KPI&menu_no_top=performance&uri=performance/listDefineKpi");
//    $subs[] = new MenuItem('definekpi', $i18n->__("Add KPI"), "index.php?uniqcode=KPI&menu_no_top=performance&uri=performance/saveKpi");
//    $subs[] = new MenuItem('definekpi', $i18n->__("Copy KPI"), "index.php?uniqcode=KPI&menu_no_top=performance&uri=performance/copyKpi");
//    $subs[] = new MenuItem('definekpi', $i18n->__("Add Review"), "index.php?uniqcode=KPI&menu_no_top=performance&uri=performance/saveReview");
//}
//
//$subs[] = new MenuItem('definekpi', $i18n->__("Reviews"), "index.php?uniqcode=KPI&menu_no_top=performance&uri=performance/viewReview/mode/new");
//
//$menuItem->setSubMenuItems($subs);
//
//$menu[] = $menuItem;

/* Start ESS menu */
if ($_SESSION['isAdmin'] != 'Yes') {
    $menuItem = new MenuItem("ess", $i18n->__('My Info'), './symfony/web/index.php/pim/viewPersonalDetails?empNumber=' . $_SESSION['empID'], "rightMenu");

    $menuItem->setCurrent($_GET['menu_no_top'] == "ess");
    $enableEssMenu = false;
    if ($_GET['menu_no_top'] == "ess") {
        $enableEssMenu = true;
    }

    $menu[] = $menuItem;
}







/* Disabling Benefits module: Begins
if (($_SESSION['empID'] != null) || $arrAllRights[Benefits]['view']) {
    $menuItem = new MenuItem("benefits", $lang_Menu_Benefits, "./index.php?menu_no_top=benefits");
    $menuItem->setCurrent($_GET['menu_no_top'] == "benefits");

    $subs = array();

    if ($_SESSION['isAdmin'] == "Yes" && $arrAllRights[Benefits]['view']) {
        $yearVal = date('Y');
        $sub = new MenuItem("hsp", $lang_Menu_Benefits_HealthSavingsPlan, "benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=Hsp_Summary&year={$yearVal}");
        $subsubs = array();
        $subsubs[] = new MenuItem("hsp", $lang_Menu_Benefits_Define_Health_savings_plans, "benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=Define_Health_Savings_Plans");
        $subsubs[] = new MenuItem("hsp", $lang_Menu_Benefits_EmployeeHspSummary, "benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=Hsp_Summary&year={$yearVal}");
        $subsubs[] = new MenuItem("hsp", $lang_Benefits_HspPaymentsDue, "benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=List_Hsp_Due");
        $subsubs[] = new MenuItem("hsp", $lang_Benefits_HspExpenditures, "benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=Hsp_Expenditures_Select_Year_And_Employee");
        $subsubs[] = new MenuItem("hsp", $lang_Benefits_HspUsed, "benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=Hsp_Used_Select_Year&year={$yearVal}");
        $sub->setSubMenuItems($subsubs);
        $subs[] = $sub;
    } else {

        if (Config::getHspCurrentPlan() > 0) {
            $sub = new MenuItem("hsp", $lang_Menu_Benefits_HealthSavingsPlan, $personalHspSummary);
        } else {
            $sub = new MenuItem("hsp", $lang_Menu_Benefits_HealthSavingsPlan, "benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=Hsp_Not_Defined");
        }
        $subsubs = array();

        if ($authorizeObj->isESS()) {
            $yearVal = date('Y');
            $subsubs[] = new MenuItem("hsp", $lang_Benefits_HspExpenditures, "benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=Hsp_Expenditures&year={$yearVal}&employeeId={$_SESSION['empID']}");

            if (Config::getHspCurrentPlan() > 0) { // Show only when Admin has defined a HSP plan
                $subsubs[] = new MenuItem("hsp", $lang_Benefits_HspRequest, "benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=Hsp_Request_Add_View");
                $subsubs[] = new MenuItem("hsp", $lang_Menu_Benefits_PersonalHspSummary, $personalHspSummary);
            }
        }
        $sub->setSubMenuItems($subsubs);
        $subs[] = $sub;
    }

    if ($_SESSION['isAdmin'] == "Yes" && $arrAllRights[Benefits]['view']) {
        $sub = new MenuItem("payrollschedule", $lang_Menu_Benefits_PayrollSchedule, "benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=Benefits_Schedule_Select_Year");

        $subsubs = array();
        $subsubs[] = new MenuItem("payrollschedule", $lang_Benefits_ViewPayrollSchedule, "benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=Benefits_Schedule_Select_Year");
        if ($arrAllRights[Benefits]['add']) {
            $subsubs[] = new MenuItem("payrollschedule", $lang_Benefits_AddPayPeriod, "benefitscode/lib/controllers/CentralController.php?benefitcode=Benefits&action=View_Add_Pay_Period");
        }
        $sub->setSubMenuItems($subsubs);

        $subs[] = $sub;
    }

    $menuItem->setSubMenuItems($subs);
    $menu[] = $menuItem;
}
   Disabling Benefits module: Ends */


/* Start parent menu */
$menuItem = new MenuItem("help", $i18n->__("Parent's Info"), '#');
$subs = array();
$subs[] = new MenuItem("forum", $i18n->__("Add Parent's Info"), "./symfony/web/index.php/parent/addParentInfo", 'rightMenu');
$subs[] = new MenuItem("support", $i18n->__("View Parent's Info"), "./symfony/web/index.php/parent/viewParentInfo", 'rightMenu');
$subs[] = new MenuItem("gateway", $i18n->__("SMS Gateway"), "./symfony/web/index.php/parent/smsGateway", 'rightMenu');
$subs[] = new MenuItem("mail", $i18n->__("E-mail Portal"), "./symfony/web/index.php/parent/mailSender", 'rightMenu');

$menuItem->setSubMenuItems($subs);
$menu[] = $menuItem;
/* End of main menu definition */




/* Start help menu */
//$menuItem = new MenuItem("help", $i18n->__("Help"), '#');
//$subs = array();
//$subs[] = new MenuItem("support", $i18n->__("Support"), "http://www.orangehrm.com/support-plans.php?utm_source=application_support&utm_medium=app_url&utm_campaign=orangeapp", '_blank');
//$subs[] = new MenuItem("forum", $i18n->__("Forum"), "http://www.orangehrm.com/forum/", '_blank');
//$subs[] = new MenuItem("blog", $i18n->__("Blog"), "http://www.orangehrm.com/blog/", '_blank');
//$subs[] = new MenuItem("support", $i18n->__("Training"), "http://www.orangehrm.com/training.php?utm_source=application_traning&utm_medium=app_url&utm_campaign=orangeapp", '_blank');
//$subs[] = new MenuItem("support", $i18n->__("Add-Ons"), "http://www.orangehrm.com/addon-plans.shtml?utm_source=application_addons&utm_medium=app_url&utm_campaign=orangeapp", '_blank');
//$subs[] = new MenuItem("support", $i18n->__("Customizations"), "http://www.orangehrm.com/customizations.php?utm_source=application_cus&utm_medium=app_url&utm_campaign=orangeapp", '_blank');
//$subs[] = new MenuItem("bug", $i18n->__("Bug Tracker"), "http://sourceforge.net/apps/mantisbt/orangehrm/view_all_bug_page.php", '_blank');
//
//$menuItem->setSubMenuItems($subs);
//$menu[] = $menuItem;
/* End of main menu definition */

/* Checking for disabled modules: Begins */

$count = count($menu);
foreach ($disabledModules as $key => $module) {
        $disabledModules[$key] = __(ucwords($module));
}
for ($i=0; $i<$count; $i++) {

    if (in_array($menu[$i]->getMenuText(), $disabledModules)) {
        unset($menu[$i]);
    }
    
}

/* Checking for disabled modules: Ends */

$welcomeMessage = preg_replace('/#username/', ((isset($_SESSION['fname'])) ? $_SESSION['fname'] : ''), $i18n->__($lang_index_WelcomeMes));

if (isset($_SESSION['ladpUser']) && $_SESSION['ladpUser'] && $_SESSION['isAdmin'] != "Yes") {
    $optionMenu = array();
} else {
    $optionMenu[] = new MenuItem("changepassword", $i18n->__($lang_index_ChangePassword),
                    "./symfony/web/index.php/admin/changeUserPassword");
}

$optionMenu[] = new MenuItem("logout", __($lang_index_Logout), './symfony/web/index.php/auth/logout', '_parent');

// Decide on home page
if (($_GET['menu_no_top'] == "eim") && ($arrRights['view'] || $allowAdminView)) {
    $uniqcode = isset($_GET['uniqcode']) ? $_GET['uniqcode'] : $defaultAdminView;
    $isAdmin = isset($_GET['isAdmin']) ? ('&amp;isAdmin=' . $_GET['isAdmin']) : '';

    /* TODO: Remove this pageNo variable */
    $pageNo = isset($_GET['pageNo']) ? '&amp;pageNo=1' : '';
    if (isset($_GET['uri'])) {
        $uri = (substr($_GET['uri'], 0, 11) == 'performance') ? $_GET['uri'] : 'performance/viewReview/mode/new';
        $home = './symfony/web/index.php/' . $uri;
    } else {
        $home = "./symfony/web/index.php/admin/viewOrganizationGeneralInformation"; //TODO: Use this after fully converted to Symfony
    }
} elseif (($_GET['menu_no_top'] == "hr") && $arrRights['view']) {

    $home = "./symfony/web/index.php/pim/viewEmployeeList/reset/1";
    if (isset($_GET['uri'])) {
        $home = $_GET['uri'];
    } elseif (isset($_GET['id'])) {
        $home = "./symfony/web/index.php/pim/viewPersonalDetails?empNumber=" . $_GET['id'];
    }
} elseif ($_GET['menu_no_top'] == "ess") {
    $home = './symfony/web/index.php/pim/viewPersonalDetails?empNumber=' . $_SESSION['empID'];
} elseif ($_GET['menu_no_top'] == "leave") {
    $home = $leaveHomePage;
} elseif ($_GET['menu_no_top'] == "time") {
    $home = $timeHomePage;
} elseif ($_GET['menu_no_top'] == "benefits") {
    $home = $beneftisHomePage;
} elseif ($_GET['menu_no_top'] == "recruit") {
    $home = $recruitHomePage;
} elseif ($_GET['menu_no_top'] == "performance") {
    $uri = (substr($_GET['uri'], 0, 11) == 'performance') ? $_GET['uri'] : 'performance/viewReview/mode/new';
    $home = './symfony/web/index.php/' . $uri;
} else {
    $rightsCount = 0;
    foreach ($arrAllRights as $moduleRights) {
        foreach ($moduleRights as $right) {
            if ($right) {
                $rightsCount++;
            }
        }
    }

    if ($rightsCount === 0) {
        $home = 'message.php?case=no-rights&type=notice';
    } else {
        $home = "";
    }
}

if (isset($_SESSION['load.admin.viewModules'])) {
    $home = "./symfony/web/index.php/admin/viewModules";
    unset($_SESSION['load.admin.viewModules']);
}

if (isset($_SESSION['load.admin.localization'])) {
    $home = "./symfony/web/index.php/admin/localization";
    unset($_SESSION['load.admin.localization']);
}

require_once 'le/le.php';
$licenceExpiry	=	new LicenceExpiryService();
$home			=	$licenceExpiry->doLicenceExpiry($home);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $cultureElements[0]; ?>" lang="<?php echo $cultureElements[0]; ?>">
    <head>
        <title>Royal College - Parents information</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="themes/<?php echo $styleSheet; ?>/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="favicon.ico" rel="icon" type="image/gif"/>
        <script type="text/javaScript" src="scripts/archive.js"></script>
<?php
$menuObj->getCSS();
$menuObj->getJavascript($menu);
?>
    </head>

    <body>
        <div id="companyLogoHeader"></div>

<!--	<div align='right' style="height:0px; width:100%;">-->
<!--	--><?php
//
//	try
//{
//if($xml = @simplexml_load_file("http://www.orangehrm.com/global_update/orangehrm_updates.xml"))
//	{
//	foreach($xml->children() as $child)
//	  {
//		$data[] = $child;
//	  }
//	for($i=0; $i<count($data); $i=$i+3)
//		{
//			echo "<div style='width: auto; float:right; margin: 12px 2px 0px 0px;'>
//				<table border='0'>
//					<tr><td><a href='".$data[$i]."' target='_blank' style='text-decoration:none;'><font style='color:green; font-weight:bold; font-size:12px;'>".$i18n->__($data[$i+1])."</font>&nbsp;&nbsp;</td></tr>
//					<tr><td align='center'><a href='".$data[$i]."' target='_blank' style='text-decoration:none;'><font style='font-size:10px; font-weight:bold;'>".$i18n->__($data[$i+2])."</font></a></td></tr>
//				</table>
//			      </div>";
//		}
//	}
//else
//	{
//		echo "";
//	}
//}
//catch(exception $e)
//{
//	echo "";
//}
//
//	?><!--	-->
<!--	</div>	-->


	<div id="rightHeaderImage"></div>
<?php $menuObj->getMenu($menu, $optionMenu, $welcomeMessage); ?>

        <div id="main-content" style="float:left;height:640px;text-align:center;padding-left:0px;">
            <iframe style="display:block;margin-left:auto;margin-right:auto;width:100%;" src="<?php echo $home; ?>" id="rightMenu" name="rightMenu" height="100%;" frameborder="0"></iframe>

        </div>

        <div id="main-footer" style="clear:both;text-align:center;height:20px;">
            Copyright &copy; 2014 <a href="http://royalcollegesds.lk/" target="_blank">Royal College.</a> All rights reserved.
        </div>
        <script type="text/javascript">
            //<![CDATA[
            function exploitSpace() {
                dimensions = windowDimensions();

                if (document.getElementById("main-content")) {
                    document.getElementById("main-content").style.height = (dimensions[1]  - 100 - <?php echo $menuObj->getMenuHeight(); ?>) + 'px';
                }

                if (document.getElementById("main-content")) {
                    if (dimensions[0] < 940) {
                        dimensions[0] = 940;
                    }

                    document.getElementById("main-content").style.width = (dimensions[0] - <?php echo $menuObj->getMenuWidth(); ?>) + 'px';
                }
            }

            exploitSpace();
            window.onresize = exploitSpace;
            //]]>
        </script>

    </body>
</html>
<?php ob_end_flush(); ?>
