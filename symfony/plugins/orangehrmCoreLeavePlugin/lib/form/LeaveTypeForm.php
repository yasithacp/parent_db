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


class LeaveTypeForm extends orangehrmForm {

    private $updateMode = false;
    private $leaveTypeService;

    public function configure() {

        sfContext::getInstance()->getConfiguration()->loadHelpers('I18N');
        
        $this->setWidgets(array(
            'txtLeaveTypeName' => new sfWidgetFormInput(array(), array('size' => 30)),
            'hdnOriginalLeaveTypeName' => new sfWidgetFormInputHidden(),
            'hdnLeaveTypeId' => new sfWidgetFormInputHidden()
        ));
        
        $this->setValidators(array(
            'txtLeaveTypeName' => 
                new sfValidatorString(array(
                        'required' => true,
                        'max_length' => 50
                    ),
                    array(
                        'required' => __('Required'),
                        'max_length' => __('Leave type name should be 50 characters or less in length')
                    )),
            'hdnOriginalLeaveTypeName' => new sfValidatorString(array('required' => false)),
            'hdnLeaveTypeId' => new sfValidatorString(array('required' => false))          
        ));
        $this->widgetSchema->setNameFormat('leaveType[%s]');
    }

    public function setDefaultValues($leaveTypeId) {

        $leaveTypeService = $this->getLeaveTypeService();
        $leaveTypeObject = $leaveTypeService->readLeaveType($leaveTypeId);

        if ($leaveTypeObject instanceof LeaveType) {

            $this->setDefault('hdnLeaveTypeId', $leaveTypeObject->getLeaveTypeId());
            $this->setDefault('txtLeaveTypeName', $leaveTypeObject->getLeaveTypeName());
            $this->setDefault('hdnOriginalLeaveTypeName', $leaveTypeObject->getLeaveTypeName());
        }
    }

    public function setUpdateMode() {
        $this->updateMode = true;
    }    

    public function isUpdateMode() {
        return $this->updateMode;
    }
    
    public function getLeaveTypeObject() {
        
        $leaveTypeId = $this->getValue('hdnLeaveTypeId');
        
        if (!empty($leaveTypeId)) {
            $leaveType = $this->getLeaveTypeService()->readLeaveType($leaveTypeId);
        } else {
            $leaveType = new LeaveType();
            $leaveType->setAvailableFlag(LeaveType::AVAILABLE);
        }        
        
        $leaveType->setLeaveTypeName($this->getValue('txtLeaveTypeName'));

        return $leaveType;        
    }
    
    public function getDeletedLeaveTypesJsonArray() {

        $leaveTypeService = $this->getLeaveTypeService();
        $deletedLeaveTypes = $leaveTypeService->getDeletedLeaveTypeList();

        $deletedTypesArray = array();

        foreach ($deletedLeaveTypes as $deletedLeaveType) {
            $deletedTypesArray[] = array('id' => $deletedLeaveType->getLeaveTypeId(),
                                         'name' => $deletedLeaveType->getLeaveTypeName());
        }

        return json_encode($deletedTypesArray);
    }

    public function getLeaveTypeService() {

        if(is_null($this->leaveTypeService)) {
            $this->leaveTypeService = new LeaveTypeService();
        }

        return $this->leaveTypeService;

    }    
    
    public function setLeaveTypeService($leaveTypeService) {
        $this->leaveTypeService = $leaveTypeService;
    }
    
    public function getJavaScripts() {
        $javaScripts = parent::getJavaScripts();
        $javaScripts[] = '/orangehrmCoreLeavePlugin/js/defineLeaveTypeSuccess.js';
        
        return $javaScripts;
    }
    
    public function getStylesheets() {
        $styleSheets = parent::getStylesheets();
        $styleSheets['/orangehrmCoreLeavePlugin/css/defineLeaveTypeSuccess.css'] = 'screen';
        
        return $styleSheets;        
    }
    
    public function getActionButtons() {

        $actionButtons = array();
        
        $actionButtons['saveButton'] = new ohrmWidgetButton('saveButton', "Save", array('class' => 'savebutton'));
        $actionButtons['resetButton'] = new ohrmWidgetButton('resetButton', "Reset", array('class' => 'savebutton', 'type'=> 'reset'));
        $actionButtons['backButton'] = new ohrmWidgetButton('backButton', "Back", array('class' => 'savebutton'));

        return $actionButtons;
    }    
}

