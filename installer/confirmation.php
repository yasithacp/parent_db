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
function confirm() {
	document.frmInstall.actionResponse.value  = 'CONFIRMED';
	document.frmInstall.submit();
}
</script>
<link href="style.css" rel="stylesheet" type="text/css" />


  <div id="content">
	<h2>Step 5: Confirmation</h2>

        <p>All information required for OrangeHRM installation collected in the earlier
         steps are given below. On confirmation the installer will create the database,
         database users, configuration file, etc.<br />
		 Click <b>[Install]</b> to continue.
		 </p>

         <p><font color="Red"><?php echo isset($error) ? $error : ''?></font></p>

        <table cellpadding="0" cellspacing="0" border="0" class="table">
		<tr>
			<th colspan="3" align="left" class="th">Details</th>
		</tr>
		<tr>
			<td class="tdComponent">Host Name</td>
			<td class="tdValues"><?php echo $_SESSION['dbInfo']['dbHostName']?></td>
		</tr>
		<tr>
			<td class="tdComponent">Database Host Port</td>
			<td class="tdValues"><?php echo $_SESSION['dbInfo']['dbHostPort']?></td>
		</tr>
		<tr>
			<td class="tdComponent">Database Name</td>
			<td class="tdValues"><?php echo $_SESSION['dbInfo']['dbName']?></td>
		</tr>
<?php if($_SESSION['dbCreateMethod'] == 'new') { ?>		
		<tr>
			<td class="tdComponent">Priviledged Database User-name</td>
			<td class="tdValues"><?php echo $_SESSION['dbInfo']['dbUserName']?></td>
		</tr>
<?php } ?>
<?php if(isset($_SESSION['dbInfo']['dbOHRMUserName'])) { ?>
		<tr>
			<td class="tdComponent">OrangeHRM Database User-name</td>
			<td class="tdValues"><?php echo $_SESSION['dbInfo']['dbOHRMUserName']?></td>
		</tr>
<?php } ?>
		<tr>
			<td class="tdComponent">OrangeHRM Admin User Name</td>
			<td class="tdValues"><?php echo $_SESSION['defUser']['AdminUserName']?></td>
		</tr>
<?php if ($_SESSION['ENCRYPTION'] == "Active") {  ?>
		<tr>
			<td class="tdComponent">Data Encryption</td>
			<td class="tdValues">Data Encryption is on. Employee Social Security Number and Employee Basic Salary would be encrypted.
			<br>Please backup encryption key located at lib/confs/cryptokeys/</td>
		</tr>
<?php } elseif ($_SESSION['ENCRYPTION'] == "Failed") { ?>
		<tr>
			<td class="tdComponent">Data Encryption</td>
			<td class="tdValues">Data Encryption configuration failed. Data Encryption would not be enabled.</td>
		</tr>
<?php } ?>
</table>
		<br />
		<input class="button" type="button" value="Back" onclick="back();" tabindex="3"/>
		<input class="button" type="button" value="Cancel Install" onclick="cancel();" tabindex="2"/>
        <input class="button" type="button" value="Install" onclick="confirm();" tabindex="1"/>
  </div>
