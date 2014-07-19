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


class LeaveCancellationMailer extends orangehrmLeaveMailer {

    public function  __construct($leaveList, $performerType, $performerId, $requestType) {

        parent::__construct();

        $this->employeeService = new EmployeeService();
        $this->leaveList = $leaveList;
        $this->performerType = $performerType;
        $this->_populatePerformer($performerId);
        $this->leaveRequest = $leaveList[0]->getLeaveRequest();
        $this->_populateRecipient();
        $this->requestType = $requestType;

    }

    private function _populatePerformer($performerId) {

        if (!empty($performerId)) {
            $this->performer = $this->employeeService->getEmployee($performerId);
        }

    }

    private function _populateRecipient() {

        $this->recipient = $this->leaveRequest->getEmployee();

    }

    public function sendToApplicant() {

        $to = $this->recipient->getEmpWorkEmail();

        if (!empty($to)) {

            try {

                $this->message->setFrom($this->getSystemFrom());
                $this->message->setTo($to);

                $message = new LeaveCancellationMailContent($this->performer, $this->recipient, $this->leaveRequest, $this->leaveList, $this->requestType);

                $this->message->setSubject($message->generateSubject());
                $this->message->setBody($message->generateBody());

                $this->mailer->send($this->message);

                $logMessage = "Leave cancellation email was sent to $to";
                $this->logResult('Success', $logMessage);

            } catch (Exception $e) {

                $logMessage = "Couldn't send leave cancellation email to $to";
                $logMessage .= '. Reason: '.$e->getMessage();
                $this->logResult('Failure', $logMessage);

            }

        }

    }

    public function sendToSubscribers() {

        $mailNotificationService = new EmailNotificationService();
        $subscriptions = $mailNotificationService->getSubscribersByNotificationId(EmailNotification::LEAVE_CANCELLATION);

        foreach ($subscriptions as $subscription) {
	
            if ($subscription instanceof EmailSubscriber) {

                if ($subscription->getEmailNotification()->getIsEnable() == EmailNotification::ENABLED) {

                $to = $subscription->getEmail();

                try {

                    $this->message->setFrom($this->getSystemFrom());
                    $this->message->setTo($to);

                    $message = new LeaveCancellationMailContent($this->performer, $this->recipient, $this->leaveRequest, $this->leaveList, $this->requestType);

                    $this->message->setSubject($message->generateSubscriberSubject());
                    $this->message->setBody($message->generateSubscriberBody());

                    $this->mailer->send($this->message);

                    $logMessage = "Leave cancellation subscription email was sent to $to";
                    $this->logResult('Success', $logMessage);

                } catch (Exception $e) {

                    $logMessage = "Couldn't send leave cancellation subscription email to $to";
                    $logMessage .= '. Reason: '.$e->getMessage();
                    $this->logResult('Failure', $logMessage);

                }

            }
	    }
        }

    }

    public function send() {

        if (!empty($this->mailer)) {

            $this->sendToApplicant();
            $this->sendToSubscribers();

        }

    }
    
}

