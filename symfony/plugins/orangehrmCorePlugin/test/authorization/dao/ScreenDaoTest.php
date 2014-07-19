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
 * Description of ScreenDaoTest
 *
 */
class ScreenDaoTest extends PHPUnit_Framework_TestCase {
    
    /** @property ScreenPermissionDao $dao */
    private $dao;
    
    /**
     * Set up method
     */
    protected function setUp() {        
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmCorePlugin/test/fixtures/ScreenDao.yml';
        
        TestDataService::populate($this->fixture);
                
        $this->dao = new ScreenDao();
    }
    
    public function testGetScreen() {
        
        $screen = $this->dao->getScreen('pim', 'viewEmployeeList');
        $this->assertNotNull($screen);
        $this->assertEquals(1, $screen->getId());
        $this->assertEquals('employee list', $screen->getName());
        $this->assertEquals(3, $screen->getModuleId());
        $this->assertEquals('viewEmployeeList', $screen->getActionUrl()); 
        
        // non existing action
        $screen = $this->dao->getScreen('pim', 'viewNoneNone');
        $this->assertFalse($screen);               
    }

}


