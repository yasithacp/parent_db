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


class TerminationReasonForm extends BaseForm {
    
    private $terminationReasonService;
    
    public function getTerminationReasonService() {
        
        if (!($this->terminationReasonService instanceof TerminationReasonService)) {
            $this->terminationReasonService = new TerminationReasonService();
        }
        
        return $this->terminationReasonService;
    }

    public function setTerminationReasonService($terminationReasonService) {
        $this->terminationReasonService = $terminationReasonService;
    }

    public function configure() {

        $this->setWidgets(array(
            'id' => new sfWidgetFormInputHidden(),
            'name' => new sfWidgetFormInputText()
        ));

        $this->setValidators(array(
            'id' => new sfValidatorNumber(array('required' => false)),
            'name' => new sfValidatorString(array('required' => true, 'max_length' => 100))
        ));

        $this->widgetSchema->setNameFormat('terminationReason[%s]');

        $this->setDefault('id', '');
	}
    
    public function save() {
        
        $id = $this->getValue('id');
        
        if (empty($id)) {
            $terminationReason = new TerminationReason();
            $message = array('SUCCESS', __(TopLevelMessages::SAVE_SUCCESS));
        } else {
            $terminationReason = $this->getTerminationReasonService()->getTerminationReasonById($id);
            $message = array('SUCCESS', __(TopLevelMessages::UPDATE_SUCCESS));
        }
        
        $terminationReason->setName($this->getValue('name'));
        $this->getTerminationReasonService()->saveTerminationReason($terminationReason);        
        
        return $message;
        
    }

}
