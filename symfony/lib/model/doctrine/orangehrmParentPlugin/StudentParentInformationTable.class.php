<?php

/**
 * StudentParentInformationTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class StudentParentInformationTable extends PluginStudentParentInformationTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object StudentParentInformationTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('StudentParentInformation');
    }
}