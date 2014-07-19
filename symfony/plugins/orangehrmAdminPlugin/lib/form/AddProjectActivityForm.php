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


class AddProjectActivityForm extends BaseForm {
	
	private $projectService;
	public $edited = false;

	public function getProjectService() {
		if (is_null($this->projectService)) {
			$this->projectService = new ProjectService();
			$this->projectService->setProjectDao(new ProjectDao());
		}
		return $this->projectService;
	}
	
	public function configure() {

		$this->setWidgets(array(
		    'projectId' => new sfWidgetFormInputHidden(),
		    'activityId' => new sfWidgetFormInputHidden(),
		    'activityName' => new sfWidgetFormInputText(),
		    
		));

		$this->setValidators(array(
		    'projectId' => new sfValidatorNumber(array('required' => true)),
		    'activityId' => new sfValidatorNumber(array('required' => false)),
		    'activityName' => new sfValidatorString(array('required' => true, 'max_length' => 102)),
		    
		));

		$this->widgetSchema->setNameFormat('addProjectActivity[%s]');

	}
	
	public function save(){
		
		$projectId = $this->getValue('projectId');
		$activityId = $this->getValue('activityId');
		
		if(!empty ($activityId)){
			$activity = $this->getProjectService()->getProjectActivityById($activityId);
			$this->edited = true;
		} else {
			$activity = new ProjectActivity();
		}
		
		$activity->setProjectId($projectId);
		$activity->setName($this->getValue('activityName'));
		$activity->setIsDeleted(ProjectActivity::ACTIVE_PROJECT);
		$activity->save();
		return $projectId;
	}

}

?>
