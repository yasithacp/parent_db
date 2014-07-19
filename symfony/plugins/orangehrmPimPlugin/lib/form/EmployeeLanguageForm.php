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

class EmployeeLanguageForm extends sfForm {
    
    private $employeeService;
    public $fullName;
    private $widgets = array();
    public $empLanguageList;

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

        $this->empLanguageList = $this->getEmployeeService()->getLanguage($empNumber);

        $i18nHelper = sfContext::getInstance()->getI18N();
        
        $availableLanguageList = $this->_getLanguageList();
        $this->langTypeList = array("" => '-- ' . $i18nHelper->__('Select') . ' --',
                               1 => $i18nHelper->__('Writing'),
                               2 => $i18nHelper->__('Speaking'),
                               3 => $i18nHelper->__('Reading'));
        $this->competencyList = array("" => '-- ' . $i18nHelper->__('Select') . ' --',
                                 1 => $i18nHelper->__('Poor'),
                                 2 => $i18nHelper->__('Basic'),
                                 3 => $i18nHelper->__('Good'),
                                 4 => $i18nHelper->__('Mother Tongue'));
        
        //initializing the components
        $this->widgets = array(
            'emp_number' => new sfWidgetFormInputHidden(),
            'code' => new sfWidgetFormSelect(array('choices' => $availableLanguageList)),
            'lang_type' => new sfWidgetFormSelect(array('choices' => $this->langTypeList)),
            'competency' => new sfWidgetFormSelect(array('choices' => $this->competencyList)),
            'comments' => new sfWidgetFormTextarea()
        );

        $this->widgets['emp_number']->setDefault($empNumber);
        $this->setWidgets($this->widgets);

        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        
        $this->setValidator('emp_number', new sfValidatorString(array('required' => false)));
        $this->setValidator('code', new sfValidatorChoice(array('choices' => array_keys($availableLanguageList))));
        $this->setValidator('lang_type', new sfValidatorChoice(array('choices' => array_keys($this->langTypeList))));
        $this->setValidator('competency', new sfValidatorChoice(array('choices' => array_keys($this->competencyList))));
        $this->setValidator('comments', new sfValidatorString(array('required' => false, 'max_length' => 100)));

        $this->widgetSchema->setNameFormat('language[%s]');
    }
    
    public function getLangTypeDesc($langType) {
        $langTypeDesc = "";
        if (isset($this->langTypeList[$langType])) {
            $langTypeDesc = $this->langTypeList[$langType];
        }    
        return $langTypeDesc;
    }
    
    public function getCompetencyDesc($competency) {
        $competencyDesc = "";
        if (isset($this->competencyList[$competency])) {
            $competencyDesc = $this->competencyList[$competency];
        }
        return $competencyDesc;
    }

    private function _getLanguageList() {
        $languageService = new LanguageService();
        $languageList = $languageService->getLanguageList();
        $list = array("" => "-- " . __('Select') . " --");

        foreach($languageList as $language) {
            $list[$language->getId()] = $language->getName();
        }

        return $list;
    }
}
?>