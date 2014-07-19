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
 * Form class for employee personal details
 */
class EmployeeCustomFieldsForm extends BaseForm {

    public function configure() {

        $customFields = $this->getOption('customFields', false);

        $this->setWidget('EmpID', new sfWidgetFormInputHidden());
        $this->setValidator('EmpID', new sfValidatorInteger(array('required' => true, 'min'=>0)));
		
       
        foreach ($customFields as $customField) {
            $fieldName = "custom" . $customField->field_num;

            if ($customField->type == CustomFields::FIELD_TYPE_SELECT) {

                $options = $customField->getOptions();
                $this->setWidget($fieldName, new sfWidgetFormSelect(array('choices'=>$options)));
                $this->setValidator($fieldName, new sfValidatorChoice(array('required' => false,
                                       'trim'=>true, 'choices'=>$options)));
            } else {
                $this->setWidget($fieldName, new sfWidgetFormInputText());
                $this->setValidator($fieldName, new sfValidatorString(array('required' => false, 'max_length' => 250)));
            }
        }
        
    }

    /**
     * Save employee custom fields
     */
    public function save() {

        $values = $this->getValues();
        $empNumber = $values['EmpID'];

        unset($values['EmpID']);

        try {
            $q = Doctrine_Query::create()
                ->update('Employee')
                ->set($values, array())
                ->where('empNumber = ?', $empNumber);

            $result = $q->execute();

        } catch (Exception $e) {
            throw new PIMServiceException($e->getMessage());
        }
    }


}

