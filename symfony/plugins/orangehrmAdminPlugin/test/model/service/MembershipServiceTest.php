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
class MembershipServiceTest extends PHPUnit_Framework_TestCase {

    private $membershipService;
    private $fixture;

    /**
     * Set up method
     */
    protected function setUp() {
        $this->membershipService = new MembershipService();
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmAdminPlugin/test/fixtures/MembershipDao.yml';
        TestDataService::populate($this->fixture);
    }

    public function testGetMembershipList() {

        $membershipList = TestDataService::loadObjectList('Membership', $this->fixture, 'Membership');

        $membershipDao = $this->getMock('MembershipDao');
        $membershipDao->expects($this->once())
                ->method('getMembershipList')
                ->will($this->returnValue($membershipList));

        $this->membershipService->setMembershipDao($membershipDao);

        $result = $this->membershipService->getMembershipList();
        $this->assertEquals($result, $membershipList);
    }

    public function testGetMembershipById() {

        $membershipList = TestDataService::loadObjectList('Membership', $this->fixture, 'Membership');

        $membershipDao = $this->getMock('MembershipDao');
        $membershipDao->expects($this->once())
                ->method('getMembershipById')
                ->with(1)
                ->will($this->returnValue($membershipList[0]));

        $this->membershipService->setMembershipDao($membershipDao);

        $result = $this->membershipService->getMembershipById(1);
        $this->assertEquals($result, $membershipList[0]);
    }

    public function testDeleteMemberships() {

        $membershipList = array(1, 2, 3);

        $membershipDao = $this->getMock('MembershipDao');
        $membershipDao->expects($this->once())
                ->method('deleteMemberships')
                ->with($membershipList)
                ->will($this->returnValue(3));

        $this->membershipService->setMembershipDao($membershipDao);

        $result = $this->membershipService->deleteMemberships($membershipList);
        $this->assertEquals($result, 3);
    }

}

