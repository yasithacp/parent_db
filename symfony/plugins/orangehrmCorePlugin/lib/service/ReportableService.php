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

class ReportableService {

    // ReportableDao Data Access Object
    private $reportableDao;

    /**
     * Gets the ReportableDao Data Access Object
     * @return ReportableDao
     */
    public function getReportableDao() {

        if (is_null($this->reportableDao)) {
            $this->reportableDao = new ReportableDao();
        }

        return $this->reportableDao;
    }

    /**
     * Sets Reportable Data Access Object
     * @param ReportableDao $ReportableDao
     * @return void
     */
    public function setReportableDao(ReportableDao $reportableDao) {

        $this->reportableDao = $reportableDao;
    }

    /**
     * Gets selected filter fields array
     * @param integer $reportId
     * @return SelectedFilterField[]
     */
    public function getSelectedFilterFields($reportId, $order) {

        $selectedFilterFields = $this->getReportableDao()->getSelectedFilterFields($reportId, $order);

        return $selectedFilterFields;
    }
    
    public function getSelectedFilterFieldNames($reportId, $order) {
        $selectedFilterFields = $this->getReportableDao()->getSelectedFilterFieldNames($reportId, $order);

        return $selectedFilterFields;        
    }

    /**
     * Gets selected display fields array.
     * @param integer $reportId
     * @return SelectedDisplayField[]
     */
    public function getSelectedDisplayFields($reportId) {

        $selectedDisplayFields = $this->getReportableDao()->getSelectedDisplayFields($reportId);

        return $selectedDisplayFields;
    }

    /**
     * Gets selected display fields group array.
     * @param integer $reportId
     * @return SelectedDisplayFieldGroup[]
     */
    public function getSelectedDisplayFieldGroups($reportId) {

        $selectedDisplayFields = $this->getReportableDao()->getSelectedDisplayFieldGroups($reportId);

        return $selectedDisplayFields;
    }    
    
    /**
     * Gets meta display fields array.
     * @param integer $reportId
     * @return SelectedDisplayField[]
     */
    public function getMetaDisplayFields($reportGroupId) {

        $metaDisplayFields = $this->getReportableDao()->getMetaDisplayFields($reportGroupId);

        return $metaDisplayFields;
    }

    /**
     * Gets the report for the given report id.
     * @param integer $reportId
     * @return Report
     */
    public function getReport($reportId) {

        $report = $this->getReportableDao()->getReport($reportId);

        return $report;
    }

    /**
     * Gets the report group for the given report group id.
     * @param integer $reportGroupId
     * @return ReportGroup
     */
    public function getReportGroup($reportGroupId) {

        $reportGroup = $this->getReportableDao()->getReportGroup($reportGroupId);

        return $reportGroup;
    }

    /**
     * Gets all display field groups for given report group
     */
    public function getDisplayFieldGroupsForReportGroup($reportGroupId) {
        $displayGroups = $this->getReportableDao()->getDisplayFieldGroupsForReportGroup($reportGroupId);

        return $displayGroups;
    }
    
    public function getDisplayFieldsForReportGroup($reportGroupId) {
        $displayFields = $this->getReportableDao()->getDisplayFieldsForReportGroup($reportGroupId);
        return $displayFields;
    }  
    
    /**
     * Gets selected group field for the given report id.
     * @param integer $reportId
     * @return SelectedGroupField
     */
    public function getSelectedGroupField($reportId) {

        $selectedGroupField = $this->getReportableDao()->getSelectedGroupField($reportId);

        return $selectedGroupField;
    }

    public function getRuntimeFilterFields($reportGroupId, $type, $selectedFilterFieldIds) {

        $runtimeFilterFields = $this->getReportableDao()->getRuntimeFilterFields($reportGroupId, $type, $selectedFilterFieldIds);

        return $runtimeFilterFields;
    }

    public function getFilterFieldsForReportGroup($reportGroupId) {
        $filterFields = $this->getReportableDao()->getFilterFieldsForReportGroup($reportGroupId);
        
        return $filterFields;
    }
    
    public function getRequiredFilterFieldsForReportGroup($reportGroupId) {
        
        return $this->getReportableDao()->getRequiredFilterFieldsForReportGroup($reportGroupId);

    }    
    
    /**
     * Executes the query and return the results as an array.
     * @param string $sql
     * @return array
     */
    public function executeSql($sql) {

        $results = $this->getReportableDao()->executeSql($sql);

        return $results;
    }

    /**
     *
     */
    public function getFilterFieldById($filterFieldId) {

        $filterField = $this->getReportableDao()->getFilterFieldById($filterFieldId);

        return $filterField;
    }

    /**
     * Gets Project Activity, given activity id.
     * @param integer $activityId
     * @return ProjectActivity
     */
    public function getProjectActivityByActivityId($activityId) {

        $projectActivity = $this->getReportableDao()->getProjectActivityByActivityId($activityId);

        return $projectActivity;
    }

