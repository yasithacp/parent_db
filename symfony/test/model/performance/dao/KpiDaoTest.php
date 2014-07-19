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


class KpiDaoTest extends PHPUnit_Framework_TestCase{
	
	private $testCases;
	private $kpiDao ;
	
	/**
     * PHPUnit setup function
     */
    public function setup() {

       $configuration 		= ProjectConfiguration::getApplicationConfiguration('orangehrm', 'test', true);       
       $this->testCases 	= sfYaml::load(sfConfig::get('sf_test_dir') . '/fixtures/performance/kpi.yml');
       $this->kpiDao		=	new KpiDao();
	   
    }
    
	/**
     * Test Save Kpi function
     *
     */
    public function testSaveKpi(){
    	
    	foreach ($this->testCases['Kpi'] as $key=>$testCase) {
			$kpi	=	new DefineKpi();
			$kpi->setJobtitlecode ($this->testCases['JobTitle']['jobtitlecode']);
			$kpi->setDesc ( $testCase['desc']);
			$kpi->setMin ( $testCase['min'] );	
			$kpi->setMax ( $testCase['max'] );
			$kpi->setDefault ( $testCase['default'] );
			$kpi->setIsactive ( $testCase['isactive'] );
			
			$kpi 	= $this->kpiDao->saveKpi( $kpi );
			$result	=	($kpi instanceof DefineKpi)?true:false;
			$this->assertTrue($result);
			
			$this->testCases['Kpi'][$key]["id"] =  $kpi->getId();
    	}

    	file_put_contents(sfConfig::get('sf_test_dir') . '/fixtures/performance/kpi.yml',sfYaml::dump($this->testCases));
    }

	/**
     * Verify fix for bug: 3006775.
     * Tests that any doctrine validator exceptions are thrown so that the action classes can handle them,
     * instead of catching them and throwing generic dao exceptions.
     *
     */
    public function testSaveKpiValidation(){

    	foreach ($this->testCases['Kpi'] as $key=>$testCase) {
			$kpi = new DefineKpi();
			$kpi->setJobtitlecode("JOB001 '+%7C%7C+'ACUtwoACU'");
			$kpi->setDesc($testCase['desc']);
			$kpi->setMin($testCase['min']);
			$kpi->setMax($testCase['max']);
			$kpi->setDefault($testCase['default']);
			$kpi->setIsactive($testCase['isactive']);

            // This save should fail because job title code is too long for the job title field.
            // Should throw a Doctrine_Validator_Exception

            try {
			    $kpi = $this->kpiDao->saveKpi($kpi);
                $this->fail("Validation exception expected.");
            } catch (Doctrine_Validator_Exception $e) {
                // expected
            } catch (Exception $e) {
                // Should not throw other exception
                $this->fail("Validation exception expected. Should not throw other exception");
            }
    	}
    }

	/**
	 * Test Read Kpi
	 * @return unknown_type
	 */
	public function testReadKpi(){
		foreach ($this->testCases['Kpi'] as $key=>$testCase) {
			$result	=	$this->kpiDao->readKpi( $testCase['id']);
			$this->assertTrue($result instanceof DefineKpi);
		}
		
	}
	
	/**
	 * Test Get default Kpi rating
	 */
	public function testGetKpiDefaultRate(  ){
		$result	=	$this->kpiDao->getKpiDefaultRate( );
		$this->assertTrue($result instanceof DefineKpi);
		
	}
	
	/**
	 * Test over ride default kpi 
	 */
	public function testOverRideKpiDefaultRate(  ){
		foreach ($this->testCases['Kpi'] as $key=>$testCase) {
			$kpi	=	$this->kpiDao->readKpi( $testCase['id']);
			$result	=	$this->kpiDao->overRideKpiDefaultRate($kpi);
			$this->assertTrue($result);
		}
		
		
	}
	
	/**
	 * Test Get default Kpi rating
	 */
	public function testGetKpiForJobTitle(  ){
		$kpiList	=	$this->kpiDao->getKpiForJobTitle( $this->testCases['JobTitle']['jobtitlecode']);
		foreach( $kpiList as $result){
			$this->assertTrue($result instanceof DefineKpi);
		}
	}
	
	/**
	 * Test delete kpi for job title
	 */
	public function testDeleteKpiForJobTitle(  ){
		$result	=	$this->kpiDao->deleteKpiForJobTitle( $this->testCases['JobTitle']['jobtitlecode']);
		$this->assertTrue($result);
	}
	
	/**
	 * Test delete Kpi
	 */
	public function testDeleteKpi(  ){
		$deleteList	=	array();
		foreach ($this->testCases['Kpi'] as $key=>$testCase) {
			array_push($deleteList,$testCase['id']);
			unset($this->testCases['Kpi'][$key]["id"]);
		}
		$result = $this->kpiDao->deleteKpi( $deleteList );
		$this->assertTrue($result);
		
		file_put_contents(sfConfig::get('sf_test_dir') . '/fixtures/performance/kpi.yml',sfYaml::dump($this->testCases));
	}
    
}