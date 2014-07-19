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
 * Form object for holiday list search
 *
 */
class HolidayListSearchForm extends sfForm {
    
    private $leavePeriodService;
    
    /**
     * Returns Leave Period
     * @return LeavePeriodService
     */
    public function getLeavePeriodService() {

        if (is_null($this->leavePeriodService)) {
            $leavePeriodService = new LeavePeriodService();
            $leavePeriodService->setLeavePeriodDao(new LeavePeriodDao());
            $this->leavePeriodService = $leavePeriodService;
        }

        return $this->leavePeriodService;
    }
    
    /**
     * Returns Leave Period
     * @return LeavePeriodService
     */
    public function setLeavePeriodService($leavePeriodService) {
        $this->leavePeriodService = $leavePeriodService;
    } 
    
    /**
     * Configuring WorkWeek form widget
     */
    public function configure() {

        sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N', 'OrangeDate'));
        
        $leavePeriodChoices = $this->getLeavePeriodChoices();                           
                            
        $this->setWidget('leave_period', new sfWidgetFormSelect(
                array('choices' => $leavePeriodChoices),
                array('class' => 'formSelect')));   
        
        // Clear the 0 option since it is not a valid choice.
        unset($leavePeriodChoices[0]);
        
        $this->setValidator('leave_period', 
                new sfValidatorChoice(array('choices' => array_keys($leavePeriodChoices)), 
                                      array('invalid' => __(ValidationMessages::INVALID))));        

        $this->widgetSchema->setLabels(array('leave_period' => __("Leave Period")));
        
        $this->widgetSchema->setNameFormat('holidayList[%s]');        
        $this->widgetSchema->setFormFormatterName('BreakTags');
    }    
    
    /**
     * Get Leave Period choices as an array.
     * @return array Array of leave periods
     */
    protected function getLeavePeriodChoices() {
        $leavePeriodChoices = array();

        $leavePeriods = $this->getLeavePeriodService()->getLeavePeriodList();
        if (empty($leavePeriods)) {  
            $leavePeriodChoices[0] = __('No Leave Periods');
        } else {
            foreach($leavePeriods as $leavePeriod) {
                $id = $leavePeriod->getLeavePeriodId();
                $label = set_datepicker_date_format($leavePeriod->getStartDate()) 
                        . " " . __("to") . " " 
                        . set_datepicker_date_format($leavePeriod->getEndDate());
                
                $leavePeriodChoices[$id] = $label;
            }
        } 
        
        return $leavePeriodChoices;
    }
    
    public function getJavaScripts() {
        $javaScripts = parent::getJavaScripts();
        $javaScripts[] = '/orangehrmCoreLeavePlugin/js/viewHolidayListSuccessSearch.js';
        
        return $javaScripts;
    }    
    
    public function getStylesheets() {
        parent::getStylesheets();
        
        $styleSheets = parent::getStylesheets();
        $styleSheets['/orangehrmCoreLeavePlugin/css/viewHolidayListSuccessSearch.css'] = 'screen';
        
        return $styleSheets;        
    }    
}

