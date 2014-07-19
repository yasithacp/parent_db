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
 * Form class for employee list in PIM
 */
class EmployeeSearchForm extends BaseForm {

    private $companyStructureService;
    private $jobService;
    private $jobTitleService;
    private $empStatusService;

    const WITHOUT_TERMINATED = 1;
    const WITH_TERMINATED = 2;
    const ONLY_TERMINATED = 3;

    public function getEmploymentStatusService() {
        if (is_null($this->empStatusService)) {
            $this->empStatusService = new EmploymentStatusService();
            $this->empStatusService->setEmploymentStatusDao(new EmploymentStatusDao());
        }
        return $this->empStatusService;
    }

    public function getJobTitleService() {
        if (is_null($this->jobTitleService)) {
            $this->jobTitleService = new JobTitleService();
            $this->jobTitleService->setJobTitleDao(new JobTitleDao());
        }
        return $this->jobTitleService;
    }

    public function configure() {

        $this->setWidgets(array(
            'employee_name' => new ohrmWidgetEmployeeNameAutoFill(array('loadingMethod'=>'ajax')),
            'id' => new sfWidgetFormInputText(),
        ));

//        $this->_setEmployeeStatusWidget();

        $this->_setTerminatedEmployeeWidget();

//        $this->setWidget('supervisor_name', new sfWidgetFormInputText());
//        $this->setValidator('supervisor_name', new sfValidatorString(array('required' => false)));

        /* Setting job titles */
//        $this->_setJobTitleWidget();

        /* Setting sub divisions */
//        $this->_setSubunitWidget();


        $this->setValidator('employee_name', new ohrmValidatorEmployeeNameAutoFill());
        $this->setValidator('id', new sfValidatorString(array('required' => false)));

        $formExtension  =   PluginFormMergeManager::instance();
        $formExtension->mergeForms( $this,'viewEmployeeList','EmployeeSearchForm');

        
        $this->widgetSchema->setNameFormat('empsearch[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        sfWidgetFormSchemaFormatterBreakTags::setNoOfColumns(4);
        $this->getWidgetSchema()->setFormFormatterName('BreakTags');

    }

    public function getSupervisorListAsJson() {

        $jsonArray = array();
        $employeeService = new EmployeeService();
        $employeeService->setEmployeeDao(new EmployeeDao());

        $employeeList = $employeeService->getSupervisorList();

        foreach ($employeeList as $employee) {

            $name = $employee->getFirstName() . " " . $employee->getMiddleName();
            $name = trim(trim($name) . " " . $employee->getLastName());

            $jsonArray[] = array('name' => $name, 'id' => $employee->getEmpNumber());
        }

        $jsonString = json_encode($jsonArray);

        return $jsonString;
    }

    public function getJobService() {
        if (is_null($this->jobService)) {
            $this->jobService = new JobService();
            $this->jobService->setJobDao(new JobDao());
        }
        return $this->jobService;
    }

    public function setJobService(JobService $jobService) {
        $this->jobService = $jobService;
    }

    private function _setJobTitleWidget() {

        $jobTitleList = $this->getJobTitleService()->getJobTitleList();
        $choices = array('0' => __('All'));

        foreach ($jobTitleList as $job) {
            $choices[$job->getId()] = $job->getJobTitleName();
        }

        $this->setWidget('job_title', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->setValidator('job_title', new sfValidatorChoice(array('choices' => array_keys($choices))));
    }

    private function _setEmployeeStatusWidget() {

        $empStatusService = $this->getEmploymentStatusService();
        $statusList = $empStatusService->getEmploymentStatusList();
        $choices = array('0' => __('All'));

        foreach ($statusList as $status) {
            $choices[$status->getId()] = $status->getName();
        }

        $this->setWidget('employee_status', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->setValidator('employee_status', new sfValidatorChoice(array('choices' => array_keys($choices))));
    }

    public function getCompanyStructureService() {
        if (is_null($this->companyStructureService)) {
            $this->companyStructureService = new CompanyStructureService();
            $this->companyStructureService->setCompanyStructureDao(new CompanyStructureDao());
        }
        return $this->companyStructureService;
    }

    public function setCompanyStructureService(CompanyStructureService $companyStructureService) {
        $this->companyStructureService = $companyStructureService;
    }

    private function _setSubunitWidget() {

        $subUnitList = array(0 => __("All"));
        $treeObject = $this->getCompanyStructureService()->getSubunitTreeObject();

        $tree = $treeObject->fetchTree();

        foreach ($tree as $node) {
            if ($node->getId() != 1) {
                $subUnitList[$node->getId()] = str_repeat('&nbsp;&nbsp;', $node['level'] - 1) . $node['name'];
            }
        }
        $this->setWidget('sub_unit', new sfWidgetFormChoice(array('choices' => $subUnitList)));
        $this->setValidator('sub_unit', new sfValidatorChoice(array('choices' => array_keys($subUnitList))));
    }

    private function _setTerminatedEmployeeWidget() {
        $terminateSelection = array(self::WITHOUT_TERMINATED => __('Current Employees Only'), self::WITH_TERMINATED => __('Current and Past Employees'), self::ONLY_TERMINATED => __('Past Employees Only'));
        $this->setWidget('termination', new sfWidgetFormChoice(array('choices' => $terminateSelection)));
        $this->setValidator('termination', new sfValidatorChoice(array('choices' => array_keys($terminateSelection))));
    }

    /**
     *
     * @return array
     */
    protected function getFormLabels() {

        $labels = array(
            'employee_name' => __('Employee Name'),
            'id' => __('Id'),
            'employee_status' => __('Employment Status'),
            'termination' => __('Include'),
            'supervisor_name' => __('Supervisor Name'),
            'job_title' => __('Job Title'),
            'sub_unit' => __('Sub Unit')
        );
        return $labels;
    }

}

