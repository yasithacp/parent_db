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
class CompanyStructureServiceTest extends PHPUnit_Framework_TestCase {

    private $companyStructureService;
    protected $fixture;

    /**
     * Set up method
     */
    protected function setUp() {
        $this->companyStructureService = new CompanyStructureService();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/CompanyStructureDao.yml';
        TestDataService::populate($this->fixture);
    }

    public function testGetSubunitById() {

        $subunit = TestDataService::fetchObject('Subunit', 1);

        $compStructureDao = $this->getMock('CompanyStructureDao');

        $compStructureDao->expects($this->once())
                ->method('getSubunitById')
                ->with($subunit->getId())
                ->will($this->returnValue($subunit));

        $this->companyStructureService->setCompanyStructureDao($compStructureDao);
        $result = $this->companyStructureService->getSubunitById($subunit->getId());
        $this->assertEquals($subunit, $result);
    }

    public function testSaveSubunit() {

        $subunit = new Subunit();
        $subunit->setName("subunit name");

        $compStructureDao = $this->getMock('CompanyStructureDao');

        $compStructureDao->expects($this->once())
                ->method('saveSubunit')
                ->with($subunit)
                ->will($this->returnValue(true));

        $this->companyStructureService->setCompanyStructureDao($compStructureDao);
        $result = $this->companyStructureService->saveSubunit($subunit);
        $this->assertTrue($result);
    }
    
    public function testAddSubunit() {

        $subunit = TestDataService::fetchObject('Subunit', 1);

        $parentSubunit = new Subunit();
        $parentSubunit->setName("new subunit");

        $compStructureDao = $this->getMock('CompanyStructureDao');

        $compStructureDao->expects($this->once())
                ->method('addSubunit')
                ->with($parentSubunit, $subunit)
                ->will($this->returnValue(true));

        $this->companyStructureService->setCompanyStructureDao($compStructureDao);
        $result = $this->companyStructureService->addSubunit($parentSubunit, $subunit);
        $this->assertTrue($result);
    }

    public function testDeleteSubunit() {

        $subunit = TestDataService::fetchObject('Subunit', 1);

        $parentSubunit = new Subunit();
        $parentSubunit->setName("new subunit");

        $compStructureDao = $this->getMock('CompanyStructureDao');

        $compStructureDao->expects($this->once())
                ->method('deleteSubunit')
                ->with($subunit)
                ->will($this->returnValue(true));

        $this->companyStructureService->setCompanyStructureDao($compStructureDao);
        $result = $this->companyStructureService->deleteSubunit($subunit);
        $this->assertTrue($result);
    }
    
    public function testSetOrganizationName() {

        $name = "Company Name";
        $returnvalue = 1;

        $compStructureDao = $this->getMock('CompanyStructureDao');

        $compStructureDao->expects($this->once())
                ->method('setOrganizationName')
                ->with($name)
                ->will($this->returnValue($returnvalue));

        $this->companyStructureService->setCompanyStructureDao($compStructureDao);
        $result = $this->companyStructureService->setOrganizationName($name);
        $this->assertEquals($returnvalue, $result);
    }
    
    public function testGetSubunitTreeObject() {

        $treeObject = Doctrine::getTable('Subunit')->getTree();

        $compStructureDao = $this->getMock('CompanyStructureDao');

        $compStructureDao->expects($this->once())
                ->method('getSubunitTreeObject')
                ->will($this->returnValue($treeObject));

        $this->companyStructureService->setCompanyStructureDao($compStructureDao);
        $result = $this->companyStructureService->getSubunitTreeObject();
        $this->assertEquals($treeObject, $result);
    }

}

