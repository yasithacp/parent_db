<?php session_start();?>
<html>
<body>


<style type="text/css">

	div.messageBalloon_warning {
	       	margin: 10px 30px 10px 30px;
	        width:auto;
	        font-size:14px;
	        padding:8px 8px 8px 20px;
	        font-weight: bold;
	        border:solid 1px #ffee99;
	        background-color:#ffffee;
	        color:#660000;
	}
	
	div.formbuttons{
		margin: 10px 30px 10px 30px;
	}
	

</style>
<div  style="">
	<?php

	if(isset($_SESSION['errorLicenceExpiry'])){?>
			<div class="messageBalloon_warning" id="messagebar">
			    <span style="font-weight: bold;"> There is an error on license expiry date validation. Please check license key. <br>
			    [Error Code-<?php echo $_SESSION['errorCodeLicenceExpiry']?>]
			    	
			    </span>
			</div>
	<?php }else{?>
	<div class="messageBalloon_warning" id="messagebar">
	    <span style="font-weight: bold;"><h2>Your Commercial License Has Expired!</h2><br />
Commercial license enables you to use the software without any restrictions and <br />keeps your
product up to date with security patches and stable versions of upgrades of the OrangeHRM software.<br /><br />
Please contact OrangeHRM sales team to renew your license.<br /><br />
Thank you for using OrangeHRM.<br />
Email - sales@orangehrm.com<br />
Phone - +1-914-458-4254<br></br>
	    	
	    </span>
	</div>
	<div class="formbuttons">
	                <input type="button" name="Skip" value="Skip" onClick="javascript:parent.location.href='../index.php?skip_le=1'">
	</div>
	<?php }?>
</div>

</body>
</html>