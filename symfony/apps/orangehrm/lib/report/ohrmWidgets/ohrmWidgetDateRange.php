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

class ohrmWidgetDateRange extends sfWidgetForm implements ohrmEmbeddableWidget {

    private $whereClauseCondition;
    private $id;

    public function configure($options = array(), $attributes = array()) {

        $this->id = $attributes['id'];
        $this->addOption($this->id . '_' . 'from_date', new ohrmWidgetDatePickerNew(array(), array('id' => $this->id . '_' . 'from_date')));
        $this->addOption($this->id . '_' . 'to_date', new ohrmWidgetDatePickerNew(array(), array('id' => $this->id . '_' . 'to_date')));


        $this->addOption('template', __('From').' &nbsp %from_date% &nbsp&nbsp&nbsp&nbsp&nbsp '.__('To').' &nbsp %to_date%');
    }

    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        $values = array_merge(array('from' => '', 'to' => '', 'is_empty' => ''), is_array($value) ? $value : array());

        return strtr($this->translate($this->getOption('template')), array(
            '%from_date%' => $this->getOption($this->attributes['id'] . '_' . 'from_date')->render($name . '[from]', null, array('id' => $this->attributes['id'] . '_' . 'from_date')),
            '%to_date%' => $this->getOption($this->attributes['id'] . '_' . 'to_date')->render($name . '[to]', null, array('id' => $this->attributes['id'] . '_' . 'to_date')),
        ));
    }

    /**
     * Embeds this widget into the form. Sets label and validator for this widget.
     * @param sfForm $form
     */
    public function embedWidgetIntoForm(sfForm &$form) {


        $widgetSchema = $form->getWidgetSchema();
        $validatorSchema = $form->getValidatorSchema();

        $widgetSchema[$this->attributes['id']] = $this;
        $widgetSchema[$this->attributes['id']]->setLabel(__(ucwords(str_replace("_", " ", $this->attributes['id']))));

        $validatorSchema[$this->attributes['id']] = new ohrmValidatorDateRange(array(), array("invalid" => "Insert a correct date"));
    }

    /**
     * Sets whereClauseCondition.
     * @param string $condition
     */
    public function setWhereClauseCondition($condition) {

        $this->whereClauseCondition = $condition;
    }

    /**
     * Gets whereClauseCondition. ( if whereClauseCondition is set returns that, else returns default condition )
     * @return string ( a condition )
     */
    public function getWhereClauseCondition() {

        if (isset($this->whereClauseCondition)) {
            $setCondition = $this->whereClauseCondition;
            return $setCondition;
        } else {
            $defaultCondition = "BETWEEN";
            return $defaultCondition;
        }
    }

    /**
     * This method generates the where clause part.
     * @param string $fieldName
     * @param string $value
     * @return string
     */
    public function generateWhereClausePart($fieldName, $dateRanges) {

        $fromDate = "1970-01-01";
        $toDate = date("Y-m-d");
        $inputDatePattern = sfContext::getInstance()->getUser()->getDateFormat();
        $datepickerDateFormat = get_datepicker_date_format($inputDatePattern);

        if (($dateRanges["from"] != $datepickerDateFormat) && ($dateRanges["to"] != $datepickerDateFormat)) {

            if (($dateRanges["to"] != "")) {
                $toDate = $dateRanges["to"];
            }
            if (($dateRanges["from"] != "")) {
                $fromDate = $dateRanges["from"];
            }
        } else if (($dateRanges["from"] == $datepickerDateFormat) && ($dateRanges["to"] != $datepickerDateFormat)) {
            if (($dateRanges["to"] != "")) {
                $toDate = $dateRanges["to"];
            }
        } else if (($dateRanges["from"] != $datepickerDateFormat) && ($dateRanges["to"] == $datepickerDateFormat)) {
            if (($dateRanges["from"] != "")) {
                $fromDate = $dateRanges["from"];
            }
        }


        return "( " . $fieldName . " " . $this->getWhereClauseCondition() . " '" . $fromDate . "' AND '" . $toDate . "' )";
    }

}

