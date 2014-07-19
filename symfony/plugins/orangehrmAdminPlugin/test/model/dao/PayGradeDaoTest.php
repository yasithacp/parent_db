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
 *  @group Admin
 */
class PayGradeDaoTest extends PHPUnit_Framework_TestCase {
	
	private $payGradeDao;
	protected $fixture;

	/**
	 * Set up method
	 */
	protected function setUp() {

		$this->payGradeDao = new PayGradeDao();
		$this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/PayGradeDao.yml';
		TestDataService::populate($this->fixture);
	}
	
	public function testGetPayGradeList(){
		$result = $this->payGradeDao->getPayGradeList();
		$this->assertEquals(count($result), 3);
	}
	
	public function testGetPayGradeById(){
		$result = $this->payGradeDao->getPayGradeById(1);
		$this->assertEquals($result->getName(), 'Pay Grade 1');
	}
	
	public function testGetCurrencyListByPayGradeId(){
		$result = $this->payGradeDao->getCurrencyListByPayGradeId(1);
		$this->assertEquals(count($result), 2);
	}
	
	public function testGetCurrencyByCurrencyIdAndPayGradeId(){
		$result = $this->payGradeDao->getCurrencyByCurrencyIdAndPayGradeId('USD', 1);
		$this->assertEquals($result->getMinSalary(), 5000);
	}
}

?>
