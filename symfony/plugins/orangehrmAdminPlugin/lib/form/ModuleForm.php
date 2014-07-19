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


class ModuleForm extends BaseForm {
    
    private $moduleService;
    
    public function getModuleService() {
        
        if (!($this->moduleService instanceof ModuleService)) {
            $this->moduleService = new ModuleService();
        }
        
        return $this->moduleService;
    }

    public function setModuleService($moduleService) {
        $this->moduleService = $moduleService;
    }

    public function configure() {

        $this->setWidgets(array(
            'admin' => new sfWidgetFormInputCheckbox(array(), array('class' => 'checkbox')),
            'pim' => new sfWidgetFormInputCheckbox(array(), array('class' => 'checkbox')),
            'leave' => new sfWidgetFormInputCheckbox(array(), array('class' => 'checkbox')),
            'time' => new sfWidgetFormInputCheckbox(array(), array('class' => 'checkbox')),
            'recruitment' => new sfWidgetFormInputCheckbox(array(), array('class' => 'checkbox')),
            'performance' => new sfWidgetFormInputCheckbox(array(), array('class' => 'checkbox')),
            'help' => new sfWidgetFormInputCheckbox(array(), array('class' => 'checkbox'))
        ));        
        
        $this->setValidators(array(
            'admin' => new sfValidatorPass(),
            'pim' => new sfValidatorPass(),
            'leave' => new sfValidatorPass(),
            'time' => new sfValidatorPass(),
            'recruitment' => new sfValidatorPass(),
            'performance' => new sfValidatorPass(),
            'help' => new sfValidatorPass()
        ));
        
        $this->setDefaults($this->_getDefaultValues());
        $this->setDefault('help', true);

        $this->widgetSchema->setNameFormat('moduleConfig[%s]');

	}
    
    protected function _getDefaultValues() {
        
        $modules = array('admin', 'pim', 'leave', 'time', 'recruitment', 'performance');
        
        $moduleService = $this->getModuleService();
        $disabledModules = $moduleService->getDisabledModuleList();
        $disabledModuleList = array();
        
        foreach ($disabledModules as $module) {
            $disabledModuleList[] = $module->getName();
        }
        
        $modules = array_diff($modules, $disabledModuleList);
        
        $defaultValues = array();
        
        foreach ($modules as $module) {
            $defaultValues[$module] = true;
        }
        
        return $defaultValues;
        
    }


    public function save() {
        
        $modules = $this->getValues();
        
        $modulesToEnable = array();
        $modulesToDisable = array();
        $defaultModules = array('admin', 'pim');
        
        foreach ($modules as $key => $value) {
            
            if (!empty($value)) {
                
                $modulesToEnable[] = $key;
                
                if ($key == 'time') {
                    $modulesToEnable[] = 'attendance';
                }
                
                if ($key == 'recruitment') {
                    $modulesToEnable[] = 'recruitmentApply';
                }                
                
            } else {
                
                if (!in_array($key, $defaultModules)) {
                
                    $modulesToDisable[] = $key;

                    if ($key == 'time') {
                        $modulesToDisable[] = 'attendance';
                    }

                    if ($key == 'recruitment') {
                        $modulesToDisable[] = 'recruitmentApply';
                    }
                
                }
                
            }
            
        }
        
        if (!empty($modulesToEnable)) {
            $this->getModuleService()->updateModuleStatus($modulesToEnable, Module::ENABLED);
        }
        
        if (!empty($modulesToDisable)) {
            $this->getModuleService()->updateModuleStatus($modulesToDisable, Module::DISABLED);
        }
        
        return array('SUCCESS', __(TopLevelMessages::SAVE_SUCCESS));
        
    }

}

?>
