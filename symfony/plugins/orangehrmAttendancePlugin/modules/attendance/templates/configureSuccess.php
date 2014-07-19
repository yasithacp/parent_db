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
<?php echo javascript_include_tag('../orangehrmAttendancePlugin/js/configure'); ?>
<?php echo stylesheet_tag('../orangehrmAttendancePlugin/css/configureSuccess'); ?>

<div id="messagebar" class="<?php echo isset($messageType) ? "messageBalloon_{$messageType}" : ''; ?>" style="margin-left: 16px;width: 470px;">
    <span style="font-weight: bold;"><?php echo isset($message) ? $message : ''; ?></span>
</div>

<div class="outerbox"  style="width: 500px" >
    <div class="maincontent">
        <div class="mainHeading">
            <h2><?php echo __('Attendance Configuration'); ?></h2>
        </div>
        <br class="clear">
        <form  id="configureForm" action=""  method="post">
            <?php echo $form['_csrf_token']; ?>
            <?php echo $form['configuration1']->render(); ?>
            <?php echo $form['configuration1']->renderLabel(__('Employee can change current time when punching in/out')); ?>
            <br class="clear"/>

            <?php echo $form['configuration2']->render(); ?>
            <?php echo $form['configuration2']->renderLabel(__('Employee can edit/delete own attendance records')); ?>
            <br class="clear"/>

            <?php echo $form['configuration3']->render(); ?>
            <?php echo $form['configuration3']->renderLabel(__('Supervisor can add/edit/delete attendance records of subordinates')); ?>
            <br class="clear"/>
            <br class="clear"/>

            &nbsp;&nbsp;&nbsp;&nbsp; <input type="submit" class="saveConfiguration" name="button" id="btnSave"
                                            onmouseover="moverButton(this);" onmouseout="moutButton(this);"
                                            value="<?php echo __('Save'); ?>" />

        </form>
        <br class="clear">
    </div>
</div>