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
 * Observer interface listening to event changes.
 */
interface EventObserver {

    /**
     * Register this observer with the given subject
     * 
     * TODO: Instead of EventMediator, use an interface or abstract super class
     *  
     * @param EventMediator 
     */
    public function register($subject);
    
    
    /**
     * Notify of event
     * @param String $event Event name
     * @param Array $data Array containing event specific data
     * @return boolean true if caller should continue UI flow, false if caller should exit 
     *      (typically done when observer handles the UI - eg redirecting the user to a confirmation page) 
     */
    public function notify($event, $data = array());
}