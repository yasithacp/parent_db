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

class ohrmReportWidgetEmployeeListAutoFill extends sfWidgetForm implements ohrmEnhancedEmbeddableWidget {

    private $whereClauseCondition;

    public function configure($options = array(), $attributes = array()) {

        $this->id = $attributes['id'];

        $this->addOption($this->id . '_' . 'empName', new sfWidgetFormInputText($options, $attributes));
        $this->addOption($this->id . '_' . 'empId', new sfWidgetFormInputHidden($options, $attributes));


        $this->addOption('template', '%empId%%empName%');
    }

    public function render($name, $value = null, $attributes = array(), $errors = array()) {

        $empName = null;
        $empId = null;
        
        if ($value != null) {
            $service = new EmployeeService();
            if (is_array($value)) {
                $empId = isset($value['empId']) ? $value['empId'] : '';
                $empName = isset($value['empName']) ? $value['empName'] : '';
            } else {
                $empId = $value;
                $employee = $service->getEmployee($value);
                if (!empty($employee)) {
                    $empName = $employee->getFirstName() . " " . $employee->getMiddleName();
                    $empName = trim(trim($empName) . " " . $employee->getLastName());
                }
            }
        }

        $values = array_merge(array('empName' => '', 'empId' => ''), is_null($value) ? array() : array('empName' => $empName, 'empId' => $empId));

        $html = strtr($this->translate($this->getOption('template')), array(
                    '%empId%' => $this->getOption($this->attributes['id'] . '_' . 'empId')->render($name . '[empId]', $values['empId'], array('id' => $this->attributes['id'] . '_' . 'empId')),
                    '%empName%' => $this->getOption($this->attributes['id'] . '_' . 'empName')->render($name . '[empName]', $values['empName'], array('id' => $this->attributes['id'] . '_' . 'empName')),
                ));

        $noEmployeeMessage = __('No Employees Available');
        $requiredMessage = __(ValidationMessages::REQUIRED);
        $invalidMessage = __(ValidationMessages::INVALID);
        $typeHint = __('Type for hints') . '...';
        
        $javaScript = $javaScript = sprintf(<<<EOF
<script type="text/javascript">

    var employees = %s;
    var employeesArray = eval(employees);
    var errorMsge;
    var employeeFlag;
    var empId;
    var valid = false; 

$(document).ready(function() {

            if ($("#%s" + "_empName").val() == '') {
                $("#%s" + "_empName").val('%s')
                .addClass("inputFormatHint");
            }

            $("#%s" + "_empName").one('focus', function() {

                if ($(this).hasClass("inputFormatHint")) {
                    $(this).val("");
                    $(this).removeClass("inputFormatHint");
                }
            });

    $("#%s" + "_empName").autocomplete(employees, {

            formatItem: function(item) {

                return item.name;
            }
            ,matchContains:true
        }).result(function(event, item) {
            $("#%s" + "_empId").val(item.id);
        }
    );

    $('#btnSav').click(function() {
                $('#defineReportForm input.inputFormatHint').val('');
                $('#defineReportForm').submit();
        });

        $('#defineReportFor').submit(function(){
            $('#validationMsg').removeAttr('class');
            $('#validationMsg').html("");
            var employeeFlag = validateInput();
            if(!employeeFlag) {
                $('#validationMsg').attr('class', "messageBalloon_failure");
                $('#validationMsg').html(errorMsge);
                return false;
            }
        });

 });

function validateInput(){

        var errorStyle = "background-color:#FFDFDF;";
        var empDateCount = employeesArray.length;
        var temp = false;
        var i;

        if(empDateCount==0){

            errorMsge = "$noEmployeeMessage";
            return false;
        }
        for (i=0; i < empDateCount; i++) {
            empName = $.trim($('#%s' + '_empName').val()).toLowerCase();
            arrayName = employeesArray[i].name.toLowerCase();

            if (empName == arrayName) {
                $('#%s' + '_empId').val(employeesArray[i].id);
                empId = employeesArray[i].id
                temp = true
                break;
            }
        }
        if(temp){
            valid = true;
            return true;
        }else if(empName == "" || empName == $.trim("$typeHint").toLowerCase()){
            errorMsge = "$requiredMessage";
            return false;
        }else{
            if(valid != true){
            errorMsge = "$invalidMessage";
            return false;
            }else{
            return true;
            }
        }
    }
 </script>
EOF
                        ,
                        $this->getEmployeeListAsJson(sfContext::getInstance()->getUser()->getAttribute("user")->getEmployeeList()),
                        $this->attributes['id'],
                        $this->attributes['id'],
                        __("Type for hints")." ...",
                        $this->attributes['id'],
                        $this->attributes['id'],
                        $this->attributes['id'],
                        $this->attributes['id'],
                        $this->attributes['id']);

        return $html . $javaScript;
    }

    public function getEmployeeListAsJson($employeeList) {

        $jsonArray = array();
        
        $employeeService = new EmployeeService();
        $employeeService->setEmployeeDao(new EmployeeDao());

        $employeeUnique = array();
        foreach ($employeeList as $employee) {

            if (!isset($employeeUnique[$employee->getEmpNumber()])) {

                $name = $employee->getFullName();
                $employeeUnique[$employee->getEmpNumber()] = $name;
                $jsonArray[] = array('name' => $name, 'id' => $employee->getEmpNumber());
                
            }
        }

        $jsonString = json_encode($jsonArray);

        return $jsonString;
    }

    /**
     * Embeds this widget into the form. Sets label and validator for this widget.
     * @param sfForm $form
     */
    public function embedWidgetIntoForm(sfForm &$form) {

        $widgetSchema = $form->getWidgetSchema();
        $widgetSchema[$this->attributes['id']] = $this;
        $label = __(ucwords(str_replace("_", " ", $this->attributes['id'])));
        $required = false;
        
        $required = false;
        
        if (isset($this->attributes['required']) && ($this->attributes['required'] == "true")) {
            $label .= "<span class='required'> * </span>";
            $required = true;            
        } 
        
        $validator = new sfValidatorCallback(array('callback' => array($this, 'validate'), 'required' => $required), 
                 array('required' => __(ValidationMessages::REQUIRED)));
        
        $widgetSchema[$this->attributes['id']]->setLabel($label);
        $form->setValidator($this->attributes['id'], $validator);
    }
    
    public function validate($callBackValidator, $value, $args) {
        $empId = isset($value['empId']) ? $value['empId'] : null;
        
        if ($callBackValidator->getOption('required') && empty($empId)) {
            throw new sfValidatorError($callBackValidator, 'required');
        } 
        
        if (!empty($empId)) {
            // validate a number
        }
        
        return($value);
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

        $whereClausePart = $fieldName . " " . $this->getWhereClauseCondition() . " " . $value['empId'];

        return $whereClausePart;
    }

    public function getDefaultValue(SelectedFilterField $selectedFilterField) {
        return $selectedFilterField->value1;
    }

}

