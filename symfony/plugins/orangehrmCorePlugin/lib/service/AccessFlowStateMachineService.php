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


class AccessFlowStateMachineService {

    private $accessFlowStateMachineDao;

    public function getAccessFlowStateMachineDao() {


        if (is_null($this->accessFlowStateMachineDao)) {
            $this->accessFlowStateMachineDao = new AccessFlowStateMachineDao();
        }

        return $this->accessFlowStateMachineDao;
    }

    public function setAccessFlowStateMachineDao(AccessFlowStateMachineDao $acessFlowStateDao) {

        $this->accessFlowStateMachineDao = $acessFlowStateDao;
    }

    public function getAccessibleFlowStateMachineDao() {

        if (is_null($this->accessFlowStateMachineDao)) {
            $this->accessFlowStateMachineDao = new AccessFlowStateMachineDao();
        }

        return $this->accessFlowStateMachineDao;
    }

    public function getAllowedActions($workflow, $state, $role) {

        $results = $this->getAccessibleFlowStateMachineDao()->getAllowedActions($workflow, $state, $role);

        if (is_null($results)) {

            return null;
        } else {

            foreach ($results as $allowedAction) {

                $allowedActionArray[] = $allowedAction->getAction();
            }

            return $allowedActionArray;
        }
    }

    public function getNextState($flow, $state, $role, $action) {

        $result = $this->getAccessibleFlowStateMachineDao()->getNextState($flow, $state, $role, $action);
        if (is_null($result)) {

            return null;
        } else {

            return $result->getResultingState();
        }
    }

    public function getAllAlowedRecruitmentApplicationStates($flow, $role) {

        $result = $this->getAccessibleFlowStateMachineDao()->getAllAlowedRecruitmentApplicationStates($flow, $role);
        if (is_null($result)) {

            return null;
        } else {
            $resultingStateList = array();
            $stateList = array();
            foreach ($result as $rslt) {
                $stateList[] = $rslt->getState();
                $resultingStateList[] = $rslt->getResultingState();
            }
            return array_merge($stateList, $resultingStateList);
        }
    }

    public function getActionableStates($flow, $role, $actions) {

        $records = $this->getAccessFlowStateMachineDao()->getActionableStates($flow, $role, $actions);

        if($records==null){
            
            return null;
        }
        
        foreach ($records as $record) {

            $tempArray[] = $record->getState();
        }
        
        return $tempArray;
    }

    public function saveWorkflowStateMachineRecord(WorkflowStateMachine $workflowStateMachineRecord) {

        return $this->getAccessFlowStateMachineDao()->saveWorkflowStateMachineRecord($workflowStateMachineRecord);
    }

	/*
    public function deleteWorkflowStateMachineRecord($flow, $state, $role, $action, $resultingState) {
		$this->getAccessFlowStateMachineDao()->deleteWorkflowStateMachinerecord($flow, $state, $role, $action, $resultingState);
	}
	*/

    public function deleteWorkflowStateMachineRecord($flow, $state, $role, $action, $resultingState){
       return  $this->getAccessFlowStateMachineDao()->deleteWorkflowStateMachinerecord($flow, $state, $role, $action, $resultingState);
    }

    public function getAllowedCandidateList($role, $empNumber) {
        $candidateService = new CandidateService();
        return $candidateService->getCandidateListForUserRole($role, $empNumber);
    }
    
    public function getAllowedProjectList($role, $empNumber) {
        $projetService = new ProjectService();
        return $projetService->getProjectListForUserRole($role, $empNumber);
    }

    public function getAllowedVacancyList($role, $empNumber) {
        $vacancyService = new VacancyService();
        return $vacancyService->getVacancyListForUserRole($role, $empNumber);
    }

    public function getAllowedCandidateHistoryList($role, $empNumber, $candidateId) {
        $candidateService = new CandidateService();
        return $candidateService->getCanidateHistoryForUserRole($role, $empNumber, $candidateId);
    }

}
