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

class viewProjectsAction extends sfAction {

	private $projectService;

	public function getProjectService() {
		if (is_null($this->projectService)) {
			$this->projectService = new ProjectService();
			$this->projectService->setProjectDao(new ProjectDao());
		}
		return $this->projectService;
	}

	public function setForm(sfForm $form) {
		if (is_null($this->form)) {
			$this->form = $form;
		}
	}

	/**
	 *
	 * @param <type> $request
	 */
	public function execute($request) {

		$usrObj = $this->getUser()->getAttribute('user');
		if (!($usrObj->isAdmin() || $usrObj->isProjectAdmin())) {
			$this->redirect('pim/viewPersonalDetails');
		}
		$allowedProjectList = $usrObj->getAllowedProjectList();
		$isPaging = $request->getParameter('pageNo');
		$sortField = $request->getParameter('sortField');
		$sortOrder = $request->getParameter('sortOrder');
		$projectId = $request->getParameter('projectId');

		$this->setForm(new SearchProjectForm());

		$pageNumber = $isPaging;
		if ($projectId > 0 && $this->getUser()->hasAttribute('pageNumber')) {
			$pageNumber = $this->getUser()->getAttribute('pageNumber');
		}
		$limit = Project::NO_OF_RECORDS_PER_PAGE;
		$offset = ($pageNumber >= 1) ? (($pageNumber - 1) * $limit) : ($request->getParameter('pageNo', 1) - 1) * $limit;
		$searchClues = $this->_setSearchClues($sortField, $sortOrder, $offset, $limit);
		if (!empty($sortField) && !empty($sortOrder) || $isPaging > 0 || $projectId > 0) {
			if ($this->getUser()->hasAttribute('searchClues')) {
				$searchClues = $this->getUser()->getAttribute('searchClues');
				$searchClues['offset'] = $offset;
				$searchClues['sortField'] = $sortField;
				$searchClues['sortOrder'] = $sortOrder;				
				$this->form->setDefaultDataToWidgets($searchClues);
			}
		} else {
			$this->getUser()->setAttribute('searchClues', $searchClues);
		}
		
		$projectList = $this->getProjectService()->searchProjects($searchClues, $allowedProjectList);		
		$projectListCount = $this->getProjectService()->getSearchProjectListCount($searchClues, $allowedProjectList);
		$this->_setListComponent($projectList, $limit, $pageNumber, $projectListCount, $usrObj);
		$this->getUser()->setAttribute('pageNumber', $pageNumber);
		$params = array();
		$this->parmetersForListCompoment = $params;

		if ($this->getUser()->hasFlash('templateMessage')) {
			list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
		}

		if ($request->isMethod('post')) {
			$offset = 0;
			$pageNumber = 1;
			$this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {				
				$searchClues = $this->_setSearchClues($sortField, $sortOrder, $offset, $limit);
				$this->getUser()->setAttribute('searchClues', $searchClues);
				$searchedProjectList = $this->getProjectService()->searchProjects($searchClues, $allowedProjectList);
				$projectListCount = $this->getProjectService()->getSearchProjectListCount($searchClues, $allowedProjectList);
				$this->_setListComponent($searchedProjectList, $limit, $pageNumber, $projectListCount,$usrObj);
			}
		}
	}

	/**
	 *
	 * @param <type> $projectList
	 * @param <type> $noOfRecords
	 * @param <type> $pageNumber
	 */
	private function _setListComponent($projectList, $limit, $pageNumber, $recordCount,$usrObj) {

		$configurationFactory = new ProjectHeaderFactory();
		if (!$usrObj->isAdmin()) {
			$configurationFactory->setRuntimeDefinitions(array(
			    'hasSelectableRows' => false,
			    'buttons' => array(),
			));
		}
		
		ohrmListComponent::setPageNumber($pageNumber);
		ohrmListComponent::setConfigurationFactory($configurationFactory);
		ohrmListComponent::setListData($projectList);
		ohrmListComponent::setItemsPerPage($limit);
		ohrmListComponent::setNumberOfRecords($recordCount);
	}

	private function _setSearchClues($sortField, $sortOrder, $offset, $limit) {
		return array(
		    'customer' => $this->form->getValue('customer'),
		    'project' => $this->form->getValue('project'),
		    'projectAdmin' => $this->form->getValue('projectAdmin'),
		    'sortField' => $sortField,
		    'sortOrder' => $sortOrder,
		    'offset' => $offset,
		    'limit' => $limit
		);
	}

}

