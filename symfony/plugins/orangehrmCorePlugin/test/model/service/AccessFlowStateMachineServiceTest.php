<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AccessFlowStateMachineServiceTest
 *
 * @group Core
 */
class AccessFlowStateMachineServiceTest extends PHPUnit_Framework_TestCase {

    private $accessFlowStateMachineService;

    protected function setUp() {

        $this->accessFlowStateMachineService = new AccessFlowStateMachineService();

        $this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmCorePlugin/test/fixtures/AccessFlowStateMachineService.yml';
    }
    
    public function testGetAccessFlowStateMachineDao(){
        
       $accessFlowStateMachineDao= $this->accessFlowStateMachineService->getAccessFlowStateMachineDao();
       
       $this->assertTrue($accessFlowStateMachineDao instanceof AccessFlowStateMachineDao);
        
        
    }
    
    public function testSetAccessFlowStateMachineDao(){
        
        $accessFlowStateMachineDao= new AccessFlowStateMachineDao();
        $this->accessFlowStateMachineService->setAccessFlowStateMachineDao($accessFlowStateMachineDao);
        
        $this->assertTrue($this->accessFlowStateMachineService->getAccessFlowStateMachineDao() instanceof AccessFlowStateMachineDao);
        
        
    }
    public function testGetAllowedActions() {
        $flow = "Time";
        $state = "SUBMITTED";
        $role = "ESS USER";
        $fetchedRecord1 = TestDataService::fetchObject('WorkflowStateMachine', 10);
        $fetchedRecord2 = TestDataService::fetchObject('WorkflowStateMachine', 12);
        $recordsArray = array($fetchedRecord1, $fetchedRecord2);

        $acessFlowStateMachineDaoMock = $this->getMock('AccessFlowStateMachineDao', array('getAllowedActions'));
        $acessFlowStateMachineDaoMock->expects($this->once())
                ->method('getAllowedActions')
                ->with($flow, $state, $role)
                ->will($this->returnValue($recordsArray));

        $this->accessFlowStateMachineService->setAccessFlowStateMachineDao($acessFlowStateMachineDaoMock);
        $retrievedActionsArray = $this->accessFlowStateMachineService->getAllowedActions($flow, $state, $role);

        $this->assertEquals($retrievedActionsArray[0], $recordsArray[0]->getAction());
        $this->assertEquals($retrievedActionsArray[1], $recordsArray[1]->getAction());

        $flow = "Attendance";
        $state = "INITIAL";
        $role = "ADMIN";
        $recordsArray = null;

        $acessFlowStateMachineDaoMock = $this->getMock('AccessFlowStateMachineDao', array('getAllowedActions'));
        $acessFlowStateMachineDaoMock->expects($this->once())
                ->method('getAllowedActions')
                ->with($flow, $state, $role)
                ->will($this->returnValue($recordsArray));

        $this->accessFlowStateMachineService->setAccessFlowStateMachineDao($acessFlowStateMachineDaoMock);
        $retrievedActionsArray = $this->accessFlowStateMachineService->getAllowedActions($flow, $state, $role);

        $this->assertNull($retrievedActionsArray);
    }

    public function testGetNextState() {

        $flow = "Time";
        $state = "SUBMITTED";
        $role = "ADMIN";
        $action = "APPROVE";

        $fetchedRecord1 = TestDataService::fetchObject('WorkflowStateMachine', 1);

        $acessFlowStateMachineDaoMock = $this->getMock('AccessFlowStateMachineDao', array('getNextState'));
        $acessFlowStateMachineDaoMock->expects($this->once())
                ->method('getNextState')
                ->with($flow, $state, $role, $action)
                ->will($this->returnValue($fetchedRecord1));

        $this->accessFlowStateMachineService->setAccessFlowStateMachineDao($acessFlowStateMachineDaoMock);
        $retrievedState = $this->accessFlowStateMachineService->getNextState($flow, $state, $role, $action);

        $this->assertEquals($retrievedState, $fetchedRecord1->getResultingState());
        
        //checking the null case
        
        $flow = "Attendace";
        $state = "SUBMITTED";
        $role = "ADMIN";
        $action = "APPROVE";

        $acessFlowStateMachineDaoMock = $this->getMock('AccessFlowStateMachineDao', array('getNextState'));
        $acessFlowStateMachineDaoMock->expects($this->once())
                ->method('getNextState')
                ->with($flow, $state, $role, $action)
                ->will($this->returnValue(null));

        $this->accessFlowStateMachineService->setAccessFlowStateMachineDao($acessFlowStateMachineDaoMock);
        $retrievedState = $this->accessFlowStateMachineService->getNextState($flow, $state, $role, $action);

        $this->assertNull($retrievedState);
    }

