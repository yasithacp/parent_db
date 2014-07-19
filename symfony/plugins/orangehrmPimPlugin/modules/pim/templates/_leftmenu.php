<script type="text/javascript"><!--//--><![CDATA[//><!--
    function showHideSubMenu(link) {

        var uldisplay;
        var newClass;

        if (link.className == 'expanded') {

            // Need to hide
            uldisplay = 'none';
            newClass = 'collapsed';

        } else {

            // Need to show
            uldisplay = 'block';
            newClass = 'expanded';
        }

        var parent = link.parentNode;
        uls = parent.getElementsByTagName('ul');
        for(var i=0; i<uls.length; i++) {
            ul = uls[i].style.display = uldisplay;
        }

        link.className = newClass;
    }

    tableDisplayStyle = "table";
    //--><!]]></script>
<!--[if IE]>
<script type="text/javaScript">
	tableDisplayStyle = "block";
</script>
<![endif]-->

<style type="text/css">
    ul.error_list {
        color: #ff0000;
    }

    :disabled:not([type="image"]) {
        background-color:#FFFFFF;
        color:#444444;
    }

    /*input[type=text] {
        border-top: 0px;
        border-left: 0px;
        border-right: 0px;
        border-bottom: 1px solid #888888;
    }*/

    table.historyTable th {
        border-width: 0px;
        padding: 3px 3px 3px 5px;
        text-align: left;
    }
    table.historyTable td {
        border-width: 0px;
        padding: 3px 3px 3px 5px;
        text-align: left;
    }

    .locationDeleteChkBox {
        padding:2px 4px 2px 4px;
        border-style: solid;
        border-width: thin;
        display:block;
    }

    .pimpanel {
        position:absolute;
        left:-9999px;
    }
    .currentpanel {
        margin-top: 10px;
        margin-left: 190px;
    }
    #photodiv {
        margin-top:19px;
        float:left;
        text-align:center;
        margin-left: 650px;
        padding: 2px;
        border: 1px solid #FAD163;
    }
    #photodiv span {
        color: black;
        font-weight: bold;
    }

    #empname {
        display:block;
        color: black;
    }

    #personalIcons,
    #employmentIcons,
    #qualificationIcons {
        display:block;
        position:absolute;
        left:-999px;
        width:400px;
        text-align:center;
        padding-left:100px;
        padding-right:100px;
    }

    #icons div a {
        display:block;
        float:left;
        height: 50px;
        width: 54px;
        text-decoration:none;
        text-align:center;
        vertial-align:bottom;
        padding-top: 45px;
        outline: 0;
        background-position: top center;
        margin-left:8px;
        margin-right:8px;
    }

    #icons div a:hover {
        color: black;
        text-decoration: underline;
    }

    #icons div a.current {
        font-weight: bold;
        color:black;
        cursor:default;
    }

    #icons div a.current:hover {
        color:black;
        text-decoration:none;
    }

    #icons {
        display:block;
        clear:both;
        margin-left: 130px;
        margin-top: 5px;
        margin-bottom: 2px;
        width:500px;
        height: 60px;
    }
    #pimleftmenu {
        display:block;
        float: left;
        /*background: #FFFBED;*/
        padding: 2px 2px 2px 2px;
        margin: 0px 0px 0px 5px;
    }
    #pimleftmenu ul {
        list-style-type: none;
        padding-left: 0;
        margin-left: 0;
        width: 156px;
    }

    #pimleftmenu ul.pimleftmenu li {
        list-style-type:none;
        margin-left: 0;
        margin-bottom: 1px;
    }

    #pimleftmenu ul li.parent {
        padding-left: 0px;
        padding-top:4px;
        font-weight: bold;
    }

    #pimleftmenu ul.pimleftmenu li a {
        display:block;
        outline: 0;
        padding: 2px 2px 2px 4px;
        text-decoration: none;
        background:#FAD163 none repeat scroll 0 0;
        border-color:#CD8500 #8B5A00 #8B5A00 #CD8500;
        border-style:solid;
        border-width:1px;
        color:#d87415;
        font-size: 11px;
        font-weight:bold;
        text-align: left;
    }
    #pimleftmenu ul.pimleftmenu li a:hover {
        color: #FFFBED;
        background-color: #e88d1e;
    }

    #pimleftmenu ul.pimleftmenu li a.current {
        color: #FFFBED;
        background-color: #e88d1e;
    }

    #pimleftmenu ul.pimleftmenu li a.collapsed,
    #pimleftmenu ul.pimleftmenu li a.expanded {
        display:block;
        outline: 0;
        padding: 2px 2px 2px 4px;
        text-decoration: none;
        border: 0 ;
        color: #CC6600;
        font-size: 11px;
        font-weight:bold;
        text-align: left;
    }

    #pimleftmenu ul.pimleftmenu li a.expanded {
        background: #FFFBED url(<?php echo public_path("../../themes/orange/icons/expanded.gif");?>) no-repeat center right;
    }

    #pimleftmenu ul.pimleftmenu li a.collapsed {
        background: #FFFBED url(<?php echo public_path("../../themes/orange/icons/collapsed.gif");?>) no-repeat center right;
        border-bottom: 1px solid #d87415;
    }

    #pimleftmenu ul.pimleftmenu li a.collapsed:hover span,
    #pimleftmenu ul.pimleftmenu li a.expanded:hover span {
        color: #8d4700;
    }


    #pimleftmenu ul span {
        display:block;
    }

    #pimleftmenu li.parent span.parent {
        color: #CC6600;
    }

    #pimleftmenu ul span span {
        display:inline;
        text-decoration:underline;
    }

    div.requirednotice {
        margin-left: 15px;
    }

    #parentPaneDependents {
        float:left;
        width: 50%;
    }

    #parentPaneChildren {
        float:left;
        width: 50%;
    }

    /** Job */
    h3#locationTitle, table#assignedLocationsTable {
        margin-left:10px;
    }

    #jobSpecDuties {
        width:400px;
    }

    /** Dependents */
    div#addPaneDependents {
        width:100%;
    }

    div#addPaneDependents label {
        width: 100px;
    }

    div#addPaneDependents br {
        clear:left;
    }

    div#addPaneDependents input {
        display:block;
        margin: 2px 2px 2px 2px;
        float:left;
    }

    div.formbuttons {
        text-align:left;
    }

    input.hiddenField {
        display:none;
    }

    /* Children */
    div#addPaneChildren {
        width:100%;
    }

    div#addPaneChildren label {
        width: 100px;
    }

    div#addPaneChildren br {
        clear:left;
    }

    div#addPaneChildren input {
        display:block;
        margin: 2px 2px 2px 2px;
        float:left;
    }

    /* education */
    div#editPaneEducation {
        width:100%;
    }

    div#editPaneEducation label {
        width: 200px;
    }

    div#editPaneEducation br {
        clear:left;
    }

    div#editPaneEducation input {
        display:block;
        margin: 2px 2px 2px 2px;
        float:left;
    }

    div#editPaneEducation #educationLabel {
        display:inline;
        font-weight:bold;
        padding-left:2px;
    }

    div.formbuttons {
        text-align:left;
    }

    /* membership */
    label#membershipLabel,
    label#membershipTypeLabel {
        font-weight:bold;
    }

    div#editPaneMemberships {
        width:100%;
    }

    div#editPaneMemberships label {
        width: 200px;
    }

    div#editPaneMemberships br {
        clear:left;
    }

    div#editPaneMemberships input {
        display:block;
        margin: 2px 2px 2px 2px;
        float:left;
    }

    div#editPaneMemberships #membershipTypeLabel,
    div#editPaneMemberships #membershipLabel {
        display:inline;
        font-weight:bold;
        padding-left:2px;
    }

    /* photo */
    #currentImage {
        padding: 2px;
        margin: 5px 4px 14px 2px;
        border: 1px solid #FAD163;
        cursor:pointer;
    }

    #imageSizeRule {
        width:200px;
    }

    #imageHint {
        font-size:10px;
        color:#999999;
        padding-left:8px;
    }
