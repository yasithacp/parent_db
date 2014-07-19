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
 * Form class for employee dependents
 */
class EmployeeDependentForm extends BaseForm {
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
        $i18nHelper = sfContext::getInstance()->getI18N();

        $relationshipChoices = array('' => "-- " . __('Select') . " --", 'child'=> $i18nHelper->__('Child'), 'other'=> $i18nHelper->__('Other'));

        $this->setWidgets(array(
            'empNumber' => new sfWidgetFormInputHidden(array(),
                    array('value' => $empNumber)),
            'seqNo' => new sfWidgetFormInputHidden(), // seq no
            'name' => new sfWidgetFormInputText(),
            'relationshipType' => new sfWidgetFormSelect(array('choices' => $relationshipChoices)),
            'relationship' => new sfWidgetFormInputText(),
            'dateOfBirth' => new ohrmWidgetDatePickerNew(array(), array('id' => 'dependent_dateOfBirth')),
        ));

        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        unset($relationshipChoices['']);
        $this->setValidators(array(
            'empNumber' => new sfValidatorNumber(array('required' => true, 'min'=> 0)),
            'seqNo' => new sfValidatorNumber(array('required' => false, 'min'=> 0)),
            'name' => new sfValidatorString(array('required' => true, 'trim'=>true, 'max_length'=>100)),
            'relationshipType' => new sfValidatorChoice(array('choices' => array_keys($relationshipChoices))),
            'relationship' => new sfValidatorString(array('required' => false, 'trim'=>true, 'max_length'=>100)),
            'dateOfBirth' =>  new ohrmDateValidator(array('date_format'=>$inputDatePattern, 'required'=>false),
                              array('invalid'=>'Date format should be '. $inputDatePattern)),

        ));


        $this->widgetSchema->setNameFormat('dependent[%s]');
    }


    /**
     * Save employee contract
     */
    public function save() {

        $empNumber = $this->getValue('empNumber');
        $seqNo = $this->getValue('seqNo');

        $dependent = false;

        if (empty($seqNo)) {

            $q = Doctrine_Query::create()
                    ->select('MAX(d.seqno)')
                    ->from('EmpDependent d')
                    ->where('d.emp_number = ?', $empNumber);
            $result = $q->execute(array(), Doctrine::HYDRATE_ARRAY);

            if (count($result) != 1) {
                throw new PIMServiceException('MAX(seqno) failed.');
            }
            $seqNo = is_null($result[0]['MAX']) ? 1 : $result[0]['MAX'] + 1;

        } else {
            $dependent = Doctrine::getTable('EmpDependent')->find(array('emp_number' => $empNumber,
                                                                      'seqno' => $seqNo));

            if ($dependent == false) {
                throw new PIMServiceException('Invalid dependent');
            }
        }

        if ($dependent === false) {
            $dependent = new EmpDependent();
            $dependent->emp_number = $empNumber;
            $dependent->seqno = $seqNo;
        }

        $dependent->name = $this->getValue('name');
        $dependent->relationship = $this->getValue('relationship');
        $dependent->relationship_type = $this->getValue('relationshipType');
        $dependent->date_of_birth = $this->getValue('dateOfBirth');

        $dependent->save();
        
    }

}

