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
<?php echo stylesheet_tag('../orangehrmTimePlugin/css/defineTimesheetPeriodSuccess'); ?>
<?php echo javascript_include_tag('../orangehrmTimePlugin/js/defineTimesheetPeriod'); ?>

<div id="messagebar" style="margin-left: 16px;width: 450px;" class="<?php echo isset($messageType) ? "messageBalloon_{$messageType}" : ''; ?>" >
    <span style="font-weight: bold;"><?php echo isset($message) ? $message : ''; ?></span>
</div>
    <div class="outerbox" style="width: 35%">

        <div class="mainHeading"><h2 id="defineTimesheet"><?php echo __('Define Timesheet Period'); ?></h2></div>
        <form id="definePeriod" method="post">

            <?php echo $form['_csrf_token']; ?>
            <div>
		<table><tr>
		<?php if($isAllowed){?>
		<td id="startDayLabel"><?php  echo __('First Day of Week').' <span class=required>*</span>';?></td>
                <td id="startDays"><?php echo $form['startingDays']->render(array("class" => "drpDown", "maxlength" => 20)); ?></td></tr>
		<?php }else{ ?>

                <br class="clear"/>
		<tr>
            <td><b><?php echo __("Timesheet period start day has not been defined. Please contact HR Admin"); ?></b></td>
		<?php } ?>
		</tr>
		</table>
            </div>
            <?php if($isAllowed){?>
	    <div class="formbuttons">
            
		    <input type="button" class="savebutton" name="btnSave" id="btnSave"
                       value="<?php echo __("Save"); ?>"onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
               
            </div> 
            <?php } ?>
        </form>
    </div>
 <?php if($isAllowed){?>
    <div class="paddingLeftRequired"><span class="required">*</span> <?php echo __(CommonMessages::REQUIRED_FIELD); ?></div>
 <?php } ?>
    <script type="text/javascript">

    var linkTodefineTimesheetPeriod="<?php echo url_for('time/defineTimesheetPeriod')?>";
    var required_msge = '<?php echo __(ValidationMessages::REQUIRED); ?>';

</script>