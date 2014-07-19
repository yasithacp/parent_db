
<?php echo stylesheet_tag('orangehrm.datepicker.css') ?>
<link href="<?php echo public_path('../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css') ?>" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.core.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_path('../../scripts/jquery/ui/ui.datepicker.js') ?>"></script>
<?php echo javascript_include_tag('orangehrm.datepicker.js') ?>
<?php
use_stylesheet('../../../themes/orange/css/jquery/jquery.autocomplete.css');
use_stylesheet('../../../themes/orange/css/ui-lightness/jquery-ui-1.7.2.custom.css');

use_javascript('../../../scripts/jquery/ui/ui.core.js');
use_javascript('../../../scripts/jquery/ui/ui.dialog.js');
use_javascript('../../../scripts/jquery/jquery.autocomplete.js');
?>
<div id="validationMsg" style="margin-left: 16px; width: 470px"><?php echo isset($messageData) ? templateMessage($messageData) : ''; ?></div>
<div class="outerbox" id="outerbox" style="width: 60%">
    <div class="mainHeading"><h2 id="reportToHeading"><?php echo __($reportName); ?></h2></div>
    <form action="<?php echo url_for("time/displayEmployeeReportCriteria?reportId=2"); ?>" id="reportForm" method="post">

        <div class="employeeTable">
            <br class="clear"/>

            <?php foreach ($sf_data->getRaw('runtimeFilterFieldWidgetNamesAndLabelsList') as $label): ?>
            <?php echo $reportForm[$label['labelName']]->renderLabel(); ?>
            <?php echo $reportForm[$label['labelName']]->render(); ?><?php echo $reportForm[$label['labelName']]->renderError(); ?>
                <div class="errorDiv"></div>
                <br class="clear"/>
                <br class="clear"/>
            <?php endforeach; ?>
            <?php echo $reportForm->renderHiddenFields(); ?>
            </div>

            <div class="formbuttons">
                <td colspan="2"><input type="button" id="viewbutton" class="viewbutton" value="<?php echo __('View') ?>"/></td>
            </div>
        </form>
    </div>
    <div class="paddingLeftRequired"><span class="required">*</span> <?php echo __(CommonMessages::REQUIRED_FIELD); ?></div>

    <style type="text/css">
        form#reportForm label {
            margin-top: 6px;
            width: 140px;
            font-weight: normal;
        }
        #time_activity_name{
            width: 160px;
        }
        #time_project_name{
            width: 160px;
        }

        .errorDiv {
            padding-left: 20px;
        }
        .viewbutton {
            margin-left: 20px;
        }

        .paddingLeftRequired{
            font-size: 8pt;
            padding-left: 15px;
            padding-top: 5px;
        }
        label.error{
            width: 230px !important;
        }
    </style>

    <script type="text/javascript">
                    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
            var lang_dateError = '<?php echo __("To date should be after from date") ?>';
            var lang_validDateMsg = '<?php echo __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => get_datepicker_date_format($sf_user->getDateFormat()))) ?>';
            var lang_required = '<?php echo __(ValidationMessages::REQUIRED); ?>';
            var lang_empNamerequired = '<?php echo __(ValidationMessages::REQUIRED); ?>';
            var lang_activityRequired = '<?php echo __(ValidationMessages::REQUIRED)?>';
        $(document).ready(function() {


         $('#viewbutton').click(function() {
            $('#reportForm').submit();
        });


        var validator = $("#reportForm").validate({

            rules: {
            'time[employee][empId]' : {
                required:true
            },
            'time[activity_name]' : {
                required:true
            },
                'time[project_date_range][from]' : {
                    valid_date: function() {
                        return {
                            format:datepickerDateFormat,
                            required:false
                        }
                    }},
                'time[project_date_range][to]' : {
                    valid_date: function() {
                        return {
                            format:datepickerDateFormat,
                            required:false
                        }
                    },
                    date_range: function() {
                        return {
                            format:datepickerDateFormat,
                            fromDate:$('#project_date_range_from_date').val()
                        }
                    }
                }
            },
            messages: {
                'time[employee][empId]' : {
                    required: lang_empNamerequired
                },
                'time[activity_name]' : {
                    required: lang_activityRequired
                },
                'time[project_date_range][from]' : {
                    valid_date: lang_validDateMsg
                },
                'time[project_date_range][to]' : {
                    valid_date: lang_validDateMsg,
                    date_range:lang_dateError
                }

            },
            errorPlacement: function(error, element) {
                error.appendTo(element.prev('label'));
                error.appendTo(element.next().next().next('div.errorDiv'));}
            //                    error.appendTo(element.prev().prev().prev().prev('label'));}


        });
    });

        function Date_toYMD() {
    var dt=new Date();
    var year, month, day;
    year = String(dt.getFullYear());
    month = String(dt.getMonth() + 1);
    if (month.length == 1) {
        month = "0" + month;
    }
    day = String(dt.getDate());
    if (day.length == 1) {
        day = "0" + day;
    }
    return year + "-" + month + "-" + day;
}
</script>