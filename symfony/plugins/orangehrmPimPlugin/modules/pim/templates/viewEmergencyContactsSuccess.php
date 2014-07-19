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

use_stylesheet('../orangehrmPimPlugin/css/viewEmergencyContactsSuccess');
use_javascript('../orangehrmPimPlugin/js/viewEmergencyContactsSuccess');

$numContacts = count($emergencyContacts);
$haveContacts = $numContacts > 0;
$allowEdit = true;
$allowDel = true;
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
<script type="text/javascript">
//<![CDATA[

var fileModified = 0;

//]]>
</script>
<?php // To be moved into layout ?>
<table cellspacing="0" cellpadding="0" border="0" >
    <tr>
        <td width="5">&nbsp;</td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <!-- this space is reserved for menus - dont use -->
        <td width="200" valign="top">
        <?php include_partial('leftmenu', array('empNumber' => $empNumber, 'form' => $form));?></td>
        <td valign="top">

<div class="formpage2col">
<div id="messagebar" class="<?php echo isset($messageType) ? "messageBalloon_{$messageType}" : ''; ?>" >
    <span style="font-weight: bold;"><?php echo isset($message) ? $message : ''; ?></span>
</div>

<div id="addPaneEmgContact" style="display:none;" >
<div class="outerbox">

    <div class="mainHeading"><h2 id="emergencyContactHeading"><?php echo __('Add Emergency Contact'); ?></h2></div>
    <form name="frmEmpEmgContact" id="frmEmpEmgContact" method="post" action="<?php echo url_for('pim/updateEmergencyContact?empNumber=' . $empNumber); ?>">

    <?php echo $form['_csrf_token']; ?>
    <?php echo $form["empNumber"]->render(); ?>
    <?php echo $form["seqNo"]->render(); ?>

    <?php echo $form['name']->renderLabel(__('Name') . ' <span class="required">*</span>'); ?>
    <?php echo $form['name']->render(array("class" => "formInputText", "maxlength" => 50)); ?>

    <?php echo $form['relationship']->renderLabel(__('Relationship') . ' <span class="required">*</span>'); ?>
    <?php echo $form['relationship']->render(array("class" => "formInputText", "maxlength" => 30)); ?>
    <br class="clear"/>

    <?php echo $form['homePhone']->renderLabel(__('Home Telephone')); ?>
    <?php echo $form['homePhone']->render(array("class" => "formInputText", "maxlength" => 25)); ?>

    <?php echo $form['mobilePhone']->renderLabel(__('Mobile')); ?>
    <?php echo $form['mobilePhone']->render(array("class" => "formInputText", "maxlength" => 25)); ?>
    <br class="clear"/>

    <?php echo $form['workPhone']->renderLabel(__('Work Telephone')); ?>
    <?php echo $form['workPhone']->render(array("class" => "formInputText", "maxlength" => 25)); ?>
    <br class="clear"/>
    
    <?php if (($allowEdit)) { ?>
            <div class="formbuttons">
                <input type="button" class="savebutton" name="btnSaveEContact" id="btnSaveEContact"
                       value="<?php echo __("Save"); ?>"
                       title="<?php echo __("Save"); ?>"
                       onmouseover="moverButton(this);" onmouseout="moutButton(this);"/>
                <input type="button" id="btnCancel" class="cancelbutton" value="<?php echo __("Cancel"); ?>"/>
            </div>
    <?php } ?>
    </form>
</div>
</div>

<div class="outerbox" id="listEmegrencyContact">
<form name="frmEmpDelEmgContacts" id="frmEmpDelEmgContacts" method="post" action="<?php echo url_for('pim/deleteEmergencyContacts?empNumber=' . $empNumber); ?>">
<?php echo $deleteForm['_csrf_token']->render(); ?>
<?php echo $deleteForm['empNumber']->render(); ?>

    <div class="mainHeading"><h2><?php echo __("Assigned Emergency Contacts"); ?></h2></div>

    <div class="actionbar" id="listActions">
            <div class="actionbuttons">