    public function testGetActionableStates() {

        $actions = array("APPROVE", "REJECT");
        $workFlow = "Time";
        $userRole = "ADMIN";

        $fetchedRecord1 = TestDataService::fetchObject('WorkflowStateMachine', 1);
        $fetchedRecord2 = TestDataService::fetchObject('WorkflowStateMachine', 5);
        $tempArray = array($fetchedRecord1, $fetchedRecord2);

        $acessFlowStateMachineDaoMock = $this->getMock('AccessFlowStateMachineDao', array('getActionableStates'));
        $acessFlowStateMachineDaoMock->expects($this->once())
                ->method('getActionableStates')
                ->with($workFlow, $userRole, $actions)
                ->will($this->returnValue($tempArray));

        $this->accessFlowStateMachineService->setAccessFlowStateMachineDao($acessFlowStateMachineDaoMock);
        $record = $this->accessFlowStateMachineService->getActionableStates($workFlow, $userRole, $actions);

        $this->assertEquals(2, count($record));
        $this->assertEquals($fetchedRecord1->getState(), $record[0]);
        $this->assertEquals($fetchedRecord2->getState(), $record[1]);
    }

    public function testSaveWorkflowStateMachineRecord() {


        $workflowStateMachineRecords = TestDataService::loadObjectList('WorkflowStateMachine', $this->fixture, 'WorkflowStateMachine');

        $workflowStateMachineRecord = $workflowStateMachineRecords[0];

        $accessFlowStateMachineDaoMock = $this->getMock('AccessFlowStateMachineDao', array('saveWorkflowStateMachineRecord'));

        $accessFlowStateMachineDaoMock->expects($this->once())
                ->method('saveWorkflowStateMachineRecord')
                ->with($workflowStateMachineRecord)
                ->will($this->returnValue($workflowStateMachineRecord));

        $this->accessFlowStateMachineService->setAccessFlowStateMachineDao($accessFlowStateMachineDaoMock);

        $this->assertTrue($this->accessFlowStateMachineService->saveWorkflowStateMachineRecord($workflowStateMachineRecord) instanceof WorkflowStateMachine);
    }

    public function testDeleteWorkflowStateMachineRecord() {
        $flow = "Time";
        $state = "SUPERVISOR APPROVED";
        $role = "ADMIN";
        $action = "VIEW TIMESHEET";
        $resultingState = "SUPERVISOR APPROVED";
        $isSuccess = true;

        $acessFlowStateMachineDaoMock = $this->getMock('AccessFlowStateMachineDao', array('deleteWorkflowStateMachineRecord'));
        $acessFlowStateMachineDaoMock->expects($this->once())
                ->method('deleteWorkflowStateMachineRecord')
                ->with($flow, $state, $role, $action, $resultingState)
                ->will($this->returnValue($isSuccess));

        $this->accessFlowStateMachineService->setAccessFlowStateMachineDao($acessFlowStateMachineDaoMock);
        $retunedValue = $this->accessFlowStateMachineService->deleteWorkflowStateMachineRecord($flow, $state, $role, $action, $resultingState);

        $this->assertEquals($isSuccess, $retunedValue);

        $flow = "Time";
        $state = "SUPERVISOR APPROVED";
        $role = "ADMIN";
        $action = "VIEW TIMESHEET";
        $resultingState = "SUPERVISOR APPROVED";
        $isSuccess = false;

        $acessFlowStateMachineDaoMock = $this->getMock('AccessFlowStateMachineDao', array('deleteWorkflowStateMachineRecord'));
        $acessFlowStateMachineDaoMock->expects($this->once())
                ->method('deleteWorkflowStateMachineRecord')
                ->with($flow, $state, $role, $action, $resultingState)
                ->will($this->returnValue($isSuccess));

        $this->accessFlowStateMachineService->setAccessFlowStateMachineDao($acessFlowStateMachineDaoMock);
        $retunedValue = $this->accessFlowStateMachineService->deleteWorkflowStateMachineRecord($flow, $state, $role, $action, $resultingState);

        $this->assertEquals($isSuccess, $retunedValue);
    }

}

?>
