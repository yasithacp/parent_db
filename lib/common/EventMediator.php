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
 * Manages event handlers (listeners)
 * Mediator and Subject of observer pattern
 * 
 * NOTE 1: Does not work correctly if more than one observer (listening to the same event) attempts to change the UI flow.
 */
class EventMediator {
    const OBSERVER_DIR = 'observers';
    
    /* Known events */
    const POST_CUSTOM_FIELD_DELETE_EVENT = 'post_custom_field_delete';
    
    // Event Mediator singleton
    private static $instance = null;
    
    private $observers;    

    /**
     * Private constructor. Use instance() method to get singleton instance
     */
    private function __construct() {
        $this->_loadObservers();
    }

    /**
     * Get singleton instance of this class
     */
    public static function instance() {
        if (self::$instance == null) {
            self::$instance = new EventMediator();
        }

        return self::$instance;
    }
    
    /**
     * Attach given observer. From now on, the passed observer will
     * be notified whenever the given event occurs
     * 
     * @param String $event Event name
     * @param EventObserver Observer object
     */
    public function attach($event, $observer) {
        if (isset($this->observers[$event])) {
            $eventListeners = $this->observers[$event];            
        } else {
            $eventListeners = array();            
        }
        $eventListeners[spl_object_hash($observer)] = $observer;
        $this->observers[$event] = $eventListeners;
    }
    
    
    public function detach($observer) {
        // not implemented    
    }
    
    /**
     * Notify listeners to the given event.
     * 
     * NOTE: Does not work correctly if more than one observer (listening to the same event) attempts to change the UI flow. 
     * redirect the user to a confirmation screen.
     *  
     * @param String $event Event name
     * @param Array $data Array containing event specific data
     * @return boolean true if caller should continue UI flow, false if caller should exit 
     *      (typically done when observer handles the UI - eg redirecting the user to a confirmation page)
     */
    public function notify($event, $data = array()) {      
        $result = true;
         
        if (isset($this->observers[$event])) {
            $eventListeners = $this->observers[$event];
            foreach ($eventListeners as $listener) {
                $result = $result && $listener->notify($event, $data);
            }
        }
        
        return $result;
    }
    
    /**
     * Loads observer classes
     */
    private function _loadObservers() {
        
        $observersDir = rtrim(ROOT_PATH, '/') . '/lib/' . self::OBSERVER_DIR;
        if (is_dir($observersDir)) {
        	$observers = $this->_listFiles($observersDir);
	        foreach ($observers as $observer) {
	            if (is_file($observer)) {
	                $fileInfo = pathinfo($observer);
	                $className = $fileInfo['basename'];
	                $extension = $fileInfo['extension'];
	                if ($extension === 'php') {
	                   $className = str_replace("." . $extension, "", $className);
	                    require_once $observer;
	                    $object = new $className;
	                    
	                    if ($object instanceof EventObserver) {
	                        $object->register($this);
	                    }
	                }
	            }
	        }
        }
        
    }
    
    /**
     * List all files (including directories) under the given directory.
     * 
     * @param String $path Directory to look in
     * @return Array Array of file/directory names
     */   
    private function _listFiles($path) {
        
        $files = array();
        
        $path = rtrim($path, '/').'/';  
        if (is_readable($path)) {
            $items = (array) glob($path.'*');

            foreach ($items as $index => $item) {
                $files[] = str_replace('\\', '/', $item);
            }
        }
        return $files;       
    }
}