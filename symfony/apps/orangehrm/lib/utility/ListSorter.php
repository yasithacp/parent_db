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
 * ListSorter 
 */
class ListSorter {
	
	const ASCENDING = 'ASC';
	const DESCENDING = 'DESC';
	
	protected $sessionVarName;
	protected $nameSpace;
	protected $sort;
	 
	protected $sortField = null;
	protected $sortOrder = null;
	protected $sortUrl = null;
	
	protected $user;
	
	/** Set via config */ 
	protected $desc_class;
	protected $asc_class;
	protected $default_class;
		 
	/**
	 * Constructor
	 */
	public function __construct($sessionVarName, $nameSpace, $user, $defaultSort) {
	    $this->sessionVarName = $sessionVarName;
	    $this->nameSpace = $nameSpace;	    
	    
	    $sort = $user->getAttribute($sessionVarName, null, $nameSpace);	    
	    $this->sort = is_null($sort) ? $defaultSort : $sort;
	    
	    $this->user = $user;
	    
	    $this->desc_class = sfConfig::get('app_sort_desc_class');
	    $this->asc_class = sfConfig::get('app_sort_asc_class');
	    $this->default_class = sfConfig::get('app_sort_default_class');
	    
	}	

    public function setSort(array $sort) {
        if (!is_null($sort[0]) && is_null($sort[1])) {
            $sort[1] = self::ASCENDING;
        }
        $this->sort = $sort;
        $this->user->setAttribute($this->sessionVarName, $sort, $this->nameSpace);
    }
        
    public function getSort() {
        return $this->sort;
    }
    
	public function sortLink($fieldName, $displayName = null, $url, $attributes = array(),$extraParam = '') {

		$class = $this->default_class;
		$nextOrder = self::ASCENDING;	

		/* Default order to Ascending and change if sorted ascending in current page */		
		if ($this->sort[0] === $fieldName) {
	
		    if ($this->sort[1] === self::ASCENDING) {
		        $nextOrder = self::DESCENDING;
		        $class = $this->asc_class;
			} else if ($this->sort[1] == self::DESCENDING) {
			    $class = $this->desc_class;
			} 
		} 
		$title = empty($displayName) ? $fieldName : $displayName;
		
                $i18n = sfContext::getInstance()->getI18N();
                if ($nextOrder == self::ASCENDING ) {
                    $toolTip = $i18n->__('Sort in Ascending Order');
                } else {
                    $toolTip = $i18n->__('Sort in Descending Order');
                }
                        
                $attributes['title'] = $toolTip;
		$attributes['class'] = $class;
                
                        
                
		$url .= '?sort=' . $fieldName . '&order=' . $nextOrder;	
		if($extraParam !='')
			$url .= '&'.$extraParam;	
		return link_to($title, $url, $attributes);
	}
}
