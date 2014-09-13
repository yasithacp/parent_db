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
 * Job Interview Service
 *
 */
class JobInterviewService extends BaseService {

	private $jobInterviewDao;

	/**
	 * Get $jobInterview Dao
	 * @return JobInterviewDao
	 */
	public function getJobInterviewDao() {
		return $this->jobInterviewDao;
	}

	/**
	 * Set $jobInterview Dao
	 * @param JobInterviewDao $jobInterviewDao
	 * @return void
	 */
	public function setJobInterviewDao(JobInterviewDao $jobInterviewDao) {
		$this->jobInterviewDao = $jobInterviewDao;
	}

	/**
	 * Construct
	 */
	public function __construct() {
		$this->jobInterviewDao = new JobInterviewDao();
	}

	public function getInterviewById($interviewId) {
		return $this->jobInterviewDao->getInterviewById($interviewId);
	}

	public function getInterviewersByInterviewId($interviewId) {
		return $this->jobInterviewDao->getInterviewersByInterviewId($interviewId);
	}

	public function getInterviewsByCandidateVacancyId($candidateVacancyId) {
		return $this->jobInterviewDao->getInterviewsByCandidateVacancyId($candidateVacancyId);
	}

	public function saveJobInterview(JobInterview $jobInterview) {
		return $this->jobInterviewDao->saveJobInterview($jobInterview);
	}

	public function updateJobInterview(JobInterview $jobInterview) {
		return $this->jobInterviewDao->updateJobInterview($jobInterview);
	}

	public function getInterviewScheduledHistoryByInterviewId($interviewId) {
		return $this->jobInterviewDao->getInterviewScheduledHistoryByInterviewId($interviewId);
	}
    
    /**
     * Get interviw objects for relevent candidate in specific date with one our time range near to the interview time
     * @param int $candidateId
     * @param dateISO $interviewDate
     * @param time $interviewTime (H:i:s)
     * @return boolean
     */
//    public function getInterviewListByCandidateIdAndInterviewDateAndTime($candidateId, $interviewDate, $interviewTime) {
//        
//        $d = explode(":", $interviewDate);
//        $t = explode("-", $interviewTime);
//        
//        $date = settype($d, "integer");
//        $time = settype($t, "integer");
//        
//        date_default_timezone_set('UTC');
//        
//        $currentTimestamp = date('c', mktime($time[0], $time[1], $time[2], $date[1], $date[2], $date[0]));
//        
//        
//        
//        $toTime = strtotime("+1 hours", $currentTimestamp);
//               
//    }

}

