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
 * Config Service: Manages configuration entries in hs_hr_config
 *
 */
class ConfigService extends BaseService {

    private $configDao;

    const KEY_LEAVE_PERIOD_DEFINED = "leave_period_defined";
    const KEY_PIM_SHOW_DEPRECATED = "pim_show_deprecated_fields";
    const KEY_PIM_SHOW_SSN = 'pim_show_ssn';
    const KEY_PIM_SHOW_SIN = 'pim_show_sin';
    const KEY_PIM_SHOW_TAX_EXEMPTIONS = 'pim_show_tax_exemptions';
    const KEY_TIMESHEET_TIME_FORMAT = 'timesheet_time_format';
    const KEY_TIMESHEET_PERIOD_AND_START_DATE = 'timesheet_period_and_start_date';
    const KEY_TIMESHEET_PERIOD_SET = 'timesheet_period_set';
    const KEY_ADMIN_LOCALIZATION_DEFAULT_LANGUAGE = 'admin.localization.default_language';
    const KEY_ADMIN_LOCALIZATION_USE_BROWSER_LANGUAGE = 'admin.localization.use_browser_language';
    const KEY_ADMIN_LOCALIZATION_DEFAULT_DATE_FORMAT = 'admin.localization.default_date_format';
    const KEY_NON_LEAP_YEAR_LEAVE_PERIOD_START_DATE = 'leave.nonLeapYearLeavePeriodStartDate';
    const KEY_IS_LEAVE_PERIOD_START_ON_FEB_29 = 'leave.isLeavePeriodStartOnFeb29th';
    const KEY_LEAVE_PERIOD_START_DATE = 'leave.leavePeriodStartDate'; 

    /**
     * Get ConfigDao
     * @return ConfigDao
     */
    public function getConfigDao() {

        if ($this->configDao instanceof ConfigDao) {
            return $this->configDao;
        } else {
            $this->configDao = new ConfigDao();
        }

        return $this->configDao;
    }

    /**
     * Set ConfigDao
     * @param ConfigDao $configDao
     * @return void
     */
    public function setConfigDao(ConfigDao $configDao) {
        $this->configDao = $configDao;
    }

    /**
     * Constructor
     */
    public function __construct() {
        
    }
    
    /**
     * Get Logger instance. Creates if not already created.
     *
     * @return Logger
     */
    protected function getLogger() {
        if (is_null($this->logger)) {
            $this->logger = Logger::getLogger('core.ConfigService');
        }

        return($this->logger);
    }    

