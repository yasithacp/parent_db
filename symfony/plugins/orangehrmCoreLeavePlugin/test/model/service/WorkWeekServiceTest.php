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
class WorkWeekServiceTest extends PHPUnit_Framework_TestCase
{

    private $workWeekService;
    private $fixture;

    protected function setUp()
    {
        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmCoreLeavePlugin/test/fixtures/WorkWeekService.yml';
        $this->workWeekService	=	new WorkWeekService();
    }

    /* test setWorkWeekDao works well */
    
    public function testSetGetWorkWeekDao() {

       $workWeekDao = new WorkWeekDao();
       $this->workWeekService->setWorkWeekDao($workWeekDao);

       $this->assertTrue($this->workWeekService->getWorkWeekDao() instanceof WorkWeekDao);
       $this->assertEquals($workWeekDao, $this->workWeekService->getWorkWeekDao());

    }

    /* test for saveWorkWeek */

    public function testSaveWorkWeek() {

      $workWeekList   = TestDataService::loadObjectList('WorkWeek', $this->fixture, 'WorkWeek');
      $workWeek       = $workWeekList[0];

      $workWeekDao = $this->getMock('WorkWeekDao', array('saveWorkWeek'));
      $workWeekDao->expects($this->once())
                  ->method('saveWorkWeek')
                  ->with($workWeek)
                  ->will($this->returnValue($workWeek));

      $this->workWeekService->setWorkWeekDao($workWeekDao);
      
      $this->assertTrue($this->workWeekService->saveWorkWeek($workWeek) instanceof WorkWeek);

    }

    /* test for getWorkWeekList */
    
    public function testGetWorkWeekList() {

      $workWeekList   = TestDataService::loadObjectList('WorkWeek', $this->fixture, 'WorkWeek');
      
      $workWeekDao = $this->getMock('WorkWeekDao', array('getWorkWeekList'));
      $workWeekDao->expects($this->once())
                  ->method('getWorkWeekList')
                  ->will($this->returnValue($workWeekList));

      $this->workWeekService->setWorkWeekDao($workWeekDao);
      $list = $this->workWeekService->getWorkWeekList();

      $this->assertEquals(7, count($list));
      foreach ($list as $workWeek) {
         $this->assertTrue($workWeek instanceof WorkWeek);
      }

    }

    /* test readWorkWeek returns WorkWeek instance */

    public function testReadWorkWeek() {

      $workWeekList   = TestDataService::loadObjectList('WorkWeek', $this->fixture, 'WorkWeek');

      $workWeekDao = $this->getMock('WorkWeekDao', array('readWorkWeek'));
      $workWeekDao->expects($this->once())
                  ->method('readWorkWeek')
                  ->with(1)
                  ->will($this->returnValue($workWeekList[0]));

      $this->workWeekService->setWorkWeekDao($workWeekDao);
      $readWorkWeek = $this->workWeekService->readWorkWeek(1);

      $this->assertTrue($readWorkWeek instanceof WorkWeek);
      $this->assertEquals($workWeekList[0], $readWorkWeek);

    }

    /* test readWorkWeek returns null in Dao */

    public function testReadWorkWeekReturnsNullInDao() {

      $workWeekDao = $this->getMock('WorkWeekDao', array('readWorkWeek'));
      $workWeekDao->expects($this->once())
                  ->method('readWorkWeek')
                  ->with(8)
                  ->will($this->returnValue(null));

      $this->workWeekService->setWorkWeekDao($workWeekDao);
      $readWorkWeek = $this->workWeekService->readWorkWeek(8);
      
      $this->assertTrue($readWorkWeek instanceof WorkWeek);

    }

    /* test isWeekend */
    
    public function testIsWeekend() {

      $workWeekDao = $this->getMock('WorkWeekDao', array('isWeekend'));
      $workWeekDao->expects($this->once())
                  ->method('isWeekend')
                  ->with(1, true)
                  ->will($this->returnValue(true));

      $this->workWeekService->setWorkWeekDao($workWeekDao);
      $this->assertTrue($this->workWeekService->isWeekend(1, true));

    }

    /* test deleteWorkWeek */
    
    public function testDeleteWorkWeek() {

      $workWeekDao = $this->getMock('WorkWeekDao', array('deleteWorkWeek'));
      $workWeekDao->expects($this->once())
                  ->method('deleteWorkWeek')
                  ->with(array(1,2))
                  ->will($this->returnValue(true));

      $this->workWeekService->setWorkWeekDao($workWeekDao);
      $this->assertTrue($this->workWeekService->deleteWorkWeek(array(1, 2)));

    }
    
    public function testGetWorkWeekOfOperationalCountry() {
        
        $defaultWorkWeek = new WorkWeek();
        $defaultWorkWeek->setId(1);
        
        $workWeek = new WorkWeek();
        $workWeek->setId(2);
        
        $recordsMock = $this->getMock('Doctrine_Collection', array('getFirst'), array('WorkWeek'));
        $recordsMock->expects($this->exactly(3))
                ->method('getFirst')
                ->will($this->onConsecutiveCalls($defaultWorkWeek, $defaultWorkWeek, $workWeek));
        
        $workWeekDaoMock = $this->getMock('WorkWeekDao', array('searchWorkWeek'));
        $workWeekDaoMock->expects($this->any())
                ->method('searchWorkWeek')
                ->will($this->returnValue($recordsMock));
        
        $this->workWeekService->setWorkWeekDao($workWeekDaoMock);
        
        $result = $this->workWeekService->getWorkWeekOfOperationalCountry(null);
        $this->assertEquals($defaultWorkWeek, $result);
        
        $result = $this->workWeekService->getWorkWeekOfOperationalCountry(1);
        $this->assertEquals($defaultWorkWeek, $result);
        
        $result = $this->workWeekService->getWorkWeekOfOperationalCountry(2);
        $this->assertEquals($workWeek, $result);
    }

}

?>