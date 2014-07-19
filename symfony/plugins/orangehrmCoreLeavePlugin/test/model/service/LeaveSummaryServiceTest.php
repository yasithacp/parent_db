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
 * @group CoreLeave 
 */
class LeaveSummaryServiceTest extends PHPUnit_Framework_TestCase {

    protected $leaveSummaryService;

    public function setup() {

        $this->leaveSummaryService  = new LeaveSummaryService();

    }

    public function testFetchRawLeaveSummaryRecords() {

        $clues = array();
        $offset = 0;
        $limit = 50;
        $resource = Array();

        $leaveSummaryDao = $this->getMock('LeaveSummaryDao', array('fetchRawLeaveSummaryRecords'));
        $leaveSummaryDao->expects($this->once())
                        ->method('fetchRawLeaveSummaryRecords')
                        ->with($clues, $offset, $limit)
                        ->will($this->returnValue(new MySqlResource()));

        $this->leaveSummaryService->setLeaveSummaryDao($leaveSummaryDao);

        $result = $this->leaveSummaryService->fetchRawLeaveSummaryRecords($clues, $offset, $limit);

        $this->assertTrue(is_array($result));

    }

    public function testFetchRawLeaveSummaryRecordsCount() {

        $clues = array();

        $leaveSummaryDao = $this->getMock('LeaveSummaryDao', array('fetchRawLeaveSummaryRecordsCount'));
        $leaveSummaryDao->expects($this->once())
                        ->method('fetchRawLeaveSummaryRecordsCount')
                        ->with($clues)
                        ->will($this->returnValue(50));

        $this->leaveSummaryService->setLeaveSummaryDao($leaveSummaryDao);

        $result = $this->leaveSummaryService->fetchRawLeaveSummaryRecordsCount($clues);

        $this->assertEquals(50, $result);

    }

}

class MySqlResource {

    public function fetch() {
        return false;
    }
}

?>
