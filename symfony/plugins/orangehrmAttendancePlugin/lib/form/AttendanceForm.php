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

class AttendanceForm extends sfForm {

    public $formWidgets = array(); 
    public $formValidators = array();
    
    public function configure() {

        $this->formWidgets['date'] = new ohrmWidgetDatePickerNew(array(), array('id' => 'attendance_date','class' => 'date', 'margin' => '0'));
        $this->formWidgets['time'] = new sfWidgetFormInputText(array(), array('class' => 'time'));
        $this->formWidgets['note'] = new sfWidgetFormTextarea(array(), array('class' => 'note', 'rows' => '5', 'cols' => '40'));

        $this->setWidgets($this->formWidgets);
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $this->formValidators['date'] = new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => false),
                    array('invalid' => 'Date format should be ' . $inputDatePattern));
        $this->formValidators['time'] = new sfValidatorDateTime(array(), array('required' => __('Enter Time')));
        $this->formValidators['note'] = new sfValidatorString(array('required' => false));

        $this->widgetSchema->setNameFormat('attendance[%s]');

        $this->setValidators($this->formValidators);
    }

}

