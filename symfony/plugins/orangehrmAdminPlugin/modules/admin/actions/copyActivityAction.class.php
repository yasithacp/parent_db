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

class copyActivityAction extends sfAction {

	private $projectService;

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

	/**
	 *
	 * @param <type> $request
	 */
	public function execute($request) {

		$this->setForm(new CopyActivityForm());
		$projectId = $request->getParameter('projectId');
		$this->form->bind($request->getParameter($this->form->getName()));

		$projectActivityList = $this->getProjectService()->getActivityListByProjectId($projectId);
		if ($this->form->isValid()) {
			$activityNameList = $request->getParameter('activityNames', array());
			$activities = new Doctrine_Collection('ProjectActivity');

			$isUnique = true;
			foreach ($activityNameList as $activityName) {
				foreach ($projectActivityList as $projectActivity) {
					if (strtolower($activityName) == strtolower($projectActivity->getName())) {
						$isUnique = false;
						break;
					}
				}
			}
			if ($isUnique) {
				foreach ($activityNameList as $activityName) {

					$activity = new ProjectActivity();
					$activity->setProjectId($projectId);
					$activity->setName($activityName);
					$activity->setIsDeleted(ProjectActivity::ACTIVE_PROJECT);
					$activities->add($activity);
				}
				$activities->save();
				$this->getUser()->setFlash('templateMessageAct', array('success', __('Successfully Copied')));
			} else {
				$this->getUser()->setFlash('templateMessageAct', array('failure', __('Name Already Exists')));
			}
			
			$this->redirect('admin/saveProject?projectId=' . $projectId);
		}
	}

}

?>
