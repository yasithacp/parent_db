<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yasitha
 * Date: 9/13/14
 * Time: 4:32 PM
 * To change this template use File | Settings | File Templates.
 */

class ParentInfoMailer extends orangehrmMailer {

    public function  __construct($emails, $mailBody) {

        parent::__construct();

        $this->emails = $emails;
        $this->mailBody = $mailBody;

    }

    public function send() {

        if (!empty($this->mailer)) {

            if (count($this->emails) > 0) {

                foreach ($this->emails as $email) {
                    if(!empty($email)) {
                        try {
                            $this->message->setFrom($this->getSystemFrom());
                            $this->message->setTo(trim($email));

                            $message = new ParentInfoMailContent(trim($this->mailBody));

                            $this->message->setSubject($message->generateSubject());
                            $this->message->setBody($message->generateBody());
                            $this->mailer->send($this->message);

                            $logMessage = "Parent Information Successfully sent to $email";
                            $this->logResult('Success', $logMessage);

                        } catch (Exception $e) {

                            $logMessage = "Couldn't send Parent Information email to $email";
                            $logMessage .= '. Reason: '.$e->getMessage();
                            $this->logResult('Failure', $logMessage);

                        }
                    }

                }

            }

        }

        return true;

    }

}