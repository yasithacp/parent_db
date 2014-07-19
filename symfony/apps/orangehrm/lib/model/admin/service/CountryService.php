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


class CountryService extends BaseService {

    protected $countryDao;

    /**
     * 
     * @return CountryDao
     */
    public function getCountryDao() {
        if (!($this->countryDao instanceof CountryDao)) {
            $this->countryDao = new CountryDao();
        }
        return $this->countryDao;
    }

    /**
     *
     * @param CountryDao $dao 
     */
    public function setCountryDao(CountryDao $dao) {
        $this->countryDao = $dao;
    }

    /**
     * Get Country list
     * @return Country
     */
    public function getCountryList() {
        try {
            $q = Doctrine_Query::create()
                    ->from('Country c')
                    ->orderBy('c.name');

            $countryList = $q->execute();

            return $countryList;
        } catch (Exception $e) {
            throw new AdminServiceException($e->getMessage());
        }
    }

    /**
     * 
     * @return Province
     */
    public function getProvinceList() {
        try {
            $q = Doctrine_Query::create()
                    ->from('Province p')
                    ->orderBy('p.province_name');

            $provinceList = $q->execute();

            return $provinceList;
        } catch (Exception $e) {
            throw new AdminServiceException($e->getMessage());
        }
    }

    /**
     *
     * @param array $searchParams 
     */
    public function searchCountries(array $searchParams) {
        try {
            return $this->getCountryDao()->searchCountries($searchParams);
        } catch (Exception $e) {
            throw new ServiceException($e->getMessage());
        }
    }

}