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
            'dadOfficeAddress' => new sfWidgetFormTextarea(),
            'dadMobileNo' => new sfWidgetFormInputText(),
            'dadOfficeNo' => new sfWidgetFormInputText(),
            'dadResidentialNo' => new sfWidgetFormInputText(),
            'dadEmail' => new sfWidgetFormInputText(),

            'momName' => new sfWidgetFormInputText(),
            'momOccupation' => new sfWidgetFormSelect(array('choices' => $this->getMomOccupationList())),
            'momOtherOccupation' => new sfWidgetFormInputText(),
            'momDesignation' => new sfWidgetFormInputText(),
            'momCompany' => new sfWidgetFormInputText(),
            'momAdmissionNumber' => new sfWidgetFormInputText(),
            'momOfficeAddress' => new sfWidgetFormTextarea(),
            'momMobileNo' => new sfWidgetFormInputText(),
            'momOfficeNo' => new sfWidgetFormInputText(),
            'momResidentialNo' => new sfWidgetFormInputText(),
            'momEmail' => new sfWidgetFormInputText(),

            'guardianName' => new sfWidgetFormInputText(),
            'guardianDesignation' => new sfWidgetFormInputText(),
            'guardianRelationship' => new sfWidgetFormInputText(),
            'guardianHomeAddress' => new sfWidgetFormTextarea(),
            'guardianOfficeAddress' => new sfWidgetFormTextarea(),
            'guardianMobileNo' => new sfWidgetFormInputText(),
            'guardianOfficeNo' => new sfWidgetFormInputText(),
            'guardianResidentialNo' => new sfWidgetFormInputText(),
            'guardianEmail' => new sfWidgetFormInputText(),

            'emergencyContactName' => new sfWidgetFormInputText(),
            'emergencyContactRelationship' => new sfWidgetFormInputText(),
            'emergencyContactAddress' => new sfWidgetFormTextarea(),
            'emergencyContactMobileNo' => new sfWidgetFormInputText(),
            'emergencyContactOfficeNo' => new sfWidgetFormInputText(),
            'emergencyContactResidentialNo' => new sfWidgetFormInputText()

        ));

        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $this->setValidators(array(
            'stuSurname' => new sfValidatorString(array('required' => true, 'max_length' => 35)),
            'stuOtherNames' => new sfValidatorString(array('required' => true, 'max_length' => 50)),
            'curClass' => new sfValidatorString(array('required' => true, 'max_length' => 10)),
            'dateOfBirth' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true),
                array('invalid' => 'Date format should be ' . $inputDatePattern)),
            'religion' => new sfValidatorString(array('required' => true, 'max_length' => 20)),
            'stuAdmissionNo' => new sfValidatorString(array('required' => true, 'max_length' => 10)),
            'classOfAdmission' => new sfValidatorString(array('required' => true, 'max_length' => 10)),
            'race' => new sfValidatorString(array('required' => true, 'max_length' => 20)),
            'dateOfAdmission' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => true),
                array('invalid' => 'Date format should be ' . $inputDatePattern)),
            'house' => new sfValidatorString(array('required' => true, 'max_length' => 20)),
            'medium' => new sfValidatorString(array('required' => true, 'max_length' => 10)),
            'resAddress' => new sfValidatorString(array('required' => true, 'max_length' => 255)),
            'scholarFromOther' => new sfValidatorPass(array('required' => false)),
            'scholarFromRoyal' => new sfValidatorPass(array('required' => false)),
            'noScholar' => new sfValidatorPass(array('required' => false)),

            'dadName' => new sfValidatorString(array('required' => true, 'max_length' => 100)),
            'dadOccupation' => new sfValidatorString(array('required' => true, 'max_length' => 50)),
            'dadOtherOccupation' => new sfValidatorString(array('required' => false, 'max_length' => 50)),
            'dadDesignation' => new sfValidatorString(array('required' => false, 'max_length' => 50)),
            'dadCompany' => new sfValidatorString(array('required' => false, 'max_length' => 50)),
            'isFatherOldBoy' => new sfValidatorChoice(array('required' => false,
                'choices' => array(1,0),
                'multiple' => false)),
            'dadObMemId' => new sfValidatorString(array('required' => false, 'max_length' => 50)),
            'dadOfficeAddress' => new sfValidatorString(array('required' => false, 'max_length' => 255)),
            'dadMobileNo' => new sfValidatorNumber(array('required' => false, 'max' => 9999999999, 'min' => 0)),
            'dadOfficeNo' => new sfValidatorNumber(array('required' => false, 'max' => 9999999999, 'min' => 0)),
            'dadResidentialNo' => new sfValidatorNumber(array('required' => false, 'max' => 9999999999, 'min' => 0)),
            'dadEmail' => new sfValidatorEmail(array('required' => false, 'max_length' => 100, 'trim' => true)),

            'momName' => new sfValidatorString(array('required' => true, 'max_length' => 100)),
            'momOccupation' => new sfValidatorString(array('required' => true, 'max_length' => 50)),
            'momOtherOccupation' => new sfValidatorString(array('required' => false, 'max_length' => 50)),
            'momDesignation' => new sfValidatorString(array('required' => false, 'max_length' => 50)),
            'momCompany' => new sfValidatorString(array('required' => false, 'max_length' => 50)),
            'momAdmissionNumber' => new sfValidatorString(array('required' => false, 'max_length' => 20)),
