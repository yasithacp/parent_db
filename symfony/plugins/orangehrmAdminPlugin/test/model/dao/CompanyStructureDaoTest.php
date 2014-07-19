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
class CompanyStructureDaoTest extends PHPUnit_Framework_TestCase {

    private $companyStructureDao;
    protected $fixture;

    /**
     * Set up method
     */
    protected function setUp() {

        $this->companyStructureDao = new CompanyStructureDao();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/CompanyStructureDao.yml';
        TestDataService::populate($this->fixture);
    }

    public function testSetOrganizationName() {
        $this->assertEquals($this->companyStructureDao->setOrganizationName("OrangeHRM"), 1);
    }

    public function testGetSubunitById() {
        $savedSubunit = $this->companyStructureDao->getSubunitById(1);
        $this->assertTrue($savedSubunit instanceof Subunit);
        $this->assertEquals($savedSubunit->getId(), 1);
        $this->assertEquals($savedSubunit->getName(), 'Organization');
    }

    public function testSaveSubunit() {
        $subunit = new Subunit();
        $subunit->setName("Open Source");
        $subunit->setDescription("Handles OrangeHRM product");
        $this->assertTrue($this->companyStructureDao->saveSubunit($subunit));
        $this->assertNotNull($subunit->getId());
    }

    public function testDeleteSubunit() {
        $subunitList = TestDataService::loadObjectList('Subunit', $this->fixture, 'Subunit');
        $subunit = $subunitList[2];
        $this->assertTrue($this->companyStructureDao->deleteSubunit($subunit));
        $result = TestDataService::fetchObject('Subunit', 3);
        $this->assertFalse($result);
    }

    public function testAddSubunit() {
        $subunitList = TestDataService::loadObjectList('Subunit', $this->fixture, 'Subunit');
        $subunit = $subunitList[2];
        $parentSubunit = new Subunit();
        $parentSubunit->setName("New Department");
        $this->assertTrue($this->companyStructureDao->addSubunit($parentSubunit, $subunit));
        $this->assertNotNull($parentSubunit->getId());
    }

    public function testGetSubunitTreeObject() {
        $treeObject = $this->companyStructureDao->getSubunitTreeObject();
        $tree = $treeObject->fetchTree();
        $this->assertNotNull($tree[0]->getLevel());
        $this->assertNotNull($tree[0]->getRgt());
        $this->assertNotNull($tree[0]->getLft());
    }

}

