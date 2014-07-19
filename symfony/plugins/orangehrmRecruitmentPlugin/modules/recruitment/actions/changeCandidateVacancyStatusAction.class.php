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

class changeCandidateVacancyStatusAction extends sfAction {

    private $performedAction;

    /**
     * @param sfForm $form
     * @return
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    /**
     *
     * @return <type>
     */
    public function getCandidateService() {
        if (is_null($this->candidateService)) {
            $this->candidateService = new CandidateService();
            $this->candidateService->setCandidateDao(new CandidateDao());
        }
        return $this->candidateService;
    }

    /**
     *
     * @param <type> $request
     */
    public function execute($request) {

        $usrObj = $this->getUser()->getAttribute('user');
        if (!($usrObj->isAdmin() || $usrObj->isHiringManager() || $usrObj->isInterviewer())) {
            $this->redirect('pim/viewPersonalDetails');
        }
        $allowedCandidateList = $usrObj->getAllowedCandidateList();
        $allowedVacancyList = $usrObj->getAllowedVacancyList();
        $allowedCandidateListToDelete = $usrObj->getAllowedCandidateListToDelete();
        $this->enableEdit = true;
        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }

        $id = $request->getParameter('id');
        if (!empty($id)) {
            $history = $this->getCandidateService()->getCandidateHistoryById($id);
            $action = $history->getAction();
            $this->interviewId = $history->getInterviewId();
            if ($action == WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_SHEDULE_INTERVIEW || $action == WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_SHEDULE_2ND_INTERVIEW) {
                if ($this->getUser()->hasFlash('templateMessage')) {
                    list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
                    $this->getUser()->setFlash('templateMessage', array($this->messageType, $this->message));
                }
                $this->redirect('recruitment/jobInterview?historyId=' . $id . '&interviewId=' . $this->interviewId);
            }
            $this->performedAction = $action;
            if ($this->getCandidateService()->isInterviewer($this->getCandidateService()->getCandidateVacancyByCandidateIdAndVacancyId($history->getCandidateId(), $history->getVacancyId()), $usrObj->getEmployeeNumber())) {
                $this->enableEdit = false;
                if ($action == WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_MARK_INTERVIEW_PASSED || $action == WorkflowStateMachine::RECRUITMENT_APPLICATION_ACTION_MARK_INTERVIEW_FAILED) {
                    $this->enableEdit = true;
                }
            }
        }
        $candidateVacancyId = $request->getParameter('candidateVacancyId');
        $this->selectedAction = $request->getParameter('selectedAction');
        $param = array();
        if ($id > 0) {
            $param = array('id' => $id);
        }
        if ($candidateVacancyId > 0 && $this->selectedAction != "") {
            $candidateVacancy = $this->getCandidateService()->getCandidateVacancyById($candidateVacancyId);
            $nextActionList = $this->getCandidateService()->getNextActionsForCandidateVacancy($candidateVacancy->getStatus(), $usrObj);
            if ($nextActionList[$this->selectedAction] == "" || !in_array($candidateVacancy->getCandidateId(), $allowedCandidateList)) {
                $this->redirect('recruitment/viewCandidates');
            }
            $param = array('candidateVacancyId' => $candidateVacancyId, 'selectedAction' => $this->selectedAction);
            $this->performedAction = $this->selectedAction;
        }

        $this->setForm(new CandidateVacancyStatusForm(array(), $param, true));
//        if (!in_array($this->form->candidateId, $allowedCandidateList) && !in_array($this->form->vacancyId, $allowedVacancyList)) {
//            $this->redirect('recruitment/viewCandidates');
//        }
//        if (!in_array($this->form->candidateId, $allowedCandidateListToDelete)) {
//            $this->enableEdit = false;
//        }
        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $result = $this->form->performAction();
                if (isset($result['messageType'])) {
                    $this->getUser()->setFlash('templateMessage', array($result['messageType'], $result['message']));
                } else {
                    $message = __(TopLevelMessages::UPDATE_SUCCESS);
                    $this->getUser()->setFlash('templateMessage', array('success', $message));
                }
                $this->redirect('recruitment/changeCandidateVacancyStatus?id=' . $this->form->historyId);
            }
        }
    }
}

