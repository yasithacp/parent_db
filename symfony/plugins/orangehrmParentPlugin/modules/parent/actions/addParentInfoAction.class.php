<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yasitha
 * Date: 7/20/14
 * Time: 12:17 AM
 * To change this template use File | Settings | File Templates.
 */

class addParentInfoAction extends sfAction {

    /**
     * @param sfForm $form
     * @return
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    public function getForm() {
        return $this->form;
    }

    public function execute($request) {

        $this->setForm(new AddPrentInfoForm());

        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }


        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $result = $this->form->save();
                if (isset($result['messageType'])) {
                    $this->getUser()->setFlash('templateMessage', array($result['messageType'], $result['message']));
                } else {
                    $this->getUser()->setFlash('templateMessage', array('success', __('Successfully Added')));
                }
            } else {
                print_r("Invalid Data");
            }
        }
    }
}