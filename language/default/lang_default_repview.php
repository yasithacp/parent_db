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



$srchlist[0] = array( -1 , 0 , 1 );
$srchlist[1] = array( "-$lang_Leave_Common_Select-" , $lang_view_ID , $lang_Commn_name);

$searchby = $lang_empview_searchby;
$searchfor = $lang_empview_description;

$Previous = $lang_empview_previous;
$Next     = $lang_empview_next;
$norecorddisplay = "$lang_empview_norecorddisplay !";
$description = $lang_Commn_description ;
$search = $lang_empview_search;

$ADD_SUCCESS = $lang_empview_ADD_SUCCESS;
$UPDATE_SUCCESS = $lang_empview_UPDATE_SUCCESS ;
$DELETE_SUCCESS = $lang_empview_DELETE_SUCCESS ;

$ADD_FAILURE = $lang_empview_ADD_FAILURE;
$UPDATE_FAILURE = $lang_empview_UPDATE_FAILURE;
$DELETE_FAILURE = $lang_empview_DELETE_FAILURE;

switch ($_GET['repcode']) {

		case 'EMPVIEW' :
			$heading = array ($lang_repview_ReportID, $lang_repview_ReportName, 1, $lang_repview_ViewEmployeeReports, $lang_repview_message);
			break;

		case 'EMPDEF' :
			$heading = array ($lang_repview_ReportID, $lang_repview_ReportName, 0, $lang_repview_DefineEmployeeReports , $lang_repview_message2);
			break;
}
?>