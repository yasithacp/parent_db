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
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.datepicker.js') ?>"></script>

<?php echo javascript_include_tag('../orangehrmAdminPlugin/js/viewOrganizationGeneralInformationSuccess'); ?>
<?php echo stylesheet_tag('../orangehrmAdminPlugin/css/viewOrganizationGeneralInformationSuccess'); ?>

<div id="messagebar"  class="<?php echo isset($messageType) ? "messageBalloon_{$messageType}" : ''; ?>" >
    <span><?php echo isset($message) ? $message : ''; ?></span>
</div>

<div id="genInfo">
    <div class="outerbox">
        <div class="mainHeading"><h2 id="genInfoHeading"><?php echo __('General Information'); ?></h2></div>
        <form name="frmGenInfo" id="frmGenInfo" method="post" action="<?php echo url_for('admin/viewOrganizationGeneralInformation'); ?>">

            <?php echo $form['_csrf_token']; ?>
            <br class="clear"/>

            <div class="leftDiv">
                <?php echo $form['name']->renderLabel(__('Organization Name') . ' <span class="required">*</span>'); ?>
                <?php echo $form['name']->render(array("class" => "txtBox", "maxlength" => 100)); ?>
                <br class="clear"/>

                <label><?php echo __("Number of Employees") ?></label>
                <div id="numOfEmployees"><?php echo $employeeCount; ?></div>
            </div>


            <div class="rightDiv">
                <?php echo $form['taxId']->renderLabel(__('Tax ID')); ?>
                <?php echo $form['taxId']->render(array("class" => "txtBox", "maxlength" => 30)); ?>
                <br class="clear"/>

                <?php echo $form['registraionNumber']->renderLabel(__('Registration Number')); ?>
                <?php echo $form['registraionNumber']->render(array("class" => "txtBox", "maxlength" => 30)); ?>
            </div>

            <br class="clear"/>
            <br />
            <div class="hrLine"></div>
            <br class="clear"/>
            <div class="leftDiv">
                <?php echo $form['phone']->renderLabel(__('Phone')); ?>
                <?php echo $form['phone']->render(array("class" => "txtBox", "maxlength" => 30)); ?>
                <br class="clear"/>

                <?php echo $form['fax']->renderLabel(__('Fax')); ?>
                <?php echo $form['fax']->render(array("class" => "txtBox", "maxlength" => 30)); ?>
            </div>

            <div class="rightDiv">
                <?php echo $form['email']->renderLabel(__('Email')); ?>
                <?php echo $form['email']->render(array("class" => "txtBox", "maxlength" => 30)); ?>
            </div>
            <br class="clear"/>
            <br />
            <div class="hrLine" ></div>
            <br class="clear"/>

            <div class="leftDiv">
                <?php echo $form['street1']->renderLabel(__('Address Street 1')); ?>
                <?php echo $form['street1']->render(array("class" => "txtBox", "maxlength" => 100)); ?>
                <br class="clear"/>

                <?php echo $form['street2']->renderLabel(__('Address Street 2')); ?>
                <?php echo $form['street2']->render(array("class" => "txtBox", "maxlength" => 100)); ?>
                <br class="clear"/>

                <?php echo $form['city']->renderLabel(__('City')); ?>
                <?php echo $form['city']->render(array("class" => "txtBox", "maxlength" => 30)); ?>
            </div>
            <div class="rightDiv">
                <?php echo $form['province']->renderLabel(__('State/Province')); ?>
                <?php echo $form['province']->render(array("class" => "txtBox", "maxlength" => 30)); ?>
                <br class="clear"/>

                <?php echo $form['zipCode']->renderLabel(__('Zip/Postal Code')); ?>
                <?php echo $form['zipCode']->render(array("class" => "txtBox", "maxlength" => 30)); ?>
                <br class="clear"/>

                <?php echo $form['country']->renderLabel(__('Country')); ?>
                <?php echo $form['country']->render(array("class" => "drpDown", "maxlength" => 30)); ?>
            </div>
            <br class="clear"/>
            <div class="leftDiv">
                <?php echo $form['note']->renderLabel(__('Note')); ?>
                <?php echo $form['note']->render(array("class" => "txtArea", "maxlength" => 255)); ?>
                <div class="errorHolder"></div>
            </div>
            <br class="clear"/>

            <div class="formbuttons">
                <input type="button" class="savebutton" name="btnSaveGenInfo" id="btnSaveGenInfo"
                       value="<?php echo __("Edit"); ?>"
                       onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
            </div>
        </form>
    </div>
</div>

<div class="paddingLeftRequired"><span class="required">*</span> <?php echo __(CommonMessages::REQUIRED_FIELD); ?></div>

<script type="text/javascript">

    //<![CDATA[
    var edit = "<?php echo __("Edit"); ?>";
    var save = "<?php echo __("Save"); ?>";
    var nameRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var invalidPhoneNumber = '<?php echo __(ValidationMessages::TP_NUMBER_INVALID); ?>';
    var invalidFaxNumber = '<?php echo __(ValidationMessages::TP_NUMBER_INVALID); ?>';
    var incorrectEmail = '<?php echo __(ValidationMessages::EMAIL_INVALID); ?>';
    var lang_exceed255Chars = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 250)); ?>';
    //]]>
</script>