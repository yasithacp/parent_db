<link href="<?php echo public_path('../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.core.js') ?>"></script>

<?php
use_stylesheet('../../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css');
use_javascript('../../../scripts/jquery/ui/ui.core.js');
use_javascript('../../../scripts/jquery/ui/ui.dialog.js');
use_stylesheet('../orangehrmRecruitmentPlugin/css/viewParentInfoSuccess');
use_javascript('../orangehrmRecruitmentPlugin/js/viewJobVacancySuccess');
?>

<div id="messagebar" class="<?php echo isset($messageType) ? "messageBalloon_{$messageType}" : ''; ?>" >
    <span style="font-weight: bold;"><?php echo isset($message) ? $message : ''; ?></span>
</div>
<div id="srchParent">
    <div class="outerbox">

        <div class="mainHeading"><h2 id="srchParentHeading"><?php echo __('Parent Information'); ?></h2></div>
        <form name="frmSrchParentInfo" id="frmSrchParentInfo" method="post" action="<?php echo url_for('parent/viewParentInfo'); ?>">

            <?php echo $form['_csrf_token']; ?>
            <br class="clear"/>
            <div class="column">
                <?php echo $form['stuName']->renderLabel(__('Student Name')); ?>
                <?php echo $form['stuName']->render(array("maxlength" => 50)); ?>
            </div>
            <div class="column">
                <?php echo $form['stuIndexNo']->renderLabel(__('Student Admission No')); ?>
                <?php echo $form['stuIndexNo']->render(array("maxlength" => 10)); ?>
            </div>
            <div class="column">
                <?php echo $form['dadOccupation']->renderLabel(__('Father\'s Occupation')); ?>
                <?php echo $form['dadOccupation']->render(array("maxlength" => 50)); ?>
            </div>
            <br class="clear"/>
            <br class="clear"/>
            <div class="column">
                <?php echo $form['momOccupation']->renderLabel(__('Mother\'s Occupation')); ?>
                <?php echo $form['momOccupation']->render(array("maxlength" => 50)); ?>
            </div>
            <br class="clear"/>
            <br class="clear"/>

            <div class="actionbar" style="border-top: 1px solid #FAD163; margin-top: 3px">
                <div class="actionbuttons">
                    <input type="button" class="searchbutton" name="btnSrch" id="btnSrch"
                           value="<?php echo __("Search"); ?>"onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
                    <input type="button" class="resetbutton" name="btnSrch" id="btnRst"
                           value="<?php echo __("Reset"); ?>"onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
                </div>
                <br class="clear"/>
            </div>
            <br class="clear"/>
        </form>
    </div>
</div>

<div id="parentSrchResults">
    <?php include_component('core', 'ohrmList', $parmetersForListCompoment); ?>
</div>

<!-- confirmation box -->
<div id="deleteConfirmation" title="<?php echo __('OrangeHRM - Confirmation Required'); ?>" style="display: none;">
    <?php echo __(CommonMessages::DELETE_CONFIRMATION); ?>
    <div class="dialogButtons">
        <input type="button" id="dialogDeleteBtn" class="savebutton" value="<?php echo __('Ok'); ?>" />
        <input type="button" id="dialogCancelBtn" class="savebutton" value="<?php echo __('Cancel'); ?>" />
    </div>
</div>

<form name="frmHiddenParam" id="frmHiddenParam" method="post" action="<?php echo url_for('parent/viewParentInfo');  ?>">
    <input type="hidden" name="pageNo" id="pageNo" value="<?php //echo $form->pageNo;   ?>" />
    <input type="hidden" name="hdnAction" id="hdnAction" value="search" />
</form>

<script type="text/javascript">

    function submitPage(pageNo) {

        document.frmHiddenParam.pageNo.value = pageNo;
        document.frmHiddenParam.hdnAction.value = 'paging';
        document.getElementById('frmHiddenParam').submit();

    }
</script>