<?php

/**
 * BaseStudentParentInformation
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property int $studentId
 * @property varchar $curClass
 * @property varchar $stuSurname
 * @property varchar $stuOtherNames
 * @property date $dateOfBirth
 * @property varchar $religion
 * @property varchar $stuAdmissionNo
 * @property varchar $classOfAdmission
 * @property varchar $race
 * @property date $dateOfAdmission
 * @property varchar $house
 * @property varchar $medium
 * @property varchar $resAddress
 * @property int $scholarFromOther
 * @property int $scholarFromRoyal
 * @property int $noScholar
 * @property varchar $dadName
 * @property varchar $dadOccupation
 * @property varchar $dadOtherOccupation
 * @property varchar $dadDesignation
 * @property varchar $dadCompany
 * @property int $isFatherOldBoy
 * @property int $dadObMemId
 * @property varchar $dadOfficeAddress
 * @property varchar $dadOfficeCity
 * @property int $dadMobileNo
 * @property int $dadOfficeNo
 * @property int $dadResidentialNo
 * @property varchar $dadEmail
 * @property varchar $momName
 * @property varchar $momOccupation
 * @property varchar $momOtherOccupation
 * @property varchar $momDesignation
 * @property varchar $momCompany
 * @property varchar $momAdmissionNumber
 * @property varchar $momOfficeAddress
 * @property varchar $momOfficeCity
 * @property int $momMobileNo
 * @property int $momOfficeNo
 * @property int $momResidentialNo
 * @property varchar $momEmail
 * @property varchar $guardianName
 * @property varchar $guardianDesignation
 * @property varchar $guardianRelationship
 * @property varchar $guardianHomeAddress
 * @property varchar $guardianOfficeAddress
 * @property int $guardianMobileNo
 * @property int $guardianOfficeNo
 * @property int $guardianResidentialNo
 * @property varchar $guardianEmail
 * @property varchar $emergencyContactName
 * @property varchar $emergencyContactRelationship
 * @property varchar $emergencyContactAddress
 * @property int $emergencyContactMobileNo
 * @property int $emergencyContactOfficeNo
 * @property int $emergencyContactResidentialNo
 * 
 * @method int                      getStudentId()                     Returns the current record's "studentId" value
 * @method varchar                  getCurClass()                      Returns the current record's "curClass" value
 * @method varchar                  getStuSurname()                    Returns the current record's "stuSurname" value
 * @method varchar                  getStuOtherNames()                 Returns the current record's "stuOtherNames" value
 * @method date                     getDateOfBirth()                   Returns the current record's "dateOfBirth" value
 * @method varchar                  getReligion()                      Returns the current record's "religion" value
 * @method varchar                  getStuAdmissionNo()                Returns the current record's "stuAdmissionNo" value
 * @method varchar                  getClassOfAdmission()              Returns the current record's "classOfAdmission" value
 * @method varchar                  getRace()                          Returns the current record's "race" value
 * @method date                     getDateOfAdmission()               Returns the current record's "dateOfAdmission" value
 * @method varchar                  getHouse()                         Returns the current record's "house" value
 * @method varchar                  getMedium()                        Returns the current record's "medium" value
 * @method varchar                  getResAddress()                    Returns the current record's "resAddress" value
 * @method int                      getScholarFromOther()              Returns the current record's "scholarFromOther" value
 * @method int                      getScholarFromRoyal()              Returns the current record's "scholarFromRoyal" value
 * @method int                      getNoScholar()                     Returns the current record's "noScholar" value
 * @method varchar                  getDadName()                       Returns the current record's "dadName" value
 * @method varchar                  getDadOccupation()                 Returns the current record's "dadOccupation" value
 * @method varchar                  getDadOtherOccupation()            Returns the current record's "dadOtherOccupation" value
 * @method varchar                  getDadDesignation()                Returns the current record's "dadDesignation" value
 * @method varchar                  getDadCompany()                    Returns the current record's "dadCompany" value
 * @method int                      getIsFatherOldBoy()                Returns the current record's "isFatherOldBoy" value
 * @method int                      getDadObMemId()                    Returns the current record's "dadObMemId" value
 * @method varchar                  getDadOfficeAddress()              Returns the current record's "dadOfficeAddress" value
 * @method varchar                  getDadOfficeCity()                 Returns the current record's "dadOfficeCity" value
 * @method int                      getDadMobileNo()                   Returns the current record's "dadMobileNo" value
 * @method int                      getDadOfficeNo()                   Returns the current record's "dadOfficeNo" value
 * @method int                      getDadResidentialNo()              Returns the current record's "dadResidentialNo" value
 * @method varchar                  getDadEmail()                      Returns the current record's "dadEmail" value
 * @method varchar                  getMomName()                       Returns the current record's "momName" value
 * @method varchar                  getMomOccupation()                 Returns the current record's "momOccupation" value
 * @method varchar                  getMomOtherOccupation()            Returns the current record's "momOtherOccupation" value
 * @method varchar                  getMomDesignation()                Returns the current record's "momDesignation" value
 * @method varchar                  getMomCompany()                    Returns the current record's "momCompany" value
 * @method varchar                  getMomAdmissionNumber()            Returns the current record's "momAdmissionNumber" value
 * @method varchar                  getMomOfficeAddress()              Returns the current record's "momOfficeAddress" value
 * @method varchar                  getMomOfficeCity()                 Returns the current record's "momOfficeCity" value
 * @method int                      getMomMobileNo()                   Returns the current record's "momMobileNo" value
 * @method int                      getMomOfficeNo()                   Returns the current record's "momOfficeNo" value
 * @method int                      getMomResidentialNo()              Returns the current record's "momResidentialNo" value
 * @method varchar                  getMomEmail()                      Returns the current record's "momEmail" value
 * @method varchar                  getGuardianName()                  Returns the current record's "guardianName" value
 * @method varchar                  getGuardianDesignation()           Returns the current record's "guardianDesignation" value
 * @method varchar                  getGuardianRelationship()          Returns the current record's "guardianRelationship" value
 * @method varchar                  getGuardianHomeAddress()           Returns the current record's "guardianHomeAddress" value
 * @method varchar                  getGuardianOfficeAddress()         Returns the current record's "guardianOfficeAddress" value
 * @method int                      getGuardianMobileNo()              Returns the current record's "guardianMobileNo" value
 * @method int                      getGuardianOfficeNo()              Returns the current record's "guardianOfficeNo" value
 * @method int                      getGuardianResidentialNo()         Returns the current record's "guardianResidentialNo" value
 * @method varchar                  getGuardianEmail()                 Returns the current record's "guardianEmail" value
 * @method varchar                  getEmergencyContactName()          Returns the current record's "emergencyContactName" value
 * @method varchar                  getEmergencyContactRelationship()  Returns the current record's "emergencyContactRelationship" value
 * @method varchar                  getEmergencyContactAddress()       Returns the current record's "emergencyContactAddress" value
 * @method int                      getEmergencyContactMobileNo()      Returns the current record's "emergencyContactMobileNo" value
 * @method int                      getEmergencyContactOfficeNo()      Returns the current record's "emergencyContactOfficeNo" value
 * @method int                      getEmergencyContactResidentialNo() Returns the current record's "emergencyContactResidentialNo" value
 * @method StudentParentInformation setStudentId()                     Sets the current record's "studentId" value
 * @method StudentParentInformation setCurClass()                      Sets the current record's "curClass" value
 * @method StudentParentInformation setStuSurname()                    Sets the current record's "stuSurname" value
 * @method StudentParentInformation setStuOtherNames()                 Sets the current record's "stuOtherNames" value
 * @method StudentParentInformation setDateOfBirth()                   Sets the current record's "dateOfBirth" value
 * @method StudentParentInformation setReligion()                      Sets the current record's "religion" value
 * @method StudentParentInformation setStuAdmissionNo()                Sets the current record's "stuAdmissionNo" value
 * @method StudentParentInformation setClassOfAdmission()              Sets the current record's "classOfAdmission" value
 * @method StudentParentInformation setRace()                          Sets the current record's "race" value
 * @method StudentParentInformation setDateOfAdmission()               Sets the current record's "dateOfAdmission" value
 * @method StudentParentInformation setHouse()                         Sets the current record's "house" value
 * @method StudentParentInformation setMedium()                        Sets the current record's "medium" value
 * @method StudentParentInformation setResAddress()                    Sets the current record's "resAddress" value
 * @method StudentParentInformation setScholarFromOther()              Sets the current record's "scholarFromOther" value
 * @method StudentParentInformation setScholarFromRoyal()              Sets the current record's "scholarFromRoyal" value
 * @method StudentParentInformation setNoScholar()                     Sets the current record's "noScholar" value
 * @method StudentParentInformation setDadName()                       Sets the current record's "dadName" value
 * @method StudentParentInformation setDadOccupation()                 Sets the current record's "dadOccupation" value
 * @method StudentParentInformation setDadOtherOccupation()            Sets the current record's "dadOtherOccupation" value
 * @method StudentParentInformation setDadDesignation()                Sets the current record's "dadDesignation" value
 * @method StudentParentInformation setDadCompany()                    Sets the current record's "dadCompany" value
 * @method StudentParentInformation setIsFatherOldBoy()                Sets the current record's "isFatherOldBoy" value
 * @method StudentParentInformation setDadObMemId()                    Sets the current record's "dadObMemId" value
 * @method StudentParentInformation setDadOfficeAddress()              Sets the current record's "dadOfficeAddress" value
 * @method StudentParentInformation setDadOfficeCity()                 Sets the current record's "dadOfficeCity" value
 * @method StudentParentInformation setDadMobileNo()                   Sets the current record's "dadMobileNo" value
 * @method StudentParentInformation setDadOfficeNo()                   Sets the current record's "dadOfficeNo" value
 * @method StudentParentInformation setDadResidentialNo()              Sets the current record's "dadResidentialNo" value
 * @method StudentParentInformation setDadEmail()                      Sets the current record's "dadEmail" value
 * @method StudentParentInformation setMomName()                       Sets the current record's "momName" value
 * @method StudentParentInformation setMomOccupation()                 Sets the current record's "momOccupation" value
 * @method StudentParentInformation setMomOtherOccupation()            Sets the current record's "momOtherOccupation" value
 * @method StudentParentInformation setMomDesignation()                Sets the current record's "momDesignation" value
 * @method StudentParentInformation setMomCompany()                    Sets the current record's "momCompany" value
 * @method StudentParentInformation setMomAdmissionNumber()            Sets the current record's "momAdmissionNumber" value
 * @method StudentParentInformation setMomOfficeAddress()              Sets the current record's "momOfficeAddress" value
 * @method StudentParentInformation setMomOfficeCity()                 Sets the current record's "momOfficeCity" value
 * @method StudentParentInformation setMomMobileNo()                   Sets the current record's "momMobileNo" value
 * @method StudentParentInformation setMomOfficeNo()                   Sets the current record's "momOfficeNo" value
 * @method StudentParentInformation setMomResidentialNo()              Sets the current record's "momResidentialNo" value
 * @method StudentParentInformation setMomEmail()                      Sets the current record's "momEmail" value
 * @method StudentParentInformation setGuardianName()                  Sets the current record's "guardianName" value
 * @method StudentParentInformation setGuardianDesignation()           Sets the current record's "guardianDesignation" value
 * @method StudentParentInformation setGuardianRelationship()          Sets the current record's "guardianRelationship" value
 * @method StudentParentInformation setGuardianHomeAddress()           Sets the current record's "guardianHomeAddress" value
 * @method StudentParentInformation setGuardianOfficeAddress()         Sets the current record's "guardianOfficeAddress" value
 * @method StudentParentInformation setGuardianMobileNo()              Sets the current record's "guardianMobileNo" value
 * @method StudentParentInformation setGuardianOfficeNo()              Sets the current record's "guardianOfficeNo" value
 * @method StudentParentInformation setGuardianResidentialNo()         Sets the current record's "guardianResidentialNo" value
 * @method StudentParentInformation setGuardianEmail()                 Sets the current record's "guardianEmail" value
 * @method StudentParentInformation setEmergencyContactName()          Sets the current record's "emergencyContactName" value
 * @method StudentParentInformation setEmergencyContactRelationship()  Sets the current record's "emergencyContactRelationship" value
 * @method StudentParentInformation setEmergencyContactAddress()       Sets the current record's "emergencyContactAddress" value
 * @method StudentParentInformation setEmergencyContactMobileNo()      Sets the current record's "emergencyContactMobileNo" value
 * @method StudentParentInformation setEmergencyContactOfficeNo()      Sets the current record's "emergencyContactOfficeNo" value
 * @method StudentParentInformation setEmergencyContactResidentialNo() Sets the current record's "emergencyContactResidentialNo" value
 * 
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseStudentParentInformation extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('rc_student_parent_information');
        $this->hasColumn('student_id as studentId', 'int', 2000000, array(
             'type' => 'int',
             'autoincrement' => true,
             'primary' => true,
             'length' => 2000000,
             ));
        $this->hasColumn('current_class as curClass', 'varchar', 10, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 10,
             ));
        $this->hasColumn('student_surname as stuSurname', 'varchar', 35, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 35,
             ));
        $this->hasColumn('student_other_names as stuOtherNames', 'varchar', 50, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 50,
             ));
        $this->hasColumn('date_of_birth as dateOfBirth', 'date', null, array(
             'type' => 'date',
             'notnull' => true,
             ));
        $this->hasColumn('religion', 'varchar', 20, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 20,
             ));
        $this->hasColumn('student_admission_no as stuAdmissionNo', 'varchar', 10, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 10,
             ));
        $this->hasColumn('class_of_admission as classOfAdmission', 'varchar', 10, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 10,
             ));
        $this->hasColumn('race', 'varchar', 20, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 20,
             ));
        $this->hasColumn('date_of_admission as dateOfAdmission', 'date', null, array(
             'type' => 'date',
             ));
        $this->hasColumn('house', 'varchar', 20, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 20,
             ));
        $this->hasColumn('medium', 'varchar', 10, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 10,
             ));
        $this->hasColumn('residential_address as resAddress', 'varchar', 255, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('scholar_from_other as scholarFromOther', 'int', 1, array(
             'type' => 'int',
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('scholar_from_royal as scholarFromRoyal', 'int', 1, array(
             'type' => 'int',
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('no_scholar as noScholar', 'int', 1, array(
             'type' => 'int',
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('father_name as dadName', 'varchar', 100, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 100,
             ));
        $this->hasColumn('father_occupation as dadOccupation', 'varchar', 50, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 50,
             ));
        $this->hasColumn('father_other_occupation as dadOtherOccupation', 'varchar', 50, array(
             'type' => 'varchar',
             'length' => 50,
             ));
        $this->hasColumn('father_designation as dadDesignation', 'varchar', 50, array(
             'type' => 'varchar',
             'length' => 50,
             ));
        $this->hasColumn('father_company as dadCompany', 'varchar', 50, array(
             'type' => 'varchar',
             'length' => 50,
             ));
        $this->hasColumn('is_father_old_boy as isFatherOldBoy', 'int', 1, array(
             'type' => 'int',
             'default' => 0,
             'length' => 1,
             ));
        $this->hasColumn('father_ob_membership_id as dadObMemId', 'int', 50, array(
             'type' => 'int',
             'length' => 50,
             ));
        $this->hasColumn('father_office_address as dadOfficeAddress', 'varchar', 255, array(
             'type' => 'varchar',
             'length' => 255,
             ));
        $this->hasColumn('father_office_city as dadOfficeCity', 'varchar', 100, array(
             'type' => 'varchar',
             'length' => 100,
             ));
        $this->hasColumn('father_contact_number_mobile as dadMobileNo', 'int', 10, array(
             'type' => 'int',
             'length' => 10,
             ));
        $this->hasColumn('father_contact_number_office as dadOfficeNo', 'int', 10, array(
             'type' => 'int',
             'length' => 10,
             ));
        $this->hasColumn('father_contact_number_residential as dadResidentialNo', 'int', 10, array(
             'type' => 'int',
             'length' => 10,
             ));
        $this->hasColumn('father_email as dadEmail', 'varchar', 100, array(
             'type' => 'varchar',
             'length' => 100,
             ));
        $this->hasColumn('mother_name as momName', 'varchar', 100, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 100,
             ));
        $this->hasColumn('mother_occupation as momOccupation', 'varchar', 50, array(
             'type' => 'varchar',
             'length' => 50,
             ));
        $this->hasColumn('mother_other_occupation as momOtherOccupation', 'varchar', 50, array(
             'type' => 'varchar',
             'length' => 50,
             ));
        $this->hasColumn('mother_designation as momDesignation', 'varchar', 50, array(
             'type' => 'varchar',
             'length' => 50,
             ));
        $this->hasColumn('mother_company as momCompany', 'varchar', 20, array(
             'type' => 'varchar',
             'length' => 20,
             ));
        $this->hasColumn('mother_admission_number as momAdmissionNumber', 'varchar', 20, array(
             'type' => 'varchar',
             'length' => 20,
             ));
        $this->hasColumn('mother_address_office as momOfficeAddress', 'varchar', 255, array(
             'type' => 'varchar',
             'length' => 255,
             ));
        $this->hasColumn('mother_office_city as momOfficeCity', 'varchar', 100, array(
             'type' => 'varchar',
             'length' => 100,
             ));
        $this->hasColumn('mother_contact_mobile as momMobileNo', 'int', 10, array(
             'type' => 'int',
             'length' => 10,
             ));
        $this->hasColumn('mother_contact_office as momOfficeNo', 'int', 10, array(
             'type' => 'int',
             'length' => 10,
             ));
        $this->hasColumn('mother_contact_residential as momResidentialNo', 'int', 10, array(
             'type' => 'int',
             'length' => 10,
             ));
        $this->hasColumn('mother_email as momEmail', 'varchar', 100, array(
             'type' => 'varchar',
             'length' => 100,
             ));
        $this->hasColumn('guardian_name as guardianName', 'varchar', 100, array(
             'type' => 'varchar',
             'length' => 100,
             ));
        $this->hasColumn('guardian_designation as guardianDesignation', 'varchar', 20, array(
             'type' => 'varchar',
             'length' => 20,
             ));
        $this->hasColumn('guardian_relationship_to_student as guardianRelationship', 'varchar', 20, array(
             'type' => 'varchar',
             'length' => 20,
             ));
        $this->hasColumn('guardian_address as guardianHomeAddress', 'varchar', 255, array(
             'type' => 'varchar',
             'length' => 255,
             ));
        $this->hasColumn('guardian_office_address as guardianOfficeAddress', 'varchar', 255, array(
             'type' => 'varchar',
             'length' => 255,
             ));
        $this->hasColumn('guardian_contact_mobile as guardianMobileNo', 'int', 10, array(
             'type' => 'int',
             'length' => 10,
             ));
        $this->hasColumn('guardian_contact_office as guardianOfficeNo', 'int', 10, array(
             'type' => 'int',
             'length' => 10,
             ));
        $this->hasColumn('guardian_contact_residential as guardianResidentialNo', 'int', 10, array(
             'type' => 'int',
             'length' => 10,
             ));
        $this->hasColumn('guardian_email as guardianEmail', 'varchar', 100, array(
             'type' => 'varchar',
             'length' => 100,
             ));
        $this->hasColumn('emergency_name as emergencyContactName', 'varchar', 100, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 100,
             ));
        $this->hasColumn('emergency_relationship_to_student as emergencyContactRelationship', 'varchar', 20, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 20,
             ));
        $this->hasColumn('emergency_address as emergencyContactAddress', 'varchar', 255, array(
             'type' => 'varchar',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('emergency_contact_mobile as emergencyContactMobileNo', 'int', 10, array(
             'type' => 'int',
             'notnull' => true,
             'length' => 10,
             ));
        $this->hasColumn('emergency_contact_office as emergencyContactOfficeNo', 'int', 10, array(
             'type' => 'int',
             'length' => 10,
             ));
        $this->hasColumn('emergency_contact_residential as emergencyContactResidentialNo', 'int', 10, array(
             'type' => 'int',
             'notnull' => true,
             'length' => 10,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}