<?php

/**
 * BaseEmpContract
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $emp_number
 * @property decimal $contract_id
 * @property timestamp $start_date
 * @property timestamp $end_date
 * @property Employee $Employee
 * 
 * @method integer     getEmpNumber()   Returns the current record's "emp_number" value
 * @method decimal     getContractId()  Returns the current record's "contract_id" value
 * @method timestamp   getStartDate()   Returns the current record's "start_date" value
 * @method timestamp   getEndDate()     Returns the current record's "end_date" value
 * @method Employee    getEmployee()    Returns the current record's "Employee" value
 * @method EmpContract setEmpNumber()   Sets the current record's "emp_number" value
 * @method EmpContract setContractId()  Sets the current record's "contract_id" value
 * @method EmpContract setStartDate()   Sets the current record's "start_date" value
 * @method EmpContract setEndDate()     Sets the current record's "end_date" value
 * @method EmpContract setEmployee()    Sets the current record's "Employee" value
 * 
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseEmpContract extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('hs_hr_emp_contract_extend');
        $this->hasColumn('emp_number', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'length' => 4,
             ));
        $this->hasColumn('econ_extend_id as contract_id', 'decimal', 10, array(
             'type' => 'decimal',
             'primary' => true,
             'length' => 10,
             ));
        $this->hasColumn('econ_extend_start_date as start_date', 'timestamp', 25, array(
             'type' => 'timestamp',
             'length' => 25,
             ));
        $this->hasColumn('econ_extend_end_date as end_date', 'timestamp', 25, array(
             'type' => 'timestamp',
             'length' => 25,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Employee', array(
             'local' => 'emp_number',
             'foreign' => 'emp_number'));
    }
}