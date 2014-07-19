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

class HolidayForm extends sfForm {

    private $workWeekEntity;
    private $holidayService;
    public $editMode = false;

    /**
     * Holiday form configuration
     */
    public function configure() {

        $this->setWidgets($this->getFormWidgets());
        $this->setValidators($this->getFormValidators());
        
        $this->getValidatorSchema()->setOption('allow_extra_fields', true);

        $this->getWidgetSchema()->setLabels($this->getFormLabels());
        $this->getWidgetSchema()->setNameFormat('holiday[%s]');
        $this->getWidgetSchema()->setFormFormatterName('BreakTags');
    }

    /**
     * Set method for Work Week Entity
     * @param WorkWeek $workWeek
     */
    public function setWorkWeekEntity(WorkWeek $workWeek) {
        $this->workWeekEntity = $workWeek;
    }

    /**
     * Get method for Work Week Entity
     * @return WorkWeek workWeekEntity
     */
    public function getWorkWeekEntity() {
        if (!($this->workWeekEntity instanceof WorkWeek)) {
            $this->workWeekEntity = new WorkWeek();
        }
        return $this->workWeekEntity;
    }

    /**
     * Set method for Holiday Service
     * @param HolidayService $holidayService
     */
    public function setHolidayService(HolidayService $holidayService) {
        $this->holidayService = $holidayService;
    }

    /**
     * Get method for Holiday Service
     * @return HolidayService
     */
    public function getHolidayService() {
        if (!($this->holidayService instanceof HolidayService)) {
            $this->holidayService = new HolidayService();
        }
        return $this->holidayService;
    }

    /**
     * Get required days Length List ignore "Weekend"
     */
    public function getDaysLengthList() {
        $fullDaysLengthList = WorkWeek::getDaysLengthList();
        unset($fullDaysLengthList[8]);
        return $fullDaysLengthList;
    }

    /**
     * Set the default values for sfWidgetForm Elements
     * @param integer $holidayId
     */
    public function setDefaultValues($holidayId) {

        $holidayObject = $this->getholidayService()->readHoliday($holidayId);

        if ($holidayObject instanceof Holiday) {
            sfContext::getInstance()->getConfiguration()->loadHelpers('OrangeDate');
            $chkRecurring = $holidayObject->getRecurring() == '1' ? true : false;

            $this->setDefault('id', $holidayObject->getId());
            $this->setDefault('description', $holidayObject->getDescription());
            $this->setDefault('date', set_datepicker_date_format($holidayObject->getDate()));
            $this->setDefault('recurring', $chkRecurring);
            $this->setDefault('length', $holidayObject->getLength());
        }
    }

    /**
     * Check for already added holiday is valid to save and validations are passed
     *
     * @param sfValidatorCallback $validator
     * @param array $values
     */
    public function checkHolidayRules($validator, $values) {
        $date = $values['date'];

        $holidayId = $values['id'];
        $holidayObjectDate = $this->getHolidayService()->readHolidayByDate($date);

        $allowToAdd = true;

        if ($this->editMode) {
            $holidayObject = $this->getHolidayService()->readHoliday($holidayId);
            /* If the selected date is already in a holiday not allow to add */
            if ($holidayObject->getDate() != $date && $date == $holidayObjectDate->getDate()) {
                $allowToAdd = false;
            }
        } else {
            /* Days already added can not be selected to add */
            if ($date == $holidayObjectDate->getDate()) {
                $allowToAdd = false;
            }
        }

        /* Error will not return if the date if not in the correct format */
        if (!$allowToAdd && !is_null($date)) {
            $error = new sfValidatorError($validator, 'Holiday date is in use');
            throw new sfValidatorErrorSchema($validator, array('date' => $error));
        }
        return $values;
    }

    /**
     *
     * @return array
     */
    protected function getFormWidgets() {
        $widgets = array();
        $widgets['id'] = new sfWidgetFormInputHidden();
        $widgets['description'] = new sfWidgetFormInput(array(), array(
                    'class' => 'formInputText',
                ));
        $widgets['date'] = new ohrmWidgetDatePickerNew(array(), array(
                    'id' => 'holiday_date',
                    'class' => 'formDateInput'
                ));
        $widgets['recurring'] = new sfWidgetFormInputCheckbox(array(), array(
                    'class' => 'formCheckbox',
                ));
        $widgets['length'] = new sfWidgetFormSelect(array(
                    'choices' => $this->getDaysLengthList(),
                        ), array(
                    'add_empty' => false,
                    'class' => 'formSelect',
                ));

        return $widgets;
    }

    /**
     *
     * @return array
     */
    protected function getFormValidators() {
        $validators = array();

        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();

        $validators['id'] = new sfValidatorString(array('required' => false));
        $validators['recurring'] = new sfValidatorString(array('required' => false));
        $validators['description'] = new sfValidatorString(array(
                    'required' => true,
                    'max_length' => 200,
                        ), array(
                    'required' => 'Holiday Name is required',
                    'max_length' => 'Name of Holiday length exceeded',
                ));
        $validators['date'] = new ohrmDateValidator(
                        array('date_format' => $inputDatePattern,
                            'required' => true)
                        , array(
                    'required' => 'Date field is required',
                    'bad_format' => __(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => get_datepicker_date_format(sfContext::getInstance()->getUser()->getDateFormat())))
                ));
        $validators['length'] = new sfValidatorChoice(array('choices' => array_keys($this->getDaysLengthList())));

        return $validators;
    }

    /**
     *
     * @return array
     */
    protected function getFormLabels() {
        $labels = array();
        
        sfContext::getInstance()->getConfiguration()->loadHelpers('Tag');

        $requiredLabel = content_tag('span', '*', array('class' => 'required'));
        
        $labels['description'] = __('Name').' '. $requiredLabel;
        $labels['date'] = __('Date').' '.$requiredLabel;
        $labels['recurring'] = __('Repeats Annually');
        $labels['length'] = __('Full Day/Half Day');
        
        return $labels;
    }
    
    public function getJavaScripts() {
        $javaScripts = parent::getJavaScripts();
        $javaScripts[] = '/orangehrmCoreLeavePlugin/js/defineHolidaySuccess.js';
        $javaScripts[] = '/orangehrmCoreLeavePlugin/js/defineHolidaySuccessValidate.js';

        return $javaScripts;
    }    
}

