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

class CompanyStructureDao extends BaseDao {

    public function getSubunitById($id) {
        try {
            return Doctrine::getTable('Subunit')->find($id);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function saveSubunit(Subunit $subunit) {
        try {
            if ($subunit->getId() == '') {
                $subunit->setId(0);
            } else {
                $tempObj = Doctrine::getTable('Subunit')->find($subunit->getId());

                $tempObj->setName($subunit->getName());
                $tempObj->setDescription($subunit->getDescription());
                $tempObj->setUnitId($subunit->getUnitId());
                $subunit = $tempObj;
            }

            $subunit->save();
            return true;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function addSubunit(Subunit $parentSubunit, Subunit $subunit) {
        try {
            $subunit->setId(0);
            $subunit->getNode()->insertAsLastChildOf($parentSubunit);

            $parentSubunit->setRgt($parentSubunit->getRgt() + 2);
            $parentSubunit->save();

            return true;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function deleteSubunit(Subunit $subunit) {
        try {
            $q = Doctrine_Query::create()
                            ->delete('Subunit')
                            ->where('lft >= ?', $subunit->getLft())
                            ->andWhere('rgt <= ?', $subunit->getRgt());
            $q->execute();
            return true;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function setOrganizationName($name) {
        try {
            $q = Doctrine_Query:: create()->update('Subunit')
                            ->set('name', '?', $name)
                            ->where('id = 1');
            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function getSubunitTreeObject() {
        try {
            return Doctrine::getTable('Subunit')->getTree();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

}
