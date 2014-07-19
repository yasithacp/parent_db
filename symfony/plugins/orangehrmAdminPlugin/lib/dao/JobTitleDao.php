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

class JobTitleDao extends BaseDao {

    public function getJobTitleList($sortField='jobTitleName', $sortOrder='ASC', $activeOnly = true, $limit = null, $offset = null) {

        $sortField = ($sortField == "") ? 'jobTitleName' : $sortField;
        $sortOrder = ($sortOrder == "") ? 'ASC' : $sortOrder;

        try {
            $q = Doctrine_Query :: create()
                            ->from('JobTitle');
            if ($activeOnly == true) {
                $q->addWhere('isDeleted = ?', JobTitle::ACTIVE);
            }
            $q->orderBy($sortField . ' ' . $sortOrder);
            if (!empty($limit)) {
                $q->offset($offset)
                  ->limit($limit);
            }
            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function deleteJobTitle($toBeDeletedJobTitleIds) {

        try {
            $q = Doctrine_Query :: create()
                            ->update('JobTitle')
                            ->set('isDeleted', '?', JobTitle::DELETED)
                            ->whereIn('id', $toBeDeletedJobTitleIds);
            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function getJobTitleById($jobTitleId) {

        try {
            return Doctrine::getTable('JobTitle')->find($jobTitleId);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    public function getJobSpecAttachmentById($attachId) {

        try {
            return Doctrine::getTable('JobSpecificationAttachment')->find($attachId);
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

}

