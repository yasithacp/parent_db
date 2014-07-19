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

class ohrmReportWidgetAgeGroup extends sfWidgetForm implements ohrmEnhancedEmbeddableWidget {

    private $whereClauseCondition;
    private $id;
    
    private $conditionMap = array(1 => '<', 2 => '>', 3 => 'BETWEEN');
        
    public function configure($options = array(), $attributes = array()) {

        $this->id = $attributes['id'];

        $choices = array(
                '' => '-- ' . __('Select') . ' --',
                '1' => __('Less Than'),
                '2' => __('Greater Than'),
                '3' => __('Range')
        );

        $this->addOption($this->id . '_' . 'comparision', new sfWidgetFormChoice(array('choices' => $choices)));
        $this->addOption($this->id . '_' . 'value1', new sfWidgetFormInputText($options, array('size' => 5)));
        $this->addOption($this->id . '_' . 'value2', new sfWidgetFormInputText($options, array('size' => 5)));


        $this->addOption('template', '%comparision% &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp %value1% &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp %value2%');
    }

    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        $values = array_merge(array('value1' => '', 'value2' => '', 'comparision' => ''), is_array($value) ? $value : array());

        $html = strtr($this->translate($this->getOption('template')), array(
                    '%comparision%' => $this->getOption($this->attributes['id'] . '_' . 'comparision')->render($name . '[comparision]', $values['comparision'], array('id' => $this->attributes['id'] . '_' . 'comparision')),
                    '%value1%' => $this->getOption($this->attributes['id'] . '_' . 'value1')->render($name . '[value1]', $values['value1'], array('id' => $this->attributes['id'] . '_' . 'value1')),
                    '%value2%' => $this->getOption($this->attributes['id'] . '_' . 'value2')->render($name . '[value2]', $values['value2'], array('id' => $this->attributes['id'] . '_' . 'value2')),
                ));

        $javaScript = $javaScript = sprintf(<<<EOF
 <script type="text/javascript">

$(document).ready(function() {

    var idValue = '%s';

    if($('#' + idValue + '_comparision').val() == ''){
        $('#' + idValue + '_value1').hide().val('');
        $('#' + idValue + '_value2').hide().val('');
    }

    $('#' + idValue + '_comparision').change(function(){
        if($('#' + idValue + '_comparision').val() == ''){
            $('#' + idValue + '_value1').hide().val('');
            $('#' + idValue + '_value2').hide().val('');
        }else if($('#' + idValue + '_comparision').val() == '1'){
            $('#' + idValue + '_value1').show();
            $('#' + idValue + '_value2').hide().val('');
        }else if($('#' + idValue + '_comparision').val() == '2'){
            $('#' + idValue + '_value1').show();
            $('#' + idValue + '_value2').hide().val('');
        }else if($('#' + idValue + '_comparision').val() == '3'){
            $('#' + idValue + '_value1').show();
            $('#' + idValue + '_value2').show();
        }
    });
    
    $('#' + idValue + '_comparision').trigger('change');
 });
 </script>
EOF
                        ,
                        $this->attributes['id']);

        return $html . $javaScript;
    }


    /**
     * Embeds this widget into the form. Sets label and validator for this widget.
     * @param sfForm $form
     */
    public function embedWidgetIntoForm(sfForm &$form) {


        $widgetSchema = $form->getWidgetSchema();
        $validatorSchema = $form->getValidatorSchema();

        $widgetSchema[$this->attributes['id']] = $this;
        $widgetSchema[$this->attributes['id']]->setLabel(ucwords(str_replace("_", " ", $this->attributes['id'])));
        
        $required = 
//        $validatorSchema[$this->attributes['id']] = new ohrmValidatorDateRange(array(), array("invalid" => "Insert a correct date"));
//        $validatorSchema[$this->attributes['id']] = new sfValidatorPass();
//        $validatorSchema->setPostValidator(new ohrmValidatorSchemaDateRange($this->attributes['id'], ohrmValidatorSchemaDateRange::LESS_THAN_EQUAL, $this->attributes['id'],
//                        array('throw_global_error' => true),
//                        array('invalid' => 'The from date ("%left_field%") must be before the to date ("%right_field%")')
//        ));

        $requiredMessage = __(ValidationMessages::REQUIRED);
        $validatorSchema[$this->attributes['id']] = new ohrmValidatorConditionalFilter(array(), array('required' => $requiredMessage));
        
    }

    /**
     * Sets whereClauseCondition.
     * @param string $condition
     */
    public function setWhereClauseCondition($condition) {
        
        if (isset($this->conditionMap[$condition])) {
            $this->whereClauseCondition = $this->conditionMap[$condition];
        }
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
            $defaultCondition = "=";
            return $defaultCondition;
        }
    }

    /**
     * This method generates the where clause part.
     * @param string $fieldName
     * @param string $value
     * @return string
     */
    public function generateWhereClausePart($fieldName, $value) {

        $condition = $this->getWhereClauseCondition();

        if($condition == '<'){
            return $whereClausePart = $fieldName . " " . $this->getWhereClauseCondition() . " " . $value['value1'];
        }else if($condition == '>'){
            return $whereClausePart = $fieldName . " " . $this->getWhereClauseCondition() . " " . $value['value1'];
        }else if($condition == 'BETWEEN'){
            return "( " . $fieldName . " " . $this->getWhereClauseCondition() . " '" . $value['value1'] . "' AND '" . $value['value2'] . "' )";
        }

        return null;
    }

    public function getDefaultValue(SelectedFilterField $selectedFilterField) {
        
        $condition = '';
        
        if (!empty($selectedFilterField->whereCondition)) {
            $condition = array_search($selectedFilterField->whereCondition, $this->conditionMap);            
        }
        
        $values = array('value1' => $selectedFilterField->value1, 
                        'value2' => $selectedFilterField->value2,
                        'comparision' => $condition);

        return $values;
    }
}

