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
 * @group Admin
 */
class ModuleDaoTest extends PHPUnit_Framework_TestCase {

	private $moduleDao;
	protected $fixture;

	/**
	 * Set up method
	 */
	protected function setUp() {

		$this->moduleDao = new ModuleDao();
		$this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/ModuleDao.yml';
		TestDataService::populate($this->fixture);
        
	}
    
    public function testGetDisabledModuleList() {
        
        $disabledModuleList = $this->moduleDao->getDisabledModuleList();
        
        $this->assertEquals(1, count($disabledModuleList));
        $this->assertTrue($disabledModuleList[0] instanceof Module);
        $this->assertEquals('benefits', $disabledModuleList[0]->getName());
        
    }

    public function testUpdateModuleStatusWithChange() {
        
        $moduleList = array('leave', 'time');
        $status = Module::DISABLED;
        $result = $this->moduleDao->updateModuleStatus($moduleList, $status);
        
        $this->assertEquals(2, $result);
        
        $module = TestDataService::fetchObject('Module', 3);
        $this->assertEquals(Module::DISABLED, $module->getStatus());

        $module = TestDataService::fetchObject('Module', 4);
        $this->assertEquals(Module::DISABLED, $module->getStatus());
        
    }

    public function testUpdateModuleStatusWithNoChange() {
        
        $moduleList = array('leave', 'time');
        $status = Module::ENABLED;
        $result = $this->moduleDao->updateModuleStatus($moduleList, $status);
        
        $this->assertEquals(0, $result);
        
        $module = TestDataService::fetchObject('Module', 3);
        $this->assertEquals(Module::ENABLED, $module->getStatus());

        $module = TestDataService::fetchObject('Module', 4);
        $this->assertEquals(Module::ENABLED, $module->getStatus());
        
    }
    
    
}