//            what is mom admission number???
            'momOfficeAddress' => new sfValidatorString(array('required' => false, 'max_length' => 255)),
            'momMobileNo' => new sfValidatorNumber(array('required' => false, 'max' => 9999999999, 'min' => 0)),
            'momOfficeNo' => new sfValidatorNumber(array('required' => false, 'max' => 9999999999, 'min' => 0)),
            'momResidentialNo' => new sfValidatorNumber(array('required' => false, 'max' => 9999999999, 'min' => 0)),
            'momEmail' => new sfValidatorEmail(array('required' => false, 'max_length' => 100, 'trim' => true)),

            'guardianName' => new sfValidatorString(array('required' => false, 'max_length' => 100)),
            'guardianDesignation' => new sfValidatorString(array('required' => false, 'max_length' => 20)),
            'guardianRelationship' => new sfValidatorString(array('required' => false, 'max_length' => 20)),
            'guardianHomeAddress' => new sfValidatorString(array('required' => false, 'max_length' => 255)),
            'guardianOfficeAddress' => new sfValidatorString(array('required' => false, 'max_length' => 255)),
            'guardianMobileNo' => new sfValidatorNumber(array('required' => false, 'max' => 9999999999, 'min' => 0)),
            'guardianOfficeNo' => new sfValidatorNumber(array('required' => false, 'max' => 9999999999, 'min' => 0)),
            'guardianResidentialNo' => new sfValidatorNumber(array('required' => false, 'max' => 9999999999, 'min' => 0)),
            'guardianEmail' => new sfValidatorEmail(array('required' => false, 'max_length' => 100, 'trim' => true)),

            'emergencyContactName' => new sfValidatorString(array('required' => true, 'max_length' => 100)),
            'emergencyContactRelationship' => new sfValidatorString(array('required' => true, 'max_length' => 20)),
            'emergencyContactAddress' => new sfValidatorString(array('required' => true, 'max_length' => 255)),
            'emergencyContactMobileNo' => new sfValidatorNumber(array('required' => true, 'max' => 9999999999, 'min' => 0)),
            'emergencyContactOfficeNo' => new sfValidatorNumber(array('required' => false, 'max' => 9999999999, 'min' => 0)),
            'emergencyContactResidentialNo' => new sfValidatorNumber(array('required' => true, 'max' => 9999999999, 'min' => 0))

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

    public function save() {

        $stuSurname = $this->getValue('stuSurname');
        $stuOtherNames = $this->getValue('stuOtherNames');
        $curClass = $this->getValue('curClass');
        $dateOfBirth = $this->getValue('dateOfBirth');
        $religion = $this->getValue('religion');
        $stuAdmissionNo= $this->getValue('stuAdmissionNo');
        $classOfAdmission = $this->getValue('classOfAdmission');
        $race = $this->getValue('race');
        $dateOfAdmission = $this->getValue('dateOfAdmission');
        $house = $this->getValue('house');
        $medium = $this->getValue('medium');
        $resAddress = $this->getValue('resAddress');
        $scholarFromOther= $this->getValue('scholarFromOther');
        $scholarFromRoyal = $this->getValue('scholarFromRoyal');
        $noScholar = $this->getValue('noScholar');

        $dadName = $this->getValue('dadName');
        $dadOccupation = $this->getValue('dadOccupation');
        $dadOtherOccupation = $this->getValue('dadOtherOccupation');
        $dadDesignation = $this->getValue('dadDesignation');
        $dadCompany = $this->getValue('dadCompany');
        $isFatherOldBoy = $this->getValue('isFatherOldBoy');
        $dadObMemId = $this->getValue('dadObMemId');
        $dadOfficeAddress = $this->getValue('dadOfficeAddress');
        $dadMobileNo= $this->getValue('dadMobileNo');
        $dadOfficeNo = $this->getValue('dadOfficeNo');
        $dadResidentialNo = $this->getValue('dadResidentialNo');
        $dadEmail= $this->getValue('dadEmail');

        $momName= $this->getValue('momName');
        $momOccupation = $this->getValue('momOccupation');
        $momOtherOccupation = $this->getValue('momOtherOccupation');
        $momDesignation = $this->getValue('momDesignation');
        $momCompany = $this->getValue('momCompany');
        $momAdmissionNumber = $this->getValue('momAdmissionNumber');
        $momOfficeAddress = $this->getValue('momOfficeAddress');
        $momMobileNo = $this->getValue('momMobileNo');
        $momOfficeNo= $this->getValue('momOfficeNo');
        $momResidentialNo= $this->getValue('momResidentialNo');
        $momEmail= $this->getValue('momEmail');

        $guardianName = $this->getValue('guardianName');
        $guardianDesignation = $this->getValue('guardianDesignation');
        $guardianRelationship = $this->getValue('guardianRelationship');
        $guardianHomeAddress = $this->getValue('guardianHomeAddress');
        $guardianOfficeAddress = $this->getValue('guardianOfficeAddress');
        $guardianMobileNo = $this->getValue('guardianMobileNo');
        $guardianOfficeNo = $this->getValue('guardianOfficeNo');
        $guardianResidentialNo = $this->getValue('guardianResidentialNo');
        $guardianEmail = $this->getValue('guardianEmail');

        $emergencyContactName = $this->getValue('emergencyContactName');
        $emergencyContactRelationship = $this->getValue('emergencyContactRelationship');
        $emergencyContactAddress = $this->getValue('emergencyContactAddress');
        $emergencyContactMobileNo = $this->getValue('emergencyContactMobileNo');
        $emergencyContactOfficeNo = $this->getValue('emergencyContactOfficeNo');
        $emergencyContactResidentialNo = $this->getValue('emergencyContactResidentialNo');

        $stuPrentRecord = new StudentParentInformation();
        $stuPrentRecord->setStuSurname($stuSurname);
        $stuPrentRecord->setStuOtherNames($stuOtherNames);
        $stuPrentRecord->setCurClass($curClass);
        $stuPrentRecord->setDateOfBirth($dateOfBirth);
        $stuPrentRecord->setReligion($religion);
        $stuPrentRecord->setStuAdmissionNo($stuAdmissionNo);
        $stuPrentRecord->setClassOfAdmission($classOfAdmission);
        $stuPrentRecord->setRace($race);
        $stuPrentRecord->setDateOfAdmission($dateOfAdmission);
        $stuPrentRecord->setHouse($house);
        $stuPrentRecord->setMedium($medium);
        $stuPrentRecord->setResAddress($resAddress);
        $stuPrentRecord->setScholarFromOther($scholarFromOther);
        $stuPrentRecord->setScholarFromRoyal($scholarFromRoyal);
        $stuPrentRecord->setNoScholar($noScholar);

        $stuPrentRecord->setDadName($dadName);
        $stuPrentRecord->setDadOccupation($dadOccupation);
        $stuPrentRecord->setDadOtherOccupation($dadOtherOccupation);
        $stuPrentRecord->setDadDesignation($dadDesignation);
        $stuPrentRecord->setDadCompany($dadCompany);
        $stuPrentRecord->setIsFatherOldBoy($isFatherOldBoy);
        $stuPrentRecord->setDadObMemId($dadObMemId);
        $stuPrentRecord->setDadOfficeAddress($dadOfficeAddress);
        $stuPrentRecord->setDadMobileNo($dadMobileNo);
        $stuPrentRecord->setDadOfficeNo($dadOfficeNo);
        $stuPrentRecord->setDadResidentialNo($dadResidentialNo);
        $stuPrentRecord->setDadEmail($dadEmail);

        $stuPrentRecord->setMomName($momName);
        $stuPrentRecord->setMomOccupation($momOccupation);
        $stuPrentRecord->setMomOtherOccupation($momOtherOccupation);
        $stuPrentRecord->setMomDesignation($momDesignation);
        $stuPrentRecord->setMomCompany($momCompany);
        $stuPrentRecord->setMomAdmissionNumber($momAdmissionNumber);
        $stuPrentRecord->setMomOfficeAddress($momOfficeAddress);
        $stuPrentRecord->setMomMobileNo($momMobileNo);
        $stuPrentRecord->setMomOfficeNo($momOfficeNo);
        $stuPrentRecord->setMomResidentialNo($momResidentialNo);
        $stuPrentRecord->setMomEmail($momEmail);

        $stuPrentRecord->setGuardianName($guardianName);
        $stuPrentRecord->setGuardianDesignation($guardianDesignation);
        $stuPrentRecord->setGuardianRelationship($guardianRelationship);
        $stuPrentRecord->setGuardianHomeAddress($guardianHomeAddress);
        $stuPrentRecord->setGuardianOfficeAddress($guardianOfficeAddress);
        $stuPrentRecord->setGuardianMobileNo($guardianMobileNo);
        $stuPrentRecord->setGuardianOfficeNo($guardianOfficeNo);
        $stuPrentRecord->setGuardianResidentialNo($guardianResidentialNo);
        $stuPrentRecord->setGuardianEmail($guardianEmail);

        $stuPrentRecord->setEmergencyContactName($emergencyContactName);
        $stuPrentRecord->setEmergencyContactRelationship($emergencyContactRelationship);
        $stuPrentRecord->setEmergencyContactAddress($emergencyContactAddress);
        $stuPrentRecord->setEmergencyContactMobileNo($emergencyContactMobileNo);
        $stuPrentRecord->setEmergencyContactOfficeNo($emergencyContactOfficeNo);
        $stuPrentRecord->setEmergencyContactResidentialNo($emergencyContactResidentialNo);

        $this->resultArray = array();
        $this->resultArray['messageType'] = 'success';
        $this->resultArray['message'] = __(TopLevelMessages::UPDATE_SUCCESS);

        $stuPrentRecord->save();

        return $this->resultArray;

    }
}