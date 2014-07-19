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
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.js') ?>"></script>

<link href="<?php echo public_path('../../themes/orange/css/jquery/jquery.autocomplete.css') ?>" rel="stylesheet" type="text/css"/>

<?php echo stylesheet_tag('../orangehrmCoreLeavePlugin/css/viewLeaveSummarySuccess'); ?>

 <!-- 9706 <script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.validate.js') ?>"></script>-->

<!--[if IE]>
<style type="text/css">
    #leaveSummary_txtEmpName {
        width: 195px;
    }
</style>
<![endif]-->
<style type="text/css">
    label.error {
        padding: 0;
        text-align: center;
    }
</style>
<form id="frmLeaveSummarySearch" name="frmLeaveSummarySearch" method="post" action="<?php echo url_for('leave/viewLeaveSummary'); ?>">
    <div class="outerbox" style="width: 850px;">
        <div class="mainHeading"><h2><?php echo __('Leave Summary') ?></h2></div>
        <div class="searchbar">
            <?php echo $form->render(); ?>
            <br class="clear" />

            <div class="formbuttons paddingLeftBtn">
                <input type="hidden" name="pageNo" id="pageNo" value="<?php echo $form->pageNo; ?>" />
                <input type="hidden" name="hdnAction" id="hdnAction" value="search" />
                <input type="button" name="btnSearch" id="btnSearch" value="<?php echo __('Search') ?>" class="savebutton" />
                <?php if ($form->userType == 'Admin' || $form->userType == 'Supervisor') { ?>
                    <input type="reset" id="btnReset" value="<?php echo __('Reset') ?>" class="savebutton" />
                <?php } ?>
                <?php include_component('core', 'ohrmPluginPannel', array('location' => 'listing_layout_navigation_bar_1')); ?>
            </div>
    
        </div>
    </div>
    <?php echo templateMessage($templateMessage); ?>

    <div id="validationMsg"></div>

    <?php include_component('core', 'ohrmList'); ?>

</form>
<script type="text/javascript">
    
    var lang_typeHint = "<?php echo __("Type for hints"); ?>" + "...";
    
    /* Define language strings here */
    var lang_not_numeric = '<?php echo __(ValidationMessages::INVALID); ?>';
    var userType = '<?php echo $form->userType; ?>';
    
    $(document).ready(function() {
        
        if ($("#leaveSummary_txtEmpName").val() == "" || $("#leaveSummary_txtEmpName").val() == lang_typeHint) {
            $("#leaveSummary_txtEmpName").addClass("inputFormatHint").val(lang_typeHint);
        }
        
        $("#leaveSummary_txtEmpName").one('focus', function() {
            if ($(this).hasClass("inputFormatHint")) {
                $(this).val("");
                $(this).removeClass("inputFormatHint");
            }
        });
        
        /* 9706
        $("#frmLeaveSummarySearch").validate({
            onsubmit : false,
            rules: {
                'txtLeaveEntitled[]':{validateAmount: true, max: 365 }
            },
            messages: {
                'txtLeaveEntitled[]':{
                    validateAmount: lang_not_numeric,
                    max: lang_not_numeric
                }
            }
        });
        */
        
        /* Valid amount */
        /* 9706
        $.validator.addMethod("validateAmount", function(value, element) {
            if(value != '') {
                return value.match(/^\d+(?:\.\d\d?)?$/);
            } else {
                return true;
            }
        });
        */
        
    });

    function submitPage(pageNo) {

        document.frmLeaveSummarySearch.pageNo.value = pageNo;
        document.frmLeaveSummarySearch.hdnAction.value = 'paging';
        if ($('#leaveSummary_txtEmpName_empName').val() == lang_typeHint) {
            $('#leaveSummary_txtEmpName_empName').val('');
        }
        document.getElementById('frmLeaveSummarySearch').submit();
    }


    var editButtonCaption = "<?php echo __('Edit'); ?>";
    var saveButtonCaption = "<?php echo __('Save'); ?>";
</script>

<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/jquery.autocomplete.js') ?>"></script>
