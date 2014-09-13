<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yasitha
 * Date: 7/23/14
 * Time: 10:19 PM
 * To change this template use File | Settings | File Templates.
 */

class ViewParentInfoForm extends BaseForm {

    private $parentDao;

    /**
     * Get CandidateService
     * @returns CandidateService
     */
    public function getParentDao() {
        if (is_null($this->parentDao)) {
            $this->parentDao = new ParentDao();
        }
        return $this->parentDao;
    }

    public function configure() {

        $this->setWidgets(array(
            'stuName' => new sfWidgetFormInputText(),
            'stuIndexNo' => new sfWidgetFormInputText(),
            'dadOccupation' => new sfWidgetFormInputText(),
            'momOccupation' => new sfWidgetFormInputText(),
        ));

        $this->setValidators(array(
            'stuName' => new sfValidatorString(array('required' => false)),
            'stuIndexNo' => new sfValidatorString(array('required' => false)),
            'dadOccupation' => new sfValidatorString(array('required' => false)),
            'momOccupation' => new sfValidatorString(array('required' => false)),
        ));

        $this->widgetSchema->setNameFormat('parentSearch[%s]');
    }

    /**
     *
     * @param <type> $searchParam
     */
    public function setDefaultDataToWidgets($searchParam) {
        $this->setDefault('stuName', $searchParam['stuName']);
        $this->setDefault('stuIndexNo', $searchParam['stuIndexNo']);
        $this->setDefault('dadOccupation', $searchParam['dadOccupation']);
        $this->setDefault('momOccupation', $searchParam['momOccupation']);
    }

    /**
     *
     * @return <type>
     */
    public function getSearchParamsBindwithFormData() {

        $srchParams = array('stuName' => $this->getValue('stuName'),
            'stuIndexNo' => $this->getValue('stuIndexNo'),
            'dadOccupation' => $this->getValue('dadOccupation'),
            'momOccupation' => $this->getValue('momOccupation'));

        return $srchParams;
    }
}