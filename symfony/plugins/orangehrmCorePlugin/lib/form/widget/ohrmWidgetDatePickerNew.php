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


class ohrmWidgetDatePickerNew extends sfWidgetFormInput {

    public function render($name, $value = null, $attributes = array(), $errors = array()) {

        if (array_key_exists('class', $attributes)) {
            $attributes['class'] .= ' ohrm_datepicker';
        } else {
            $attributes['class'] = 'ohrm_datepicker';
        }

        $html = parent::render($name, $value, $attributes, $errors);
        $html .= $this->renderTag('input', array(
                    'type' => 'button',
                    'id' => "{$this->attributes['id']}_Button",
                    'class' => 'calendarBtn',
                    'style' => 'float: none; display: inline; margin-left: 6px;',
                    'value' => '',
                ));

        $javaScript = sprintf(<<<EOF
 <script type="text/javascript">

    var datepickerDateFormat = '%s';

    $(document).ready(function(){

        var rDate = trim($("#%s").val());
            if (rDate == '') {
                $("#%s").val(datepickerDateFormat);
            }

        //Bind date picker
        daymarker.bindElement("#%s",
        {
            onSelect: function(date){

            },
            dateFormat : datepickerDateFormat,
            onClose: function(){
                $(this).valid();
            }
        });

        $('#%s_Button').click(function(){
            daymarker.show("#%s");

        });
    });
</script>
EOF
                        ,
                        get_datepicker_date_format(sfContext::getInstance()->getUser()->getDateFormat()),
                        $this->attributes['id'],
                        $this->attributes['id'],
                        $this->attributes['id'],
                        $this->attributes['id'],
                        $this->attributes['id']
        );

        return $html . $javaScript;
    }

}

