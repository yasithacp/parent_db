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
<?php 


use_stylesheet('../../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css');
use_stylesheet('../orangehrmCoreLeavePlugin/css/common.css');
use_javascript('../../../scripts/jquery/ui/ui.core.js');
use_javascript('../../../scripts/jquery/ui/ui.dialog.js');

use_javascripts_for_form($searchForm);
use_stylesheets_for_form($searchForm);
?>

<div id="messagebar" class="messageBalloon_<?php echo $messageType; ?>" >
    <span>
<?php 
    if (!empty($messageType)) {
       echo $message; 
    }
?>
    </span>
</div>

<div id="errorDiv">
<?php
    if ($searchForm->hasErrors()) { 
        echo __("Please Correct The Following Errors");
    }
?>
</div>

<div class="outerbox" id="searchDiv">
    <div class="mainHeading"><h2><?php echo __('Holidays'); ?></h2></div>
    
    <form id="frmHolidaySearch" name="frmHolidaySearch" method="post" action="<?php echo url_for('leave/viewHolidayList') ?>" >            
        <?php echo $searchForm->render() ?>

        <div class="formbuttons">
        <input type="button" name="btnSearch" id="btnSearch" value="<?php echo __("Search") ?>" class="savebutton" />
        </div>        
    </form>

</div>
   <br class="clear" />
   <div id="listDiv">   
        <?php include_component('core', 'ohrmList'); ?>
    </div>
   <!--
                               <?php //echo set_datepicker_date_format($holiday->getDate()); ?>
                            <?php //echo __($daysLenthList[$holiday->getLength()]); ?>
                            <?php //echo __($yesNoList[$holiday->getRecurring()]); ?>
   -->

<div id="deleteConfirmation" title="<?php echo __('OrangeHRM - Confirmation Required'); ?>" style="display: none;">
    <?php echo __(CommonMessages::DELETE_CONFIRMATION); ?>
    <div class="dialogButtons">
        <input type="button" id="dialogDeleteBtn" class="savebutton" value="<?php echo __('Ok'); ?>" />
        <input type="button" id="dialogCancelBtn" class="savebutton" value="<?php echo __('Cancel'); ?>" />
    </div>
</div>

<script type="text/javascript"> 
//<![CDATA[    
    var defineHolidayUrl = '<?php echo url_for('leave/defineHoliday'); ?>';
    var lang_SelectHolidayToDelete = '<?php echo __(TopLevelMessages::SELECT_RECORDS); ?>';      
//]]>    
</script>
    
