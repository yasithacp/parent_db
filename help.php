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


session_start();

$role = $_SESSION['hp-role'];
$module = isset($_SESSION['hp-module'])?$_SESSION['hp-module']:'';
$action = isset($_SESSION['hp-action'])?$_SESSION['hp-action']:'';
$userType = isset($_SESSION['hp-userType'])?$_SESSION['hp-userType']:''; // Used for the help of creating users: Admin > Users

?>

<html>
<head>
<title>Help</title>
</head>
<body>
<form action="http://www.orangehrm.com/help/" name="frmHelp" method="post">
<input type="hidden" name="role" value="<?php echo $role;?>" />
<input type="hidden" name="module" value="<?php echo $module;?>" />
<input type="hidden" name="action" value="<?php echo $action;?>" />
<input type="hidden" name="userType" value="<?php echo $userType;?>" />
</form>
<script type="text/javascript">
document.frmHelp.submit();
</script>
</body>
</html>