    public function getSelectedCompositeDisplayFields($reportId) {

        $selectedCompositeDisplayField = $this->getReportableDao()->getSelectedCompositeDisplayFields($reportId);

        return $selectedCompositeDisplayField;
    }

    public function getAllPredefinedReports($type, $sortField = 'name', $sortOrder = 'ASC'){
        
        $reports = $this->getReportableDao()->getAllPredefinedReports($type, $sortField, $sortOrder);
        return $reports;
    }

    public function getSelectedFilterFieldsByType($reportId, $type, $order){
        $selectedFilterFields = $this->getReportableDao()->getSelectedFilterFieldsByType($reportId, $type, $order);
        return $selectedFilterFields;
    }

    /**
     * 
     * @param <type> $type
     * @param <type> $searchString
     * @return <type> 
     */
    public function getPredefinedReportsByPartName($type, $searchString, $noOfRecords = NULL , $offset = NULL, $sortField = 'name', $sortOrder = 'ASC'){
        $reports = $this->getReportableDao()->getPredefinedReportsByPartName($type, $searchString, $noOfRecords, $offset, $sortField, $sortOrder);
        return $reports;
    }
    
    
    /**
     * 
     * @param <type> $type
     * @param <type> $searchString
     * @return <type> 
     */
    public function getPredefinedReportCountByPartName($type, $searchString){
        $count = $this->getReportableDao()->getPredefinedReportCountByPartName($type, $searchString);
        return $count;
    }    

    /**
     * Delete reports given report ids.
     * @param integer[] $reportIds
     * @return integer
     */
    public function deleteReports($reportIds){
        $results = $this->getReportableDao()->deleteReports($reportIds);
        return $results;
    }

    public function getPredefinedReportsCount($type) {
        $results = $this->getReportableDao()->getPredefinedReportsCount($type);
        return $results;
    }

    public function getPredefinedReports($type, $noOfRecords, $offset, $sortField = 'name', $sortOrder = 'ASC') {
        $reportList = $this->getReportableDao()->getPredefinedReports($type, $noOfRecords, $offset, $sortField, $sortOrder);
        return $reportList;
    }

    public function saveReport($reportName, $reportGroupId, $useFilterField, $type){
        $result = $this->getReportableDao()->saveReport($reportName, $reportGroupId, $useFilterField, $type);
        return $result;
    }
    
    public function updateReportName($reportId, $name) {
        $result = $this->getReportableDao()->updateReportName($reportId, $name);
        return $result;
    }

    public function saveSelectedFilterField($reportId, $filterFieldId, $filterFieldOrder, $value1, $value2, $whereCondition, $type){
        $result = $this->getReportableDao()->saveSelectedFilterField($reportId, $filterFieldId, $filterFieldOrder, $value1, $value2, $whereCondition, $type);
        return $result;
    }

    public function saveSelectedDispalyField($displayFieldId, $reportId){
        $result = $this->getReportableDao()->saveSelectedDispalyField($displayFieldId, $reportId);
        return $result;
    }
    
   public function saveSelectedDisplayFieldGroup($displayFieldGroupId, $reportId){
        $result = $this->getReportableDao()->saveSelectedDisplayFieldGroup($displayFieldGroupId, $reportId);
        return $result;
    }
        

    /**
     * Gets a filter field given name
     * @param string $name
     * @return FilterField
     */
    public function getFilterFieldByName($name){
        $filterField = $this->getReportableDao()->getFilterFieldByName($name);
        return $filterField;
    }

    public function removeSelectedFilterFields($reportId) {
        $this->getReportableDao()->removeSelectedFilterFields($reportId);
    }
    /**
     * Saves a custom display field.
     * @param string[] $columns
     * @return DisplayField
     */
    public function saveCustomDisplayField($columns){
        $displayField = $this->getReportableDao()->saveCustomDisplayField($columns);
        return $displayField;
    }

    /**
     *
     * @param <type> $customDisplayFieldName
     * @return <type> 
     */
    public function deleteCustomDisplayField($customDisplayFieldName) {
        $result = $this->getReportableDao()->deleteCustomDisplayField($customDisplayFieldName);
        return $result;
    }

    /**
     *
     * @param <type> $name
     * @return <type> 
     */
    public function getDisplayFieldByName($name) {
        $result = $this->getReportableDao()->getDisplayFieldByName($name);
        return $result;
    }
    
    public function removeSelectedDisplayFields($reportId) {
        $this->getReportableDao()->removeSelectedDisplayFields($reportId);
    }
    
    public function removeSelectedDisplayFieldGroups($reportId) {
        $this->getReportableDao()->removeSelectedDisplayFieldGroups($reportId);
    }    
    
}

