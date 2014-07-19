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

class attachmentsComponent extends sfComponent {

	/**
	 * Get RecruitmentAttachmentService
	 * @returns RecruitmentAttachmentService
	 */
	public function getRecruitmentAttachmentService() {
		if (is_null($this->recruitmentAttachmentService)) {
			$this->recruitmentAttachmentService = new RecruitmentAttachmentService();
			$this->recruitmentAttachmentService->setRecruitmentAttachmentDao(new RecruitmentAttachmentDao());
		}
		return $this->recruitmentAttachmentService;
	}

	/**
	 * Execute method of component
	 *
	 * @param type $request
	 */
	public function execute($request) {

		$this->scrollToAttachments = false;

		if ($this->getUser()->hasFlash('attachmentMessage')) {

			$this->scrollToAttachments = true;
			list($this->attachmentMessageType, $this->attachmentMessage) = $this->getUser()->getFlash('attachmentMessage');
		}

		//$attachments = $this->getRecruitmentAttachmentService()->getVacancyAttachment($this->id);
		$attachments = $this->getRecruitmentAttachmentService()->getAttachments($this->id, $this->screen);
		$this->attachmentList = array();
		if (!empty($attachments)) {
			foreach ($attachments as $attachment) {
				$this->attachmentList[] = $attachment;
			}
		}
		$param = array('screen' => $this->screen);
		$this->form = new RecruitmentAttachmentForm(array(), $param, true);
		$this->deleteForm = new RecruitmentAttachmentDeleteForm(array(), $param, true);
	}

}
