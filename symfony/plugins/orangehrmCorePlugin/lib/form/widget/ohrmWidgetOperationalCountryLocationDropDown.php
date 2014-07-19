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
 * Widget that displays countries and locations within the country
 * in a single drop down
 *
 */
class ohrmWidgetOperationalCountryLocationDropDown extends ohrmWidgetSelectableGroupDropDown {
    
    private $operationalCountryService;
    
    private $choices = null;
  
    protected function configure($options = array(), $attributes = array()) {
                
        parent::configure($options, $attributes);
        
        // Parent requires the 'choices' option.
        $this->addOption('choices', array());

    }
    
    /**
     * Get array of operational country and location choices
     */
    public function getChoices() {
        
        if (is_null($this->choices)) {
           
            $operationalCountries = $this->getOperationalCountryService()->getOperationalCountryList();
            
            $manager = UserRoleManagerFactory::getUserRoleManager();
            
            $accessibleCountryIds = $manager->getAccessibleEntityIds('OperationalCountry');
            
            $user = sfContext::getInstance()->getUser();
            
            // Special case for supervisor - can see all operational countries
            $showAll = false;
            if ($user->getAttribute('auth.isSupervisor')) {
                $showAll = true;
            }

            $choices = array();

            foreach ($operationalCountries as $operationalCountry) {

                $countryId = $operationalCountry->getId();
                
                if ($showAll || in_array($countryId, $accessibleCountryIds)) {
                    $country = $operationalCountry->getCountry();                

                    $locations = $country->getLocation();

                    if (count($locations) > 0) {
                        $locationChoices = array();
                        foreach ($locations as $location) {
                            $locationChoices[$location->getId()] = $location->getName();
                        }
                        asort($locationChoices);
                        $choices[$country->getCouName()] = $locationChoices;
                    }
                }

            }        
            $this->choices = $choices;            
        }
        
        return $this->choices;               
    }
    
    public function getValidValues() {
        $choices = $this->getChoices();
        return array_keys($choices);
    }
    
    
    /**
     *
     * @param OperationalCountryService $service 
     */
    public function setOperationalCountryService(OperationalCountryService $service) {
        $this->operationalCountryService = $service;
    }
    
    /**
     * 
     * @return OperationalCountryService
     */
    public function getOperationalCountryService() {
       if (!($this->operationalCountryService instanceof OperationalCountryService)) {
           $this->operationalCountryService = new OperationalCountryService();
       }
       return $this->operationalCountryService;
    }    
}

