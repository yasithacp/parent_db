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
 * Candidate Service
 *
 */
class CandidateService extends BaseService {

    protected $candidateDao;
    protected $employeeService;

    /**
     * Get Candidate Dao
     * @return CandidateDao
     */
    public function getCandidateDao() {
        return $this->candidateDao;
    }

    /**
     *
     * @return EmployeeService
     */
    public function getEmployeeService() {
        if (!($this->employeeService instanceof EmployeeService)) {
            $this->employeeService = new EmployeeService();
        }
        return $this->employeeService;
    }

    public function setEmployeeService(EmployeeService $service) {
        $this->employeeService = $service;
    }

    /**
     * Set Candidate Dao
     * @param CandidateDao $candidateDao
     * @return void
     */
    public function setCandidateDao(CandidateDao $candidateDao) {
        $this->candidateDao = $candidateDao;
    }

    /**
     * Construct
     */
    public function __construct() {
        $this->candidateDao = new CandidateDao();
    }

    /**
     * Retrieve all candidates
     * @returns JobCandidate doctrine collection
     * @throws RecruitmentException
     */
    public function getCandidateList($allowedCandidateList) {
        return $this->getCandidateDao()->getCandidateList($allowedCandidateList);
    }

    /**
     * Retrieve  candidate list
     * @returns  doctrine collection
     * @throws RecruitmentException
     */
    public function searchCandidates($searchParam) {
        $searchCandidateQuery = $this->buildSearchQuery($searchParam);
        return $this->getCandidateDao()->searchCandidates($searchCandidateQuery);
    }

    /**
     * Retrieve  candidate list
     * @returns  doctrine collection
     * @throws RecruitmentException
     */
    public function getCandidateRecordsCount($parameterObject) {
        $countQuery = $this->buildSearchCountQuery($parameterObject);
        return $this->getCandidateDao()->getCandidateRecordsCount($countQuery);
    }

    /**
     *
     * @param JobCandidate $candidate
     * @return <type>
     */
    public function saveCandidate(JobCandidate $candidate) {
        return $this->candidateDao->saveCandidate($candidate);
    }

    /**
     *
     * @param JobCandidate $candidate
     * @return <type>
     */
    public function getCandidateById($candidateId) {
        return $this->candidateDao->getCandidateById($candidateId);
    }

    /**
     *
     * @param JobCandidateVacancy $candidateVacancy
     * @return <type>
     */
    public function saveCandidateVacancy(JobCandidateVacancy $candidateVacancy) {
        return $this->candidateDao->saveCandidateVacancy($candidateVacancy);
    }

    /**
     *
     * @param <type> $candidate
     * @return <type>
     */
    public function updateCandidate($candidate) {
        return $this->candidateDao->updateCandidate($candidate);
    }

    /**
     *
     * @param CandidateHistory $candidateHistory
     * @return <type>
     */
    public function updateCandidateHistory(CandidateHistory $candidateHistory) {
        return $this->candidateDao->updateCandidateHistory($candidateHistory);
    }

    /**
     *
     * @param <type> $state
     * @return <type>
     */
    public function getNextActionsForCandidateVacancy($state, $userObj) {
        $stateMachine = new WorkflowStateMachine();
        $list = array("" => __('Select Action'));
        $allowedActions = $userObj->getAllowedActions(PluginWorkflowStateMachine::FLOW_RECRUITMENT, $state);
        if (empty($allowedActions)) {
            $list[""] = __('No Actions');
        } else {
            foreach ($allowedActions as $action) {
                $list[$action] = $stateMachine->getRecruitmentActionName($action);
            }
        }
        return $list;
    }

    /**
     *
     * @param <type> $candidateVacancyId
     * @return <type> 
     */
    public function getCandidateVacancyById($candidateVacancyId) {
        return $this->candidateDao->getCandidateVacancyById($candidateVacancyId);
    }

    /**
     *
     * @param <type> $state
     * @param <type> $action
     * @return <type>
     */
    public function getNextStateForCandidateVacancy($state, $action, $userObj) {
        return $userObj->getNextState(PluginWorkflowStateMachine::FLOW_RECRUITMENT, $state, $action);
    }

    /**
     *
     * @param JobCandidateVacancy $candidateVacancy
     * @param <type> $action
     * @return <type>
     */
    public function updateCandidateVacancy(JobCandidateVacancy $candidateVacancy, $action, $userObj) {
        $candidateVacancy->setStatus($this->getNextStateForCandidateVacancy($candidateVacancy->getStatus(), $action, $userObj));
        return $this->candidateDao->updateCandidateVacancy($candidateVacancy);
    }

    /**
     *
     * @param CandidateHistory $candidateHistory
     * @return <type>
     */
    public function saveCandidateHistory(CandidateHistory $candidateHistory) {
        return $this->candidateDao->saveCandidateHistory($candidateHistory);
    }