    /**
     *
     * @param type $key 
     */
    protected function _getConfigValue($key) {

        try {
            return $this->getConfigDao()->getValue($key);
        } catch (DaoException $e) {
            $this->getLogger()->error("Exception in _getConfigValue:" . $e);
            throw new CoreServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     *
     * @param type $key
     * @param type $value 
     */
    protected function _setConfigValue($key, $value) {
        try {
            $this->getConfigDao()->setValue($key, $value);
        } catch (DaoException $e) {
            $this->getLogger()->error("Exception in _setConfigValue:" . $e);
            throw new CoreServiceException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function setIsLeavePeriodDefined($value) {
        if ($value != 'Yes' && $value != 'No') {
            throw new Exception("Given value for setIsLeavePriodDefined should be 'Yes' or 'No'");
        }
        $this->_setConfigValue(self::KEY_LEAVE_PERIOD_DEFINED, $value);
    }

    /**
     * Get Value: Whether leave period has been set
     * @return bool Returns true if leave period has been set
     */
    public function isLeavePeriodDefined() {
        $val = $this->_getConfigValue(self::KEY_LEAVE_PERIOD_DEFINED);
        return ($val == 'Yes');
    }

    /**
     * Set show deprecated fields config value
     * @param boolean $value true or false
     */
    public function setShowPimDeprecatedFields($value) {
        $flag = $value ? 1 : 0;
        $this->_setConfigValue(self::KEY_PIM_SHOW_DEPRECATED, $flag);
    }

    public function showPimDeprecatedFields() {
        $val = $this->_getConfigValue(self::KEY_PIM_SHOW_DEPRECATED);
        return ($val == 1);
    }

    public function setShowPimSSN($value) {
        $flag = $value ? 1 : 0;
        $this->_setConfigValue(self::KEY_PIM_SHOW_SSN, $flag);
    }

    /**
     * Show PIM Deprecated Fields
     * @return bool
     */
    public function showPimSSN() {
        $val = $this->_getConfigValue(self::KEY_PIM_SHOW_SSN);
        return ($val == 1);
    }

    public function setShowPimSIN($value) {
        $flag = $value ? 1 : 0;
        $this->_setConfigValue(self::KEY_PIM_SHOW_SIN, $flag);
    }

    /**
     * Show PIM Deprecated Fields
     * @return bool
     */
    public function showPimSIN() {
        $val = $this->_getConfigValue(self::KEY_PIM_SHOW_SIN);
        return ($val == 1);
    }

    /**
     * @param boolean $value 
     * @return void
     */
    public function setShowPimTaxExemptions($value) {
        $flag = $value ? 1 : 0;
        $this->_setConfigValue(self::KEY_PIM_SHOW_TAX_EXEMPTIONS, $flag);
    }

    public function showPimTaxExemptions() {
        $val = $this->_getConfigValue(self::KEY_PIM_SHOW_TAX_EXEMPTIONS);
        return ($val == 1);
    }

    public function setAdminLocalizationDefaultLanguage($value){
        $this->_setConfigValue(self::KEY_ADMIN_LOCALIZATION_DEFAULT_LANGUAGE, $value);
    }

    public function setAdminLocalizationUseBrowserLanguage($value){
        $this->_setConfigValue(self::KEY_ADMIN_LOCALIZATION_USE_BROWSER_LANGUAGE, $value);
    }

    public function setAdminLocalizationDefaultDateFormat($value){
        $this->_setConfigValue(self::KEY_ADMIN_LOCALIZATION_DEFAULT_DATE_FORMAT, $value);
    }

    public function getAdminLocalizationUseBrowserLanguage(){
        return $this->_getConfigValue(self::KEY_ADMIN_LOCALIZATION_USE_BROWSER_LANGUAGE);
    }

    public function getAdminLocalizationDefaultDateFormat(){
        return $this->_getConfigValue(self::KEY_ADMIN_LOCALIZATION_DEFAULT_DATE_FORMAT);
    }

    public function getAdminLocalizationDefaultLanguage(){
        return $this->_getConfigValue(self::KEY_ADMIN_LOCALIZATION_DEFAULT_LANGUAGE);
    }
    
    public function getNonLeapYearLeavePeriodStartDate() {
        return $this->_getConfigValue(self::KEY_NON_LEAP_YEAR_LEAVE_PERIOD_START_DATE);        
    }
    
    public function setNonLeapYearLeavePeriodStartDate($startDate) {
        $this->_setConfigValue(self::KEY_NON_LEAP_YEAR_LEAVE_PERIOD_START_DATE, $startDate);  
    }
    
    public function getIsLeavePeriodStartOnFeb29th() {
        return $this->_getConfigValue(self::KEY_IS_LEAVE_PERIOD_START_ON_FEB_29);
    }
    
    public function setIsLeavePeriodStartOnFeb29th($value) {
        $this->_setConfigValue(self::KEY_IS_LEAVE_PERIOD_START_ON_FEB_29, $value);
    }
    
    public function getLeavePeriodStartDate() {
        return $this->_getConfigValue(self::KEY_LEAVE_PERIOD_START_DATE);
    }
    
    public function setLeavePeriodStartDate($startDate) {
        $this->_setConfigValue(self::KEY_LEAVE_PERIOD_START_DATE, $startDate);
    }     

}