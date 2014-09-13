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

<link href="<?php echo public_path('../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.core.js') ?>"></script>

<?php
use_stylesheet('../../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css');
use_javascript('../../../scripts/jquery/ui/ui.core.js');
use_javascript('../../../scripts/jquery/ui/ui.dialog.js');
use_stylesheet('../orangehrmRecruitmentPlugin/css/viewJobVacancySuccess');
use_javascript('../orangehrmRecruitmentPlugin/js/viewJobVacancySuccess');
?>

<div id="messagebar" class="<?php echo isset($messageType) ? "messageBalloon_{$messageType}" : ''; ?>" >
    <span style="font-weight: bold;"><?php echo isset($message) ? $message : ''; ?></span>
</div>
<div id="srchVacancy">
    <div class="outerbox">

        <div class="mainHeading"><h2 id="srchVacancyHeading"><?php echo __('Vacancies'); ?></h2></div>
        <form name="frmSrchJobVacancy" id="frmSrchJobVacancy" method="post" action="<?php echo url_for('recruitment/viewJobVacancy'); ?>">

            <?php echo $form['_csrf_token']; ?>
            <br class="clear"/>
            <div class="column">
                <?php echo $form['jobTitle']->renderLabel(__('Job Title'), array("class" => "jobTitleLabel")); ?>
                <?php echo $form['jobTitle']->render(array("class" => "drpDown", "maxlength" => 50)); ?>
            </div>
            <div class="column">
                <?php echo $form['jobVacancy']->renderLabel(__('Vacancy'), array("class" => "vacancyLabel")); ?>
                <?php echo $form['jobVacancy']->render(array("class" => "drpDown", "maxlength" => 50)); ?>
            </div>
            <div class="column">
                <?php echo $form['hiringManager']->renderLabel(__('Hiring Manager'), array("class" => "hiringManagerLabel")); ?>
                <?php echo $form['hiringManager']->render(array("class" => "drpDown", "maxlength" => 50)); ?>
            </div>
            <div class="column">
                <?php echo $form['status']->renderLabel(__('Status'), array("class" => "statusLabel")); ?>
                <?php echo $form['status']->render(array("class" => "drpDown", "maxlength" => 50)); ?>
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

<div id="vacancySrchResults">
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

            <form name="frmHiddenParam" id="frmHiddenParam" method="post" action="<?php echo url_for('recruitment/viewJobVacancy');  ?>">
                <input type="hidden" name="pageNo" id="pageNo" value="<?php //echo $form->pageNo;   ?>" />
                <input type="hidden" name="hdnAction" id="hdnAction" value="search" />
            </form>

            <script type="text/javascript">

                function submitPage(pageNo) {

                    document.frmHiddenParam.pageNo.value = pageNo;
                    document.frmHiddenParam.hdnAction.value = 'paging';
                    document.getElementById('frmHiddenParam').submit();

                }
                //<![CDATA[
                var addJobVacancyUrl = '<?php echo url_for('recruitment/addJobVacancy'); ?>';
                var vacancyListUrl = '<?php echo url_for('recruitment/getVacancyListForJobTitleJson?jobTitle='); ?>';
                var hiringManagerListUrlForJobTitle = '<?php echo url_for('recruitment/getHiringManagerListJson?jobTitle='); ?>';
                var hiringManagerListUrlForVacancyId = '<?php echo url_for('recruitment/getHiringManagerListJson?vacancyId='); ?>';
                var lang_all = '<?php echo __("All") ?>';
    //]]>
</script>