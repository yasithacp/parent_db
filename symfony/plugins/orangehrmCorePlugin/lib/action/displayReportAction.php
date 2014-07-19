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

abstract class displayReportAction extends sfAction {

    private $confFactory;
    private $form;
    protected $reportName = 'pim-report';
    protected $reportTitle = 'PIM Report';
    
    /**
     *
     * @return string
     */
    public function getReportName() {
        return $this->reportName;
    }

    /**
     *
     * @param string $reportName 
     */
    public function setReportName($reportName) {
        $this->reportName = $reportName;
    }

    /**
     *
     * @return string
     */
    public function getReportTitle() {
        return $this->reportTitle;
    }

    /**
     *
     * @param string $reportTitle 
     */
    public function setReportTitle($reportTitle) {
        $this->reportTitle = $reportTitle;
    }

    
    public function execute($request) {

        $reportId = $request->getParameter("reportId");
        $backRequest = $request->getParameter("backRequest");

        $reportableGeneratorService = new ReportGeneratorService();

        $sql = $request->getParameter("sql");

        $reportableService = new ReportableService();
        $this->report = $reportableService->getReport($reportId);

        if (empty($this->report)) {
            return $this->renderText(__('Invalid Report Specified'));
        }

        $useFilterField = $this->report->getUseFilterField();
        if (!$useFilterField) {

            $this->setCriteriaForm();
            if ($request->isMethod('post')) {

                $this->form->bind($request->getParameter($this->form->getName()));

                if ($this->form->isValid()) {
                    $reportGeneratorService = new ReportGeneratorService();
                    $formValues = $this->form->getValues();
                    $this->setReportCriteriaInfoInRequest($formValues);
                    $sql = $reportGeneratorService->generateSqlForNotUseFilterFieldReports($reportId, $formValues);
                }
            }
        } else {

            if ($request->isMethod("get")) {
                $reportGeneratorService = new ReportGeneratorService();
//                $selectedRuntimeFilterFieldList = $reportGeneratorService->getSelectedRuntimeFilterFields($reportId);

                $selectedFilterFieldList = $reportableService->getSelectedFilterFields($reportId, false);
                
                $values = $this->setValues();

//                $linkedFilterFieldIdsAndFormValues = $reportGeneratorService->linkFilterFieldIdsToFormValues($selectedRuntimeFilterFieldList, $values);
//                $runtimeWhereClauseConditionArray = $reportGeneratorService->generateWhereClauseConditionArray($linkedFilterFieldIdsAndFormValues);

                $runtimeWhereClauseConditionArray = $reportGeneratorService->generateWhereClauseConditionArray($selectedFilterFieldList, $values);
                $sql = $reportGeneratorService->generateSql($reportId, $runtimeWhereClauseConditionArray);
            }
        }

        $paramArray = array();

        if ($reportId == 1) {
            if (!isset($backRequest)) {
                $this->getUser()->setAttribute("reportCriteriaSql", $sql);
                $this->getUser()->setAttribute("parametersForListComponent", $this->setParametersForListComponent());
            }
            if (isset($backRequest) && $this->getUser()->hasAttribute("reportCriteriaSql")) {
                $sql = $this->getUser()->getAttribute("reportCriteriaSql");
                $paramArray = $this->getUser()->getAttribute("parametersForListComponent");
            }
        }


        $params = (!empty($paramArray)) ? $paramArray : $this->setParametersForListComponent();
        $rawDataSet = $reportableGeneratorService->generateReportDataSet($reportId, $sql);

        $dataSet = self::escapeData($rawDataSet);
        
        $headerGroups = $reportableGeneratorService->getHeaderGroups($reportId);

        $this->setConfigurationFactory();
        $configurationFactory = $this->getConfFactory();
        $configurationFactory->setHeaderGroups($headerGroups);

        if ($reportId == 3) {
            if (empty($dataSet[0]['employeeName']) && $dataSet[0]['totalduration'] == 0) {
                $dataSet = null;
            }
        }

        ohrmListComponent::setConfigurationFactory($configurationFactory);

        $this->setListHeaderPartial();

        ohrmListComponent::setListData($dataSet);

        $this->parmetersForListComponent = $params;
        
        $this->initilizeDataRetriever($configurationFactory, $reportableGeneratorService, 'generateReportDataSet', array($reportId, $sql));
    }

    abstract public function setParametersForListComponent();

    abstract public function setConfigurationFactory();

    abstract public function setListHeaderPartial();

    abstract public function setValues();

    public function getConfFactory() {

        return $this->confFactory;
    }

    public function setConfFactory(ListConfigurationFactory $configurationFactory) {

        $this->confFactory = $configurationFactory;
    }

    public function setReportCriteriaInfoInRequest($formValues) {
        
    }

    public function setCriteriaForm() {
        
    }

    public function setForm($form) {
        $this->form = $form;
    }
    
    public function initilizeDataRetriever(ohrmListConfigurationFactory $configurationFactory, BaseService $dataRetrievalService, $dataRetrievalMethod, array $dataRetrievalParams) {
        $dataRetriever = new ExportDataRetriever();
        $dataRetriever->setConfigurationFactory($configurationFactory);
        $dataRetriever->setDataRetrievalService($dataRetrievalService);
        $dataRetriever->setDataRetrievalMethod($dataRetrievalMethod);
        $dataRetriever->setDataRetrievalParams($dataRetrievalParams);

        $this->getUser()->setAttribute('persistant.exportDataRetriever', $dataRetriever);
        $this->getUser()->setAttribute('persistant.exportFileName', $this->getReportName());
        $this->getUser()->setAttribute('persistant.exportDocumentTitle', $this->getReportTitle());
        $this->getUser()->setAttribute('persistant.exportDocumentDescription', 'Generated at ' . date('Y-m-d H:i'));
    }
    
    public function escapeData($data) {
        if (is_array($data)) {
            $escapedArray = array();
            foreach ($data as $key => $rawData) {
                $escapedArray[$key] = self::escapeData($rawData);
            }
            return $escapedArray;
        } else {
            return htmlspecialchars($data);
        } 
    }

}