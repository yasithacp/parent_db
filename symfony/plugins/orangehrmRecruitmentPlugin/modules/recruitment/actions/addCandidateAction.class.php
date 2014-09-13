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

class addCandidateAction extends sfAction {

    /**
     * @param sfForm $form
     * @return
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    public function getForm() {
        return $this->form;
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
     * @return <type>
     */
    public function getVacancyService() {
        if (is_null($this->vacancyService)) {
            $this->vacancyService = new VacancyService();
            $this->vacancyService->setVacancyDao(new VacancyDao());
        }
        return $this->vacancyService;
    }

    /**
     *
     * @param <type> $request
     */
    public function execute($request) {

        $userObj = $this->getUser()->getAttribute('user');
        $allowedVacancyList = $userObj->getAllowedVacancyList();
        $allowedCandidateListToDelete = $userObj->getAllowedCandidateListToDelete();
        $this->candidateId = $request->getParameter('id');
        $this->invalidFile = false;
        $reDirect = false;
        $this->edit = true;
        if ($this->candidateId > 0 && !(in_array($this->candidateId, $allowedCandidateListToDelete))) {
            $reDirect = true;
            $this->edit = false;
        }
        $param = array('candidateId' => $this->candidateId, 'allowedVacancyList' => $allowedVacancyList, 'empNumber' => $userObj->getEmployeeNumber(), 'isAdmin' => $userObj->isAdmin());
        $this->setForm(new AddCandidateForm(array(), $param, true));

        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }

        $this->jobVacancyList = $this->getVacancyService()->getAllVacancies();
        $this->candidateStatus = JobCandidate::ACTIVE;
        
        if ($this->candidateId > 0) {
            $allowedCandidateList = $userObj->getAllowedCandidateList();
            if (!in_array($this->candidateId, $allowedCandidateList)) {
                $this->redirect('recruitment/viewCandidates');
            }
            $this->actionForm = new ViewCandidateActionForm(array(), $param, true);
            $allowedHistoryList = $userObj->getAllowedCandidateHistoryList($this->candidateId);

            $candidateHistory = $this->getCandidateService()->getCandidateHistoryForCandidateId($this->candidateId, $allowedHistoryList);
            $candidateHistoryService = new CandidateHistoryService();
            $this->_setListComponent($candidateHistoryService->getCandidateHistoryList($candidateHistory));
            $params = array();
            $this->parmetersForListCompoment = $params;
            $this->candidateStatus = $this->getCandidateService()->getCandidateById($this->candidateId)->getStatus();
        } else {
            if (!($userObj->isAdmin() || $userObj->isHiringManager())) {
                $this->redirect('recruitment/viewCandidates');
            }
        }

        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            $file = $request->getFiles($this->form->getName());

            if (($_FILES['addCandidate']['size']['resume'] > 1024000) || ($_FILES['addCandidate']['error']['resume'] && $_FILES['addCandidate']['name']['resume'])) {
                $title = ($this->candidateId > 0) ? __('Editing Candidate') : __('Adding Candidate');	 
                $this->templateMessage = array('WARNING', '' . __(TopLevelMessages::FILE_SIZE_SAVE_FAILURE));
            } elseif ($_FILES == null) {
                $title = ($this->candidateId > 0) ? __('Editing Candidate') : __('Adding Candidate');
                $this->getUser()->setFlash('templateMessage', array('warning', __(TopLevelMessages::FILE_SIZE_SAVE_FAILURE)));
                $this->redirect('recruitment/addCandidate');
            } else {
                $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
                $file = $request->getFiles($this->form->getName());

                if ($this->form->isValid()) {

                    $result = $this->form->save();

                    if (isset($result['messageType'])) {
                        $this->messageType = $result['messageType'];
                        $this->message = $result['message'];
                        $this->invalidFile = true;
                    } else {
                        $this->candidateId = $result['candidateId'];
                        $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));
                        $this->redirect('recruitment/addCandidate?id=' . $this->candidateId);
                    }
                }
            }
        }
    }

    /**
     *
     * @param <type> $candidateHistory
     */
    private function _setListComponent($candidateHistory) {
        $configurationFactory = new CandidateHistoryHeaderFactory();
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        ohrmListComponent::setListData($candidateHistory);
    }

}
