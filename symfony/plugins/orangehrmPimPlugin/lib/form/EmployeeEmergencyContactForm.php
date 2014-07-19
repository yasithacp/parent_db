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
 * Form class for employee contact detail
 */
class EmployeeEmergencyContactForm extends BaseForm {
    public $fullName;
    private $employeeService;
    
    /**
     * Get EmployeeService
     * @returns EmployeeService
     */
    public function getEmployeeService() {
        if(is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }

    /**
     * Set EmployeeService
     * @param EmployeeService $employeeService
     */
    public function setEmployeeService(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }
    
    public function configure() {

        $empNumber = $this->getOption('empNumber');
        $employee = $this->getEmployeeService()->getEmployee($empNumber);
        $this->fullName = $employee->getFullName();
        
        // Note: Widget names were kept from old non-symfony version
        $this->setWidgets(array(
            'empNumber' => new sfWidgetFormInputHidden(array(), 
                    array('value' => $empNumber)),
            'seqNo' => new sfWidgetFormInputHidden(), // seq no
            'name' => new sfWidgetFormInputText(),
            'relationship' => new sfWidgetFormInputText(),
            'homePhone' => new sfWidgetFormInputText(),
            'mobilePhone' => new sfWidgetFormInputText(),
            'workPhone' => new sfWidgetFormInputText(),

        ));

        $this->setValidators(array(
            'empNumber' => new sfValidatorNumber(array('required' => true, 'min'=> 0)),
            'seqNo' => new sfValidatorNumber(array('required' => false, 'min' => 1)),
            'name' => new sfValidatorString(array('required' => true)),
            'relationship' => new sfValidatorString(array('required' => true)),
            'homePhone' => new sfValidatorString(array('required' => false)),
            'mobilePhone' => new sfValidatorString(array('required' => false)),
            'workPhone' => new sfValidatorString(array('required' => false))
        ));


        // set up your post validator method
        $this->validatorSchema->setPostValidator(
          new sfValidatorCallback(array(
            'callback' => array($this, 'postValidate')
          ))
        );

        $this->widgetSchema->setNameFormat('emgcontacts[%s]');
    }

    public function postValidate($validator, $values) {

        $homePhone = $values['homePhone'];
        $mobile = $values['mobilePhone'];
        $workPhone = $values['workPhone'];

        if (empty($homePhone) && empty($mobile) && empty($workPhone)) {

            $message = sfContext::getInstance()->getI18N()->__('Specify at least one phone number.');
            $error = new sfValidatorError($validator, $message);
            throw new sfValidatorErrorSchema($validator, array('' => $error));

        }
        
        return $values;
    }


    /**
     * Save employee contract
     */
    public function save() {

        $empNumber = $this->getValue('empNumber');
        $seqNo = $this->getValue('seqNo');

        $emergencyContact = false;

        if (empty($seqNo)) {

            $q = Doctrine_Query::create()
                    ->select('MAX(ec.seqno)')
                    ->from('EmpEmergencyContact ec')
                    ->where('ec.emp_number = ?', $empNumber);
            $result = $q->execute(array(), Doctrine::HYDRATE_ARRAY);

            if (count($result) != 1) {
                throw new PIMServiceException('MAX(seqno) failed.');
            }
            $seqNo = is_null($result[0]['MAX']) ? 1 : $result[0]['MAX'] + 1;

        } else {
            $emergencyContact = Doctrine::getTable('EmpEmergencyContact')->find(array('emp_number' => $empNumber,
                                                                                'seqno' => $seqNo));

            if ($emergencyContact == false) {
                throw new PIMServiceException('Invalid emergency contact');
            }
        }

        if ($emergencyContact === false) {
            $emergencyContact = new EmpEmergencyContact();
            $emergencyContact->emp_number = $empNumber;
            $emergencyContact->seqno = $seqNo;
        }

        $emergencyContact->name = $this->getValue('name');
        $emergencyContact->relationship = $this->getValue('relationship');
        $emergencyContact->home_phone = $this->getValue('homePhone');
        $emergencyContact->mobile_phone = $this->getValue('mobilePhone');
        $emergencyContact->office_phone = $this->getValue('workPhone');

        $emergencyContact->save();
    }

}

