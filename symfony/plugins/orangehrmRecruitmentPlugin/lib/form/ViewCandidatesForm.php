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
 * Form class for search candidates
 */
class viewCandidatesForm extends BaseForm {

    private $candidateService;
    private $vacancyService;
    private $allowedCandidateList;
    private $allowedVacancyList;
    public $allowedCandidateListToDelete;
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

        $this->allowedCandidateList = $this->getOption('allowedCandidateList');
        $this->allowedVacancyList = $this->getOption('allowedVacancyList');
        $this->allowedCandidateListToDelete = $this->getOption('allowedCandidateListToDelete');
        $jobVacancyList = $this->getVacancyList();
        $modeOfApplication = array('' => __('All'), JobCandidate::MODE_OF_APPLICATION_MANUAL => __('Manual'), JobCandidate::MODE_OF_APPLICATION_ONLINE => __('Online'));
        $hiringManagerList = $this->getHiringManagersList();
        $jobTitleList = $this->getJobTitleList();
        $statusList = $this->getStatusList();
        //creating widgets
        $this->setWidgets(array(
            'jobTitle' => new sfWidgetFormSelect(array('choices' => $jobTitleList)),
            'jobVacancy' => new sfWidgetFormSelect(array('choices' => $jobVacancyList)),
            'hiringManager' => new sfWidgetFormSelect(array('choices' => $hiringManagerList)),
            'status' => new sfWidgetFormSelect(array('choices' => $statusList)),
            'candidateName' => new sfWidgetFormInputText(),
            'selectedCandidate' => new sfWidgetFormInputHidden(),
            'keywords' => new sfWidgetFormInputText(),
            'modeOfApplication' => new sfWidgetFormSelect(array('choices' => $modeOfApplication)),
            'fromDate' => new ohrmWidgetDatePickerNew(array(), array('id' => 'candidateSearch_fromDate')),
            'toDate' => new ohrmWidgetDatePickerNew(array(), array('id' => 'candidateSearch_toDate'))
        ));

        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        //Setting validators
        $this->setValidators(array(
            'jobTitle' => new sfValidatorString(array('required' => false)),
            'jobVacancy' => new sfValidatorString(array('required' => false)),
            'hiringManager' => new sfValidatorString(array('required' => false)),
            'status' => new sfValidatorString(array('required' => false)),
            'candidateName' => new sfValidatorString(array('required' => false)),
            'selectedCandidate' => new sfValidatorNumber(array('required' => false, 'min' => 0)),
            'keywords' => new sfValidatorString(array('required' => false)),
            'modeOfApplication' => new sfValidatorString(array('required' => false)),
            'fromDate' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => false),
                    array('invalid' => 'Date format should be ' . $inputDatePattern)),
            'toDate' => new ohrmDateValidator(array('date_format' => $inputDatePattern, 'required' => false),
                    array('invalid' => 'Date format should be ' . $inputDatePattern)),
        ));
        $this->widgetSchema->setNameFormat('candidateSearch[%s]');
    }

    /**
     *
     * @param CandidateSearchParameters $searchParam
     * @return CandidateSearchParameters
     */
    public function getSearchParamsBindwithFormData(CandidateSearchParameters $searchParam) {

        $searchParam->setJobTitleCode($this->getValue('jobTitle'));
        $searchParam->setVacancyId($this->getValue('jobVacancy'));
        $searchParam->setHiringManagerId($this->getValue('hiringManager'));
        $searchParam->setStatus($this->getValue('status'));
        $searchParam->setCandidateId($this->getValue('selectedCandidate'));
        $searchParam->setModeOfApplication($this->getValue('modeOfApplication'));
        $searchParam->setFromDate($this->getValue('fromDate'));
        $searchParam->setToDate($this->getValue('toDate'));
        $searchParam->setKeywords($this->getValue('keywords'));
        $searchParam->setCandidateName($this->getValue('candidateName'));

        return $searchParam;
    }

    /**
     *
     * @param CandidateSearchParameters $searchParam
     */
    public function setDefaultDataToWidgets(CandidateSearchParameters $searchParam) {

        $newSearchParam = new CandidateSearchParameters();

        $this->setDefault('jobTitle', $searchParam->getJobTitleCode());
        $this->setDefault('jobVacancy', $searchParam->getVacancyId());
        $this->setDefault('hiringManager', $searchParam->getHiringManagerId());
        $this->setDefault('status', $searchParam->getStatus());
        $this->setDefault('selectedCandidate', $searchParam->getCandidateId());
        $this->setDefault('modeOfApplication', $searchParam->getModeOfApplication());

        $displayFromDate = ($searchParam->getFromDate() == $newSearchParam->getFromDate()) ? "" : $searchParam->getFromDate();
        $displayToDate = ($searchParam->getToDate() == $newSearchParam->getToDate()) ? "" : $searchParam->getToDate();

        $this->setDefault('fromDate', set_datepicker_date_format($displayFromDate));
        $this->setDefault('toDate', set_datepicker_date_format($displayToDate));
        $this->setDefault('keywords', $searchParam->getKeywords());
        $this->setDefault('candidateName', $searchParam->getCandidateName());
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
        $list = array("" => __('All'));
        $userObj = sfContext::getInstance()->getUser()->getAttribute('user');
        $allowedStates = $userObj->getAllAlowedRecruitmentApplicationStates(PluginWorkflowStateMachine::FLOW_RECRUITMENT);
        $uniqueStatesList = array_unique($allowedStates);
        foreach ($uniqueStatesList as $key => &$value) {
            if ($value == "INITIAL") {
                unset($uniqueStatesList[$key]);
            } else {
                $list[$value] = ucwords(strtolower($value));
            }
        }
        return $list;
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
     * Returns Vacancy List
     * @return array
     */
    private function getVacancyList() {
        $list = array("" => __('All'));
        $vacancyList = $this->getVacancyService()->getAllVacancies();
        foreach ($vacancyList as $vacancy) {
            $list[$vacancy->getId()] = $vacancy->getName();
        }
        return $list;
    }

    /**
     * Returns Action List
     * @return array
     */
    private function getActionList() {

        $list = array("" => __('All'));
        $userObj = sfContext::getInstance()->getUser()->getAttribute('user');
        $allowedActions = $userObj->getAllowedActions(PluginWorkflowStateMachine::FLOW_RECRUITMENT, "");

        foreach ($allowedActions as $action) {
            if ($action != 0) {
                $list[$action] = $this->getActionName($action);
            }
        }
        return $list;
    }

    /**
     * Returns Candidate json list
     * @return JsonCandidate List
     */
    public function getCandidateListAsJson() {

        $jsonArray = array();
        $candidateList = $this->getCandidateService()->getCandidateList($this->allowedCandidateList);
        foreach ($candidateList as $candidate) {

            $name = trim($candidate->getFullName());

            $jsonArray[] = array('name' => $name, 'id' => $candidate->getId());
        }
        $jsonString = json_encode($jsonArray);
        return $jsonString;
    }

}

