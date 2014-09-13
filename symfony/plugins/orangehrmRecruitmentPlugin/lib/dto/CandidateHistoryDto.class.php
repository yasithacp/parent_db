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

class CandidateHistoryDto {

	private $id;
	private $performedDate;
	private $vacancyName;
	private $description;
	private $details;

	public function getId() {
		return $this->id;
	}

	public function getPerformedDate() {
		return $this->performedDate;
	}

	public function getVacancyName() {
		return $this->vacancyName;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getDetails() {
		return $this->details;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setPerformedDate($performedDate) {
		$this->performedDate = $performedDate;
	}

	public function setVacancyName($vacancyName) {
		$this->vacancyName = ($vacancyName == null) ? "" : $vacancyName;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function setDetails($details) {
		$this->details = $details;
	}

        public function getFormattedPerformedDateToDisplay(){
            return set_datepicker_date_format($this->getPerformedDate());
        }

}
