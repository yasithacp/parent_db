<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yasitha
 * Date: 9/9/14
 * Time: 10:26 PM
 * To change this template use File | Settings | File Templates.
 */

class SmsGatewayForm extends BaseForm {

    public function configure() {
        $this->setWidgets(array(
            'number' => new sfWidgetFormInputText(),
            'message' => new sfWidgetFormTextArea()
        ));

        $this->setValidators(array(
            'number' => new sfValidatorString(array('required' => true)),
            'message' => new sfValidatorString(array('required' => true, 'max_length' => 120))
        ));

        $this->widgetSchema->setNameFormat('gateway[%s]');
    }

    public function submit(){
        $username = "esmsusr_rcg";
        $password = "password";

        $gateway = new SampleService();
        $session = $gateway->createSession('',$username,$password,'');

        $alias = "RCG";
        $message_body = $this->getValue('message');

        $numbers = explode(",", $this->getValue('number'));
        $response = $gateway->sendMessages($session,$alias,$message_body,$numbers);

        if ($response == '200') {
            $this->resultArray['messageType'] = 'success';
            $this->resultArray['message'] = "Successfully Sent";
        } else if ($response == '169'){
            $this->resultArray['messageType'] = 'warning';
            $this->resultArray['message'] = "Invalid Alias";
        } else if ($response == '151'){
            $this->resultArray['messageType'] = 'warning';
            $this->resultArray['message'] = "Invalid Session";
        }

        $gateway->closeSession($session);

        return $this->resultArray;
    }
}