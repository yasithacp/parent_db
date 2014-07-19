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

class applyVacancyAction extends sfAction {

    /**
     * @param sfForm $form
     * @return
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }
    
    /**
     *
     * @return ApplyVacancyForm 
     */
    public function getForm() {
        return $this->form;
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
     *
     * @param <type> $request
     */
    public function execute($request) {
        $param = null;
        $this->candidateId = null;

        $this->vacancyId = $request->getParameter('id');
        //$this->candidateId = $request->getParameter('candidateId');
	$this->getResponse()->setTitle(__("Vacancy Apply Form"));
        //$param = array('candidateId' => $this->candidateId);
        $this->setForm(new ApplyVacancyForm(array(), $param, true));

        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }
        if (!empty($this->vacancyId)) {
            $vacancy = $this->getVacancyService()->getVacancyById($this->vacancyId);
	    if(empty ($vacancy)){
		   $this->redirect('recruitmentApply/jobs.html');
	    }
            $this->description = $vacancy->getDescription();
            $this->name = $vacancy->getName();
        } else {
		$this->redirect('recruitmentApply/jobs.html');
	}
        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
            $file = $request->getFiles($this->form->getName());
            
            if ($_FILES['addCandidate']['size']['resume'] > 1024000 ) {
                 $this->templateMessage = array ('WARNING', __(TopLevelMessages::FILE_SIZE_SAVE_FAILURE));
	    } else if ($_FILES == null){
		 $this->getUser()->setFlash('templateMessage', array('warning', __(TopLevelMessages::FILE_SIZE_SAVE_FAILURE)));
		 $this->redirect('recruitmentApply/applyVacancy?id=' . $this->vacancyId);
            } else {

                if ($this->form->isValid()) { 
                    
                    $result = $this->form->save();                   
                    if (isset($result['messageType'])) {
                        $this->messageType = $result['messageType'];
                        $this->message = $result['message'];
                    } else {
                        $this->candidateId = $result['candidateId'];
			if(!empty ($this->candidateId)){
			    $this->messageType = 'success';
                            $this->message = __('Application Received');
			}
			
                        //$this->getUser()->setFlash('templateMessage', array('success', __('Your Application for the Position of ' . $this->name . ' Was Received')));
                        //$this->redirect('recruitmentApply/applyVacancy?id=' . $this->vacancyId . '&candidateId=' . $this->form->candidateId);
                    }                    
                }
            }
        }
    }

}

