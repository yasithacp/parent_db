<link href="<?php echo public_path('../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.core.js') ?>"></script>

<?php use_stylesheet('../orangehrmParentPlugin/css/gatewaySuccess'); ?>
<?php use_javascript('../orangehrmParentPlugin/js/gatewaySuccess'); ?>

<?php echo isset($templateMessage) ? templateMessage($templateMessage) : ''; ?>
<div id="messagebar" class="<?php echo isset($messageType) ? "messageBalloon_{$messageType}" : ''; ?>" >
    <span><?php echo isset($message) ? $message : ''; ?></span>
</div>

<div id="gateway">
    <div class="outerbox">

        <div class="mainHeading"><h2 id="gatewayHeading"><?php echo __("SMS Gateway"); ?></h2></div>
        <form name="frmGateway" id="frmGateway" method="post" action="<?php echo url_for('parent/smsGateway'); ?>" >

            <?php echo $form['_csrf_token']; ?>
            <?php echo $form->renderHiddenFields(); ?>

            <br class="clear"/>
            <div class="newColumn">
                <?php echo $form['number']->renderLabel(__('Send To') . ' <span class="required">*</span>'); ?>
                <?php echo $form['number']->render(array("class" => "formInput", "maxlength" => 120)); ?>
                <div class="errorHolder"></div>
            </div>
            <br class="clear"/>
            <div class="newColumn">
                <?php echo $form['message']->renderLabel(__('Message') . ' <span class="required">*</span>'); ?>
                <?php echo $form['message']->render(array("class" => "formInput", "maxlength" => 175)); ?>
                <div class="errorHolder"></div>
            </div>

            <div class="formbuttons">
                <input type="button" class="savebutton" name="btnSave" id="btnSave"
                       value="<?php echo __("Send"); ?>"onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
                <input type="button" class="cancelbutton" name="btnCancel" id="btnCancel"
                       value="<?php echo __("Cancel"); ?>"onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
            </div>

        </form>
    </div>
    <div class="paddingLeftRequired"><span class="required">*</span> <?php echo __(CommonMessages::REQUIRED_FIELD); ?></div>
</div>