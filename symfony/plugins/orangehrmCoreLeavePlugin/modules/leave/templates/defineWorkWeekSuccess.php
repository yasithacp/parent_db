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

<?php use_stylesheet('../orangehrmCoreLeavePlugin/css/defineWorkWeekSuccess'); ?>
<?php use_javascripts_for_form($workWeekForm); ?>
<?php use_stylesheets_for_form($workWeekForm); ?>

<div id="messageBalloonContainer" style="width:380px;">
    <?php echo isset($templateMessage) ? templateMessage($templateMessage) : ''; ?>
</div>
<div class="formpageNarrow">
    <div class="outerbox">
        <div class="mainHeading"><h2><?php echo __('Work Week'); ?></h2></div>

        <div id="errorDiv"></div>
        
        <form id="frmWorkWeek" name="frmWorkWeek" method="post" action="<?php echo url_for('leave/defineWorkWeek') ?>" >            
            <?php echo $workWeekForm->render() ?>
            <br class="clear"/>
<?php 
    $permissions = $sf_context->get('screen_permissions');
    if ($permissions->canUpdate()) {
?>
            <div class="formbuttons">            
                <input type="button" class="savebutton" id="saveBtn" value="<?php echo __('Edit'); ?>" />
                <input type="button" class="clearbutton" onclick="reset();" value="<?php echo __('Reset'); ?>" />                
            </div>
<?php } ?>            
        </form>
    </div>
    <script type="text/javascript">
        //<![CDATA[
        var permissions = {
            canRead: <?php echo $permissions->canRead() ? 'true' : 'false';?>,
            canCreate: <?php echo $permissions->canCreate() ? 'true' : 'false';?>,            
            canUpdate: <?php echo $permissions->canUpdate() ? 'true' : 'false';?>,
            canDelete: <?php echo $permissions->canDelete() ? 'true' : 'false';?>
        };
        
        var lang_Save = "<?php echo __('Save') ?>";
        var lang_Edit = "<?php echo __('Edit') ?>";
        var lang_AtLeastOneWorkDay = "<?php echo __('At Least One Day Should Be a Working Day') ?>";
        var lang_Required = "<?php echo __(ValidationMessages::REQUIRED);?>";
        //]]>
    </script>
</div>
