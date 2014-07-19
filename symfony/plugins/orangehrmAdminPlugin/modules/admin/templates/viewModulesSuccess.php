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

<?php use_stylesheet('../orangehrmAdminPlugin/css/viewModulesSuccess'); ?>

<?php echo isset($templateMessage) ? templateMessage($templateMessage) : ''; ?>

<div id="saveFormDiv">
    <div class="outerbox">

    <div class="mainHeading"><h2 id="saveFormHeading"><?php echo __('Module Configuration') ?></h2></div>

        <form name="frmSave" id="frmSave" method="post" action="<?php echo url_for('admin/viewModules'); ?>">
            
            <?php echo $form['_csrf_token']; ?>
            
            <!--<div class="errorHolder"></div>-->
            
            <?php echo $form['admin']->render(); ?>
            <?php echo $form['admin']->renderLabel(__('Enable Admin module') . ' <span class="required">*</span>'); ?>
            <br class="clear"/>   
            
            <?php echo $form['pim']->render(); ?>
            <?php echo $form['pim']->renderLabel(__('Enable PIM module') . ' <span class="required">*</span>'); ?>
            <br class="clear"/>          
            
            <?php echo $form['leave']->render(); ?>
            <?php echo $form['leave']->renderLabel(__('Enable Leave module')); ?>
            <br class="clear"/>  
            
            <?php echo $form['time']->render(); ?>
            <?php echo $form['time']->renderLabel(__('Enable Time module')); ?>
            <br class="clear"/>  
            
            <?php echo $form['recruitment']->render(); ?>
            <?php echo $form['recruitment']->renderLabel(__('Enable Recruitment module')); ?>
            <br class="clear"/>  
            
            <?php echo $form['performance']->render(); ?>
            <?php echo $form['performance']->renderLabel(__('Enable Performance module')); ?>
            <br class="clear"/>  
            
            <?php echo $form['help']->render(); ?>
            <?php echo $form['help']->renderLabel(__('Enable Help') . ' <span class="required">*</span>'); ?>
            <br class="clear"/>            
            
            <div class="formbuttons">
                <input type="button" class="savebutton" name="btnSave" id="btnSave"
                       value="<?php echo __('Edit'); ?>"
                       title="<?php echo __('Edit'); ?>"
                       onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
            </div>

        </form>
    
    </div>
    
    <div class="helpText"><span class="required">*</span> <?php echo __('compulsory'); ?></div>
    
</div> <!-- saveFormDiv -->



<?php use_javascript('../orangehrmAdminPlugin/js/viewModulesSuccess'); ?>

<script type="text/javascript">
//<![CDATA[	    
    
    var lang_edit = "<?php echo __('Edit'); ?>";
    var lang_save = "<?php echo __('Save'); ?>";
    var reloadParent = <?php echo isset($templateMessage)?'true':'false'; ?>;
    
//]]>	
</script>