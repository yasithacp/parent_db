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


/**
 * Form class for Custom fields
 */
class CustomFieldForm extends BaseForm {

    
    public function configure() {
        
        $screens = $this->getScreens();
        
        $types = $this->getFieldTypes();
    
        $this->setWidgets(array(
            'field_num' => new sfWidgetFormInputHidden(),
            'name' => new sfWidgetFormInputText(),
            'type' => new sfWidgetFormSelect(array('choices' => $types)),
            'screen' => new sfWidgetFormSelect(array('choices' => $screens)),            
            'extra_data' => new sfWidgetFormInputText(),
        ));

        //
        // Remove default -- select -- option from valid values
        unset($types['']);
        unset($screens['']);
        
        $this->setValidators(array(
            'field_num' => new sfValidatorNumber(array('required' => false, 'min'=> 1, 'max'=>10)),
            'name' => new sfValidatorString(array('required' => true, 'max_length'=>250)),
            'type' => new sfValidatorChoice(array('choices' => array_keys($types))),
            'screen' => new sfValidatorChoice(array('choices' => array_keys($screens))),
            'extra_data' => new sfValidatorString(array('required' => false, 'trim'=>true, 'max_length'=>250))
        ));
       
        $this->widgetSchema->setNameFormat('customField[%s]');
    }
    
    public function getFieldTypes() {
        $types = array('' => '-- ' . __('Select') . ' --',
                      CustomFields::FIELD_TYPE_STRING => __('Text or Number'),
                      CustomFields::FIELD_TYPE_SELECT => __('Drop Down'));        
        
        return $types;
    }
    
    public function getScreens() {
        $screens = array('' =>  '-- ' . __('Select') . ' --',
                             'personal'=> __('Personal Details'),
                             'contact' => __('Contact Details'),
                             'emergency' => __('Emergency Contacts'),
                             'dependents' => __('Dependents'),
                             'immigration' => __('Immigration'),
                             'qualifications' => __('Qualifications'),
                             'tax' => __('Tax Exemptions'),
                             'salary' => __('Salary'),
                             'job' => __('Job'),
                             'report-to' => __('Report-to'),
                             'membership' => __('Membership')
                             );
        return $screens;
    }

}