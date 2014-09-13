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

class RecruitmentAttachmentService extends BaseService {

	private $recruitmentAttachmentDao;

	/**
	 * Get recruitmentAttachmentDao Dao
	 * @return recruitmentAttachmentDao
	 */
	public function getRecruitmentAttachmentDao() {
		return $this->recruitmentAttachmentDao;
	}

	/**
	 * Set Candidate Dao
	 * @param CandidateDao $candidateDao
	 * @return void
	 */
	public function setRecruitmentAttachmentDao(RecruitmentAttachmentDao $recruitmentAttachmentDao) {
		$this->recruitmentAttachmentDao = $recruitmentAttachmentDao;
	}

	/**
	 * Construct
	 */
	public function __construct() {
		$this->recruitmentAttachmentDao = new RecruitmentAttachmentDao();
	}

	/**
	 *
	 * @param JobVacancyAttachment $resume
	 * @return <type>
	 */
	public function saveVacancyAttachment(JobVacancyAttachment $attachment) {
		return $this->recruitmentAttachmentDao->saveVacancyAttachment($attachment);
	}

	/**
	 *
	 * @param JobCandidateAttachment $resume
	 * @return <type>
	 */
	public function saveCandidateAttachment(JobCandidateAttachment $attachment) {
		return $this->recruitmentAttachmentDao->saveCandidateAttachment($attachment);
	}

	/**
	 *
	 * @param <type> $attachId
	 * @return <type>
	 */
	public function getVacancyAttachment($attachId) {
		return $this->recruitmentAttachmentDao->getVacancyAttachment($attachId);
	}

	/**
	 *
	 * @param <type> $attachId
	 * @return <type>
	 */
	public function getCandidateAttachment($attachId) {
		return $this->recruitmentAttachmentDao->getCandidateAttachment($attachId);
	}

	/**
	 *
	 * @param <type> $id
	 * @param <type> $screen 
	 */
	public function getAttachment($id, $screen){

		if($screen == JobCandidate::TYPE){
			return $this->recruitmentAttachmentDao->getCandidateAttachment($id);
		} elseif($screen == JobVacancy::TYPE){
			return $this->recruitmentAttachmentDao->getVacancyAttachment($id);
		} elseif($screen == JobInterview::TYPE){
			return $this->recruitmentAttachmentDao->getInterviewAttachment($id);
		} else return false;
	}

	/**
	 *
	 * @param <type> $id
	 * @param <type> $screen
	 */
	public function getAttachments($id, $screen){
		
		if($screen == JobVacancy::TYPE){
			return $this->recruitmentAttachmentDao->getVacancyAttachments($id);
		} elseif($screen == JobInterview::TYPE){
			return $this->recruitmentAttachmentDao->getInterviewAttachments($id);
		} else return false;	
	}

	public function getNewAttachment($screen, $id){

		if($screen == JobVacancy::TYPE){
			$attachment = new JobVacancyAttachment();
			$attachment->vacancyId = $id;
		} elseif($screen == JobInterview::TYPE){
			$attachment =  new JobInterviewAttachment();
			$attachment->interviewId = $id;
		}
		return $attachment;
	}

}

?>
