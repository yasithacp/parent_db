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
 * SchedulerService class - execute all the schedulers in the system through Scheduler Service
 *
 * $schedulerService = new SchedulerService();
 * $schedulerService->addSchedule(array('className', 'methodName', array(param1, param2, param3)));
 * $schedulerService->run();
 *
 * @author Priyantha Gunawardena
 *
 */

class SchedulerService
{


    private $scheduleCollector = array(); // collect all teh schedules added by the user

    const SCHEDULE_TRACK_START = 'start';
    const SCHEDULE_TRACK_FINISHED = 'finished';
    const SCHEDULE_TRACK_ERROR = 'error';
    const SCHEDULE_TRACK_SUCCESS = 'success';

    private $classObject = null;
    private $method = '';
    private $params = array();
    /**
     * Public method for the user to add his chedule
     * @param string $className
     * @param string $methodName
     * @param array $params
     */
    public function addSchedule($className, $methodName, $params=array())
    {
        $this->scheduleCollector[] = array("class" => $className, "method"=>$methodName, "params" => $params);
    }

    /**
     * Run all the schedules
     * @return void
     */
    public function run()
    {
        try
        {
            foreach($this->scheduleCollector as $schedule)
            {
                $this->logSchedule($schedule, self::SCHEDULE_TRACK_START);
                if($this->isValidSchedule($schedule))
                {
                    //call the scheduler
                    call_user_func_array(array($this->classObject, $this->method), $this->params);
                    $this->logSchedule($schedule, self::SCHEDULE_TRACK_SUCCESS);
                }
                else
                {
                    $this->logSchedule($schedule, self::SCHEDULE_TRACK_ERROR);
                    return false;
                }
                $this->logSchedule($schedule, self::SCHEDULE_TRACK_FINISHED);
            }
            return true;
        }
        catch(Exception $e)
        {
            $this->logSchedule($schedule, self::SCHEDULE_TRACK_ERROR, "Could not execute the schedule".$e->getMessage());
            return false;
        }

    }

    /**
     * Validate the given scheduler
     * @param array $schedule
     * @return boolean
     */
    private function isValidSchedule($schedule)
    {

        $class = $schedule['class'];
        $method = $schedule['method'];
        $params = $schedule['params'];

        // check whether the class is exist
        if(class_exists($class))
        {
            $this->classObject = new $class;

            // check whether the method if exist
            if(!is_callable(array($this->classObject, $method)))
            {
                $this->logSchedule($schedule, self::SCHEDULE_TRACK_ERROR, 'Method not found');
                return false;
            }

        }else
        {
            $this->logSchedule($schedule, self::SCHEDULE_TRACK_ERROR, 'Class not found');
            return false;

        }

        $this->method = $method;
        $this->params = $params;
        return true;
        
    }

    /**
     * log errors in the scheduler
     * @param array $schedule
     * @param string $logType
     * @param string $message
     */
    private function logSchedule($schedule, $logType, $message='')
    {
        ////
        // TODO :- insted of echo add in to a log file log/scheduler.log
        ////
        switch ($logType)
        {
            case self::SCHEDULE_TRACK_START;
                echo "\n==========================================\n";
                echo "START ". $schedule['class'] . " => " . $schedule['method']. "\n";
                echo ($message!="")?"\t [" . $message . "]\n\n":"\n";
                break;

            case self::SCHEDULE_TRACK_FINISHED:
                echo "FINISHED ". $schedule['class'] . " => " . $schedule['method']. "\n";
                echo ($message!="")?"\t [" . $message . "]\n\n":"\n";
                break;

            case self::SCHEDULE_TRACK_SUCCESS:
                echo "SUCCESS in ". $schedule['class'] . " => " . $schedule['method']. "\n";
                echo ($message!="")?"\t [" . $message . "]\n\n":"\n";
                break;

            case self::SCHEDULE_TRACK_ERROR:
                echo "ERROR found in ". $schedule['class'] . " => " . $schedule['method']. "\n";
                echo ($message!="")?"\t [" . $message . "]\n\n":"\n";
                break;
        }
    }



}

?>