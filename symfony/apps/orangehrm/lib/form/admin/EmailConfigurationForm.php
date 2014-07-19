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


/**
 * Form class for EmailConfigurationForm
 */
class EmailConfigurationForm extends BaseForm {

    public function configure() {

        /* Widgests */

        /*$formWidgets['cmbMailType'] = new sfWidgetFormChoice(array('choices' => array('SMTP', 'Sendmail')));
        $formWidgets['txtSentAs'] = new sfWidgetFormInputText();

        $formWidgets['txtSmtpHost'] = new sfWidgetFormInputText();
        $formWidgets['txtSmtpPort'] = new sfWidgetFormInputText();
        $formWidgets['optSmtpAuth'] = new sfWidgetFormChoice(array('expanded' => true, 'choices' => array('No', 'Yes')));
        $formWidgets['txtSmtpUsername'] = new sfWidgetFormInputText();
        $formWidgets['txtSmtpPassword'] = new sfWidgetFormInputText();
        $formWidgets['optSmtpSecurity'] = new sfWidgetFormChoice(array('expanded' => true, 'choices' => array('No', 'SSL', 'TLS')));

        $formWidgets['txtSendmailPath'] = new sfWidgetFormInputText();

        $formWidgets['chkTestEmail'] = new sfWidgetFormChoice(array('expanded' => true, 'multiple' => true, 'choices' => array('Send Test Email')));
        $formWidgets['txtTestEmail'] = new sfWidgetFormInputText();*/

        /* Validators */

        /*$formValidators['cmbMailType'] = new sfValidatorChoice(array('choices' => array('SMTP', 'Sendmail')));
        $formValidators['txtSentAs'] = new sfValidatorString(array('required' => true));

        $formValidators['txtSmtpHost'] = new sfValidatorString(array('required' => false));
        $formValidators['txtSmtpPort'] = new sfValidatorString(array('required' => false));
        $formValidators['optSmtpAuth'] = new sfValidatorString(array('required' => false));
        $formValidators['txtSmtpUsername'] = new sfValidatorString(array('required' => false));
        $formValidators['txtSmtpPassword'] = new sfValidatorString(array('required' => false));
        $formValidators['optSmtpSecurity'] = new sfValidatorString(array('required' => false));

        $formValidators['txtSendmailPath'] = new sfValidatorString(array('required' => false));

        $formValidators['chkTestEmail'] = new sfValidatorString(array('required' => false));
        $formValidators['txtTestEmail'] = new sfValidatorString(array('required' => false));

    	$this->setWidgets($formWidgets);
    	$this->setValidators($formValidators);*/

        $this->widgetSchema->setNameFormat('emailConfigurationForm[%s]');

     }

    public function populateEmailConfiguration($request) {

        $emailConfigurationService = new EmailConfigurationService();
        $emailConfiguration = $emailConfigurationService->getEmailConfiguration();

        $stmpPort = $request->getParameter('txtSmtpPort');
        $emailConfiguration->setMailType($request->getParameter('cmbMailSendingMethod'));
        $emailConfiguration->setSentAs($request->getParameter('txtMailAddress'));
        $emailConfiguration->setSmtpHost($request->getParameter('txtSmtpHost'));
        $emailConfiguration->setSmtpPort($stmpPort ? $stmpPort : NULL);
        $emailConfiguration->setSmtpUsername($request->getParameter('txtSmtpUser'));
        $emailConfiguration->setSmtpPassword($request->getParameter('txtSmtpPass'));
        $emailConfiguration->setSmtpAuthType($request->getParameter('optAuth'));
        $emailConfiguration->setSmtpSecurityType($request->getParameter('optSecurity'));
        $emailConfiguration->setSendmailPath($request->getParameter('txtSendmailPath'));

        return $emailConfiguration;

    }





}

?>