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


class getInterviewSheduledTimeListJsonAction extends sfAction {
    
    private $jobInterviewService;
    
    /**
     * Get JobInterviewService
     * @returns JobInterviewService Object
     */
    public function getJobInterviewService() {
        
        if (is_null($this->jobInterviewService)) {
            $this->jobInterviewService = new JobInterviewService();
        }
        
        return $this->jobInterviewService;       
    }

    /**
     * Set JobInterviewService
     * @param JobInterviewService $jobInterviewService
     */
    public function setJobInterviewService(JobInterviewService $jobInterviewService) {
        $this->jobInterviewService = $jobInterviewService;
    }

    /**
     *
     * @param <type> $request
     */
    public function execute($request) {
        
        $candidateId = $request->getParameter('candidateId');
        //print($candidateId);die;
        
        //$timeList = $this->getJobInterviewService();
        
        //$dao = new CandidateDao();
        //print_r(count($dao->getCandidateById($candidateId)->getJobCandidateVacancy()));die;
        
        $service = new JobInterviewService();
//        $service->getInterviewListByCandidateIdAndInterviewDateAndTime(1, '2011-08-10', '10:30:00');
        

//        $allowedVacancyList = $this->getUser()->getAttribute('user')->getAllowedVacancyList();
//
//        $this->setLayout(false);
//        sfConfig::set('sf_web_debug', false);
//        sfConfig::set('sf_debug', false);
//
//        $vacancyList = array();
//
//        if ($this->getRequest()->isXmlHttpRequest()) {
//            $this->getResponse()->setHttpHeader('Content-Type', 'application/json; charset=utf-8');
//        }
//
//        $jobTitle = $request->getParameter('jobTitle');
//
//        $vacancyService = new VacancyService();
//        $vacancyList = $vacancyService->getVacancyListForJobTitle($jobTitle, $allowedVacancyList, true);
//	$newVacancyList = array();
//        foreach ($vacancyList as $vacancy) {
//            if ($vacancy['status'] == JobVacancy::CLOSED) {
//                $vacancy['name'] = $vacancy['name'] . " (Closed)";
//            }
//            $newVacancyList[] = $vacancy;
//        }     
//        return $this->renderText(json_encode($newVacancyList));
    }

    
}
