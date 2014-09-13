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

class deleteJobVacancyAction extends baseRecruitmentAction {

    private $vacancyService;

    /**
     * Get VacancyService
     * @returns vacancyService
     */
    protected function getVacancyService() {

        if (is_null($this->vacancyService)) {
            $this->vacancyService = new VacancyService();
            $this->vacancyService->setVacancyDao(new VacancyDao());
        }
        return $this->vacancyService;
    }
    
    /**
     *
     * @param <type> $request
     */
    public function execute($request) {

        $toBeDeletedVacancyIds = $request->getParameter('chkSelectRow');

        $isDeletionSucceeded = $this->getVacancyService()->deleteVacancies($toBeDeletedVacancyIds);

            $this->getUser()->setFlash('vacancyDeletionMessageItems', array('success', __(TopLevelMessages::DELETE_SUCCESS)));
       
        $this->redirect('recruitment/viewJobVacancy');
    }

}
