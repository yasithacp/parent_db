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
 * Vacancy Service
 *
 */
class VacancyService extends BaseService {

    private $vacancyDao;

    /**
     * Get Vacancy Dao
     * @return VacancyDao
     */
    public function getVacancyDao() {
        return $this->vacancyDao;
    }

    /**
     * Set Vacancy Dao
     * @param VacancyDao $vacancyDao
     * @return void
     */
    public function setVacancyDao(VacancyDao $vacancyDao) {
        $this->vacancyDao = $vacancyDao;
    }

    /**
     * Construct
     */
    public function __construct() {
        $this->vacancyDao = new VacancyDao();
    }

    /**
     * Retrieve hiring managers list
     * @returns array
     * @throws RecruitmentException
     */
    public function getHiringManagersList($jobTitle, $vacancyId, $allowedVacancyList) {
        return $this->getVacancyDao()->getHiringManagersList($jobTitle, $vacancyId, $allowedVacancyList);
    }

    /**
     * Retrieve hiring managers list
     * @returns array
     * @throws RecruitmentException
     */
    public function getVacancyListForJobTitle($jobTitle, $allowedVacancyList, $asArray = false) {
        return $this->getVacancyDao()->getVacancyListForJobTitle($jobTitle, $allowedVacancyList, $asArray);
    }

    /**
     * Retrieve vacancy list
     * @returns array
     * @throws RecruitmentException
     */
    public function getVacancyList() {
        return $this->getVacancyDao()->getVacancyList();
    }

    /**
     * Retrieve vacancy list
     * @returns array
     * @throws RecruitmentException
     */
    public function getAllVacancies($status = "") {
        return $this->getVacancyDao()->getAllVacancies($status);
    }

    /**
     * Get list of vacancies published to web/rss
     * 
     * @return type Array of JobVacancy objects
     * @throws RecruitmentException
     */
    public function getPublishedVacancies() {
        return $this->getVacancyDao()->getPublishedVacancies();
    }

    /**
     * Retrieve vacancy list
     * @returns array
     * @throws RecruitmentException
     */
    public function saveJobVacancy(JobVacancy $jobVacancy) {
        return $this->getVacancyDao()->saveJobVacancy($jobVacancy);
    }

    /**
     *
     * @param <type> $srchParams
     * @return doctrine collection
     */
    public function searchVacancies($srchParams) {
        return $this->getVacancyDao()->searchVacancies($srchParams);
    }

    /**
     *
     * @param <type> $srchParams
     * @return count
     */
    public function searchVacanciesCount($srchParams) {
        return $this->getVacancyDao()->searchVacanciesCount($srchParams);
    }

    /**
     *
     */
    public function getVacancyById($vacancyId) {
        return $this->getVacancyDao()->getVacancyById($vacancyId);
    }

    /**
     * Delete vacancies
     * @param array $toBeDeletedVacancyIds
     * @return boolean
     */
    public function deleteVacancies($toBeDeletedVacancyIds) {

        if (count($toBeDeletedVacancyIds) > 0) {

            $isDeletionSucceeded = $this->getVacancyDao()->deleteVacancies($toBeDeletedVacancyIds);

            if ($isDeletionSucceeded) {
                return true;
            }
        }

        return false;
    }

    public function getVacancyListForUserRole($role, $empNumber) {
        return $this->getVacancyDao()->getVacancyListForUserRole($role, $empNumber);
    }

    /**
     *
     * @param int $empNumber
     * @return bool 
     */
    public function isHiringManager($empNumber) {
        try {
            $results = $this->searchVacancies(array(
                'jobTitle' => null,
                'jobVacancy' => null,
                'status' => null,
                'hiringManager' => $empNumber,
                'offset' => 0,
                'noOfRecords' => 1,
                    ));

            return ($results->count() > 0);
        } catch (DaoException $e) {
            // TODO: Warn
            return false;
        }
    }
    
    /**
     *
     * @param int $empNumber
     * @return bool 
     */
    public function isInterviewer($empNumber) {
        try {
            $result = $this->getVacancyDao()->searchInterviews($empNumber);
            return ($result->count() > 0);
        } catch (DaoException $e) {
            // TODO: Warn
            return false;
        }
    }

}

