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
 * Actions class for PIM module viewAttachment
 */
class viewAttachmentAction extends sfAction {

   /**
     *
     * @return <type>
     */
    public function getRecruitmentAttachmentService() {
        if (is_null($this->recruitmentAttachmentService)) {
            $this->recruitmentAttachmentService = new RecruitmentAttachmentService();
            $this->recruitmentAttachmentService->setRecruitmentAttachmentDao(new RecruitmentAttachmentDao());
        }
        return $this->recruitmentAttachmentService;
    }

    /**
     *
     * @return <type>
     */
    public function getVacancyService() {
        if (is_null($this->vacancyService)) {
            $this->vacancyService = new VacancyService();
            $this->vacancyService->setVacancyDao(new VacancyDao());
        }
        return $this->vacancyService;
    }

    /**
     * Add / update employee customFields
     *
     * @param int $empNumber Employee number
     *
     * @return boolean true if successfully assigned, false otherwise
     */
    public function execute($request) {

        // this should probably be kept in session?
        $attachId = $request->getParameter('attachId');
        $screen = $request->getParameter('screen');
        $candidateService = $this->getRecruitmentAttachmentService();
        $attachment = $candidateService->getAttachment($attachId, $screen);

        $response = $this->getResponse();

        if (!empty($attachment)) {
            $contents = $attachment->getFileContent();
            $contentType = $attachment->getAttachmentType();
            $fileName = $attachment->getFileName();
            $fileLength = $attachment->getFileSize();

            $response->setHttpHeader('Pragma', 'public');

            $response->setHttpHeader('Expires', '0');
            $response->setHttpHeader("Cache-Control", "must-revalidate, post-check=0, pre-check=0");
            $response->setHttpHeader("Cache-Control", "private", false);
            $response->setHttpHeader("Content-Type", $contentType);
            $response->setHttpHeader("Content-Disposition", 'attachment; filename="' . $fileName . '";');
            $response->setHttpHeader("Content-Transfer-Encoding", "binary");
            $response->setHttpHeader("Content-Length", $fileLength);

            $response->setContent($contents);
            $response->send();
        } else {
            $response->setStatusCode(404, 'This attachment does not exist');
        }

        return sfView::NONE;
    }

}
