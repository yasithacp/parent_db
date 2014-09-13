<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yasitha
 * Date: 9/13/14
 * Time: 4:47 PM
 * To change this template use File | Settings | File Templates.
 */

class ParentInfoMailContent extends orangehrmMailContent {

    public function  __construct($mailBody) {
        $this->mailBody = $mailBody;
    }

    public function generateSubject() {

        if (empty($this->subject)) {

            $this->subject = "College re-open for the third term";

        }

        return $this->subject;

    }

    public function generateBody(){
        return $this->mailBody;
    }

}