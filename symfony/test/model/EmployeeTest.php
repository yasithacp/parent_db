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
 * Unit test for Employee class
 */
class EmployeeTest extends PHPUnit_Framework_TestCase {

    /** 
     * Test data
     * Loaded from employee_fixture.yml.
     */
    protected $testCases;
    
    /**
     * Employee
     *
     * @var Employee
     */
    protected $employee;
        
    /**
     * PhpUnit Setup function. 
     */
    public function setup() {
        $configuration = ProjectConfiguration::getApplicationConfiguration('orangehrm', 'test', true);       
        $this->testCases = sfYaml::load(sfConfig::get('sf_test_dir') . '/fixtures/employee_test.yml');

        $this->employee = new Employee();
    }
    
    /**
     * Test the getFullName() function
     */
    public function testGetFullName() {
       
       foreach ($this->testCases as $testCase) {
           $this->employee->fromArray($testCase);
           $this->assertEquals($testCase['fullName'], $this->employee->getFullName());    
       }       
       
    }    

    /**
     * Test the GetSupervisorNames function
     */
    public function testGetSupervisorNames() {
        
        // Employee with no supervisors
        $this->assertEquals('', $this->employee->getSupervisorNames());

        // Employee with one supervisor     
        $names = array($this->testCases[0]);
        $repTo = $this->_setSupervisors($names);        
        
        $expected = $this->_getFirstAndLastName($this->testCases[0]);
        $this->assertEquals($expected, $this->employee->getSupervisorNames());
        
        // Employee with 3 supervisors
        
        // add two more supervisors
        $names = array_slice($this->testCases, 1, 2);
        $this->_setSupervisors($names);        
        
        $expected = $this->_getFirstAndLastName($this->testCases[0]) . ', '
                    . $this->_getFirstAndLastName($this->testCases[1]) . ', '
                    . $this->_getFirstAndLastName($this->testCases[2]);
         
        $this->assertEquals($expected, $this->employee->getSupervisorNames());                
    }

    /**
     * Returns a Doctrine_Collection of supervisor names suitable for assigning
     * to an employee.
     * @param array $supervisorNames 
     * @return Doctrine_Collection of Employee objects 
     */
    private function _setSupervisors(array $supervisors) {

        foreach ($supervisors as $supervisor){
            $employee = new Employee();
            $employee->fromArray($supervisor);

            $this->employee->supervisors[] = $employee;
        }
    }
    
    private function _getFirstAndLastName($row) {
        return trim($row['firstName'] . ' ' . $row['lastName']);
    }


}