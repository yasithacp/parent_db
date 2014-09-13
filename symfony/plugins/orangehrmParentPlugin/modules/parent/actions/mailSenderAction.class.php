<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yasitha
 * Date: 9/13/14
 * Time: 3:45 PM
 * To change this template use File | Settings | File Templates.
 */

class mailSenderAction extends sfAction {

    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    function execute($request) {

        $this->setForm(new MailSenderForm());

        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }

        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $result = $this->form->submit();
                $this->getUser()->setFlash('templateMessage', array($result['messageType'], $result['message']));
                $this->redirect('parent/mailSender');
            }
        }
    }

}