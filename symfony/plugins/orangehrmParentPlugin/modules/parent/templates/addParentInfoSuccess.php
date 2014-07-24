<link href="<?php echo public_path('../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.core.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.datepicker.js') ?>"></script>
<?php echo stylesheet_tag('orangehrm.datepicker.css') ?>
<?php echo javascript_include_tag('orangehrm.datepicker.js') ?>
<?php use_stylesheet('../../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css'); ?>
<?php use_javascript('../../../scripts/jquery/ui/ui.core.js'); ?>
<?php use_javascript('../../../scripts/jquery/ui/ui.dialog.js'); ?>
<?php use_stylesheet('../orangehrmParentPlugin/css/addParentSuccess'); ?>
<?php use_javascript('../orangehrmParentPlugin/js/addParentSuccess'); ?>
<?php $browser = $_SERVER['HTTP_USER_AGENT']; ?>
<?php if (strstr($browser, "MSIE 8.0")): ?>
    <?php $keyWrdWidth = 'width: 276px' ?>
    <?php $resumeWidth = 37 ?>
<?php else: ?>
    <?php $keyWrdWidth = 'width: 271px' ?>
    <?php $resumeWidth = 38; ?>
<?php endif; ?>

<?php echo isset($templateMessage) ? templateMessage($templateMessage) : ''; ?>

<div id="messagebar" class="<?php echo isset($messageType) ? "messageBalloon_{$messageType}" : ''; ?>" >
    <span style="font-weight: bold;"><?php echo isset($message) ? $message : ''; ?></span>
</div>

