<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yasitha
 * Date: 9/13/14
 * Time: 3:46 PM
 * To change this template use File | Settings | File Templates.
 */

class MailSenderForm extends BaseForm {

    public function configure() {
        $this->setWidgets(array(
            'mail' => new sfWidgetFormSelect(array('choices' => array('all' => 'All Parents'))),
            'message' => new sfWidgetFormTextArea()
        ));

        $this->setValidators(array(
            'mail' => new sfValidatorString(array('required' => true)),
            'message' => new sfValidatorString(array('required' => true, 'max_length' => 2500))
        ));

        $this->widgetSchema->setNameFormat('email[%s]');
    }

    public function submit(){

        $dao = new ParentDao();
        $emails = $dao->getParentsEmailsTest();
//        $emails = array('yasitha4@gmail.com', 'rajith3k@gmail.com');
        $mailBody = $this->getValue('message');
        $mailService = new ParentMailService();
        $response = $mailService->sendEmails($emails, $mailBody);

        if($response) {
            $this->resultArray['messageType'] = 'success';
            $this->resultArray['message'] = "Successfully Sent";
        } else {
            $this->resultArray['messageType'] = 'warning';
            $this->resultArray['message'] = "Error Occurred";
        }

        return $this->resultArray;
    }

}