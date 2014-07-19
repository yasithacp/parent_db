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

class NationalityForm extends BaseForm {
    private $nationalityService;

    public function getNationalityService() {
        if (is_null($this->nationalityService)) {
            $this->nationalityService = new NationalityService();
            $this->nationalityService->setNationalityDao(new NationalityDao());
        }
        return $this->nationalityService;
    }

    public function configure() {

        $this->setWidgets(array(
            'nationalityId' => new sfWidgetFormInputHidden(),
            'name' => new sfWidgetFormInputText()
        ));

        $this->setValidators(array(
            'nationalityId' => new sfValidatorNumber(array('required' => false)),
            'name' => new sfValidatorString(array('required' => true, 'max_length' => 100))
        ));

        $this->widgetSchema->setNameFormat('nationality[%s]');
    }

    public function save() {

        $nationalityId = $this->getValue('nationalityId');
        if (!empty($nationalityId)) {
            $nationality = $this->getNationalityService()->getNationalityById($nationalityId);
        } else {
            $nationality = new Nationality();
        }
        $nationality->setName($this->getValue('name'));
        $nationality->save();
    }

    public function getNationalityListAsJson() {

        $list = array();
        $nationalityList = $this->getNationalityService()->getNationalityList();
        foreach ($nationalityList as $nationality) {
            $list[] = array('id' => $nationality->getId(), 'name' => $nationality->getName());
        }
        return json_encode($list);
    }
}

