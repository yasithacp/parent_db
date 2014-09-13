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

class jobInterviewAction extends sfAction {

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
    public function getJobInterviewService() {
        if (is_null($this->jobInterviewService)) {
            $this->jobInterviewService = new JobInterviewService();
            $this->jobInterviewService->setJobInterviewDao(new JobInterviewDao());
        }
        return $this->jobInterviewService;
    }

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
        $allowedCandidateList = $usrObj->getAllowedCandidateList();
        $allowedVacancyList = $usrObj->getAllowedVacancyList();
        $empNumber = $usrObj->getEmployeeNumber();

        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }

        $this->historyId = $request->getParameter('historyId');
        $this->interviewId = $request->getParameter('interviewId');
        $candidateVacancyId = $request->getParameter('candidateVacancyId');
        $selectedAction = $request->getParameter('selectedAction');
        $this->editHiringManager = true;

        $param = array();
        if ($candidateVacancyId > 0 && $selectedAction != "") {
            $interviewHistory = $this->getJobInterviewService()->getInterviewScheduledHistoryByInterviewId($this->interviewId);
            $param = array('interviewId' => $this->interviewId, 'candidateVacancyId' => $candidateVacancyId, 'selectedAction' => $selectedAction, 'historyId' => (!empty($interviewHistory)) ? $interviewHistory->getId() : null);
        }

        if (!empty($this->historyId) && !empty($this->interviewId)) {
            $history = $this->getCandidateService()->getCandidateHistoryById($this->historyId);
            $candidateVacancyId = $this->getCandidateService()->getCandidateVacancyByCandidateIdAndVacancyId($history->getCandidateId(), $history->getVacancyId());
            $selectedAction = $history->getAction();
            $param = array('id' => $this->interviewId, 'candidateVacancyId' => $candidateVacancyId, 'selectedAction' => $selectedAction);
        }
        if (!$this->getCandidateService()->isHiringManager($candidateVacancyId, $empNumber) && $this->getCandidateService()->isInterviewer($candidateVacancyId, $empNumber)) {
            $this->editHiringManager = false;
        }
//        $lastAction = $this->getCandidateService()->getLastPerformedActionByCandidateVAcancyId($candidateVacancyId);
        $this->setForm(new JobInterviewForm(array(), $param, true));

        if (!in_array($this->form->candidateId, $allowedCandidateList) && !in_array($this->form->vacancyId, $allowedVacancyList)) {
            $this->redirect('recruitment/viewCandidates');
        }

        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $result = $this->form->save();
                if (isset($result['messageType'])) {
                    $this->getUser()->setFlash('templateMessage', array($result['messageType'], $result['message']));
                } else {
                    $this->getUser()->setFlash('templateMessage', array('success', __('Successfully Scheduled')));
                }
                $this->redirect('recruitment/changeCandidateVacancyStatus?id=' . $this->form->historyId);
            }
        }
    }

}

