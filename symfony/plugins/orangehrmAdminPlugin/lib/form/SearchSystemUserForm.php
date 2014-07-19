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

class SearchSystemUserForm extends BaseForm {

    private $systemUserService;

    public function getSystemUserService() {
        $this->systemUserService = new SystemUserService();
        return $this->systemUserService;
    }

    public function configure() {

        $userRoleList = $this->getAssignableUserRoleList();
        $statusList = $this->getStatusList();

        $widgets = array();

        $widgets['userName'] = new sfWidgetFormInputText();
        $widgets['userType'] = new sfWidgetFormSelect(array('choices' => $userRoleList));
        $widgets['employeeName'] = new ohrmWidgetEmployeeNameAutoFill();
        $widgets['status'] = new sfWidgetFormSelect(array('choices' => $statusList));        
        $this->setWidgets($widgets);
                
        $validators = array();
        $validators['userName'] = new sfValidatorString(array('required' => false));
        $validators['userType'] = new sfValidatorChoice(array('required' => false, 
                'choices' => array_keys($userRoleList)));                
        $validators['employeeName'] = new ohrmValidatorEmployeeNameAutoFill();
        $validators['status'] = new sfValidatorChoice(array('required' => false, 
                'choices' => array_keys($statusList)));
        
        $this->setValidators($validators);

        //merge location filter
        $formExtension = PluginFormMergeManager::instance();
        $formExtension->mergeForms($this, 'viewSystemUsers', 'SearchSystemUserForm');

        $this->getWidgetSchema()->setNameFormat('searchSystemUser[%s]');
        $this->getWidgetSchema()->setLabels($this->getFormLabels());

        sfWidgetFormSchemaFormatterBreakTags::setNoOfColumns(3);
        $this->getWidgetSchema()->setFormFormatterName('BreakTags');
    }

    /**
     * Get Pre Defined User Role List
     * 
     * @return array
     */
    private function getAssignableUserRoleList() {
        $list = array();
        $list[] = __("All");
        $userRoles = $this->getSystemUserService()->getAssignableUserRoles();
        
        $accessibleRoleIds = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntityIds('UserRole');
        
        foreach ($userRoles as $userRole) {
            if (in_array($userRole->getId(), $accessibleRoleIds)) {
                $list[$userRole->getId()] = $userRole->getDisplayName();
            }
        }
        return $list;
    }

    private function getStatusList() {
        $list = array();
        $list[''] = __("All");
        $list['1'] = __("Enabled");
        $list['0'] = __("Disabled");

        return $list;
    }

    public function setDefaultDataToWidgets($searchClues) {
        $this->setDefault('userName', $searchClues['userName']);
        $this->setDefault('userType', $searchClues['userType']);
        if (isset($searchClues['employeeName'])) {
            $this->setDefault('employeeName', $searchClues['employeeName']);
        }
        $this->setDefault('status', $searchClues['status']);
    }

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $labels = array(
            'userName' => __('Username'),
            'userType' => __('User Type'),
            'employeeName' => __('Employee Name'),
            'status' => __('Status')
        );

        return $labels;
    }

}