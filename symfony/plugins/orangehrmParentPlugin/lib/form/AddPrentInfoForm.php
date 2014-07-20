<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yasitha
 * Date: 7/20/14
 * Time: 10:59 AM
 * To change this template use File | Settings | File Templates.
 */

class AddPrentInfoForm extends BaseForm {

    public function configure() {

        // creating widgets
        $this->setWidgets(array(
            'stuSurname' => new sfWidgetFormInputText(),
            'stuOtherNames' => new sfWidgetFormInputText(),
            'curClass' => new sfWidgetFormInputText(),
            'dateOfBirth' => new ohrmWidgetDatePickerNew(array(), array('id' => 'addParent_dateOfBirth')),
            'religion' => new sfWidgetFormInputText(),
            'stuAdmissionNo' => new sfWidgetFormInputText(),
            'classOfAdmission' => new sfWidgetFormInputText(),
            'race' => new sfWidgetFormInputText(),
            'dateOfAdmission' => new ohrmWidgetDatePickerNew(array(), array('id' => 'addParent_dateOfAdmission')),
            'house' => new sfWidgetFormInputText(),
            'medium' => new sfWidgetFormInputText(),
            'resAddress' => new sfWidgetFormTextarea(),
            'scholarFromOther' => new sfWidgetFormInputCheckbox(),
            'scholarFromRoyal' => new sfWidgetFormInputCheckbox(),
            'noScholar' => new sfWidgetFormInputCheckbox(),

            'dadName' => new sfWidgetFormInputText(),
            'dadOccupation' => new sfWidgetFormSelect(array('choices' => $this->getDadOccupationList())),
            'dadOtherOccupation' => new sfWidgetFormInputText(),
            'dadDesignation' => new sfWidgetFormInputText(),
            'dadCompany' => new sfWidgetFormInputText(),
            'isFatherOldBoy' => new sfWidgetFormChoice(array('expanded' => true, 'choices'  => array(1 => __("Yes"), 0 => __("No")))),
            'dadObMemId' => new sfWidgetFormInputText(),

            'momName' => new sfWidgetFormInputText(),
            'momOccupation' => new sfWidgetFormSelect(array('choices' => $this->getMomOccupationList())),
            'momOtherOccupation' => new sfWidgetFormInputText(),
            'momDesignation' => new sfWidgetFormInputText(),
            'momCompany' => new sfWidgetFormInputText(),
            'momAdmissionNumber' => new sfWidgetFormInputText(),

        ));

        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $this->setValidators(array(
            'stuSurname' => new sfValidatorString(array('required' => true, 'max_length' => 35)),
            'stuOtherNames' => new sfValidatorString(array('required' => false, 'max_length' => 50)),
            'curClass' => new sfValidatorString(array('required' => true, 'max_length' => 10)),
            'dateOfBirth' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => false),
                array('invalid' => 'Date format should be ' . $inputDatePattern)),
            'religion' => new sfValidatorString(array('required' => true, 'max_length' => 20)),
            'stuAdmissionNo' => new sfValidatorString(array('required' => true, 'max_length' => 10)),
            'classOfAdmission' => new sfValidatorString(array('required' => true, 'max_length' => 10)),
            'race' => new sfValidatorString(array('required' => true, 'max_length' => 20)),
            'dateOfAdmission' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => false),
                array('invalid' => 'Date format should be ' . $inputDatePattern)),
            'house' => new sfValidatorString(array('required' => true, 'max_length' => 20)),
            'medium' => new sfValidatorString(array('required' => true, 'max_length' => 10)),
            'resAddress' => new sfValidatorString(array('required' => true, 'max_length' => 255)),

            'dadName' => new sfValidatorString(array('required' => true, 'max_length' => 100)),
            'dadOccupation' => new sfValidatorString(array('required' => true, 'max_length' => 50)),
            'dadOtherOccupation' => new sfValidatorString(array('required' => false, 'max_length' => 50)),
            'dadDesignation' => new sfValidatorString(array('required' => true, 'max_length' => 50)),
            'dadCompany' => new sfValidatorString(array('required' => true, 'max_length' => 50)),
            'isFatherOldBoy' => new sfValidatorChoice(array('required' => false,
                'choices' => array(1,0),
                'multiple' => false)),
            'dadObMemId' => new sfValidatorString(array('required' => true, 'max_length' => 50)),

            'momName' => new sfValidatorString(array('required' => true, 'max_length' => 100)),
            'momOccupation' => new sfValidatorString(array('required' => true, 'max_length' => 50)),
            'momOtherOccupation' => new sfValidatorString(array('required' => false, 'max_length' => 50)),
            'momDesignation' => new sfValidatorString(array('required' => true, 'max_length' => 50)),
            'momCompany' => new sfValidatorString(array('required' => true, 'max_length' => 50)),
            'momAdmissionNumber' => new sfValidatorString(array('required' => true, 'max_length' => 20)),

        ));

        $this->widgetSchema->setNameFormat('addParent[%s]');
        $this->widgetSchema['dateOfBirth']->setAttribute('style', 'width:100px');
        $this->setDefault('dateOfBirth', set_datepicker_date_format(date('Y-m-d')));
        $this->widgetSchema['dateOfAdmission']->setAttribute('style', 'width:100px');
        $this->setDefault('dateOfAdmission', set_datepicker_date_format(date('Y-m-d')));

    }

    public function getDadOccupationList(){
        $choices = array();
        $choices[""] = " -- Select --";
        $choices["Engineer"] = "Engineer";
        $choices["Technical Officer"] = "Technical Officer";
        $choices["Doctor"] = "Doctor";
        $choices["Accountant"] = "Accountant";
        $choices["Legal Officer"] = "Legal Officer";
        $choices["School Teacher"] = "School Teacher";
        $choices["University Lecturer"] = "University Lecturer";
        $choices["Principal"] = "Principal";
        $choices["Entrepreneur"] = "Entrepreneur";
        $choices["Banker"] = "Banker";
        $choices["Architect"] = "Architect";
        $choices["Govn. Admin.Officer"] = "Govn. Admin.Officer";
        $choices["Police Officer"] = "Police Officer";
        $choices["Armed Forces "] = "Armed Forces ";
        $choices["Male Nurse"] = "Male Nurse";
        $choices["Lawyer"] = "Lawyer";
        $choices["Other"] = "Other";

        return $choices;

    }

    public function getMomOccupationList(){
        $choices = array();
        $choices[""] = " -- Select --";
        $choices["Engineer"] = "Engineer";
        $choices["Technical Officer"] = "Technical Officer";
        $choices["Doctor"] = "Doctor";
        $choices["Accountant"] = "Accountant";
        $choices["Legal Officer"] = "Legal Officer";
        $choices["School Teacher"] = "School Teacher";
        $choices["University Lecturer"] = "University Lecturer";
        $choices["Principal"] = "Principal";
        $choices["Entrepreneur"] = "Entrepreneur";
        $choices["Banker"] = "Banker";
        $choices["Architect"] = "Architect";
        $choices["Govn. Admin.Officer"] = "Govn. Admin.Officer";
        $choices["Police Officer"] = "Police Officer";
        $choices["Armed Forces "] = "Armed Forces ";
        $choices["Nurse"] = "Nurse";
        $choices["Lawyer"] = "Lawyer";
        $choices["House Wife"] = "House Wife";
        $choices["Other"] = "Other";

        return $choices;

    }
}