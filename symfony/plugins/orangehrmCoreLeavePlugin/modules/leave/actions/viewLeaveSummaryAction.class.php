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


class viewLeaveSummaryAction extends sfAction implements ohrmExportableAction {

    protected $employeeService;

    /**
     * @param sfForm $form
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    /**
     * Get EmployeeService
     * @return EmployeeService object
     */
    public function getEmployeeService() {
        if (is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
        }
        return $this->employeeService;
    }

    /**
     * Sets EmployeeService
     * @param EmployeeService $service
     */
    public function setEmployeeService(EmployeeService $service) {
        $this->employeeService = $service;
    }

    /**
     * Get instance of form used by this action.
     * Allows subclasses to override the form class used in the action.
     *
     * See: sfForm::__construct for parameter description
     */
    protected function getFormInstance($defaults = array(), $options = array(), $CSRFSecret = null) {
        return new LeaveSummaryForm($defaults, $options, $CSRFSecret);
    }

    public function execute($request) {
        $userDetails = $this->getLoggedInUserDetails();

        $this->templateMessage = $this->getUser()->getFlash('templateMessage', array('', ''));

        $searchParam = array();
        $searchParam['employeeId'] = (trim($request->getParameter("employeeId")) != "") ? trim($request->getParameter("employeeId")) : null;
        if (!is_null($searchParam['employeeId'])) {
            $terminationId = $this->getEmployeeService()->getEmployee($searchParam['employeeId'])->getTerminationId();
            if (!empty($terminationId)) {
                $searchParam['cmbWithTerminated'] = 'on';
            } else {
                $searchParam['cmbWithTerminated'] = 0;
            }
        }
        $params = array_merge($searchParam, $userDetails);

        $this->setForm($this->getFormInstance(array(), $params, true));
        $this->setLeaveSummaryRecordsLimit($request);
        $this->form->setRecordsLimitDefaultValue();

        if ($request->isMethod(sfRequest::POST)) {
            $this->searchFlag = 1;
            $this->form->bind($request->getParameter($this->form->getName()));
        }

        $this->form->recordsCount = $this->form->getLeaveSummaryRecordsCount();
        $this->form->setPager($request);

        $permissions = $this->getContext()->get('screen_permissions');        
        LeaveSummaryConfigurationFactory::setPermissions($permissions);

        $leaveSummaryService = new LeaveSummaryService();
        $leaveSummaryDao = new LeaveSummaryDao();
        $configurationFactory = new LeaveSummaryConfigurationFactory();

        $leaveSummaryService->setLeaveSummaryDao($leaveSummaryDao);

        $clues = $this->form->getSearchClues();
        $clues['loggedUserId'] = $userDetails['loggedUserId'];
        if (!is_null($searchParam['employeeId'])) {
            $terminationId = $this->getEmployeeService()->getEmployee($searchParam['employeeId'])->getTerminationId();
            $empName = $this->getEmployeeService()->getEmployee($searchParam['employeeId'])->getFirstAndLastNames();            
            if (!empty($empName)) {
                $clues['txtEmpName'] = $empName;
                $this->form->setDefault('txtEmpName', array('empName' => $empName, 'empId' => $searchParam['employeeId']));
            }
            
            if (!empty($terminationId)) {
                $clues['cmbWithTerminated'] = 'on';
                $this->form->setDefault('cmbWithTerminated', true);
            } else {
                $this->form->setDefault('cmbWithTerminated', false);
                $clues['cmbWithTerminated'] = 0;
            }
        }

        $noOfRecords = isset($clues['cmbRecordsCount']) ? (int) $clues['cmbRecordsCount'] : $this->form->recordsLimit;
        $pageNo = $request->getParameter('hdnAction') == 'search' ? 1 : $request->getParameter('pageNo', 1);
        $offset = ($pageNo - 1) * $noOfRecords;

        $listData = $leaveSummaryService->fetchRawLeaveSummaryRecords($clues, $offset, $noOfRecords);
        $totalRecordsCount = $leaveSummaryService->fetchRawLeaveSummaryRecordsCount($clues);

        $listComponentParameters = new ListCompnentParameterHolder();
        $listComponentParameters->populateByArray(array(
            'configurationFactory' => $configurationFactory,
            'listData' => $listData,
            'noOfRecords' => $noOfRecords,
            'totalRecordsCount' => $totalRecordsCount,
            'pageNumber' => $pageNo,
        ));
        $this->initializeListComponent($listComponentParameters);

        $this->initilizeDataRetriever($configurationFactory, $leaveSummaryService, 'fetchRawLeaveSummaryRecords', array($this->form->getSearchClues(),
            0,
            $totalRecordsCount
        ));

        if (isset($this->form->recordsCount) && $this->form->recordsCount == 0 && isset($this->searchFlag) && $this->searchFlag == 1) {
            $this->templateMessage = array('NOTICE', __(TopLevelMessages::NO_RECORDS_FOUND));
        }

    }

    /**
     *
     * @param ListCompnentParameterHolder $parameters
     */
    protected function initializeListComponent(ListCompnentParameterHolder $parameters) {
        ohrmListComponent::setConfigurationFactory($parameters->getConfigurationFactory());
        ohrmListComponent::setActivePlugin('orangehrmCoreLeavePlugin');
        ohrmListComponent::setListData($parameters->getListData());
        ohrmListComponent::setItemsPerPage($parameters->getNoOfRecords());
        ohrmListComponent::setNumberOfRecords($parameters->getTotalRecordsCount());
        ohrmListComponent::$pageNumber = $parameters->getPageNumber();
    }

    /**
     * Returns Logged in user details
     * @return array
     */
    protected function getLoggedInUserDetails() {
        $userDetails = array();

        /* Value 0 is assigned for default admin */
        $userDetails['loggedUserId'] = (empty($_SESSION['empNumber'])) ? 0 : $_SESSION['empNumber'];
        $userDetails['empId'] = (empty($_SESSION['empID'])) ? 0 : $_SESSION['empID'];
        
        return $userDetails;
    }

    protected function setLeaveSummaryRecordsLimit($request) {

        $params = $request->getParameter('leaveSummary');

        if (isset($params['cmbRecordsCount'])) {
            $this->form->recordsLimit = $params['cmbRecordsCount'];
            $this->getUser()->setAttribute('leaveSummaryLimit', $this->form->recordsLimit);
        } elseif ($this->getUser()->hasAttribute('leaveSummaryLimit')) {
            $this->form->recordsLimit = $this->getUser()->getAttribute('leaveSummaryLimit');
        }
    }

    /**
     * Sets user details for testing purposes
     */
    public function setUserDetails($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function initilizeDataRetriever(ohrmListConfigurationFactory $configurationFactory, BaseService $dataRetrievalService, $dataRetrievalMethod, array $dataRetrievalParams) {
        $dataRetriever = new ExportDataRetriever();
        $dataRetriever->setConfigurationFactory($configurationFactory);
        $dataRetriever->setDataRetrievalService($dataRetrievalService);
        $dataRetriever->setDataRetrievalMethod($dataRetrievalMethod);
        $dataRetriever->setDataRetrievalParams($dataRetrievalParams);

        $this->getUser()->setAttribute('persistant.exportDataRetriever', $dataRetriever);
        $this->getUser()->setAttribute('persistant.exportFileName', 'leave-summary');
        $this->getUser()->setAttribute('persistant.exportDocumentTitle', 'Leave Summary');
        $this->getUser()->setAttribute('persistant.exportDocumentDescription', 'Generated at ' . date('Y-m-d H:i'));
    }

}

