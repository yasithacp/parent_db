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


class JobCategoryForm extends BaseForm {
	
	private $jobCatService;

	public function getJobCategoryService() {
		if (is_null($this->jobCatService)) {
			$this->jobCatService = new JobCategoryService();
			$this->jobCatService->setJobCategoryDao(new JobCategoryDao());
		}
		return $this->jobCatService;
	}
	
	public function configure() {

		$this->setWidgets(array(
		    'jobCategoryId' => new sfWidgetFormInputHidden(),
		    'name' => new sfWidgetFormInputText(),
		));

		$this->setValidators(array(
		    'jobCategoryId' => new sfValidatorNumber(array('required' => false)),
		    'name' => new sfValidatorString(array('required' => true, 'max_length' => 52, 'trim' => true)),
		));

		$this->widgetSchema->setNameFormat('jobCategory[%s]');
				
	}
	
	public function save(){
		
		$jobCatId = $this->getValue('jobCategoryId');
		if(!empty ($jobCatId)){
			$jobCat = $this->getJobCategoryService()->getJobCategoryById($jobCatId);
		} else {
			$jobCat = new JobCategory();
		}
		$jobCat->setName($this->getValue('name'));
		$jobCat->save();
	}
	
	public function getJobCategoryListAsJson() {
		
		$list = array();
		$jobCatList = $this->getJobCategoryService()->getJobCategoryList();
		foreach ($jobCatList as $jobCat) {
			$list[] = array('id' => $jobCat->getId(), 'name' => $jobCat->getName());
		}
		return json_encode($list);
	}
}

?>
