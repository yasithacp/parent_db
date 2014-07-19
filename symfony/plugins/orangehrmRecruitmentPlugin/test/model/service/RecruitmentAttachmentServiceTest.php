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

require_once sfConfig::get('sf_test_dir') . '/util/TestDataService.php';

/**
 * @group Recruitment
 */
class RecruitmentAttachmentServiceTest extends PHPUnit_Framework_TestCase {

	private $recruitmentAttachmentService;
	protected $fixture;

	/**
	 * Set up method
	 */
	protected function setUp() {

		$this->recruitmentAttachmentService = new RecruitmentAttachmentService();
		$this->fixture = sfConfig::get('sf_plugins_dir') . '/orangehrmRecruitmentPlugin/test/fixtures/CandidateDao.yml';
		TestDataService::populate($this->fixture);
	}

	/**
	 *
	 */
	public function testSaveVacancyAttachment() {

		$resume = new JobVacancyAttachment();

		$recruitmentAttachmentDao = $this->getMock('RecruitmentAttachmentDao');
		$recruitmentAttachmentDao->expects($this->once())
			->method('saveVacancyAttachment')
			->with($resume)
			->will($this->returnValue(true));

		$this->recruitmentAttachmentService->setRecruitmentAttachmentDao($recruitmentAttachmentDao);

		$return = $this->recruitmentAttachmentService->saveVacancyAttachment($resume);
		$this->assertTrue($return);
	}

	/**
	 * 
	 */
	public function testSaveCandidateAttachment() {

		$resume = new JobCandidateAttachment();

		$recruitmentAttachmentDao = $this->getMock('RecruitmentAttachmentDao');
		$recruitmentAttachmentDao->expects($this->once())
			->method('saveCandidateAttachment')
			->with($resume)
			->will($this->returnValue(true));

		$this->recruitmentAttachmentService->setRecruitmentAttachmentDao($recruitmentAttachmentDao);

		$return = $this->recruitmentAttachmentService->saveCandidateAttachment($resume);
		$this->assertTrue($return);
	}

	/**
	 * Testing getVacancyAttachments
	 */
	public function testGetVacancyAttachment() {

		$vacancyId = 1;
		$vacancyList = TestDataService::loadObjectList('JobVacancyAttachment', $this->fixture, 'JobVacancyAttachment');
		$testVacancyList = array($vacancyList[0], $vacancyList[1]);

		$recruitmentAttachmentDao = $this->getMock('RecruitmentAttachmentDao');

		$recruitmentAttachmentDao->expects($this->once())
			->method('getVacancyAttachment')
			->will($this->returnValue($testVacancyList));

		$this->recruitmentAttachmentService->setRecruitmentAttachmentDao($recruitmentAttachmentDao);

		$readVacancyList = $this->recruitmentAttachmentService->getVacancyAttachment($vacancyId);

		$this->assertEquals($readVacancyList, $testVacancyList);
	}

	/**
	 * Testing getVacancyAttachments
	 */
	public function testGetCandidateAttachment() {

		$candidateId = 1;
		$candidateList = TestDataService::loadObjectList('JobCandidateAttachment', $this->fixture, 'JobCandidateAttachment');
		$testCandidateList = array($candidateList[0], $candidateList[1]);

		$recruitmentAttachmentDao = $this->getMock('RecruitmentAttachmentDao');

		$recruitmentAttachmentDao->expects($this->once())
			->method('getCandidateAttachment')
			->will($this->returnValue($testCandidateList));

		$this->recruitmentAttachmentService->setRecruitmentAttachmentDao($recruitmentAttachmentDao);

		$readCandidateList = $this->recruitmentAttachmentService->getCandidateAttachment($candidateId);

		$this->assertEquals($readCandidateList, $testCandidateList);
	}

	public function testGetRecruitmentAttachmentDao() {

		$dao = $this->recruitmentAttachmentService->getRecruitmentAttachmentDao();
		$this->assertTrue($dao instanceof RecruitmentAttachmentDao);
	}

	public function testGetAttachmentForCandidate() {

		$id = 1;
		$screen = "CANDIDATE";
		$candidateList = TestDataService::loadObjectList('JobCandidateAttachment', $this->fixture, 'JobCandidateAttachment');
		$recruitmentAttachmentDao = $this->getMock('RecruitmentAttachmentDao');

		$recruitmentAttachmentDao->expects($this->once())
			->method('getCandidateAttachment')
			->will($this->returnValue($candidateList));

		$this->recruitmentAttachmentService->setRecruitmentAttachmentDao($recruitmentAttachmentDao);

		$attachment = $this->recruitmentAttachmentService->getAttachment($id, $screen);
		$this->assertEquals($attachment, $candidateList);
	}

