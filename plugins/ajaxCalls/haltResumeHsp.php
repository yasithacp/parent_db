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


ob_start();

session_start();

if (!defined('ROOT_PATH'))
	define('ROOT_PATH', $_SESSION['path']);

require_once ROOT_PATH . '/benefitscode/lib/models/benefits/Hsp.php';
require_once ROOT_PATH . '/benefitscode/lib/models/benefits/mail/HspMailNotification.php';

try {
	$hspSummaryId 	= $_GET['hspSummaryId'];
	$newHspStatus   = $_GET['newHspStatus'];
	$empId		= $_GET['empId'];

	$hsp = new Hsp();
	$hsp->setEmployeeId($empId);
	$hsp->setSummaryId($hspSummaryId);
	$hsp->setHspPlanStatus($newHspStatus);

	$hspMailNotification = new HspMailNotification();

	if(Hsp::updateStatus($hspSummaryId, $newHspStatus)) {
		switch ($newHspStatus) {
			case Hsp::HSP_STATUS_HALTED :
				$hspMailNotification -> sendHspPlanHaltedByHRAdminNotification($hsp);
				break;
			case Hsp::HSP_STATUS_ACTIVE :
				break;
			case Hsp::HSP_STATUS_ESS_HALTED :
				$hspMailNotification -> sendHspPlanHaltedByHRAdminOnRequestNotification($hsp);
				break;
			case Hsp::HSP_STATUS_PENDING_HALT :
				$hspMailNotification->sendHspPlanHaltRequestedByESSNotification($hsp);
				break;
		}
		echo 'done:'. $newHspStatus;
	} else {
		echo 'fail:Error while changing the new HSP status';
	}
} catch(Exception $e) {
	echo 'fail:Error while performing the requested action';
}

?>