</style>
<?php
$empMode = "EMP";
if($empNumber == $_SESSION['empID']) {
    $empMode = "ESS";
}

    // Old links require empNumber padded with 0's
    $idLength = OrangeConfig::getInstance()->getSysConf()->getEmployeeIdLength();

    $paddedEmpNumber = str_pad($empNumber, $idLength, "0", STR_PAD_LEFT);
    
    $employeeService = new EmployeeService();
    $empPicture = $employeeService->getPicture($empNumber);

    $width = '150';
    $height = '180';
    if (!empty($empPicture)) {
        $width = $empPicture->width;
        $height = $empPicture->height;
    }
?>

<form id="frmEmp" action=""></form>
<div id="pimleftmenu">
    <?php include_partial('photo', array('empNumber' => $empNumber,
        'width' => $width, 'height' => $height,
        'editMode' => isset($editPhotoMode) ? $editPhotoMode : false,
        'fullName' => htmlspecialchars($form->fullName)));?>
    <ul class="pimleftmenu">
        <li class="l1 parent">
            <a href="#" class="expanded" onclick="showHideSubMenu(this);">
                <span class="parent personal"><?php echo __("Personal");?></span></a>
            <ul class="l2">
                <li class="l2">

                    <a href="<?php echo url_for('pim/viewPersonalDetails?empNumber=' . $empNumber); ?>" id="personalLink" class="personal" accesskey="p">
                        <span><?php echo __("Personal Details");?></span></a></li>
                <li class="l2">
                    <a href="<?php echo url_for('pim/contactDetails?empNumber=' . $empNumber); ?>" id="contactsLink" class="personal" accesskey="c">
                        <span><?php echo __("Contact Details");?></span></a></li>
                <li class="l2">
                    <a href="<?php echo url_for('pim/viewEmergencyContacts?empNumber=' . $empNumber); ?>" id="emgcontactsLink" class="personal"  accesskey="e">
                        <span><?php echo __("Emergency Contacts");?></span></a></li>

                <li class="l2">
                    <a href="<?php echo url_for('pim/viewDependents?empNumber=' . $empNumber); ?>" id="dependentsLink" class="personal"  accesskey="d">
                        <span><?php echo __("Dependents");?></span></a></li>
                <li class="l2">
                    <a href="<?php echo url_for('pim/viewImmigration?empNumber=' . $empNumber); ?>" id="immigrationLink" class="personal" accesskey="i" >
                        <span><?php echo __("Immigration");?></span></a></li>
                <li class="l2">
                    <a href="<?php echo url_for('pim/viewJobDetails?empNumber=' . $empNumber);?>" id="jobLink" accesskey="j" class="employment"  >

                        <span><?php echo __("Job");?></span></a></li>
                <li class="l2">
                    <a href="<?php echo url_for('pim/viewSalaryList?empNumber=' . $empNumber);?>" id="paymentsLink" class="employment" accesskey="s" >
                        <span><?php echo __("Salary");?></span></a></li>
