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
 * Displaying ApplyLeave UI and saving data
 *
 * @author Samantha Jayasinghe
 */
class getHolidayAjaxAction extends sfAction {

   /**
     * get Method for Holiday Service
     *
     * @return HolidayService $holidayService
     */
    public function getHolidayService() {
        if (is_null($this->holidayService)) {
            $this->holidayService = new HolidayService();
        }
        return $this->holidayService;
    }

    /**
     * Set HolidayService
     * @param HolidayService $holidayService
     */
    public function setHolidayService(HolidayService $holidayService) {
        $this->holidayService = $holidayService;
    }
    
    /**
     *
     * @param type $request 
     */
    public function execute( $request ){
        sfConfig::set('sf_web_debug', false);
        sfConfig::set('sf_debug', false);
        
        $year = $request->getParameter("year");

        $holidayList = $this->getHolidayList();

        $dates = "";
        foreach ($holidayList as $holiday) {
            $htype = $holiday->getLength() == 0 ? 'f' : 'h';
            $dates .= "[" . str_replace("-", ",", $holiday->getdate()) . ", '" . $htype . "', " . $holiday->getRecurring() . "],";
        }

        $dates = rtrim($dates, ",");

        $response = $this->getResponse();
        $response->setHttpHeader('Expires', '0');
        $response->setHttpHeader("Cache-Control", "must-revalidate, post-check=0, pre-check=0");
        $response->setHttpHeader("Cache-Control", "private", false);
        
        echo "[";
        echo $dates;
        echo "]";
        
        return sfView::NONE;
    }
    
    /**
     * 
     * @return Holiday List 
     */
    public function getHolidayList(){
        return $this->getHolidayService()->getFullHolidayList();
    }
    

}

?>
