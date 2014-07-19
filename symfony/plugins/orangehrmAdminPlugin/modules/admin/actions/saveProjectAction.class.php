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

class saveProjectAction extends sfAction {

	private $projectService;
	private $customerService;

	public function getCustomerService() {
		if (is_null($this->customerService)) {
			$this->customerService = new CustomerService();
			$this->customerService->setCustomerDao(new CustomerDao());
		}
		return $this->customerService;
	}

	public function getProjectService() {
		if (is_null($this->projectService)) {
			$this->projectService = new ProjectService();
			$this->projectService->setProjectDao(new ProjectDao());
		}
		return $this->projectService;
	}

	/**
	 * @param sfForm $form
	 * @return
	 */
	public function setForm(sfForm $form) {
		if (is_null($this->form)) {
			$this->form = $form;
		}
	}

	public function execute($request) {

		$usrObj = $this->getUser()->getAttribute('user');
		if (!($usrObj->isAdmin() || $usrObj->isProjectAdmin())) {
			$this->redirect('pim/viewPersonalDetails');
		}
		$this->isProjectAdmin = false;
		if ($usrObj->isProjectAdmin()) {
			$this->isProjectAdmin = true;
		}
		$this->projectId = $request->getParameter('projectId');
		$this->custId = $request->getParameter('custId');

		$values = array('projectId' => $this->projectId);
		$this->setForm(new ProjectForm(array(), $values));
		$this->customerForm = new CustomerForm();

		if ($this->custId > 0) {
			$customer = $this->getCustomerService()->getCustomerById($this->custId);
			$customerName = $customer->getName();
			$this->form->setDefault('customerName', $customerName);
			print_r($this->customerName);
			$this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));
		}

		if (!empty($this->projectId)) {
			$this->activityForm = new AddProjectActivityForm();
			$this->copyActForm = new CopyActivityForm();
			//For list activities
			$this->activityList = $this->getProjectService()->getActivityListByProjectId($this->projectId);
			$this->_setListComponent($this->activityList);
			$params = array();
			$this->parmetersForListCompoment = $params;
		}

		if ($this->getUser()->hasFlash('templateMessage')) {
			list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
		}

		if ($this->getUser()->hasFlash('templateMessageAct')) {
			list($this->messageTypeAct, $this->messageAct) = $this->getUser()->getFlash('templateMessageAct');
		}

		if ($request->isMethod('post')) {

			$this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {

				$projectId = $this->form->save();
				if ($this->form->edited) {
					$this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::UPDATE_SUCCESS)));
				} else {
					$this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));
				}
				$this->redirect('admin/saveProject?projectId=' . $projectId);
			}
		}
	}

	/**
	 *
	 * @param <type> $customerList
	 * @param <type> $noOfRecords
	 * @param <type> $pageNumber
	 */
	private function _setListComponent($customerList, $noOfRecords, $pageNumber) {

		$configurationFactory = new ProjectActivityHeaderFactory();
		ohrmListComponent::setConfigurationFactory($configurationFactory);
		ohrmListComponent::setListData($customerList);
	}

}