    /**
     *
     * @param <type> $candidateId
     * @return <type>
     */
    public function getCandidateHistoryForCandidateId($candidateId, $allowedHistoryList) {
        return $this->candidateDao->getCandidateHistoryForCandidateId($candidateId, $allowedHistoryList);
    }

    /**
     *
     * @param <type> $id
     * @return <type>
     */
    public function getCandidateHistoryById($id) {
        return $this->candidateDao->getCandidateHistoryById($id);
    }

    /**
     * Delete Candidate Completely or Delete Candidat-Vacancy Relationship
     * @param array $toBeDeletedCandiateVacancies
     * @return boolean
     */
    public function deleteCandidateVacancies($candidateVacancies) {

        $candidateIds = array();
        $vacancyIds = array();
        $toBeDeletedCandidateVacancyArray = array();

        if (!empty($candidateVacancies)) {

            foreach ($candidateVacancies as $candidateId => $vacancyIdArray) {

                $allVacancies = $this->candidateDao->getAllVacancyIdsForCandidate($candidateId);

                if (!(array_diff($allVacancies, $vacancyIdArray))) {

                    $toBeDeletedCandidates[] = $candidateId;
                } else {

                    foreach ($vacancyIdArray as $vacancyId) {
                        $toBeDeletedCandidateVacancyArray[] = array($candidateId, $vacancyId);
                    }
                }
            }

            $canidateDeletion = true;
            $vacancyDeletion = true;

            if (!empty($toBeDeletedCandidates)) {
                $canidateDeletion = $this->candidateDao->deleteCandidates($toBeDeletedCandidates);
            }

            if (!empty($toBeDeletedCandidateVacancyArray)) {
                $vacancyDeletion = $this->candidateDao->deleteCandidateVacancies($toBeDeletedCandidateVacancyArray);
            }

            if ($canidateDeletion && $vacancyDeletion) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get Vacancies Of Candidates As 2-D Array
     * @param array $toBeDeletedCandiateVacancies
     * @return array $toBeDeletedCandiateVacanciesArray
     */
    public function processCandidatesVacancyArray($toBeDeletedCandiateVacancies) {

        $candidateVacancies = array();
        $candidateIds = array();

        if (!empty($toBeDeletedCandiateVacancies)) {

            foreach ($toBeDeletedCandiateVacancies as $val) {
                $candidateVacancies[] = explode("_", $val);
            }

            foreach ($candidateVacancies as $record) {
                $candidateIds = array();
                foreach ($candidateVacancies as $value) {
                    $candidateIds[] = $value[0];
                }
            }
            $candidateIds = array_unique($candidateIds);
        }

        return $candidateIds;
    }

    /**
     *
     * @param <type> $employee
     */
    public function addEmployee($employee) {

        return $this->getEmployeeService()->addEmployee($employee);
    }

    public function deleteCandidate($candidateIds) {

        return $this->candidateDao->deleteCandidates($candidateIds);
    }

    public function getCandidateListForUserRole($role, $empNumber) {
        return $this->candidateDao->getCandidateListForUserRole($role, $empNumber);
    }

    public function getCanidateHistoryForUserRole($role, $empNumber, $candidateId) {
        return $this->candidateDao->getCanidateHistoryForUserRole($role, $empNumber, $candidateId);
    }

    protected function buildSearchQuery($parameterObject) {
        $query = $this->getCandidateDao()->buildSearchQuery($parameterObject);

        $serviceName = 'CandidateService';
        $methodName = 'searchCandidates';
        $query = $this->decorateQuery($serviceName, $methodName, $query, $parameterObject->getAdditionalParams());
        return $query;
    }

    protected function buildSearchCountQuery($parameterObject) {
        $query = $this->getCandidateDao()->buildSearchQuery($parameterObject, true);

        $serviceName = 'CandidateService';
        $methodName = 'getCandidateRecordsCount';
        $query = $this->decorateQuery($serviceName, $methodName, $query, $parameterObject->getAdditionalParams());
        return $query;
    }

    /* public function getLastPerformedActionByCandidateVAcancyId($candidateVacancyId) {
      return $this->candidateDao->getLastPerformedActionByCandidateVAcancyId($candidateVacancyId);
      } */

    public function isHiringManager($candidateVacancyId, $empNumber) {
        return $this->candidateDao->isHiringManager($candidateVacancyId, $empNumber);
    }

    public function isInterviewer($candidateVacancyId, $empNumber) {
        return $this->candidateDao->isInterviewer($candidateVacancyId, $empNumber);
    }

    public function getCandidateVacancyByCandidateIdAndVacancyId($candidateId, $vacancyId) {
        return $this->candidateDao->getCandidateVacancyByCandidateIdAndVacancyId($candidateId, $vacancyId);
    }

}

