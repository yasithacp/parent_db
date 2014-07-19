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

require_once sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

/**
 * @group Pim
 */
class EmployeeListDaoTest extends PHPUnit_Framework_TestCase {
    private $testCase;
    private $employeeDao;
    protected $fixture;

    /**
     * Set up method
     */
    protected function setUp() {
        $this->employeeDao = new EmployeeDao();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmPimPlugin/test/fixtures/EmployeeDao.yml';
        TestDataService::populate($this->fixture);
    }
    
   
    public function testSearchEmployeeList() {
        $result = $this->employeeDao->searchEmployeeList();
        
         $this->assertTrue( $result instanceof Doctrine_Collection);
    }
    
    public function testGetEmployeeCount(){
         $result = $this->employeeDao->getSearchEmployeeCount();
         $this->assertEquals(5,$result);
    }
    
    public function testSearchEmployeeListByFirstName(){
      
         $filters = array ( 
                        'employee_name' => 'Kayla',
                        'id'=>'',
                        'employee_status' => 0,
                        'termination' => 1,
                        'supervisor_name' => '',
                        'job_title' => 0,
                        'sub_unit' => 0
                        );
        
        
         $result = $this->employeeDao->searchEmployeeList('empNumber','asc',$filters);
         $this->assertEquals( $result[0]->getFirstName(),'Kayla');
         $this->assertEquals(1,count($result));
    }
    

}
?>
