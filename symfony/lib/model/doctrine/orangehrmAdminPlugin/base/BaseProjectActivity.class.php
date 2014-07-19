<?php

/**
 * BaseProjectActivity
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $activityId
 * @property integer $projectId
 * @property integer $is_deleted
 * @property string $name
 * @property Project $Project
 * @property Doctrine_Collection $TimesheetItem
 * 
 * @method integer             getActivityId()    Returns the current record's "activityId" value
 * @method integer             getProjectId()     Returns the current record's "projectId" value
 * @method integer             getIsDeleted()     Returns the current record's "is_deleted" value
 * @method string              getName()          Returns the current record's "name" value
 * @method Project             getProject()       Returns the current record's "Project" value
 * @method Doctrine_Collection getTimesheetItem() Returns the current record's "TimesheetItem" collection
 * @method ProjectActivity     setActivityId()    Sets the current record's "activityId" value
 * @method ProjectActivity     setProjectId()     Sets the current record's "projectId" value
 * @method ProjectActivity     setIsDeleted()     Sets the current record's "is_deleted" value
 * @method ProjectActivity     setName()          Sets the current record's "name" value
 * @method ProjectActivity     setProject()       Sets the current record's "Project" value
 * @method ProjectActivity     setTimesheetItem() Sets the current record's "TimesheetItem" collection
 * 
 * @package    orangehrm
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseProjectActivity extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('ohrm_project_activity');
        $this->hasColumn('activity_id as activityId', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'autoincrement' => true,
             'length' => 4,
             ));
        $this->hasColumn('project_id as projectId', 'integer', 4, array(
             'type' => 'integer',
             'notnull' => true,
             'length' => 4,
             ));
        $this->hasColumn('is_deleted', 'integer', 1, array(
             'type' => 'integer',
             'default' => '0',
             'length' => 1,
             ));
        $this->hasColumn('name', 'string', 110, array(
             'type' => 'string',
             'length' => 110,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Project', array(
             'local' => 'project_id',
             'foreign' => 'project_id'));

        $this->hasMany('TimesheetItem', array(
             'local' => 'activity_id',
             'foreign' => 'activityId'));
    }
}