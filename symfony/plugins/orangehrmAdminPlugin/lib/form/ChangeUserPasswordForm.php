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

class ChangeUserPasswordForm extends BaseForm {

    public function configure() {

        $this->setWidgets(array(
            'userId' => new sfWidgetFormInputHidden(),
            'currentPassword' => new sfWidgetFormInputPassword(array(), array("class" => "formInputText", "maxlength" => 20)),
            'newPassword' => new sfWidgetFormInputPassword(array(), array("class" => "formInputText", "maxlength" => 20)),
            'confirmNewPassword' => new sfWidgetFormInputPassword(array(), array("class" => "formInputText", "maxlength" => 20))
        ));

        $this->setValidators(array(
            'userId' => new sfValidatorNumber(array('required' => false)),
            'currentPassword' => new sfValidatorString(array('required' => true, 'max_length' => 20)),
            'newPassword' => new sfValidatorString(array('required' => true, 'max_length' => 20)),
            'confirmNewPassword' => new sfValidatorString(array('required' => true, 'max_length' => 20))
        ));

        
        $this->widgetSchema->setNameFormat('changeUserPassword[%s]');

        $this->getWidgetSchema()->setLabels($this->getFormLabels());

        //merge secondary password
        $formExtension = PluginFormMergeManager::instance();
        $formExtension->mergeForms($this, 'changeUserPassword', 'ChangeUserPasswordForm');

        sfWidgetFormSchemaFormatterBreakTags::setNoOfColumns(1);
        $this->getWidgetSchema()->setFormFormatterName('BreakTags');
    }

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $labels = array(
            'userId' => false,
            'currentPassword' => __('Current Password') . '<span class="required">*</span>',
            'newPassword' => __('New Password') . '<span class="required">*</span>',
            'confirmNewPassword' => __('Confirm New Password') . '<span class="required">*</span>',
            'currentPassword' => __('Current Password') . '<span class="required">*</span>',
        );

        return $labels;
    }

    public function save() {

        $userId = sfContext::getInstance()->getUser()->getAttribute('user')->getUserId();
        $systemUserService = new SystemUserService();
        $posts = $this->getValues();
        $systemUserService->updatePassword($userId, $posts['newPassword']);

        //save secondary password
        $formExtension = PluginFormMergeManager::instance();
        $formExtension->saveMergeForms($this, 'changeUserPassword', 'ChangeUserPasswordForm');
    }

}
