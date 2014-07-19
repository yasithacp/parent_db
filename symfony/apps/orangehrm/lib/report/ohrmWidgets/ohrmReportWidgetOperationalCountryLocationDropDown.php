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
class ohrmReportWidgetOperationalCountryLocationDropDown extends ohrmWidgetSelectableGroupDropDown implements ohrmEnhancedEmbeddableWidget {

    private $operationalCountryService;
    private $choices = null;

    protected function configure($options = array(), $attributes = array()) {
        
        parent::configure($options, $attributes);

        // Parent requires the 'choices' option.
        $this->addOption('choices', array());
        $this->addOption('all_option_value', '-1');
        $this->addOption('show_all_locations', false);
    }

    /**
     * Get array of operational country and location choices
     */
    public function getChoices() {

        if (is_null($this->choices)) {

            $operationalCountries = $this->getOperationalCountryService()->getOperationalCountryList();
            $locationList = $this->_getLocationList();

            $showAll = $this->getOption('show_all_locations');
                    
            $choices = array();
            $addedLocationIds = array();

            // adding locations that assigned to operational country first
            $accessibleCountries = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntityIds('OperationalCountry'); 
            
            foreach ($operationalCountries as $operationalCountry) {

                $country = $operationalCountry->getCountry();
                
                if ($showAll || in_array($operationalCountry->getId(), $accessibleCountries)) {
                    $locations = $country->getLocation();

                    if (count($locations) > 0) {
                        $locationChoices = array();
                        foreach ($locations as $location) {
                            $addedLocationIds[] = $location->getId();
                            $locationChoices[$location->getId()] = $location->getName();
                        }
                        asort($locationChoices);
                        $choices[$country->getCouName()] = $locationChoices;
                    }
                }
            }

            //after that, adding all the remaining locations to the list
            foreach ($locationList as $id => $location) {
                if (!in_array($id, $addedLocationIds)) {
                    $choices[$id] = $location;
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

    public function embedWidgetIntoForm(sfForm &$form) {

        $requiredMess = 'Select a location';

        $widgetSchema = $form->getWidgetSchema();
        $widgetSchema[$this->attributes['id']] = $this;
        $label = ucwords(str_replace("_", " ", $this->attributes['id']));
        $validator = new sfValidatorString();
        if (isset($this->attributes['required']) && ($this->attributes['required'] == "true")) {
            $label .= "<span class='required'> * </span>";
            $validator = new sfValidatorString(array('required' => true), array('required' => $requiredMess));
        }
        $widgetSchema[$this->attributes['id']]->setLabel($label);
        $form->setValidator($this->attributes['id'], $validator);
    }

    /**
     * Sets whereClauseCondition.
     * @param string $condition
     */
    public function setWhereClauseCondition($condition) {

        $this->whereClauseCondition = $condition;
    }

    /**
     * Gets whereClauseCondition. ( if whereClauseCondition is set returns that, else returns default condition )
     * @return string ( a condition )
     */
    public function getWhereClauseCondition() {

        if (isset($this->whereClauseCondition)) {
            $setCondition = $this->whereClauseCondition;
            return $setCondition;
        } else {
            $defaultCondition = "IN";
            return $defaultCondition;
        }
    }

    /**
     * This method generates the where clause part.
     * @param string $fieldName
     * @param string $value
     * @return string
     */
    public function generateWhereClausePart($fieldName, $value) {

        if ($value == '-1') {
            $whereClausePart = null;
        } else {
            $whereClausePart = $fieldName . " " . $this->getWhereClauseCondition() . " " . $value;
        }

        return $whereClausePart;
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

    /**
     * Gets all locations.
     * @return string[] $locationList
     */
    private function _getLocationList() {
        $locationService = new LocationService();

        $showAll = $this->getOption('show_all_locations');
        
        $locationList = array();
        $locations = $locationService->getLocationList();

        $accessibleLocations = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntityIds('Location');
        
        foreach ($locations as $location) {
            if ($showAll || in_array($location->id, $accessibleLocations)) {
                $locationList[$location->id] = $location->name;
            }
        }

        return ($locationList);
    }

    public function getDefaultValue(SelectedFilterField $selectedFilterField) {
        return $selectedFilterField->value1;
    }

}