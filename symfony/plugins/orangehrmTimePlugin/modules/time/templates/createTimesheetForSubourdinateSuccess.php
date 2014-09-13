<?php /**
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
<link href="<?php echo public_path('../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.core.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.datepicker.js') ?>"></script>
<?php echo stylesheet_tag('orangehrm.datepicker.css') ?>
<?php echo javascript_include_tag('orangehrm.datepicker.js') ?>

<?php echo stylesheet_tag('../orangehrmTimePlugin/css/createTimesheetForSubourdinateSuccess'); ?>
<?php echo javascript_include_tag('../orangehrmTimePlugin/js/createTimesheetForSubourdinateSuccess'); ?>

<div id="validationMsg"><?php echo isset($messageData) ? templateMessage($messageData) : ''; ?></div>

 &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<?php if (in_array(WorkflowStateMachine::TIMESHEET_ACTION_CREATE, $sf_data->getRaw('allowedToCreateTimesheets'))): ?>
    <input type="button" class="addTimesheetbutton" name="button" id="btnAddTimesheet"
           onmouseover="moverButton(this);" onmouseout="moutButton(this);"
           value="<?php echo __('Add Timesheet') ?>" />
       <?php endif; ?>

<div id="createTimesheet">
    <br class="clear"/>
    <form  id="createTimesheetForm" action=""  method="post">
        <?php echo $createTimesheetForm['_csrf_token']; ?>

        <?php echo $createTimesheetForm['date']->renderLabel(__('Select a Day to Create Timesheet')); ?>
        <?php echo $createTimesheetForm['date']->render(); ?><input id="DateBtn" type="button" name="" value="" class="calendarBtn"style="display: inline;margin:0;float:none; "/>
        <?php echo $createTimesheetForm['date']->renderError() ?>
        <br class="clear"/>
    </form>

</div>


<script type="text/javascript">
                                                    
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var employeeId = "<?php echo $employeeId; ?>";
    var linkForViewTimesheet="<?php echo url_for('time/viewTimesheet') ?>";
    var validateStartDate="<?php echo url_for('time/validateStartDate'); ?>";
    var createTimesheet="<?php echo url_for('time/createTimesheet'); ?>";
    var returnEndDate="<?php echo url_for('time/returnEndDate'); ?>";
    var currentDate= "<?php echo $currentDate; ?>";
    var lang_noFutureTimesheets= "<?php echo __("Failed to Create: Future Timesheets Not Allowed"); ?>";
    var lang_overlappingTimesheets= "<?php echo __("Timesheet Overlaps with Existing Timesheets"); ?>";
    var lang_timesheetExists= "<?php echo __("Timesheet Already Exists"); ?>";
    var lang_invalidDate= '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => get_datepicker_date_format($sf_user->getDateFormat()))) ?>';
</script>