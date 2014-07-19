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
 * View employee list action
 */
class viewEmployeeListAction extends basePimAction {

    /**
     * Index action. Displays employee list
     *      `
     * @param sfWebRequest $request
     */
    public function execute($request) {
        
        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }
        
        $empNumber = $request->getParameter('empNumber');
        $isPaging = $request->getParameter('hdnAction') == 'search' ? 1 : $request->getParameter('pageNo', 1);

        $pageNumber = $isPaging;
        if (!empty($empNumber) && $this->getUser()->hasAttribute('pageNumber')) {
            $pageNumber = $this->getUser()->getAttribute('pageNumber');
        }

        $sortField = $request->getParameter('sortField');
        $sortOrder = $request->getParameter('sortOrder');

        $noOfRecords = sfConfig::get('app_items_per_page');

        $offset = ($pageNumber >= 1) ? (($pageNumber - 1) * $noOfRecords) : ($request->getParameter('pageNo', 1) - 1) * $noOfRecords;

         // Reset filters if requested to
        if ($request->hasParameter('reset')) {
            $this->setFilters(array());
            $this->setPage(1);
        }

        $this->form = new EmployeeSearchForm($this->getFilters());
        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter($this->form->getName()));

            if ($this->form->isValid()) {
                $this->setFilters($this->form->getValues());
            } else {
                $this->setFilters(array());
            }

            $this->setPage(1);
        }

        $filters = $this->getFilters();
        if( isset(  $filters['employee_name'])){
            $filters['employee_name'] = str_replace(' (' . __('Past Employee') . ')', '', $filters['employee_name']['empName']);        
        }
        
        
        $this->filterApply = !empty($filters);

        $accessibleEmployees = UserRoleManagerFactory::getUserRoleManager()->getAccessibleEntityIds('Employee');

        if (count($accessibleEmployees) > 0) {
            $filters['employee_id_list'] = $accessibleEmployees;
            $count = $this->getEmployeeService()->getSearchEmployeeCount( $filters );

            $list = $this->getEmployeeService()->searchEmployeeList( $sortField, $sortOrder, $filters, $offset, $noOfRecords );
            
            //$table = Doctrine::getTable('Employee');
            //$count = $table->getEmployeeCount($filters);

            //$list = $table->getEmployeeList($sortField, $sortOrder, $filters, $offset, $noOfRecords);
        } else {
            $count = 0;
            $list = array();
        }

        $this->setListComponent($list, $count, $noOfRecords, $pageNumber);

        // Show message if list is empty, and we don't already have a message.
        if (empty($this->message) && (count($list) == 0)) {

            // Check to see if we have any employees in system
            $employeeCount = $this->getEmployeeService()->getEmployeeCount();
            $this->messageType = "warning";

            if (empty($employeeCount)) {
                $this->message = __("No Employees Available");
            } else {
                $this->message = __(TopLevelMessages::NO_RECORDS_FOUND);
            }

        }
    }
    
    protected function setListComponent($employeeList, $count, $noOfRecords, $page) {
        
        $configurationFactory = $this->getListConfigurationFactory();

        $permissions = $this->getContext()->get('screen_permissions');
        $runtimeDefinitions = array();
        $buttons = array();

        if ($permissions->canCreate()) {
            $buttons['Add'] = array('label' => 'Add');
        }
        if (!$permissions->canDelete()) {
            $runtimeDefinitions['hasSelectableRows'] = false;
        } else {
            $buttons['Delete'] = array('label' => 'Delete', 'type' => 'submit');
        }

        $runtimeDefinitions['buttons'] = $buttons;
        $configurationFactory->setRuntimeDefinitions($runtimeDefinitions);
        
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        
        ohrmListComponent::setActivePlugin('orangehrmPimPlugin');
        ohrmListComponent::setListData($employeeList);
        ohrmListComponent::setItemsPerPage($noOfRecords);
        ohrmListComponent::setNumberOfRecords($count);      
        ohrmListComponent::setPageNumber($page);
    }
    
    protected function getListConfigurationFactory() {
        $configurationFactory = new EmployeeListConfigurationFactory();        
        return $configurationFactory;
    }

    /**
     * Set's the current page number in the user session.
     * @param $page int Page Number
     * @return None
     */
    protected function setPage($page) {
        $this->getUser()->setAttribute('emplist.page', $page, 'pim_module');
    }

    /**
     * Get the current page number from the user session.
     * @return int Page number
     */
    protected function getPage() {
        return $this->getUser()->getAttribute('emplist.page', 1, 'pim_module');
    }

    /**
     *
     * @param array $filters
     * @return unknown_type
     */
    protected function setFilters(array $filters) {
        return $this->getUser()->setAttribute('emplist.filters', $filters, 'pim_module');
    }

    /**
     *
     * @return unknown_type
     */
    protected function getFilters() {
        return $this->getUser()->getAttribute('emplist.filters', null, 'pim_module');
    }

    protected function _getFilterValue($filters, $parameter, $default = null) {
        $value = $default;
        if (isset($filters[$parameter])) {
            $value = $filters[$parameter];
        }

        return $value;
    }

}
