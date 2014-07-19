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

class LocationService extends BaseService {

    private $locationDao;

    /**
     * Construct
     */
    public function __construct() {
        $this->locationDao = new LocationDao();
    }

    /**
     *
     * @return <type>
     */
    public function getLocationDao() {
        if (!($this->locationDao instanceof LocationDao)) {
            $this->locationDao = new LocationDao();
        }
        return $this->locationDao;
    }

    /**
     *
     * @param LocationDao $locationDao 
     */
    public function setLocationDao(LocationDao $locationDao) {
        $this->locationDao = $locationDao;
    }

    /**
     * Get Location by id
     * 
     * @param type $locationId
     * @return type 
     */
    public function getLocationById($locationId) {
        return $this->locationDao->getLocationById($locationId);
    }

    /**
     * 
     * Search location by project name, city and country.
     * 
     * @param type $srchClues
     * @return type 
     */
    public function searchLocations($srchClues) {
        return $this->locationDao->searchLocations($srchClues);
    }

    /**
     *
     * Get location count of the search results.
     *
     * @param type $srchClues
     * @return type 
     */
    public function getSearchLocationListCount($srchClues) {
        return $this->locationDao->getSearchLocationListCount($srchClues);
    }

    /**
     * Get total number of employees in a location.
     * 
     * @param type $locationId
     * @return type 
     */
    public function getNumberOfEmplyeesForLocation($locationId) {
        return $this->locationDao->getNumberOfEmplyeesForLocation($locationId);
    }

    /**
     * Get all locations
     * 
     * @return type 
     */
    public function getLocationList() {
        return $this->locationDao->getLocationList();
    }

}

?>