<?php if ($allowEdit) { ?>

                    <input type="button" class="addbutton" id="btnAddContact" onmouseover="moverButton(this);" onmouseout="moutButton(this);" value="<?php echo __("Add"); ?>" title="<?php echo __("Add"); ?>"/>
            <?php } ?>
            <?php if ($allowDel) {
 ?>

                <input type="button" class="delbutton" id="delContactsBtn" onmouseover="moverButton(this);" onmouseout="moutButton(this);" value="<?php echo __("Delete"); ?>" title="<?php echo __("Delete"); ?>"/>
            <?php } ?>
        </div>
    </div>

    <table width="550" cellspacing="0" cellpadding="0" class="data-table" id="emgcontact_list">
        <thead>
            <tr>
                <td class="check"><input type='checkbox' id='checkAll' class="checkbox" /></td>
                <td class="emgContactName"><?php echo __("Name"); ?></td>
                <td><?php echo __("Relationship"); ?></td>
                <td><?php echo __("Home Telephone"); ?></td>
                <td><?php echo __("Mobile"); ?></td>
                <td><?php echo __("Work Telephone"); ?></td>
            </tr>
        </thead>
        <tbody>
    <?php
            $row = 0;
            foreach ($emergencyContacts as $contact) {
                $cssClass = ($row % 2) ? 'even' : 'odd';
                echo '<tr class="' . $cssClass . '">';
                echo "<td class='check'><input type='checkbox' class='checkbox' name='chkecontactdel[]' value='" . $contact->seqno . "'/></td>";
?>
        <td class="emgContactName" valign="top"><a href="#"><?php echo $contact->name; ?></a></td>
            <?php
                echo "<td valigh='top'>" . $contact->relationship . "</td>";
                echo "<td valigh='top'>" . $contact->home_phone . '</td>';
                echo "<td valigh='top'>" . $contact->mobile_phone . '</td>';
                echo "<td valigh='top'>" . $contact->office_phone . '</td>';
                echo '</tr>';
                $row++;
            } ?>
            </tbody>
        </table>
    </form>
</div>
<div class="paddingLeftRequired"><span class="required">*</span> <?php echo __(CommonMessages::REQUIRED_FIELD); ?></div>
<?php echo include_component('pim', 'customFields', array('empNumber'=>$empNumber, 'screen' => 'emergency'));?>
<?php echo include_component('pim', 'attachments', array('empNumber'=>$empNumber, 'screen' => 'emergency'));?>
    
</div>
            </td>
            <!-- To be moved to layout file -->
            <td valign="top" style="text-align:left;">
            </td>
    </tr>