<div id="addParent">
    <div class="outerbox">

        <div class="mainHeading"><h2 id="addParentHeading"><?php echo "Add Parent Information"; ?></h2></div>
        <form name="frmAddParent" id="frmAddParent" method="post" action="<?php echo url_for('parent/addParentInfo'); ?>">

            <?php echo $form['_csrf_token']; ?>
            <br class="clear"/>
            <span class="subHeading">Student's Information <lable id="studentTog" class="toggleButton">[-]</lable></span>
            <br class="clear"/>
            <br class="clear"/>

            <div id="studentTogDiv">
                <div>
                    <?php echo $form['stuSurname']->renderLabel(__('Student Surname') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['stuSurname']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['stuOtherNames']->renderLabel(__('Student Other Names') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['stuOtherNames']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['curClass']->renderLabel(__('Current Class') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['curClass']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['dateOfBirth']->renderLabel(__('Date of Birth') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['dateOfBirth']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['religion']->renderLabel(__('Religion') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['religion']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['stuAdmissionNo']->renderLabel(__('Student Admission No') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['stuAdmissionNo']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['classOfAdmission']->renderLabel(__('Class of Admission') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['classOfAdmission']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['race']->renderLabel(__('Race') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['race']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['dateOfAdmission']->renderLabel(__('Date of Admission') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['dateOfAdmission']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['house']->renderLabel(__('House') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['house']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['medium']->renderLabel(__('Medium') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['medium']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['resAddress']->renderLabel(__('Residential Address') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['resAddress']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['scholarFromOther']->renderLabel(__('Scholarship holder from other school ')); ?>
                    <?php echo $form['scholarFromOther']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['scholarFromRoyal']->renderLabel(__('Scholarship Holder from this school')); ?>
                    <?php echo $form['scholarFromRoyal']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['noScholar']->renderLabel(__('Not a Scholarship Holder')); ?>
                    <?php echo $form['noScholar']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
            </div>
            <div class="hrLine"></div>

            <span class="subHeading">Father's Information <lable id="fatherTog" class="toggleButton">[+]</lable></span>
            <br class="clear"/>
            <br class="clear"/>

            <div id="dadTogDiv">
                <div>
                    <?php echo $form['dadName']->renderLabel(__('Name with initials') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['dadName']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['dadOccupation']->renderLabel(__('Occupation') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['dadOccupation']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div id="dadOtherOccuDiv">
                    <?php echo $form['dadOtherOccupation']->renderLabel(__('If Other, Please specify')); ?>
                    <?php echo $form['dadOtherOccupation']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['dadDesignation']->renderLabel(__('Designation')); ?>
                    <?php echo $form['dadDesignation']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['dadCompany']->renderLabel(__('Name of the Company')); ?>
                    <?php echo $form['dadCompany']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['isFatherOldBoy']->renderLabel(__('Father is an Old Boy')); ?>
                    <?php echo $form['isFatherOldBoy']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['dadObMemId']->renderLabel(__('OB Membership ID')); ?>
                    <?php echo $form['dadObMemId']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['dadOfficeAddress']->renderLabel(__('Present Office Address')); ?>
                    <?php echo $form['dadOfficeAddress']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['dadMobileNo']->renderLabel(__('Mobile Number')); ?>
                    <?php echo $form['dadMobileNo']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['dadOfficeNo']->renderLabel(__('Office Number')); ?>
                    <?php echo $form['dadOfficeNo']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['dadResidentialNo']->renderLabel(__('Residential Number')); ?>
                    <?php echo $form['dadResidentialNo']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['dadEmail']->renderLabel(__('Email')); ?>
                    <?php echo $form['dadEmail']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
            </div>
            <div class="hrLine"></div>

            <span class="subHeading">Mother's Information <lable id="motherTog" class="toggleButton">[+]</lable></span>
            <br class="clear"/>
            <br class="clear"/>

            <div id="momTogDiv">
                <div>
                    <?php echo $form['momName']->renderLabel(__('Name with initials') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['momName']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['momOccupation']->renderLabel(__('Occupation') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['momOccupation']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div id="momOtherOccuDiv">
                    <?php echo $form['momOtherOccupation']->renderLabel(__('If Other, Please specify')); ?>
                    <?php echo $form['momOtherOccupation']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['momDesignation']->renderLabel(__('Designation')); ?>
                    <?php echo $form['momDesignation']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['momCompany']->renderLabel(__('Name of the Company')); ?>
                    <?php echo $form['momCompany']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['momAdmissionNumber']->renderLabel(__('Admission Number')); ?>
                    <?php echo $form['momAdmissionNumber']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['momOfficeAddress']->renderLabel(__('Present Office Address')); ?>
                    <?php echo $form['momOfficeAddress']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['momMobileNo']->renderLabel(__('Mobile Number')); ?>
                    <?php echo $form['momMobileNo']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['momOfficeNo']->renderLabel(__('Office Number')); ?>
                    <?php echo $form['momOfficeNo']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['momResidentialNo']->renderLabel(__('Residential Number')); ?>
                    <?php echo $form['momResidentialNo']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['momEmail']->renderLabel(__('Email')); ?>
                    <?php echo $form['momEmail']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
            </div>

            <div class="hrLine"></div>

            <span class="subHeading">Guardian's Information <lable id="guardianTog" class="toggleButton">[+]</lable></span>
            <br class="clear"/>
            <br class="clear"/>

            <div id="guardTogDiv">
                <div>
                    <?php echo $form['guardianName']->renderLabel(__('Name')); ?>
                    <?php echo $form['guardianName']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['guardianDesignation']->renderLabel(__('Designation')); ?>
                    <?php echo $form['guardianDesignation']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['guardianRelationship']->renderLabel(__('Relationship')); ?>
                    <?php echo $form['guardianRelationship']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['guardianHomeAddress']->renderLabel(__('Home Address')); ?>
                    <?php echo $form['guardianHomeAddress']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['guardianOfficeAddress']->renderLabel(__('Office Address')); ?>
                    <?php echo $form['guardianOfficeAddress']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['guardianMobileNo']->renderLabel(__('Mobile')); ?>
                    <?php echo $form['guardianMobileNo']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['guardianOfficeNo']->renderLabel(__('Office Number')); ?>
                    <?php echo $form['guardianOfficeNo']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['guardianResidentialNo']->renderLabel(__('Residential Number')); ?>
                    <?php echo $form['guardianResidentialNo']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['guardianEmail']->renderLabel(__('Email')); ?>
                    <?php echo $form['guardianEmail']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
            </div>
            <div class="hrLine"></div>

            <span class="subHeading">Emergency Contact's Information <lable id="emergeTog" class="toggleButton">[+]</lable></span>
            <br class="clear"/>
            <br class="clear"/>

            <div id="emergeTogDiv">
                <div>
                    <?php echo $form['emergencyContactName']->renderLabel(__('Name') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['emergencyContactName']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['emergencyContactRelationship']->renderLabel(__('Relationship') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['emergencyContactRelationship']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['emergencyContactAddress']->renderLabel(__('Address') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['emergencyContactAddress']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['emergencyContactMobileNo']->renderLabel(__('Mobile No') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['emergencyContactMobileNo']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['emergencyContactOfficeNo']->renderLabel(__('Office No')); ?>
                    <?php echo $form['emergencyContactOfficeNo']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
                <div>
                    <?php echo $form['emergencyContactResidentialNo']->renderLabel(__('Residential No') . ' <span class="required">*</span>'); ?>
                    <?php echo $form['emergencyContactResidentialNo']->render(array("class" => "formInputText")); ?>
                    <div class="errorHolder below"></div>
                </div>
                <br class="clear" />
            </div>

            <div class="formbuttons">
                    <input type="button" class="savebutton" name="btnSave" id="btnSave"
                           value="<?php echo __("Save"); ?>"onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
                    <input type="button" class="backbutton" name="btnBack" id="btnBack"
                           value="<?php echo __("Back"); ?>"onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
            </div>
        </form>
    </div>
</div>
<div class="paddingLeftRequired"><span class="required">*</span> <?php echo __(CommonMessages::REQUIRED_FIELD); ?></div>
<br class="clear" />
<script type="text/javascript">
    var lang_fieldRequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
    var lang_exceed50Charactors = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 50)); ?>';
    var lang_exceed100Charactors = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 100)); ?>';
    var lang_exceed20Charactors = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 20)); ?>';
    var lang_exceed255Charactors = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 255)); ?>';
    var lang_exceed35Charactors = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 35)); ?>';
    var lang_exceed10Charactors = '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS, array('%amount%' => 10)); ?>';

</script>

