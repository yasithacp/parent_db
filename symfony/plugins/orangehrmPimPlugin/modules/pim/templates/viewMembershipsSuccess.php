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
<?php echo stylesheet_tag('orangehrm.datepicker.css') ?>
<?php echo javascript_include_tag('orangehrm.datepicker.js') ?>
<?php
use_stylesheet('../orangehrmPimPlugin/css/viewMembershipsSuccess');
use_javascript('../orangehrmPimPlugin/js/viewMembershipsSuccess');

$numMemDetails = count($membershipDetails);
$hasMemDetails = $numMemDetails > 0;
$allowDel = true;
$allowEdit = true;
?>
<?php if ($form->hasErrors()): ?>
<span class="error">
<?php
echo $form->renderGlobalErrors();

foreach($form->getWidgetSchema()->getPositions() as $widgetName) {
  echo $form[$widgetName]->renderError();
}
?>
</span>
<?php endif; ?>
<table cellspacing="0" cellpadding="0" border="0" >
    <tr>
        <td width="5">&nbsp;</td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <!-- this space is reserved for menus - dont use -->
        <td width="200" valign="top">
            <?php include_partial('leftmenu', array('empNumber' => $empNumber, 'form' => $form)); ?></td>
        <td valign="top" width="1000">
            <div class="formpage2col">
                <div id="messagebar" class="<?php echo isset($messageType) ? "messageBalloon_{$messageType}" : ''; ?>" >
                    <span style="font-weight: bold;"><?php echo isset($message) ? $message : ''; ?></span>
                </div>

                <div id="addPaneMembership" style="display:none;">
                    <div class="outerbox">

                        <div class="mainHeading"><h2 id="membershipHeading"><?php echo __('Add Membership Details'); ?></h2></div>
                        <form name="frmEmpMembership" id="frmEmpMembership" method="post" action="<?php echo url_for('pim/updateMembership?empNumber=' . $empNumber); ?>">

                            <?php echo $form['_csrf_token']; ?>
                            <?php echo $form["empNumber"]->render(); ?>

                            <?php echo $form['membership']->renderLabel(__('Membership'). ' <span class="required">*</span>'); ?>
                            <?php echo $form['membership']->render(array("class" => "drpDown", "maxlength" => 50)); ?>
                            <br class="clear"/>

                            <?php echo $form['subscriptionPaidBy']->renderLabel(__('Subscription Paid By')); ?>
                            <?php echo $form['subscriptionPaidBy']->render(array("class" => "drpDown", "maxlength" => 50)); ?>
                            <br class="clear"/>

                            <?php echo $form['subscriptionAmount']->renderLabel(__('Subscription Amount')); ?>
                            <?php echo $form['subscriptionAmount']->render(array("class" => "formInputM", "maxlength" => 13)); ?>
                            <br class="clear"/>

                            <?php echo $form['currency']->renderLabel(__('Currency')); ?>
                            <?php echo $form['currency']->render(array("class" => "drpDown", "maxlength" => 50)); ?>
                            <br class="clear"/>

                            <?php echo $form['subscriptionCommenceDate']->renderLabel(__('Subscription Commence Date')); ?>
                            <?php echo $form['subscriptionCommenceDate']->render(array("class" => "formDateInput")); ?>
                            <br class="clear"/>

                            <?php echo $form['subscriptionRenewalDate']->renderLabel(__('Subscription Renewal Date')); ?>
                            <?php echo $form['subscriptionRenewalDate']->render(array("class" => "formDateInput")); ?>
                            <br class="clear"/>

                            <?php if ($allowEdit) {
                            ?>
                                <div class="formbuttons">
                                    <input type="button" class="savebutton" name="btnSaveMembership" id="btnSaveMembership"
                                           value="<?php echo __("Save"); ?>"
                                           title="<?php echo __("Save"); ?>"
                                           onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
                                    <input type="button" id="btnCancel" class="cancelbutton" value="<?php echo __("Cancel"); ?>"/>
                                </div>
                            <?php } ?>
                        </form>
                    </div>
                </div>

                <div class="outerbox" id="listMembershipDetails">
                    <form name="frmEmpDelMemberships" id="frmEmpDelMemberships" method="post" action="<?php echo url_for('pim/deleteMemberships?empNumber=' . $empNumber); ?>">
                        <?php echo $deleteForm['_csrf_token']->render(); ?>
                        <?php echo $deleteForm['empNumber']->render(); ?>

                            <div class="mainHeading"><h2><?php echo __("Assigned Memberships"); ?></h2></div>

                            <div class="actionbar" id="listActions">
                                <div class="actionbuttons">
                                <?php if ($allowEdit) {?>

                                    <input type="button" class="addbutton" id="btnAddMembershipDetail" onmouseover="moverButton(this);" onmouseout="moutButton(this);" value="<?php echo __("Add"); ?>" title="<?php echo __("Add"); ?>"/>
                                <?php } ?>
                                <?php if ($allowDel) { ?>

                                    <input type="button" class="delbutton" id="delMemsBtn" onmouseover="moverButton(this);" onmouseout="moutButton(this);" value="<?php echo __("Delete"); ?>" title="<?php echo __("Delete"); ?>"/>
                                <?php } ?>
                            </div>
                        </div>

                        <table width="550" cellspacing="0" cellpadding="0" class="data-table" id="mem_list">
                            <thead>
                                <tr>
                                    <td class="check"><input type='checkbox' id='checkAllMem' class="checkboxMem" /></td>
                                    <td class="memshipCode"><?php echo __("Membership"); ?></td>
                                    <td><?php echo __("Subscription Paid By"); ?></td>
                                    <td class="memshipAmount"><?php echo __("Subscription Amount"); ?></td>
                                    <td><?php echo __("Currency"); ?></td>
                                    <td><?php echo __("Subscription Commence Date"); ?></td>
                                    <td><?php echo __("Subscription Renewal Date"); ?></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $row = 0;
                                foreach ($membershipDetails as $memship) {
                                    $cssClass = ($row % 2) ? 'even' : 'odd';
                                    echo '<tr class="' . $cssClass . '">';
                                    $chkBoxValue = $empNumber . " " . $memship->membershipCode;
                                    echo "<td class='check'><input type='checkbox' class='checkboxMem' name='chkmemdel[]' value='" . $chkBoxValue . "'/></td>";
                                ?>
                                <?php $newMembership = $memship->getMembership();?>
                                <td class="memshipCode" valign="top"><a href="#"><?php echo $newMembership->name; ?></a></td>
                            <?php
                                    echo "<td class='memship' valigh='top'>" . $memship->subscriptionPaidBy . '</td>';
                                    echo "<td  class='memshipAmount1' valigh='top'>" . $memship->subscriptionAmount . '</td>';
                                    echo "<td class='memship'valigh='top'>" . $memship->subscriptionCurrency . '</td>';
                                    echo "<td class='memship'valigh='top'>" . set_datepicker_date_format($memship->subscriptionCommenceDate) . '</td>';
                                    echo "<td class='memship'valigh='top'>" . set_datepicker_date_format($memship->subscriptionRenewalDate) . '</td>';
                                    echo '</tr>';
                                    $row++;
                                }
                            ?>
                                </tbody>
                            </table>
                        </form>
                    </div>
                        <div class="paddingLeftRequired"><span class="required">*</span> <?php echo __(CommonMessages::REQUIRED_FIELD); ?></div>
                        <?php echo include_component('pim', 'customFields', array('empNumber' => $empNumber, 'screen' => 'membership')); ?>
                        <?php echo include_component('pim', 'attachments', array('empNumber' => $empNumber, 'screen' => 'membership')); ?>
                            </div>
                        </td>
                        <!-- To be moved to layout file -->
                        <td valign="top" style="text-align:left;">
                        </td>
                    </tr>
                </table>

                <script type="text/javascript">
                    //<![CDATA[

                    var fileModified = 0;
                    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
                    var deleteError = '<?php echo __(TopLevelMessages::SELECT_RECORDS); ?>';
                    var addMembershipDetail = '<?php echo __("Add Membership Details"); ?>';
                    var editMembershipDetail = '<?php echo __("Edit Membership Detail"); ?>';
                    var getMembershipsUrl = "<?php echo url_for('pim/getMemberships')?>";
                    var selectAMembership = '<?php echo __(ValidationMessages::REQUIRED); ?>';
                    var validDateMsg = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => get_datepicker_date_format($sf_user->getDateFormat()))) ?>';
                    var validNumberMsg = '<?php echo __("Should be a number"); ?>';
                    var dateError = '<?php echo __("Renewal date should be after commence date"); ?>';
                    var lang_negativeAmount = "<?php echo __("Should be a positive number"); ?>";
                    var lang_tooLargeAmount = "<?php echo __("Should be less than %amount%", array("%amount%" => '1000,000,000.00')); ?>";
                    //]]>
                </script>