</table>
<script type="text/javascript">
    //<![CDATA[

    // Move to separate js after completing initial work
    
    function clearAddForm() {
        $('#emgcontacts_seqNo').val('');
        $('#emgcontacts_name').val('');
        $('#emgcontacts_relationship').val('');
        $('#emgcontacts_homePhone').val('');
        $('#emgcontacts_mobilePhone').val('');
        $('#emgcontacts_workPhone').val('');
        $('div#addPaneEmgContact label.error').hide();
        $('div#messagebar').hide();
    }

    function addEditLinks() {
        // called here to avoid double adding links - When in edit mode and cancel is pressed.
        removeEditLinks();
        $('#emgcontact_list tbody td.emgContactName').wrapInner('<a href="#"/>');
    }

    function removeEditLinks() {
        $('#emgcontact_list tbody td.emgContactName a').each(function(index) {
            $(this).parent().text($(this).text());
        });
    }

    $(document).ready(function() {
        
        $("#checkAll").click(function(){
            if($("#checkAll:checked").attr('value') == 'on') {
                $(".checkbox").attr('checked', 'checked');
            } else {
                $(".checkbox").removeAttr('checked');
            }
        });

        if($(".checkbox").length > 1) {
            $(".paddingLeftRequired").hide();
            $("#addPaneEmgContact").hide();
        } else {
            $("#btnCancel").hide();
            $(".paddingLeftRequired").show();
            $("#addPaneEmgContact").show();
            $("#listEmegrencyContact").hide();
        }

        $(".checkbox").click(function() {
            $("#checkAll").removeAttr('checked');
            if(($(".checkbox").length - 1) == $(".checkbox:checked").length) {
                $("#checkAll").attr('checked', 'checked');
            }
        });
        // Edit a emergency contact in the list
        $('#frmEmpDelEmgContacts a').live('click', function() {

            var row = $(this).closest("tr");
            var seqNo = row.find('input.checkbox:first').val();
            var name = $(this).text();
            var relationship = row.find("td:nth-child(3)").text();
            var homePhone = row.find("td:nth-child(4)").text();
            var mobilePhone = row.find("td:nth-child(5)").text();
            var workPhone = row.find("td:nth-child(6)").text();

            $('#emgcontacts_seqNo').val(seqNo);
            $('#emgcontacts_name').val(name);
            $('#emgcontacts_relationship').val(relationship);
            $('#emgcontacts_homePhone').val(homePhone);
            $('#emgcontacts_mobilePhone').val(mobilePhone);
            $('#emgcontacts_workPhone').val(workPhone);

            $(".paddingLeftRequired").show();
            $("#emergencyContactHeading").text("<?php echo __("Edit Emergency Contact");?>");
            $('div#messagebar').hide();
            // hide validation error messages

            $('#listActions').hide();
            $('#emgcontact_list td.check').hide();
            $('#addPaneEmgContact').css('display', 'block');

        });

        // Cancel in add pane
        $('#btnCancel').click(function() {
            clearAddForm();
            $('#addPaneEmgContact').css('display', 'none');
            $('#listActions').show();
            $('#emgcontact_list td.check').show();
            addEditLinks();
            $('div#messagebar').hide();
            $(".paddingLeftRequired").hide();
        });

        // Add a emergency contact
        $('#btnAddContact').click(function() {
            $("#emergencyContactHeading").text("<?php echo __("Add Emergency Contact");?>");
            $(".paddingLeftRequired").show();
            clearAddForm();

            // Hide list action buttons and checkbox
            $('#listActions').hide();
            $('#emgcontact_list td.check').hide();
            removeEditLinks();
            $('div#messagebar').hide();

            //
            //            // hide validation error messages
            //            $("label.errortd[generated='true']").css('display', 'none');
            $('#addPaneEmgContact').css('display', 'block');
        });

        /* Valid Contact Phone */
        $.validator.addMethod("validContactPhone", function(value, element) {
            if ( $('#emgcontacts_homePhone').val() == '' && $('#emgcontacts_mobilePhone').val() == '' &&
                    $('#emgcontacts_workPhone').val() == '' )
                return false;
            else
                return true
        });
        
        $("#frmEmpEmgContact").validate({

            rules: {
                'emgcontacts[name]' : {required:true, maxlength:100},
                'emgcontacts[relationship]' : {required: true, maxlength:100},
                'emgcontacts[homePhone]' : {phone: true, validContactPhone:true, maxlength:100},
                'emgcontacts[mobilePhone]' : {phone: true, maxlength:100},
                'emgcontacts[workPhone]' : {phone: true, maxlength:100}
            },
            messages: {
                'emgcontacts[name]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED); ?>',
                    maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS,array('%amount%' => 100)); ?>'
                },
                'emgcontacts[relationship]': {
                    required:'<?php echo __(ValidationMessages::REQUIRED); ?>',
                    maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS,array('%amount%' => 100)); ?>'
                },
                'emgcontacts[homePhone]' : {
                    phone:'<?php echo __(ValidationMessages::TP_NUMBER_INVALID); ?>',
                    validContactPhone:'<?php echo __('At least one phone number is required'); ?>',
                    maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS,array('%amount%' => 100)); ?>'
                },
                'emgcontacts[mobilePhone]' : {
                	phone:'<?php echo __(ValidationMessages::TP_NUMBER_INVALID); ?>',
                	maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS,array('%amount%' => 100)); ?>'

                },
                'emgcontacts[workPhone]' : {
                	phone:'<?php echo __(ValidationMessages::TP_NUMBER_INVALID); ?>',
                	maxlength: '<?php echo __(ValidationMessages::TEXT_LENGTH_EXCEEDS,array('%amount%' => 100)); ?>'
                }
            },
            errorPlacement: function(error, element) {
                    error.appendTo( element.prev('label') );
                }


        });

        
        $('#delContactsBtn').click(function() {
            var checked = $('#frmEmpDelEmgContacts input:checked').length;

            if (checked == 0) {
                $("#messagebar").attr("class", "messageBalloon_notice");
                $("#messagebar").text("<?php echo __(TopLevelMessages::SELECT_RECORDS); ?>");
            } else {
                $('#frmEmpDelEmgContacts').submit();
            }
        });

        $('#btnSaveEContact').click(function() {
            $('#frmEmpEmgContact').submit();
        });
});
//]]>
</script>

