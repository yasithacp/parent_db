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
 * Form class for employee direct deposit
 */
class EmployeeDirectDepositForm extends BaseForm {
    
    const ACCOUNT_TYPE_SAVINGS = 'SAVINGS';
    const ACCOUNT_TYPE_CHECKING = 'CHECKING';
    const ACCOUNT_TYPE_OTHER = 'OTHER';
          
    private $accountTypes;
    
    public function configure() {
        
        $this->accountTypes = array('' => '-- ' . __('Select') . ' --',
                             self::ACCOUNT_TYPE_SAVINGS => __('Savings'),
                             self::ACCOUNT_TYPE_CHECKING => __('Checking'),
                             self::ACCOUNT_TYPE_OTHER => __('Other'));
        
        // Note: Widget names were kept from old non-symfony version
        $this->setWidgets(array(
            'id' => new sfWidgetFormInputHidden(),
            'account' => new sfWidgetFormInputText(),
            'account_type' => new sfWidgetFormSelect(array('choices' => $this->accountTypes)),
            'account_type_other' => new sfWidgetFormInputText(),
            'routing_num' => new sfWidgetFormInputText(),
            'amount' => new sfWidgetFormInputText(),
        ));
        
        $this->setValidators(array(
            'id' => new sfValidatorNumber(array('required' => false, 'min'=> 0)),
            'account' => new sfValidatorString(array('required' => true, 'max_length'=>100)),
            'account_type' => new sfValidatorChoice(array('required' => true, 'choices' => array_keys($this->accountTypes))),
            'account_type_other' => new sfValidatorString(array('required' => false)), // only required if account_type = 'OTHER'.
            'routing_num' => new sfValidatorNumber(array('required' => true, 'trim'=>true, 'max' => 2147483647)),
            'amount' => new sfValidatorNumber(array('required' => true, 'min' => 0, 'max'=> 999999999.99)),
        ));

         $this->widgetSchema->setNameFormat('directdeposit[%s]');
         
        // set up your post validator method
        $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array(
            'callback' => array($this, 'postValidate')
          ))
        );

    }

    public function postValidate($validator, $values) {

        $accountType = $values['account_type'];
        
        if ($accountType == self::ACCOUNT_TYPE_OTHER) {
            $other = $values['account_type_other'];
            if ($other == '') {
                $message = __(ValidationMessages::REQUIRED);
                $error = new sfValidatorError($validator, $message);
                throw new sfValidatorErrorSchema($validator, array('account_type_other' => $error));                

            } 
        }
                
        /* 
         * Validate amount field :decimal (11,2) - 
         * ie. Precision is 11 digits
         */        
        /*amount = $values['amount'];
        
        // Round to 2 decimals
        $amount = round($amount, 2);
        
        // Format as string and replace decimal point if any 
        /*$amountStr = str_replace('.', '', sprintf("%.2F", $amount));
        var_dump(sprintf("%.2F", $amount));
        
        // Check that number of digits is 11 or less
        var_dump($amountStr);die;
        if (strlen($amountStr) > 11) {
            $message = __('Amount is too large. Should be 11 digits or less');
            $error = new sfValidatorError($validator, $message);            
            throw new sfValidatorErrorSchema($validator, array('amount' => $error)); 
        } else {
            $values['amount'] = $amount;
        }*/

        
        return $values;
    }
    
    /**
     * Adds direct deposit information to the salary object
     * 
     * @param type $salary EmpBasicsalary object - passed by reference
     * @return None 
     */
    public function getDirectDeposit(&$salary) {
        
        $id = $this->getValue('id');
        if (!empty($id)) {
            $salary->directDebit->id = $id;
        }
        
        $salary->directDebit->account = $this->getValue('account');
        $accountType = $this->getValue('account_type');
        
        if ($accountType == self::ACCOUNT_TYPE_OTHER) {
            $salary->directDebit->account_type = $this->getValue('account_type_other');
        } else {
            $salary->directDebit->account_type = $accountType;
        }
        
        $salary->directDebit->routing_num = $this->getValue('routing_num');        
        $salary->directDebit->amount = $this->getValue('amount');        
    }
    
    public function getAccountTypeDescription($accountType) {
        $accountTypeDescription = $accountType;
        
        if (!empty($accountType) && isset($this->accountTypes[$accountType])) {
            $accountTypeDescription = $this->accountTypes[$accountType];            
        }

        return($accountTypeDescription);
    }
  
}

