<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yasitha
 * Date: 9/13/14
 * Time: 4:30 PM
 * To change this template use File | Settings | File Templates.
 */

class ParentMailService {

    public function sendEmails($emails, $mailBody){

        $parentMailer = new ParentInfoMailer($emails, $mailBody);
        return $parentMailer->send();
    }
}