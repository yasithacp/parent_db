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


class CountryDao extends BaseDao {

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
            throw new DaoException($e->getMessage());
        }
    }

    /**
     * Fetch list of provinces
     *
     * @param String $countryCode Country code - defaults to null
     * @return Province
     */
    public function getProvinceList($countryCode = NULL) {
        try {
            $q = Doctrine_Query::create()
                    ->from('Province p');

            if (!empty($countryCode)) {
                $q->where('cou_code = ?', $countryCode);
            }

            $q->orderBy('p.province_name');

            $provinceList = $q->execute();

            return $provinceList;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function searchCountries(array $searchParams) {
        try {
            $query = Doctrine_Query::create()
                    ->from('Country c');
            
            foreach ($searchParams as $field => $filterValue) {
                $query->addWhere($field . ' = ?', $filterValue);
            }
            
            return $query->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

}