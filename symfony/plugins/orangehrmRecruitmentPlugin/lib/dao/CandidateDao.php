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
 * CandidateDao for CRUD operation
 *
 */
class CandidateDao extends BaseDao {

    /**
     * Retrieve candidate by candidateId
     * @param int $candidateId
     * @returns jobCandidate doctrine object
     * @throws DaoException
     */
    public function getCandidateById($candidateId) {
        try {
            return Doctrine :: getTable('JobCandidate')->find($candidateId);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Retrieve all candidates
     * @returns JobCandidate doctrine collection
     * @throws DaoException
     */
    public function getCandidateList($allowedCandidateList, $status = JobCandidate::ACTIVE) {
        try {
            $q = Doctrine_Query :: create()
                    ->from('JobCandidate jc');
            if ($allowedCandidateList != null) {
                $q->whereIn('jc.id', $allowedCandidateList);
            }
            if (!empty($status)) {
                $q->addWhere('jc.status = ?', $status);
            }
            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function getCandidateListForUserRole($role, $empNumber) {

        try {
            $q = Doctrine_Query :: create()
                    ->select('jc.id')
                    ->from('JobCandidate jc');
            if ($role == HiringManagerUserRoleDecorator::HIRING_MANAGER) {
                $q->leftJoin('jc.JobCandidateVacancy jcv')
                        ->leftJoin('jcv.JobVacancy jv')
                        ->where('jv.hiringManagerId = ?', $empNumber)
                        ->orWhere('jc.id NOT IN (SELECT ojcv.candidateId FROM JobCandidateVacancy ojcv) AND jc.addedPerson = ?', $empNumber);
            }
            if ($role == InterviewerUserRoleDecorator::INTERVIEWER) {
                $q->leftJoin('jc.JobCandidateVacancy jcv')
                        ->leftJoin('jcv.JobInterview ji')
                        ->leftJoin('ji.JobInterviewInterviewer jii')
                        ->where('jii.interviewerId = ?', $empNumber);
            }
            $result = $q->fetchArray();
            $idList = array();
            foreach ($result as $item) {
                $idList[] = $item['id'];
            }
            return $idList;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Retriving candidates based on the search criteria
     * @param CandidateSearchParameters $searchParam
     * @return CandidateSearchParameters
     */
    public function searchCandidates($searchCandidateQuery) {

        try {
            $pdo = Doctrine_Manager::connection()->getDbh();
            $res = $pdo->query($searchCandidateQuery);

            $candidateList = $res->fetchAll();

            $candidatesList = array();
            foreach ($candidateList as $candidate) {

                $param = new CandidateSearchParameters();
                $param->setVacancyName($candidate['name']);
                $param->setVacancyStatus($candidate['vacancyStatus']);
                $param->setCandidateId($candidate['id']);
                $param->setVacancyId($candidate['vacancyId']);
                $param->setCandidateName($candidate['first_name'] . " " . $candidate['middle_name'] . " " . $candidate['last_name'] . $this->_getCandidateNameSuffix($candidate['candidateStatus']));
                $employeeName = $candidate['emp_firstname'] . " " . $candidate['emp_middle_name'] . " " . $candidate['emp_lastname'];
                $hmName = (!empty($candidate['termination_id'])) ? $employeeName." (".__("Past Employee").")" : $employeeName;
                $param->setHiringManagerName($hmName);
                $param->setDateOfApplication($candidate['date_of_application']);
                $param->setAttachmentId($candidate['attachmentId']);
                $param->setStatusName(ucwords(strtolower($candidate['status'])));
                $candidatesList[] = $param;
            }
            return $candidatesList;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param CandidateSearchParameters $searchParam
     * @return <type>
     */
    public function getCandidateRecordsCount($countQuery) {

        try {
            $pdo = Doctrine_Manager::connection()->getDbh();
            $res = $pdo->query($countQuery);
            $count = $res->fetch();
            return $count[0];
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param JobCandidate $candidate
     * @return <type>
     */
    public function saveCandidate(JobCandidate $candidate) {
        try {
            if ($candidate->getId() == "") {
                $idGenService = new IDGeneratorService();
                $idGenService->setEntity($candidate);
                $candidate->setId($idGenService->getNextID());
            }
            $candidate->save();
            return true;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param JobCandidateVacancy $candidateVacancy
     * @return <type>
     */
    public function saveCandidateVacancy(JobCandidateVacancy $candidateVacancy) {
        try {
            if ($candidateVacancy->getId() == '') {
                $idGenService = new IDGeneratorService();
                $idGenService->setEntity($candidateVacancy);
                $candidateVacancy->setId($idGenService->getNextID());
            }
            $candidateVacancy->save();
            return true;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param JobCandidate $candidate
     * @return <type>
     */
    public function updateCandidate(JobCandidate $candidate) {
        try {
            $q = Doctrine_Query:: create()->update('JobCandidate')
                    ->set('firstName', '?', $candidate->firstName)
                    ->set('lastName', '?', $candidate->lastName)
                    ->set('contactNumber', '?', $candidate->contactNumber)
                    ->set('keywords', '?', $candidate->keywords)
                    ->set('email', '?', $candidate->email)
                    ->set('middleName', '?', $candidate->middleName)
                    ->set('dateOfApplication', '?', $candidate->dateOfApplication)
                    ->set('comment', '?', $candidate->comment)
                    ->where('id = ?', $candidate->id);

            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param JobCandidate $candidate
     * @return <type>
     */
    public function updateCandidateHistory(CandidateHistory $candidateHistory) {
        try {
            $q = Doctrine_Query:: create()->update('CandidateHistory')
                    ->set('interviewers', '?', $candidateHistory->interviewers)
                    ->where('id = ?', $candidateHistory->id);

            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param <type> $candidateVacancyId
     * @return <type>
     */
    public function getCandidateVacancyById($candidateVacancyId) {
        try {
            $q = Doctrine_Query :: create()
                    ->from('JobCandidateVacancy jcv')
                    ->where('jcv.id = ?', $candidateVacancyId);
            return $q->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param JobCandidateVacancy $candidateVacancy
     * @return <type>
     */
    public function updateCandidateVacancy(JobCandidateVacancy $candidateVacancy) {
        try {
            $q = Doctrine_Query:: create()->update('JobCandidateVacancy')
                    ->set('status', '?', $candidateVacancy->status)
                    ->where('id = ?', $candidateVacancy->id);
            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param CandidateHistory $candidateHistory
     * @return <type>
     */
    public function saveCandidateHistory(CandidateHistory $candidateHistory) {
        try {
            if ($candidateHistory->getId() == '') {
                $idGenService = new IDGeneratorService();
                $idGenService->setEntity($candidateHistory);
                $candidateHistory->setId($idGenService->getNextID());
            }
            $candidateHistory->save();
            return true;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param <type> $candidateId
     * @return <type>
     */
    public function getCandidateHistoryForCandidateId($candidateId, $allowedHistoryList) {
        try {
            $q = Doctrine_Query:: create()
                    ->from('CandidateHistory ch')
                    ->whereIn('ch.id', $allowedHistoryList)
                    ->andWhere('ch.candidateId = ?', $candidateId)
                    ->orderBy('ch.performedDate DESC');
            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param <type> $id
     * @return <type>
     */
    public function getCandidateHistoryById($id) {
        try {
            $q = Doctrine_Query:: create()
                    ->from('CandidateHistory')
                    ->where('id = ?', $id);
            return $q->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function getCanidateHistoryForUserRole($role, $empNumber, $candidateId) {
        try {
            $q = Doctrine_Query :: create()
                    ->select('ch.id')
                    ->from('CandidateHistory ch');
            if ($role == HiringManagerUserRoleDecorator::HIRING_MANAGER) {
                $q->leftJoin('ch.JobVacancy jv')
                        ->leftJoin('ch.JobCandidate jc')
                        ->where('jv.hiringManagerId = ?', $empNumber)
                        ->andWhere('ch.candidateId = ?', $candidateId)
                        ->orWhereIn('ch.action', array(CandidateHistory::RECRUITMENT_CANDIDATE_ACTION_ADD))
                        ->orWhere('ch.candidateId NOT IN (SELECT ojcv.candidateId FROM JobCandidateVacancy ojcv) AND jc.addedPerson = ?', $empNumber)
                        ->orWhere('ch.performedBy = ?', $empNumber);
            }
            if ($role == InterviewerUserRoleDecorator::INTERVIEWER) {
                $q->leftJoin('ch.JobInterview ji ON ji.id = ch.interview_id')
                        ->leftJoin('ji.JobInterviewInterviewer jii')
                        ->where('jii.interviewerId = ?', $empNumber)
                        ->andWhere('ch.candidateId = ?', $candidateId)
                        ->orWhere('ch.performedBy = ?', $empNumber)
                        ->orWhereIn('ch.action', array(CandidateHistory::RECRUITMENT_CANDIDATE_ACTION_ADD, WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_ATTACH_VACANCY, WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_SHORTLIST, WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_SHEDULE_INTERVIEW, WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_MARK_INTERVIEW_FAILED, WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_MARK_INTERVIEW_PASSED));
//                        ->orWhere('jcv.id IN (SELECT ojcv.id FROM JobCandidateVacancy ojcv LEFT JOIN ojcv.JobInterview oji ON ojcv.id = oji.candidate_vacancy_id LEFT JOIN oji.JobInterviewInterviewer ojii ON ojii.interview_id = oji.id WHERE ojii.interviewerId = ?)', $empNumber);
            }
            if ($role == AdminUserRoleDecorator::ADMIN_USER) {
                $q->where('ch.candidateId = ?', $candidateId);
            }
            $result = $q->fetchArray();
            $idList = array();
            foreach ($result as $item) {
                $idList[] = $item['id'];
            }
            return $idList;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Get all vacancy Ids for relevent candidate
     * @param int $candidateId
     * @return array $vacancies
     */
    public function getAllVacancyIdsForCandidate($candidateId) {

        try {

            $q = Doctrine_Query:: create()
                    ->from('JobCandidateVacancy v')
                    ->where('v.candidateId = ?', $candidateId);
            $vacancies = $q->execute();

            $vacancyIdsForCandidate = array();
            foreach ($vacancies as $value) {
                $vacancyIdsForCandidate[] = $value->getVacancyId();
            }
            return $vacancyIdsForCandidate;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Delete Candidate
     * @param array $toBeDeletedCandidateIds
     * @return boolean
     */
    public function deleteCandidates($toBeDeletedCandidateIds) {

        try {
            $q = Doctrine_Query:: create()
                    ->delete()
                    ->from('JobCandidate')
                    ->whereIn('id', $toBeDeletedCandidateIds);

            $result = $q->execute();
            return true;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Delete Candidate-Vacancy Relations
     * @param array $toBeDeletedRecords
     * @return boolean
     */
    public function deleteCandidateVacancies($toBeDeletedRecords) {

        try {
            $q = Doctrine_Query:: create()
                    ->delete()
                    ->from('JobCandidateVacancy cv')
                    ->where('candidateId = ? AND vacancyId = ?', $toBeDeletedRecords[0]);
            for ($i = 1; $i < count($toBeDeletedRecords); $i++) {
                $q->orWhere('candidateId = ? AND vacancyId = ?', $toBeDeletedRecords[$i]);
            }

            $deleted = $q->execute();
            if ($deleted > 0) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function buildSearchQuery(CandidateSearchParameters $paramObject, $countQuery = false) {

        try {
            $query = ($countQuery) ? "SELECT COUNT(*)" : "SELECT jc.id, jc.first_name, jc.middle_name, jc.last_name, jc.date_of_application, jcv.status, jv.name, e.emp_firstname, e.emp_middle_name, e.emp_lastname, e.termination_id, jv.status as vacancyStatus, jv.id as vacancyId, ca.id as attachmentId, jc.status as candidateStatus";
            $query .= "  FROM ohrm_job_candidate jc";
            $query .= " LEFT JOIN ohrm_job_candidate_vacancy jcv ON jc.id = jcv.candidate_id";
            $query .= " LEFT JOIN ohrm_job_vacancy jv ON jcv.vacancy_id = jv.id";
            $query .= " LEFT JOIN hs_hr_employee e ON jv.hiring_manager_id = e.emp_number";
            $query .= " LEFT JOIN ohrm_job_candidate_attachment ca ON jc.id = ca.candidate_id";
            $query .= ' WHERE jc.date_of_application  BETWEEN ' . "'{$paramObject->getFromDate()}'" . ' AND ' . "'{$paramObject->getToDate()}'";

            $candidateStatuses = $paramObject->getCandidateStatus();
            if (!empty($candidateStatuses)) {
                $query .= " AND jc.status IN (" . implode(",", $candidateStatuses) . ")";
            }

            $query .= $this->_buildKeywordsQueryClause($paramObject->getKeywords());
            $query .= $this->_buildAdditionalWhereClauses($paramObject);
            $query .= " ORDER BY " . $this->_buildSortQueryClause($paramObject->getSortField(), $paramObject->getSortOrder());
            if (!$countQuery) {
                $query .= " LIMIT " . $paramObject->getOffset() . ", " . $paramObject->getLimit();
            }
            return $query;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param array $keywords
     * @return string
     */
    private function _buildKeywordsQueryClause($keywords) {
        $keywordsQueryClause = '';
        if (!empty($keywords)) {
            $keywords = str_replace("'", "\'", $keywords);
            $words = explode(',', $keywords);
            $length = count($words);
            for ($i = 0; $i < $length; $i++) {
                $keywordsQueryClause .= ' AND jc.keywords LIKE ' . "'" . '%' . trim($words[$i]) . '%' . "'";
            }
        }

        return $keywordsQueryClause;
    }

    /**
     *
     * @param string $sortField
     * @param string $sortOrder
     * @return string
     */
    private function _buildSortQueryClause($sortField, $sortOrder) {
        $sortQuery = '';

        if ($sortField == 'jc.first_name') {
            $sortQuery = 'jc.last_name ' . $sortOrder . ', ' . 'jc.first_name ' . $sortOrder;
        } elseif ($sortField == 'e.emp_firstname') {
            $sortQuery = 'e.emp_lastname ' . $sortOrder . ', ' . 'e.emp_firstname ' . $sortOrder;
        } elseif ($sortField == 'jc.date_of_application') {
            $sortQuery = 'jc.date_of_application ' . $sortOrder . ', ' . 'jc.last_name ASC, jc.first_name ASC';
        } else {
            $sortQuery = $sortField . " " . $sortOrder;
        }

        return $sortQuery;
    }

    /**
     * @param CandidateSearchParameters $paramObject
     * @return string
     */
    private function _buildAdditionalWhereClauses(CandidateSearchParameters $paramObject) {

        $allowedCandidateList = $paramObject->getAllowedCandidateList();
        $jobTitleCode = $paramObject->getJobTitleCode();
        $jobVacancyId = $paramObject->getVacancyId();
        $hiringManagerId = $paramObject->getHiringManagerId();
        $status = $paramObject->getStatus();
        $allowedVacancyList = $paramObject->getAllowedVacancyList();
        $isAdmin = $paramObject->getIsAdmin();
        $empNumber = $paramObject->getEmpNumber();

        $whereClause = '';
        $whereFilters = array();
        if ($allowedVacancyList != null && !$isAdmin) {
            $this->_addAdditionalWhereClause($whereFilters, 'jv.id', '(' . implode(',', $allowedVacancyList) . ')', 'IN');
        }
        if ($allowedCandidateList != null && !$isAdmin) {
            $this->_addAdditionalWhereClause($whereFilters, 'jc.id', '(' . implode(',', $allowedCandidateList) . ')', 'IN');
        }
        if (!empty($jobTitleCode) || !empty($jobVacancyId) || !empty($hiringManagerId) || !empty($status)) {
            $this->_addAdditionalWhereClause($whereFilters, 'jv.status', $paramObject->getVacancyStatus());
        }


        $this->_addAdditionalWhereClause($whereFilters, 'jv.job_title_code', $paramObject->getJobTitleCode());
        $this->_addAdditionalWhereClause($whereFilters, 'jv.id', $paramObject->getVacancyId());
        $this->_addAdditionalWhereClause($whereFilters, 'jv.hiring_manager_id', $paramObject->getHiringManagerId());
        $this->_addAdditionalWhereClause($whereFilters, 'jcv.status', $paramObject->getStatus());

        $this->_addCandidateNameClause($whereFilters, $paramObject);

        $this->_addAdditionalWhereClause($whereFilters, 'jc.mode_of_application', $paramObject->getModeOfApplication());


        $whereClause .= ( count($whereFilters) > 0) ? (' AND ' . implode('AND ', $whereFilters)) : '';
        if ($empNumber != null) {
            $whereClause .= "OR jc.id NOT IN (SELECT ojcv.candidate_id FROM ohrm_job_candidate_vacancy ojcv) AND jc.added_person = " . $empNumber;
        }

        return $whereClause;
    }

    /**
     *
     * @param array_pointer $where
     * @param string $field
     * @param mixed $value
     * @param string $operator
     */
    private function _addAdditionalWhereClause(&$where, $field, $value, $operator = '=') {
        if (!empty($value)) {
            if ($operator === '=') {
                $value = "'{$value}'";
            }
            $where[] = "{$field}  {$operator} {$value}";
        }
    }

    /**
     * Add where clause to search by candidate name.
     * 
     * @param type $where Where Clause
     * @param type $paramObject Search Parameter object
     */
    private function _addCandidateNameClause(&$where, $paramObject) {

        // Search by Name
        $candidateName = $paramObject->getCandidateName();

        if (!empty($candidateName)) {

            $candidateFullNameClause = "concat_ws(' ', jc.first_name, " .
                    "IF(jc.middle_name <> '', jc.middle_name, NULL), " .
                    "jc.last_name)";

            // Replace multiple spaces in string with single space
            $candidateName = preg_replace('!\s+!', ' ', $candidateName);
            $candidateName = "'%" . $candidateName . "%'";

            $this->_addAdditionalWhereClause($where, $candidateFullNameClause, $candidateName, 'LIKE');
        }
    }

    public function isHiringManager($candidateVacancyId, $empNumber) {
        try {
            $q = Doctrine_Query :: create()
                    ->select('COUNT(*)')
                    ->from('JobCandidateVacancy jcv')
                    ->leftJoin('jcv.JobVacancy jv')
                    ->where('jcv.id = ?', $candidateVacancyId)
                    ->andWhere('jv.hiringManagerId = ?', $empNumber);

            $count = $q->fetchOne(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
            return ($count > 0);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function isInterviewer($candidateVacancyId, $empNumber) {
        try {
            $q = Doctrine_Query :: create()
                    ->select('COUNT(*)')
                    ->from('JobInterviewInterviewer jii')
                    ->leftJoin('jii.JobInterview ji')
                    ->leftJoin('ji.JobCandidateVacancy jcv')
                    ->where('jcv.id = ?', $candidateVacancyId)
                    ->andWhere('jii.interviewerId = ?', $empNumber);

            $count = $q->fetchOne(array(), Doctrine_Core::HYDRATE_SINGLE_SCALAR);
            return ($count > 0);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Get candidate name suffix according to the candidate status
     * @param integer $statusCode
     * return string $suffix
     */
    private function _getCandidateNameSuffix($statusCode) {

        $suffix = "";

        if ($statusCode == JobCandidate::ARCHIVED) {
            $suffix = " (" . __('Archived') . ")";
        }

        return $suffix;
    }

    public function getCandidateVacancyByCandidateIdAndVacancyId($candidateId, $vacancyId) {
        try {
            $q = Doctrine_Query :: create()
                    ->from('JobCandidateVacancy jcv')
                    ->where('jcv.candidateId = ?', $candidateId)
                    ->andWhere('jcv.vacancyId = ?', $vacancyId);
            return $q->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

}