<?php 

    $showTax = OrangeConfig::getInstance()->getAppConfValue(ConfigService::KEY_PIM_SHOW_TAX_EXEMPTIONS);
    if ($showTax) { ?>                        
                <li class="l2">
                    <a href="<?php echo url_for('pim/viewUsTaxExemptions?empNumber=' . $empNumber);?>" id="taxLink" class="employment" accesskey="t" >
                        <span><?php echo __("Tax Exemptions");?></span></a></li>
<?php } ?>
                <li class="l2">
                    <a href="<?php echo url_for('pim/viewReportToDetails?empNumber=' . $empNumber);?>" id="report-toLink" class="employment" accesskey="r" >
                        <span><?php echo __("Report-to");?></span></a></li>   
                <li class="l2">

                    <a href="<?php echo url_for('pim/viewQualifications?empNumber=' . $empNumber); ?>" id="personalLink" class="personal" accesskey="p">
                        <span><?php echo __("Qualifications");?></span></a></li>
                
                <li class="l2">
                    <a href="<?php echo url_for('pim/viewMemberships?empNumber=' . $empNumber);?>" id="membershipsLink" class="pimmemberships" accesskey="m">
                        <span><?php echo __("Membership");?></span>
                    </a>
                </li>                        
            </ul>
        </li>

        <!-- start of leave section -->
        <?php if(isset ($_SESSION['leavePeriodDefined']) && $_SESSION['leavePeriodDefined'] == 1 && !in_array('leave', $_SESSION['admin.disabledModules'])) { ?>
        <li class="l1 parent">
            <a href="#" class="expanded" onclick="showHideSubMenu(this);"><span><?php echo __("Leave");?></span></a>
            <ul class="l2">
                <li class="l2">
					<a href="javascript:leaveFormSubmission('leaveSummary');">
						<span><?php echo __("Leave Summary");?></span>
					</a>
				</li>
                <li class="l2">
					<a href="javascript:leaveFormSubmission('leaveList');">
						<span><?php echo __("Leave List");?></span>
					</a>
				</li>
            </ul>
        </li>
        <?php } ?>

    </ul>
    
</div>

<!-- end of leave section -->
<script type="text/javascript">
    //<![CDATA[
            function leaveFormSubmission(redirect) {
                //$("#" + formId).submit();
                //document.frmLeaveList.submit();
                //document.getElementById(formId).submit();
                var frm = document.getElementById("frmEmp");
                var input = document.createElement("input");
                var empId = "";
<?php if (!empty($empNumber)) {?>
                    empId = parseInt("<?php echo $empNumber;?>", 10);
    <?php }?>
                    input.setAttribute("type", "hidden");

                    if(redirect == "leaveList") {
    
                        //any user tries accesses his own information
<?php if(isset($_SESSION['empID']) && $_SESSION['empID'] == $empNumber) {?>
                            frm.action = "<?php echo url_for('leave/viewMyLeaveList');?>";
<?php } else { ?>
                            frm.action = "<?php echo url_for('leave/viewLeaveList');?>";    
<?php } ?>        
                            input.setAttribute("name", "txtEmpID");
                        }

                        if(redirect == "leaveSummary") {
                            //frm.action = "../leave/viewLeaveSummary";
                            frm.action = "<?php echo url_for('leave/viewLeaveSummary');?>";
                            input.setAttribute("name", "employeeId");
                        }
                        input.setAttribute("value", empId);
                        frm.appendChild(input);
                        frm.submit();
                    }
    //]]>
</script>
