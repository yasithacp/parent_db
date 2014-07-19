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
 * Gives access to OrangeHRM config files sysConf.php and conf.php
 */
class OrangeConfig {

    private $sysConf = null;
    private $conf = null;
    private $appConf = null;
    private $configService = null;
    private static $instance = null;    

    /**
     * Private constructor. Use the getInstance() method to get object instance
     */
    private function __construct() {
        
    }

    /**
     * Returns an instance of this class
     *
     * @return OrangeConfig
     */
    public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get orangehrm's sysConf configuration object
     *
     * @return sysConf object
     */
    public function getSysConf() {
        if (is_null($this->sysConf)) {

            require_once sfConfig::get('sf_root_dir') . '/../lib/confs/sysConf.php';
            $this->sysConf = new sysConf();
        }

        return $this->sysConf;
    }

    /**
     * Get orangehrm's Conf configuration object
     *
     * @return Conf object
     */
    public function getConf() {
        if (is_null($this->conf)) {

            require_once sfConfig::get('sf_root_dir') . '/../lib/confs/Conf.php';
            $this->conf = new Conf();
        }

        return $this->conf;
    }

    public function getAppConfValue($key) {

        $configService = $this->getConfigService();
        switch ($key) {
            case ConfigService :: KEY_LEAVE_PERIOD_DEFINED:
                return $configService->isLeavePeriodDefined();
                break;

            case ConfigService::KEY_PIM_SHOW_DEPRECATED:
                return $configService->showPimDeprecatedFields();
                break;
            case ConfigService::KEY_PIM_SHOW_SSN:
                return $configService->showPimSSN();
                break;
            case ConfigService::KEY_PIM_SHOW_SIN:
                return $configService->showPimSIN();
                break;
            case ConfigService::KEY_PIM_SHOW_TAX_EXEMPTIONS:
                return $configService->showPimTaxExemptions();
                break;
            case ConfigService::KEY_NON_LEAP_YEAR_LEAVE_PERIOD_START_DATE:
                return $configService->getNonLeapYearLeavePeriodStartDate();
                break;                
            case ConfigService::KEY_IS_LEAVE_PERIOD_START_ON_FEB_29:
                return $configService->getIsLeavePeriodStartOnFeb29th();
                break;
            case ConfigService::KEY_LEAVE_PERIOD_START_DATE:
                return $configService->getLeavePeriodStartDate();
                break;
            default:
                throw new Exception("Getting {$key} is not implemented yet");
                break;
        }
    }

    public function setAppConfValue($key, $value) {

        $configService = $this->getConfigService();

        switch ($key) {
            case ConfigService:: KEY_LEAVE_PERIOD_DEFINED:
                $configService->setIsLeavePeriodDefined($value);
                break;

            case ConfigService::KEY_PIM_SHOW_DEPRECATED:
                $configService->setShowPimDeprecatedFields($value);
                break;
            case ConfigService::KEY_PIM_SHOW_SSN:
                return $configService->setShowPimSSN($value);
                break;
            case ConfigService::KEY_PIM_SHOW_SIN:
                return $configService->setShowPimSIN($value);
                break;
            case ConfigService::KEY_PIM_SHOW_TAX_EXEMPTIONS:
                return $configService->setShowPimTaxExemptions($value);
                break;            
            case ConfigService::KEY_NON_LEAP_YEAR_LEAVE_PERIOD_START_DATE:
                return $configService->setNonLeapYearLeavePeriodStartDate($value);
                break;                
            case ConfigService::KEY_IS_LEAVE_PERIOD_START_ON_FEB_29:
                return $configService->setIsLeavePeriodStartOnFeb29th($value);
                break;
            case ConfigService::KEY_LEAVE_PERIOD_START_DATE:
                return $configService->setLeavePeriodStartDate($value);
                break;            
            default:
                throw new Exception("Setting {$key} is not implemented yet");
                break;
        }
    }
    
    protected function getConfigService() {
        if (!isset($this->configService)) {
            $this->configService = new ConfigService();
        }
        
        return $this->configService;
    }

}