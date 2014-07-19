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

class ProjectDaoTest extends PHPUnit_Framework_TestCase {

    private $projectDao;
    /**
     * Set up method
     */
    protected function setUp() {

        $this->projectDao = new ProjectDao();
        TestDataService::truncateTables(array('ProjectAdmin', 'Employee', 'Project'));
        TestDataService::populate(sfConfig::get('sf_test_dir') . '/fixtures/admin/dao/ProjectDao.yml');
    }

    /* Tests getActiveProjectList method */

    public function testGetActiveProjectList() {

        $activeProjects = $this->projectDao->getActiveProjectList();

        $this->assertTrue($activeProjects[0] instanceof Project);
        $this->assertEquals(4, count($activeProjects));
        $this->assertEquals("RedHat", $activeProjects[2]->getName());
    }

    /* Tests getProjectsByProjectIds method */
    public function testGetActiveProjectsByProjectIds() {

        $projectIdArray = array(1, 3, 5, 7);
        $activeProjects = $this->projectDao->getProjectsByProjectIds($projectIdArray);

        $this->assertTrue($activeProjects[0] instanceof Project);
        $this->assertEquals(2, count($activeProjects));
        $this->assertEquals("NUS", $activeProjects[1]->getName());
    }

    /** Tests getAllProjectsByProjectIds method */
    public function testGetAllProjectsByProjectIds() {
        
        $projectIdArray = array(1, 4, 7);
        $activeProjects = $this->projectDao->getAllProjectsByProjectIds($projectIdArray);

        $this->assertTrue($activeProjects[0] instanceof Project);
        $this->assertEquals(3, count($activeProjects));
        $this->assertEquals("UOM", $activeProjects[2]->getName());
    }

    public function testGetProjectAdminRecordsByEmpNo() {

        $empNo = 1;
        $projectAdmin = $this->projectDao->getProjectAdminByEmpNumber($empNo);

        $this->assertTrue($projectAdmin[0] instanceof ProjectAdmin);
        $this->assertEquals(3, count($projectAdmin));
        $this->assertEquals(5, $projectAdmin[2]->getProjectId());
    }

}

