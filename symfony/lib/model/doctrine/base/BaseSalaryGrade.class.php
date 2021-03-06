<?php

/**
 * BaseSalaryGrade
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $sal_grd_code
 * @property string $sal_grd_name
 * @property Doctrine_Collection $EmpBasicsalary
 * @property Doctrine_Collection $JobTitle
 * @property Doctrine_Collection $PayGradeCurrency
 * 
 * @method string              getSalGrdCode()       Returns the current record's "sal_grd_code" value
 * @method string              getSalGrdName()       Returns the current record's "sal_grd_name" value
 * @method Doctrine_Collection getEmpBasicsalary()   Returns the current record's "EmpBasicsalary" collection
 * @method Doctrine_Collection getJobTitle()         Returns the current record's "JobTitle" collection
 * @method Doctrine_Collection getPayGradeCurrency() Returns the current record's "PayGradeCurrency" collection
 * @method SalaryGrade         setSalGrdCode()       Sets the current record's "sal_grd_code" value
 * @method SalaryGrade         setSalGrdName()       Sets the current record's "sal_grd_name" value
 * @method SalaryGrade         setEmpBasicsalary()   Sets the current record's "EmpBasicsalary" collection
 * @method SalaryGrade         setJobTitle()         Sets the current record's "JobTitle" collection
 * @method SalaryGrade         setPayGradeCurrency() Sets the current record's "PayGradeCurrency" collection
 * 
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSalaryGrade extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_pay_grade');
        $this->hasColumn('sal_grd_code', 'string', 13, array(
             'type' => 'string',
             'primary' => true,
             'length' => 13,
             ));
        $this->hasColumn('sal_grd_name', 'string', 60, array(
             'type' => 'string',
             'length' => 60,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('EmpBasicsalary', array(
             'local' => 'sal_grd_code',
             'foreign' => 'sal_grd_code'));

        $this->hasMany('JobTitle', array(
             'local' => 'sal_grd_code',
             'foreign' => 'sal_grd_code'));

        $this->hasMany('PayGradeCurrency', array(
             'local' => 'sal_grd_code',
             'foreign' => 'sal_grd_code'));
    }
}