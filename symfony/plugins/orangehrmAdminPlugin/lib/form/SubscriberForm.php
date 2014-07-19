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

class SubscriberForm extends BaseForm {

    private $emailNotoficationService;
    private $notificationId;

    public function getEmailNotificationService() {
        if (is_null($this->emailNotoficationService)) {
            $this->emailNotoficationService = new EmailNotificationService();
            $this->emailNotoficationService->setEmailNotificationDao(new EmailNotificationDao());
        }
        return $this->emailNotoficationService;
    }

    public function configure() {

        $this->notificationId = $this->getOption('notificationId');

        $this->setWidgets(array(
            'subscriberId' => new sfWidgetFormInputHidden(),
            'name' => new sfWidgetFormInputText(),
             'email' => new sfWidgetFormInputText()
        ));

        $this->setValidators(array(
            'subscriberId' => new sfValidatorNumber(array('required' => false)),
            'name' => new sfValidatorString(array('required' => true, 'max_length' => 100)),
            'email' => new sfValidatorEmail(array('required' => true, 'max_length' => 100, 'trim' => true))
        ));

        $this->widgetSchema->setNameFormat('subscriber[%s]');
    }

    public function save() {

        $subscriberId = $this->getValue('subscriberId');
        if (!empty($subscriberId)) {
            $subscriber = $this->getEmailNotificationService()->getSubscriberById($subscriberId);
        } else {
            $subscriber = new EmailSubscriber();
        }
        $subscriber->setNotificationId($this->notificationId);
        $subscriber->setName($this->getValue('name'));
        $subscriber->setEmail($this->getValue('email'));
        $subscriber->save();
    }

    public function getSubscriberListForNotificationAsJson() {

        $list = array();
        $subscriberList = $this->getEmailNotificationService()->getSubscribersByNotificationId($this->notificationId);
        foreach ($subscriberList as $subscriber) {
            $list[] = array('id' => $subscriber->getId(), 'email' => $subscriber->getEmail());
        }
        return json_encode($list);
    }

}

