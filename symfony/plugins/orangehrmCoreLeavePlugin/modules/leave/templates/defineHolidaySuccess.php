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
<?php use_javascripts_for_form($form); ?>
<?php use_stylesheets_for_form($form); ?>

<link href="<?php echo public_path('../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css') ?>" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.core.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.datepicker.js') ?>"></script>

<!-- Datepicker Plugin-->
<?php echo stylesheet_tag('orangehrm.datepicker.css') ?>
<?php echo javascript_include_tag('orangehrm.datepicker.js') ?>

<div class="formpageNarrow">

    <?php echo isset($templateMessage) ? templateMessage($templateMessage) : ''; ?>

    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo ($editMode) ? __('Edit') . " " . __('Holiday') : __('Add') . " " . __('Holiday'); ?></h2></div>

        <div id="errorDiv"> </div>

        <form id="frmHoliday" name="frmHoliday" method="post" action="<?php echo url_for('leave/defineHoliday') ?>">
            <?php echo $form->render(); ?>

            <input type="hidden" name="hdnEditMode" id="hdnEditMode" value="<?php echo ($editMode) ? 'yes' : 'no'; ?>" />

            <div class="formbuttons">
                <input type="button" class="savebutton" id="saveBtn" value="<?php echo __('Save'); ?>" />
                <input type="reset" class="resetbutton" value="<?php echo __('Reset'); ?>" id="btnReset" />
                <input type="button" class="savebutton" id="btnBack" value="<?php echo __('Back'); ?>" />
            </div>
        </form>
    </div>
    <div class="requirednotice"><span class="required">*</span> <?php echo __(CommonMessages::REQUIRED_FIELD); ?></div>
</div>
<style type="text/css">
    label label.error{
        padding-left: 130px;
        width:230px !important;
    }
    
    .maincontent span ul.error_list {
        margin-left: 20px;
        margin-top: 10px;
        font-size: 11px;
    }
    .maincontent ul.error_list {
        margin-left: 140px;
        font-size: 11px;
    }
    
    label, span.formLabel, span.wideFormLabel {
        width: 130px !important;
    }
    
    br {
        clear: both;
    }
</style>

<script type="text/javascript">
    //<![CDATA[
    
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var backUrl = '<?php echo url_for('leave/viewHolidayList'); ?>';

    var lang_Required = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_DateFormatIsWrong = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => get_datepicker_date_format($sf_user->getDateFormat()))) ?>';
    var lang_NameIsOverLimit = "<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 200));?>";        

    //]]>
</script>
