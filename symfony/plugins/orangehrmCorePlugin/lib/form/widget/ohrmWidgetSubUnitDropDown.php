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
 * Description of ohrmWidgetSubUnit
 *
 */
class ohrmWidgetSubUnitDropDown extends sfWidgetFormSelect {
    
    private $companyStructureService;
    
    private $choices = null;
    
    public function setCompanyStructureService(CompanyStructureService $service) {
        $this->companyStructureService = $service;
    }
    
    public function getCompanyStructureService() {
        
        if (empty($this->companyStructureService)) {
            $this->companyStructureService = new CompanyStructureService();
        }
        return $this->companyStructureService;
    }
  
    protected function configure($options = array(), $attributes = array()) {
                
        parent::configure($options, $attributes);
        
        //
        // option value for 'all' checkbox. Set to a valid option to enable the 'All' option
        //
        $this->addOption('show_all_option', true);
        $this->addOption('all_option_label', __('All'));

        $this->addOption('show_root', false);
        
        $this->addOption('indent', true);
        $this->addOption('indent_string', "&nbsp;&nbsp;");

        // Parent requires the 'choices' option.
        $this->addOption('choices', array());

    }
    
    /**
     * Get array of subunit choices
     */
    public function getChoices() {
        
        if (is_null($this->choices)) {
            $choices = array();

            $indent = $this->getOption('indent');
            $indentWith = $this->getOption('indent_string');
            $showRoot = $this->getOption('show_root');


            if ($this->getOption('show_all_option')) {
                $choices[0] = $this->getOption('all_option_label');
            }

            $treeObject = $this->getCompanyStructureService()->getSubunitTreeObject();
            $tree = $treeObject->fetchTree();

            foreach ($tree as $node) {
                if ($node->getId() != 1) {

                    $label = $node['name'];
                    if ($indent) {
                        $label = str_repeat($indentWith, $node['level'] - 1) . $label;
                    }

                    $choices[$node->getId()] = $label;
                }
            }
            
            $this->choices = $choices;            
        }
        
//        asort($this->choices);
        return $this->choices;               
    }
    
    public function getValidValues() {
        $choices = $this->getChoices();
        return array_keys($choices);
    }
    
    
}

