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

class CompanyStructureService extends BaseService {

    private $companyStructureDao;

    public function getCompanyStructureDao() {
        if (!($this->companyStructureDao instanceof CompanyStructureDao)) {
            $this->companyStructureDao = new CompanyStructureDao();
        }
        return $this->companyStructureDao;
    }

    public function setCompanyStructureDao(CompanyStructureDao $dao) {
        $this->companyStructureDao = $dao;
    }

    /**
     * Get sub unit for a given id
     *
     * @version
     * @param int $id Subunit auto incremental id
     * @return Subunit instance if found or a dao exception
     */
    public function getSubunitById($id) {
        return $this->getCompanyStructureDao()->getSubunitById($id);
    }

    /**
     * Save a Subunit
     *
     * If id is not set, it will be set to next available value and a new subunit
     * will be added.
     *
     * If id is set the belonged subunit will be updated.
     *
     * @version
     * @param Subunit $subunit
     * @return boolean
     */
    public function saveSubunit(Subunit $subunit) {
        return $this->getCompanyStructureDao()->saveSubunit($subunit);
    }

    /**
     * Save the parent sub unit again
     *
     * This will update the parent sub unit if the child is changed.
     *
     * @version
     * @param Subunit $parentSubunit
     * @param Subunit $subunit
     * @return boolean
     */
    public function addSubunit(Subunit $parentSubunit, Subunit $subunit) {
        return $this->getCompanyStructureDao()->addSubunit($parentSubunit, $subunit);
    }

    /**
     * Delete subunit
     *
     * This will delete the passed subunit and it's children
     *
     * @version
     * @param Subunit $subunit
     * @return boolean
     */
    public function deleteSubunit(Subunit $subunit) {
        return $this->getCompanyStructureDao()->deleteSubunit($subunit);
    }

    /**
     * Set the organization name to the root of the tree. Previously the root has the name
     * 'Organization' then if the company name is set this will update the root node of the tree
     *
     * @version
     * @param string $name
     * @return int - affected rows
     */
    public function setOrganizationName($name) {
        return $this->getCompanyStructureDao()->setOrganizationName($name);
    }

    /**
     * Get the whole subunit tree
     *
     * @version
     * @return Nested set - Subunit object list
     */
    public function getSubunitTreeObject() {
        return $this->getCompanyStructureDao()->getSubunitTreeObject();
    }

}
