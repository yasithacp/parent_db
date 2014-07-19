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

class pimCsvImportAction extends baseCsvImportAction {

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

		$this->setForm(new PimCsvImportForm());

		if ($this->getUser()->hasFlash('templateMessage')) {
			list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
		}

		if ($request->isMethod('post')) {

			$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
			$file = $request->getFiles($this->form->getName());
			if ($_FILES['pimCsvImport']['size']['csvFile'] > 1024000 || $_FILES == null) {
				$this->getUser()->setFlash('templateMessage', array('warning', __('Failed to Import: File Size Exceeded')));
				$this->redirect('admin/pimCsvImport');
			}
			if ($this->form->isValid()) {
				$result = $this->form->save();

				if (isset($result['messageType'])) {
					$this->messageType = $result['messageType'];
					$this->message = $result['message'];
				} else {
				    if($result != 0) {
					   $this->getUser()->setFlash('templateMessage', array('success', __('Number of Records Imported').": ".$result));
				    } else {
				        $this->getUser()->setFlash('templateMessage', array('warning', __('Failed to Import: No Compatible Records')));
				    }
					$this->redirect('admin/pimCsvImport');
				}
			}
		}
	}

}

?>
