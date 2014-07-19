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

class ViewJobVacancyForm extends BaseForm {

    private $candidateService;
    private $vacancyService;
    private $allowedVacancyList;
    private $jobTitleService;

    public function getJobTitleService() {
        if (is_null($this->jobTitleService)) {
            $this->jobTitleService = new JobTitleService();
            $this->jobTitleService->setJobTitleDao(new JobTitleDao());
        }
        return $this->jobTitleService;
    }

    /**
     * Get CandidateService
     * @returns CandidateService
     */
    public function getCandidateService() {
        if (is_null($this->candidateService)) {
            $this->candidateService = new CandidateService();
            $this->candidateService->setCandidateDao(new CandidateDao());
        }
        return $this->candidateService;
    }

    /**
     * Set CandidateService
     * @param CandidateService $candidateService
     */
    public function setCandidateService(CandidateService $candidateService) {
        $this->candidateService = $candidateService;
    }

    /**
     * Get VacancyService
     * @returns VacncyService
     */
    public function getVacancyService() {
        if (is_null($this->vacancyService)) {
            $this->vacancyService = new VacancyService();
            $this->vacancyService->setVacancyDao(new VacancyDao());
        }
        return $this->vacancyService;
    }

    /**
     * Set VacancyService
     * @param VacancyService $vacancyService
     */
    public function setVacancyService(VacancyService $vacancyService) {
        $this->vacancyService = $vacancyService;
    }

    /**
     * 
     */
    public function configure() {

        $this->allowedVacancyList = $this->getOption('allowedVacancyList');
        $hiringManagerList = $this->getHiringManagersList();
        $jobTitleList = $this->getJobTitleList();
        $statusList = $this->getStatusList();
        $jobVacancyList = $this->getVacancyList();

        $this->setWidgets(array(
            'jobTitle' => new sfWidgetFormSelect(array('choices' => $jobTitleList)),
            'jobVacancy' => new sfWidgetFormSelect(array('choices' => $jobVacancyList)),
            'hiringManager' => new sfWidgetFormSelect(array('choices' => $hiringManagerList)),
            'status' => new sfWidgetFormSelect(array('choices' => $statusList)),
        ));

        $this->setValidators(array(
            'jobTitle' => new sfValidatorString(array('required' => false)),
            'jobVacancy' => new sfValidatorString(array('required' => false)),
            'hiringManager' => new sfValidatorString(array('required' => false)),
            'status' => new sfValidatorString(array('required' => false)),
        ));

        $this->widgetSchema->setNameFormat('vacancySearch[%s]');
    }

    /**
     *
     * @param <type> $searchParam 
     */
    public function setDefaultDataToWidgets($searchParam) {
        $this->setDefault('jobTitle', $searchParam['jobTitle']);
        $this->setDefault('jobVacancy', $searchParam['jobVacancy']);
        $this->setDefault('hiringManager', $searchParam['hiringManager']);
        $this->setDefault('status', $searchParam['status']);
    }

    /**
     * Returns HiringManager List
     * @return array
     */
    private function getHiringManagersList() {

        $list = array("" => __('All'));
        $hiringManagersList = $this->getVacancyService()->getHiringManagersList("", "", $this->allowedVacancyList);
        foreach ($hiringManagersList as $hiringManager) {
            $list[$hiringManager['id']] = $hiringManager['name'];
        }

        return $list;
    }

    /**
     * Returns job Title List
     * @return array
     */
    private function getJobTitleList() {
        $jobTitleList = $this->getJobTitleService()->getJobTitleList();
        $list = array("" => __('All'));
        foreach ($jobTitleList as $jobTitle) {
            $list[$jobTitle->getId()] = $jobTitle->getJobTitleName();
        }
        return $list;
    }

    /**
     * Make status List
     * @return array
     */
    private function getStatusList() {
        $list = array("" => __('All'), JobVacancy::ACTIVE => __('Active'), JobVacancy::CLOSED => __("Closed"));
        return $list;
    }

    /**
     * Returns Vacancy List
     * @return array
     */
    private function getVacancyList() {
        $list = array("" => __('All'));
        $vacancyList = $this->getVacancyService()->getVacancyList();
        foreach ($vacancyList as $vacancy) {
            $list[$vacancy->getId()] = $vacancy->getName();
        }
        return $list;
    }

    /**
     *
     * @return <type>
     */
    public function getSearchParamsBindwithFormData() {

        $srchParams = array('jobTitle' => $this->getValue('jobTitle'),
            'jobVacancy' => $this->getValue('jobVacancy'),
            'hiringManager' => $this->getValue('hiringManager'),
            'status' => $this->getValue('status'));

        return $srchParams;
    }

}
