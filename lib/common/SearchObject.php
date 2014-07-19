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


/**
 * Class to represent a search
 */
 class SearchObject {

	const SEARCH_FIELD_NONE = -1;
	const SORT_ORDER_ASC = 'ASC';
	const SORT_ORDER_DESC = 'DESC';

	private $pageNumber = 1;
	private $searchField = self::SEARCH_FIELD_NONE;
	private $searchString = '';
    private $sortField = 0;
    private $sortOrder = self::SORT_ORDER_ASC;

	public function setPageNumber($pageNumber) {
	    $this->pageNumber = $pageNumber;
	}

	public function getPageNumber() {
	    return $this->pageNumber;
	}

	public function setSearchField($searchField) {
	    $this->searchField = $searchField;
	}

	public function getSearchField() {
	    return $this->searchField;
	}

	public function setSearchString($searchString) {
	    $this->searchString = $searchString;
	}

	public function getSearchString() {
	    return $this->searchString;
	}

	public function setSortField($sortField) {
	    $this->sortField = $sortField;
	}

	public function getSortField() {
	    return $this->sortField;
	}

	public function setSortOrder($sortOrder) {
	    $this->sortOrder = $sortOrder;
	}

	public function getSortOrder() {
	    return $this->sortOrder;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
	}
}

?>
