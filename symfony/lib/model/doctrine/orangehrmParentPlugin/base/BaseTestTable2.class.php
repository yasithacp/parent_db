<?php

/**
 * BaseTestTable2
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property int $studentId
 * @property varchar $momEmail
 * @property varchar $dadEmail
 * 
 * @method int        getStudentId() Returns the current record's "studentId" value
 * @method varchar    getMomEmail()  Returns the current record's "momEmail" value
 * @method varchar    getDadEmail()  Returns the current record's "dadEmail" value
 * @method TestTable2 setStudentId() Sets the current record's "studentId" value
 * @method TestTable2 setMomEmail()  Sets the current record's "momEmail" value
 * @method TestTable2 setDadEmail()  Sets the current record's "dadEmail" value
 * 
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseTestTable2 extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('test_table2');
        $this->hasColumn('student_id as studentId', 'int', 2000000, array(
             'type' => 'int',
             'autoincrement' => true,
             'primary' => true,
             'length' => 2000000,
             ));
        $this->hasColumn('mother_email as momEmail', 'varchar', 100, array(
             'type' => 'varchar',
             'length' => 100,
             ));
        $this->hasColumn('father_email as dadEmail', 'varchar', 100, array(
             'type' => 'varchar',
             'length' => 100,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}