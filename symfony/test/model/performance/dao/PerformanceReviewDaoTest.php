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
 * Kpi Dao Test class 
 *
 * @author Samantha Jayasinghe
 */


class PerformanceReviewDaoTest extends PHPUnit_Framework_TestCase{
	
	private $testCases;
	private $performanceReviewDao ;
	
	/**
     * PHPUnit setup function
     */
    public function setup() {

       $configuration 		= ProjectConfiguration::getApplicationConfiguration('orangehrm', 'test', true);       
       $this->testCases 	= sfYaml::load(sfConfig::get('sf_test_dir') . '/fixtures/performance/performanceReview.yml');
       $this->performanceReviewDao	=	new PerformanceReviewDao();
	   
    }
    
	/**
     * Test Save Performance Review
     *
     */
    public function testSavePerformanceReview()
    {
    	
    	foreach ($this->testCases['PerformanceReview'] as $key=>$testCase) {
			$performanceReview	=	new PerformanceReview();
    		$performanceReview->setEmployeeId( $testCase['employeeId']);
    		$performanceReview->setReviewerId( $testCase['reviewerId']);
    		$performanceReview->setPeriodFrom( date('y-m-d',strtotime($testCase['periodFrom'])));
    		$performanceReview->setPeriodTo( date('y-m-d',strtotime($testCase['periodTo'])));
    		$performanceReview->setKpis( $testCase['kpis']);
    		
    		$performanceReview	=	$this->performanceReviewDao->savePerformanceReview( $performanceReview );
    		$result	=	($performanceReview instanceof PerformanceReview)?true:false;
			$this->assertTrue($result);
			
			$this->testCases['PerformanceReview'][$key]["id"] =  $performanceReview->getId();
    	}

    	file_put_contents(sfConfig::get('sf_test_dir') . '/fixtures/performance/performanceReview.yml',sfYaml::dump($this->testCases));
    }
    
	/**
	 * Test Read Performance Review
	 * @return unknown_type
	 */
	public function testReadKpi(){
		foreach ($this->testCases['PerformanceReview'] as $key=>$testCase) {
			$result	=	$this->performanceReviewDao->readPerformanceReview( $testCase['id']);
			$this->assertTrue($result instanceof PerformanceReview);
		}
		
	}
	
	/**
	 * Test Get default Kpi rating
	 */
	public function testGetPerformanceReviewList(  ){
		$performanceReviewList	=	$this->performanceReviewDao->getPerformanceReviewList( );
		foreach( $performanceReviewList as $result){
			$this->assertTrue($result instanceof PerformanceReview);
		}
		
	}

    /**
     * Tests SQL Escaping in searchPerformanceReview. This was added to validate fix for bug:
     *  3007215 - Ess view performance  review Vulnerable to SQL injection
     *
     */
    public function testSearchPerformanceReviewSqlEscaping() {

        $searchParams = array(array('from' => '2010-06-01', 'to' => '2010-06-17', 'due' => null, 'jobCode' => "'",
                                    'divisionId' => '0', 'empName' => null, 'empId' => 0, 'reviewerName' => null,
                                    'reviewerId' =>  0, 'pageNo' => null, 'loggedEmpId' => '1'),
                              array('from' => "'", 'to' => '2010-06-17', 'due' => null, 'jobCode' => "JOB001",
                                    'divisionId' => '0', 'empName' => null, 'empId' => 0, 'reviewerName' => null,
                                    'reviewerId' =>  0, 'pageNo' => null, 'loggedEmpId' => '1'),
                              array('from' => '2010-06-01', 'to' => "'", 'due' => null, 'jobCode' => "JOB001",
                                    'divisionId' => '0', 'empName' => null, 'empId' => 0, 'reviewerName' => null,
                                    'reviewerId' =>  0, 'pageNo' => null, 'loggedEmpId' => '1'),
                              array('from' => '2010-06-01', 'to' => "2010-06-17", 'due' => null, 'jobCode' => "JOB001",
                                    'divisionId' => "'", 'empName' => null, 'empId' => 0, 'reviewerName' => null,
                                    'reviewerId' =>  0, 'pageNo' => null, 'loggedEmpId' => '1'));

        // All queries should succeed without exceptions
        try {
            foreach($searchParams as $params) {
                $results = $this->performanceReviewDao->searchPerformanceReview($params);
            }
        } catch (Exception $e) {
            $this->fail("SQL parameters not properly escaped. Should not throw exception! ");
        }
    }


   /**
    * Test deletePerformanceReview
    */
   function testDeletePerformanceReview() {
      foreach ($this->testCases['PerformanceReview'] as $key=>$testCase) {
         $result = $this->performanceReviewDao->deletePerformanceReview($testCase['id']);
         $this->assertTrue($result);
         unset($this->testCases['PerformanceReview'][$key]["id"]);
      }
      file_put_contents(sfConfig::get('sf_test_dir') . '/fixtures/performance/performanceReview.yml',sfYaml::dump($this->testCases));
   }


}