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
class ViewCandidateActionForm extends BaseForm {

    private $candidateService;
    public $candidateId;
    public $candidate;
    public $empNumber;
    private $isAdmin;

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

    public function getInterviewService() {
        if (is_null($this->interviewService)) {
            $this->interviewService = new JobInterviewService();
            $this->interviewService->setJobInterviewDao(new JobInterviewDao());
        }
        return $this->interviewService;
    }

    /**
     *
     */
    public function configure() {

        $this->candidateId = $this->getOption('candidateId');
        $this->empNumber = $this->getOption('empNumber');
        $this->isAdmin = $this->getOption('isAdmin');
        if ($this->candidateId > 0) {
            $this->candidate = $this->getCandidateService()->getCandidateById($this->candidateId);
            $existingVacancyList = $this->candidate->getJobCandidateVacancy();
            if ($existingVacancyList[0]->getVacancyId() > 0) {
                $userObj = new User();
                $userRoleDecorator = new SimpleUserRoleFactory();
                $userRoleArray = array();
                foreach ($existingVacancyList as $candidateVacancy) {
                    $userRoleArray['isHiringManager'] = $this->getCandidateService()->isHiringManager($candidateVacancy->getId(), $this->empNumber);
                    $userRoleArray['isInterviewer'] = $this->getCandidateService()->isInterviewer($candidateVacancy->getId(), $this->empNumber);
                    if ($this->isAdmin) {
                        $userRoleArray['isAdmin'] = true;
                    }
                    $newlyDecoratedUserObj = $userRoleDecorator->decorateUserRole($userObj, $userRoleArray);
                    $choicesList = $this->getCandidateService()->getNextActionsForCandidateVacancy($candidateVacancy->getStatus(), $newlyDecoratedUserObj);
                    $interviewCount = count($this->getInterviewService()->getInterviewsByCandidateVacancyId($candidateVacancy->getId()));
                    if ($interviewCount == JobInterview::NO_OF_INTERVIEWS) {
                        unset($choicesList[WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_SHEDULE_INTERVIEW]);
                    }
                    if ($candidateVacancy->getJobVacancy()->getStatus() == JobVacancy::CLOSED) {
                        $choicesList = array("" => __("No Actions"));
                    }
                    $widgetName = $candidateVacancy->getId();
                    $this->setWidget($widgetName, new sfWidgetFormSelect(array('choices' => $choicesList)));
                    $this->setValidator($widgetName, new sfValidatorString(array('required' => false)));
                }
            }
        }
        $this->widgetSchema->setNameFormat('viewCandidateAction[%s]');
    }

}

