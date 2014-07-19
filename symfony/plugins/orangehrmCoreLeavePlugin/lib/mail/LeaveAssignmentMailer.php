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


class LeaveAssignmentMailer extends orangehrmLeaveMailer {

    public function  __construct($leaveRequest, $leaveList, $performerId) {

        parent::__construct();

        $this->employeeService = new EmployeeService();
        $this->leaveList = $leaveList;
        $this->_populatePerformer($performerId);
        $this->leaveRequest = $leaveRequest;
        $this->_populateRecipient();

    }

    private function _populatePerformer($performerId) {

        if (!empty($performerId)) {
            $this->performer = $this->employeeService->getEmployee($performerId);
        }

    }

    private function _populateRecipient() {

        $this->recipient = $this->leaveRequest->getEmployee();

    }
    
    public function sendToAssignee() {

        $to = $this->recipient->getEmpWorkEmail();

        if (!empty($to)) {

            try {

                $this->message->setFrom($this->getSystemFrom());
                $this->message->setTo($to);

                $message = new LeaveAssignmentMailContent($this->performer, $this->recipient, $this->leaveRequest, $this->leaveList);

                $this->message->setSubject($message->generateSubject());
                $this->message->setBody($message->generateBody());

                $this->mailer->send($this->message);

                $logMessage = "Leave assignment email was sent to $to";
                $this->logResult('Success', $logMessage);

            } catch (Exception $e) {

                $logMessage = "Couldn't send leave assignment email to $to";
                $logMessage .= '. Reason: '.$e->getMessage();
                $this->logResult('Failure', $logMessage);

            }

        }

    }
    
    /*
     * Send mail notifications to supervisors of the assignee
     */
    public function sendToSupervisors() {
        
        $supervisors = $this->recipient->getSupervisors();

        if (count($supervisors) > 0) {

            foreach ($supervisors as $supervisor) {

                $to = $supervisor->getEmpWorkEmail();

                if (!empty($to) && ((empty($this->performer) || (($this->performer instanceof Employee) && ($to != $this->performer->getEmpWorkEmail()))))) {

                    try {

                        $this->message->setFrom($this->getSystemFrom());
                        $this->message->setTo($to);

                        $message = new LeaveAssignmentMailContent($this->performer, $supervisor, $this->leaveRequest, $this->leaveList);

                        $this->message->setSubject($message->generateSubjectForSupervisors());
                        $this->message->setBody($message->generateBodyForSupervisors());

                        $this->mailer->send($this->message);

                        $logMessage = "Leave assignment email was sent to $to";
                        $this->logResult('Success', $logMessage);

                    } catch (Exception $e) {

                        $logMessage = "Couldn't send leave assignment email to $to";
                        $logMessage .= '. Reason: '.$e->getMessage();
                        $this->logResult('Failure', $logMessage);

                    }

                }

            }

        }
        
    }
    
    /*
     * Send mail notifications to subscribers
     */
    public function sendToSubscribers() {

        $mailNotificationService = new EmailNotificationService();
        $subscriptions = $mailNotificationService->getSubscribersByNotificationId(EmailNotification::LEAVE_ASSIGNMENT);

        foreach ($subscriptions as $subscription) {

            if ($subscription instanceof EmailSubscriber) {

                if ($subscription->getEmailNotification()->getIsEnable() == EmailNotification::ENABLED) {

                    $to = $subscription->getEmail();

                    try {

                        $this->message->setFrom($this->getSystemFrom());
                        $this->message->setTo($to);

                        $message = new LeaveAssignmentMailContent($this->performer, NULL, $this->leaveRequest, $this->leaveList);

                        $this->message->setSubject($message->generateSubscriberSubject());
                        $this->message->setBody($message->generateSubscriberBody());

                        $this->mailer->send($this->message);

                        $logMessage = "Leave assignment subscription email was sent to $to";
                        $this->logResult('Success', $logMessage);

                    } catch (Exception $e) {

                        $logMessage = "Couldn't send leave assignment subscription email to $to";
                        $logMessage .= '. Reason: '.$e->getMessage();
                        $this->logResult('Failure', $logMessage);

                    }

                }

            }
        }
    }

    public function send() {

        if (!empty($this->mailer)) {

            $this->sendToAssignee();
            $this->sendToSupervisors();
            $this->sendToSubscribers();

        }

    }
    
}

