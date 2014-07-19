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

class RecruitmentAttachmentDao extends BaseDao {

    /**
     *
     * @param JobVacancyAttachment $attachment
     * @return <type>
     */
    public function saveVacancyAttachment(JobVacancyAttachment $attachment) {
        try {
            if ($attachment->getId() == '') {
                $idGenService = new IDGeneratorService();
                $idGenService->setEntity($attachment);
                $attachment->setId($idGenService->getNextID());
            }
            $attachment->save();
            return true;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param JobCandidateAttachment $attachment
     * @return <type>
     */
    public function saveCandidateAttachment(JobCandidateAttachment $attachment) {
        try {
            if ($attachment->getId() == '') {
                $idGenService = new IDGeneratorService();
                $idGenService->setEntity($attachment);
                $attachment->setId($idGenService->getNextID());
            }
            $attachment->save();
            return true;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param <type> $attachId
     * @return <type>
     */
    public function getVacancyAttachment($attachId) {
        try {
            $q = Doctrine_Query:: create()
                            ->from('JobVacancyAttachment a')
                            ->where('a.id = ?', $attachId);
            return $q->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param <type> $attachId
     * @return <type>
     */
    public function getInterviewAttachment($attachId) {
        try {
            $q = Doctrine_Query:: create()
                            ->from('JobInterviewAttachment a')
                            ->where('a.id = ?', $attachId);
            return $q->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param <type> $attachId
     * @return <type>
     */
    public function getCandidateAttachment($attachId) {
        try {
            $q = Doctrine_Query:: create()
                            ->from('JobCandidateAttachment a')
                            ->where('a.id = ?', $attachId);
            return $q->fetchOne();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    /**
     *
     * @param <type> $vacancyId
     * @return <type>
     */
    public function getVacancyAttachments($vacancyId) {
        try {
            $q = Doctrine_Query :: create()
                            ->from('JobVacancyAttachment')
                            ->where('vacancyId =?', $vacancyId)
                            ->orderBy('fileName ASC');
            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

    
    /**
     *
     * @param <type> $interviewId
     * @return <type>
     */
    public function getInterviewAttachments($interviewId) {
        try {
            $q = Doctrine_Query :: create()
                            ->from('JobInterviewAttachment')
                            ->where('interview_id =?', $interviewId)
                            ->orderBy('fileName ASC');
            return $q->execute();
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }
    }

}
