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
 * Form class for Performance reviews Admin/Reviewer/Employee
 */
class ViewPerformanceReviewForm extends BaseForm {

    private $companyStructureService;

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

    public function configure() {

        $this->setWidgets(array(
            'ReviewPeriodFrom' => new sfWidgetFormInputText(),
            'ReviewPeriodTo' => new sfWidgetFormInputText(),
            'JobTitle' => new sfWidgetFormDoctrineChoice(array('model' => 'JobTitle', 'add_empty' => '- Select -')),
            'SubUnit' => new sfWidgetFormChoice(array('choices' => $this->__getSubunitList())),
            'Employee' => new sfWidgetFormInputText(array(), array('onkeyup' => 'lookup(this.value);', 'onblur' => 'fill();')),
            'Reviewer' => new sfWidgetFormInputText(),
        ));

        $this->widgetSchema->setNameFormat('viewreview[%s]');

        $this->setValidators(array(
            'ReviewPeriodFrom' => new sfValidatorDate(array('required' => false)),
            'ReviewPeriodTo' => new sfValidatorDate(array('required' => false)),
            'JobTitle' => new sfValidatorDoctrineChoice(array('model' => 'JobTitle', 'column' => 'jobtit_code ', 'required' => false)),
            'SubUnit' => new sfValidatorChoice(array('choices' => array_keys($this->__getSubunitList()) , 'required' => false)),
            'Employee' => new sfValidatorNumber(array('required' => false)),
            'Reviewer' => new sfValidatorString(array('required' => false)),
        ));
        /* $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array('callback' => array($this, 'checkMinMaxRates')))
          ); */
    }

    private function __getSubunitList() {
        $subUnitList = array(0 => __("All"));
        $treeObject = $this->getCompanyStructureService()->getSubunitTreeObject();

        $tree = $treeObject->fetchTree();

        foreach ($tree as $node) {
            if ($node->getId() != 1) {
                $subUnitList[$node->getId()] = str_repeat('&nbsp;&nbsp;', $node['level'] - 1) . $node['name'];
            }
        }
        return $subUnitList;
    }

    /**
     * check if the minimum rate is higher than the maximum value
     * @param $validator
     * @param $values
     * @return array
     */
    /* public function checkMinMaxRates($validator, $values){
      if (($values['MinRate'] >= $values['MaxRate']) && ($values['MinRate'] && $values['MaxRate'])){
      throw new sfValidatorError($validator, 'Minimum Value is higher than Maximum value. Please correct the values properly.');
      } else if(($values['MinRate'] == "") && ($values['MaxRate'] != "")) {
      throw new sfValidatorError($validator, 'Minimum value is not entered.');
      } else if(($values['MaxRate'] == "") && ($values['MinRate'] != "")) {
      throw new sfValidatorError($validator, 'Maximum value is not entered.');
      } else {
      return $values;
      }
      } */

    private function _getAllJobTitles() {
        $jobTitle = new JobTitle();

        $kpiDefinedJobTitles = $jobTitle->getJobTitlesDefined();

        if (empty($kpiDefinedJobTitles)) {
            $choices = array('-1' => '- Select -');
        } else {
            foreach ($kpiDefinedJobTitles as $key => $val) {
                foreach ($val as $jobTitleId => $jobTitleName) {
                    $arrFinal[$jobTitleId] = $jobTitleName;
                }
            }
            $choices = array('-1' => '- Select -') + $arrFinal;
        }
        return $choices;
    }

}