	public function testGetAttachmentForVacancy() {

		$id = 1;
		$screen = "VACANCY";
		$candidateList = TestDataService::loadObjectList('JobVacancyAttachment', $this->fixture, 'JobVacancyAttachment');
		$recruitmentAttachmentDao = $this->getMock('RecruitmentAttachmentDao');

		$recruitmentAttachmentDao->expects($this->once())
			->method('getVacancyAttachment')
			->will($this->returnValue($candidateList));

		$this->recruitmentAttachmentService->setRecruitmentAttachmentDao($recruitmentAttachmentDao);
		$attachment = $this->recruitmentAttachmentService->getAttachment($id, $screen);
		$this->assertEquals($attachment, $candidateList);
	}

	public function testGetAttachmentForInterview() {

		$id = 1;
		$screen = "INTERVIEW";
		$candidateList = TestDataService::loadObjectList('JobInterviewAttachment', $this->fixture, 'JobInterviewAttachment');
		$recruitmentAttachmentDao = $this->getMock('RecruitmentAttachmentDao');

		$recruitmentAttachmentDao->expects($this->once())
			->method('getInterviewAttachment')
			->will($this->returnValue($candidateList));

		$this->recruitmentAttachmentService->setRecruitmentAttachmentDao($recruitmentAttachmentDao);
		$attachment = $this->recruitmentAttachmentService->getAttachment($id, $screen);
		$this->assertEquals($attachment, $candidateList);
	}

	public function testGetAttachmentForInvalidScreen() {

		$id = 1;
		$screen = "INVALID";
		$attachment = $this->recruitmentAttachmentService->getAttachment($id, $screen);
		$this->assertFalse($attachment);
	}

	public function testGetAttachmentsForVacancy() {

		$id = 1;
		$screen = "VACANCY";
		$candidateList = TestDataService::loadObjectList('JobVacancyAttachment', $this->fixture, 'JobVacancyAttachment');
		$recruitmentAttachmentDao = $this->getMock('RecruitmentAttachmentDao');

		$recruitmentAttachmentDao->expects($this->once())
			->method('getVacancyAttachments')
			->will($this->returnValue($candidateList));

		$this->recruitmentAttachmentService->setRecruitmentAttachmentDao($recruitmentAttachmentDao);
		$attachment = $this->recruitmentAttachmentService->getAttachments($id, $screen);
		$this->assertEquals($attachment, $candidateList);
	}

	public function testGetAttachmentsForInterview() {

		$id = 1;
		$screen = "INTERVIEW";
		$candidateList = TestDataService::loadObjectList('JobInterviewAttachment', $this->fixture, 'JobInterviewAttachment');
		$recruitmentAttachmentDao = $this->getMock('RecruitmentAttachmentDao');

		$recruitmentAttachmentDao->expects($this->once())
			->method('getInterviewAttachments')
			->will($this->returnValue($candidateList));

		$this->recruitmentAttachmentService->setRecruitmentAttachmentDao($recruitmentAttachmentDao);
		$attachment = $this->recruitmentAttachmentService->getAttachments($id, $screen);
		$this->assertEquals($attachment, $candidateList);
	}

	public function testGetAttachmentsForInvalidScreen() {

		$id = 1;
		$screen = "INVALID";
		$attachment = $this->recruitmentAttachmentService->getAttachments($id, $screen);
		$this->assertFalse($attachment);
	}

	public function testGetNewAttachmentForVacancy() {

		$id = 1;
		$screen = "VACANCY";
		$attach = $this->recruitmentAttachmentService->getNewAttachment($screen, $id);
		$this->assertTrue($attach instanceof JobVacancyAttachment);
		$this->assertEquals($attach->getVacancyId(), 1);
	}

	public function testGetNewAttachmentForInterview() {

		$id = 1;
		$screen = "INTERVIEW";
		$attach = $this->recruitmentAttachmentService->getNewAttachment($screen, $id);
		$this->assertTrue($attach instanceof JobInterviewAttachment);
		$this->assertEquals($attach->getInterviewId(), 1);
	}

}
