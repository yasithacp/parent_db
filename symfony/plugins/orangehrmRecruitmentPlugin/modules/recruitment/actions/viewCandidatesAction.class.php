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

class viewCandidatesAction extends sfAction {

    private $candidateService;

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
     * @param <type> $request
     */
    public function execute($request) {

        $usrObj = $this->getUser()->getAttribute('user');
        $allowedCandidateList = $usrObj->getAllowedCandidateList();
        $allowedVacancyList = $usrObj->getAllowedVacancyList();
        $allowedCandidateListToDelete = $usrObj->getAllowedCandidateListToDelete();

        $isAdmin = $usrObj->isAdmin();
        if (!($usrObj->isAdmin() || $usrObj->isHiringManager() || $usrObj->isInterviewer())) {
            $this->redirect('pim/viewPersonalDetails');
        }
        $param = array('allowedCandidateList' => $allowedCandidateList, 'allowedVacancyList' => $allowedVacancyList, 'allowedCandidateListToDelete' => $allowedCandidateListToDelete);
        list($this->messageType, $this->message) = $this->getUser()->getFlash('candidateListMessageItems');
        $candidateId = $request->getParameter('candidateId');
        $sortField = $request->getParameter('sortField');
        $sortOrder = $request->getParameter('sortOrder');
        $isPaging = $request->getParameter('pageNo');

        $pageNumber = $isPaging;
        if (!is_null($this->getUser()->getAttribute('pageNumber')) && !($pageNumber >= 1)) {
            $pageNumber = $this->getUser()->getAttribute('pageNumber');
        }
        $this->getUser()->setAttribute('pageNumber', $pageNumber);

        $searchParam = new CandidateSearchParameters();

        $searchParam->setIsAdmin($isAdmin);
        $searchParam->setEmpNumber($usrObj->getEmployeeNumber());
        $noOfRecords = $searchParam->getLimit();
        $offset = ($pageNumber >= 1) ? (($pageNumber - 1) * $noOfRecords) : ($request->getParameter('pageNo', 1) - 1) * $noOfRecords;
        $searchParam->setAdditionalParams($request->getParameter('additionalParams', array()));
        $this->setForm(new viewCandidatesForm(array(), $param, true));
        if (!empty($sortField) && !empty($sortOrder) || $isPaging > 0 || $candidateId > 0) {
            if ($this->getUser()->hasAttribute('searchParameters')) {
                $searchParam = $this->getUser()->getAttribute('searchParameters');
                $this->form->setDefaultDataToWidgets($searchParam);
            }
            $searchParam->setSortField($sortField);
            $searchParam->setSortOrder($sortOrder);
        } else {
            $this->getUser()->setAttribute('searchParameters', $searchParam);
            $offset = 0;
            $pageNumber = 1;
        }
        $searchParam->setAllowedCandidateList($allowedCandidateList);
        $searchParam->setAllowedVacancyList($allowedVacancyList);
        $searchParam->setOffset($offset);
        $candidates = $this->getCandidateService()->searchCandidates($searchParam);
        $this->_setListComponent($usrObj, $candidates, $noOfRecords, $searchParam, $pageNumber);

        $params = array();
        $this->parmetersForListCompoment = $params;
        if (empty($isPaging)) {
            if ($request->isMethod('post')) {

                $pageNumber = 1;
                $searchParam->setOffset(0);
                $this->getUser()->setAttribute('pageNumber', $pageNumber);

                $this->form->bind($request->getParameter($this->form->getName()));
                if ($this->form->isValid()) {
                    $srchParams = $this->form->getSearchParamsBindwithFormData($searchParam);
                    $this->getUser()->setAttribute('searchParameters', $srchParams);
                    $candidates = $this->getCandidateService()->searchCandidates($srchParams);
                    $this->_setListComponent($usrObj, $candidates, $noOfRecords, $searchParam, $pageNumber);
                }
            }
        }
    }

    /**
     *
     * @param <type> $candidates
     * @param <type> $noOfRecords
     * @param CandidateSearchParameters $searchParam
     */
    private function _setListComponent($usrObj, $candidates, $noOfRecords, CandidateSearchParameters $searchParam, $pageNumber) {

        $configurationFactory = new CandidateHeaderFactory();

        if (!($usrObj->isAdmin() || $usrObj->isHiringManager())) {
            $configurationFactory->setRuntimeDefinitions(array(
                'hasSelectableRows' => false,
                'buttons' => array(),
            ));
        }
        ohrmListComponent::setPageNumber($pageNumber);
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        ohrmListComponent::setListData($candidates);
        ohrmListComponent::setItemsPerPage($noOfRecords);
        ohrmListComponent::setNumberOfRecords($this->getCandidateService()->getCandidateRecordsCount($searchParam));
    }

}

