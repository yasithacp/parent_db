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

?>
<script language="JavaScript">

function submitDefUserInfo() {

	frm = document.frmInstall;
	if(frm.OHRMAdminUserName.value.length < 5) {
		alert('OrangeHRM Admin User-name should be at least 5 char. long!');
		frm.OHRMAdminUserName.focus();
		return;
	}

	if(frm.OHRMAdminPassword.value == '') {
		alert('OrangeHRM Admin Password left Empty!');
		frm.OHRMAdminPassword.focus();
		return;
	}

	if(frm.OHRMAdminPassword.value != frm.OHRMAdminPasswordConfirm.value) {
		alert('OrangeHRM Admin Password and Confirm OrangeHRM Admin Password don\'t match!');
		frm.OHRMAdminPassword.focus();
		return;
	}

document.frmInstall.actionResponse.value  = 'DEFUSERINFO';
document.frmInstall.submit();
}
</script>
<link href="style.css" rel="stylesheet" type="text/css" />


<div id="content">
	<h2>Step 4: Admin User Creation</h2>

        <p>After OrangeHRM is configured you will need an Administrator Account to Login into OrangeHRM. <br />
        Please fill in the Username and User Password for the Administrator login. </p>

        <table cellpadding="0" cellspacing="0" border="0" class="table">
<tr><th colspan="3" align="left">Admin User Creation</th></tr>
<tr>
	<td class="tdComponent_n">OrangeHRM Admin Username</td>
	<td class="tdValues_n"><input type="text" name="OHRMAdminUserName" value="Admin" tabindex="1"/></td>
</tr>
<tr>
	<td class="tdComponent_n">OrangeHRM Admin User Password</td>
	<td class="tdValues_n"><input type="password" name="OHRMAdminPassword" value="" tabindex="2"/></td>
</tr>
<tr>
	<td class="tdComponent_n">Confirm OrangeHRM Admin User Password</td>
	<td class="tdValues_n"><input type="password" name="OHRMAdminPasswordConfirm" value="" tabindex="3"/></td>
</tr>

</table><br />
<input class="button" type="button" value="Back" onclick="back();" tabindex="5"/>
<input type="button" value="Next" onclick="submitDefUserInfo()" tabindex="4"/>
</div>