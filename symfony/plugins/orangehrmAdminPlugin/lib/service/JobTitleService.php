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

class JobTitleService extends BaseService {

    private $jobTitleDao;

    public function __construct() {
        $this->jobTitleDao = new JobTitleDao();
    }

    public function getJobTitleDao() {
        if (!($this->jobTitleDao instanceof JobTitleDao)) {
            $this->jobTitleDao = new JobTitleDao();
        }
        return $this->jobTitleDao;
    }

    public function setJobTitleDao(JobTitleDao $jobTitleDao) {
        $this->jobTitleDao = $jobTitleDao;
    }

    /**
     * Returns JobTitlelist - By default this will returns the active jobTitle list
     * To get the all the jobTitles(with deleted) should pass the $activeOnly as false
     *
     * @param string $sortField
     * @param string $sortOrder
     * @param boolean $activeOnly
     * @return JobTitle Doctrine collection
     */
    public function getJobTitleList($sortField='jobTitleName', $sortOrder='ASC', $activeOnly = true, $limit = null, $offset = null) {
        return $this->getJobTitleDao()->getJobTitleList($sortField, $sortOrder, $activeOnly, $limit, $offset);
    }

    /**
     * This will flag the jobTitles as deleted
     *
     * @param array $toBeDeletedJobTitleIds
     * @return int number of affected rows
     */
    public function deleteJobTitle($toBeDeletedJobTitleIds) {
        return $this->getJobTitleDao()->deleteJobTitle($toBeDeletedJobTitleIds);
    }

    /**
     * Will return the JobTitle doctrine object for a purticular id
     *
     * @param int $jobTitleId
     * @return JobTitle doctrine object
     */
    public function getJobTitleById($jobTitleId) {
        return $this->getJobTitleDao()->getJobTitleById($jobTitleId);
    }

    /**
     * Will return the JobSpecificationAttachment doctrine object for a purticular id
     *
     * @param int $attachId
     * @return JobSpecificationAttachment doctrine object
     */
    public function getJobSpecAttachmentById($attachId) {
        return $this->getJobTitleDao()->getJobSpecAttachmentById($attachId);
    }

}

