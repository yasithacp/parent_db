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



$srchlist[0] = array( -1 , 0 , 1, 2, 3, 6, 9, 7, 8);
$srchlist[1] = array( "-$lang_Leave_Common_Select-",
					  $lang_empview_EmpID,
					  $lang_empview_EmpFirstName,
					  $lang_empview_EmpLastName,
					  $lang_empview_EmpMiddleName,
					  $lang_empview_JobTitle,
					  $lang_empview_EmploymentStatus,
					  $lang_empview_SubDivision,
					  $lang_empview_Supervisor);


$search    			= $lang_empview_search;
$searchby 			= $lang_empview_searchby;
$description		= $lang_empview_description;
$norecorddisplay 	= $lang_empview_norecorddisplay;
$previous 			= $lang_empview_previous;
$next				= $lang_empview_next;
$employeeid 		= $lang_empview_employeeid;
$employeename 		= $lang_empview_employeename;

$ADD_SUCCESS = $lang_empview_ADD_SUCCESS;
$UPDATE_SUCCESS = $lang_empview_UPDATE_SUCCESS;
$DELETE_SUCCESS = $lang_empview_DELETE_SUCCESS;

$ADD_FAILURE = $lang_empview_ADD_FAILURE;
$UPDATE_FAILURE = $lang_empview_UPDATE_FAILURE;
$DELETE_FAILURE = $lang_empview_DELETE_FAILURE;

switch ($_GET['reqcode']) {

	case  'LAN' :
		       $headingInfo = array ($lang_empview_Language, 1);
		       break;

	case  'CSE' :
			   $headingInfo = array ($lang_empview_WorkExperience, 1);
			   break;

	case  'SAL' :
			   $headingInfo = array ($lang_empview_Payment, 1);
			   break;
	case  'SKI' :
			   $headingInfo = array ($lang_empview_Skills, 1);
			   break;

	case  'LIC' :
			  $headingInfo = array ($lang_empview_Licenses, 1);
			  break;

	case 'EMP' :
		   	 $headingInfo = array ($lang_empview_EmployeeInformation,1);
			  break;

	case  'MEM' :
			$headingInfo = array ($lang_empview_Memberships, 1);
			break;

	case 'REP' :
			$headingInfo = array ($lang_empview_ReportTo,1);
			break;
}
?>
