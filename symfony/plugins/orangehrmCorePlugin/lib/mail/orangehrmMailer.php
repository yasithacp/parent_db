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


abstract class orangehrmMailer {

    protected $mailer;
    protected $transport;
    protected $message;
    protected $logPath;

    public function getMailer() {
        return $this->mailer;
    }

    public function setMailer($mailer) {
        $this->mailer = $mailer;
    }

    public function getTransport() {
        return $this->transport;
    }

    public function setTransport($transport) {
        $this->transport = $transport;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function getLogPath() {
        return $this->logPath;
    }

    public function setLogPath($logPath) {
        $this->logPath = $logPath;
    }

    public function  __construct() {

        $orangehrmMailTransport = new orangehrmMailTransport();
        $this->transport = $orangehrmMailTransport->getTransport();
        $this->mailer = empty($this->transport)?null:Swift_Mailer::newInstance($this->transport);
        $this->message = Swift_Message::newInstance();
        $this->logPath = ROOT_PATH . '/lib/logs/notification_mails.log';

    }

    public function getSystemFrom() {

        $emailConfigurationService = new EmailConfigurationService();
        $emailConfig = $emailConfigurationService->getEmailConfiguration();
        return array($emailConfig->getSentAs() => 'RCIN');

    }

    public function logResult($type = '', $logMessage = '') {

        if (file_exists($this->logPath) && !is_writable($this->logPath)) {
            throw new Exception("Email Notifications : Log file is not writable");
        }

        $message = '========== Message Begins ==========';
        $message .= "\r\n\n";
        $message .= 'Time : '.date("F j, Y, g:i a");
        $message .= "\r\n";
        $message .= 'Message Type : '.$type;
        $message .= "\r\n";
        $message .= 'Message : '.$logMessage;
        $message .= "\r\n\n";
        $message .= '========== Message Ends ==========';
        $message .= "\r\n\n";

        file_put_contents($this->logPath, $message, FILE_APPEND);

    }


}

