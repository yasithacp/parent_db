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

class ohrmWidgetProjectActivityList extends sfWidgetForm implements ohrmEmbeddableWidget {

    private $whereClauseCondition;

    public function configure($options = array(), $attributes = array()) {

        $activityNameList = $this->_getDataList();

        $this->addOption('choices', $activityNameList);
    }

    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        $value = $value === null ? 'null' : $value;

        $options = array();

        foreach ($this->getOption('choices') as $key => $option) {
            $attributes = array('value' => self::escapeOnce($key));

            $options[] = $this->renderContentTag(
                            'option',
                            self::escapeOnce($option),
                            $attributes
            );
        }

        $html = $this->renderContentTag(
                        'select',
                        "\n" . implode("\n", $options) . "\n",
                        array_merge(array('name' => $name), $attributes
                ));


        $javaScript = $javaScript = sprintf(<<<EOF
 <script type="text/javascript">

$(document).ready(function() {
     var getActivitiesLink = '%s';

     $('#time_project_name').change(function() {
        
        var projectId = $('#time_project_name').val();
        var urlData = "projectId="+projectId;

        var r = $.ajax({
			type: "POST",
			url: getActivitiesLink,
			dataType: "html",
			data: urlData,
			success: function(msg){
				$('#time_activity_name').html(msg);
				}
		}).responseText;
    });

     $('#activity_show_deleted').change(function() {
        var projectId = $('#time_project_name').val();
        var urlData = "projectId="+projectId;

        if($('#activity_show_deleted').length > 0) {

            if($('#activity_show_deleted').is(':checked')) {
                urlData = urlData + "&deleted=true";
            }
        }

        var r = $.ajax({
			type: "POST",
			url: getActivitiesLink,
			dataType: "html",
			data: urlData,
			success: function(msg){
				$('#time_activity_name').html(msg);
				}
		}).responseText;
     });
 });
 </script>
EOF
                        ,
                        url_for('admin/getActivitiesRelatedToAProjectAjax'));

        return $html . $javaScript;
    }

    /**
     * Gets all the names of available projects, including deleted projects.
     * @return array() $projectNameList
     */
    private function _getDataList() {

        $activityNameList = array();

        $projectService = new ProjectService();
        $projectList = $projectService->getActiveProjectList();
        $projectId = -1;

        foreach ($projectList as $project) {

            $projectId = $project->getProjectId();
            break;
        }

        $timesheetDao = new TimesheetDao();
        $activityList = $timesheetDao->getProjectActivitiesByPorjectId($projectId);

        if ($activityList != null) {
            $activityNameList[-1] = __("All");
        } else {
            $activityNameList[null] = "--".__("No Project Activities")."--";
        }

        return $activityNameList;
    }

    /**
     * Embeds this widget into the form. Sets label and validator for this widget.
     * @param sfForm $form
     */
    public function embedWidgetIntoForm(sfForm &$form) {

        $widgetSchema = $form->getWidgetSchema();
        $widgetSchema[$this->attributes['id']] = $this;
        $label = __(ucwords(str_replace("_", " ", $this->attributes['id'])));
        $validator = new sfValidatorString(array('required' => false));
        if ($this->attributes['required'] == "true") {
            $label .= "<span class='required'> * </span>";
            $validator = new sfValidatorString(array('required' => true), array('required' => __('Add an activiy to view')));
        }
        $widgetSchema[$this->attributes['id']]->setLabel($label);
        $form->setValidator($this->attributes['id'], $validator);
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


        if ($value == -1) {
            return null;
        } else {
            $whereClausePart = $fieldName . " " . $this->getWhereClauseCondition() . " " . $value;
            return $whereClausePart;
        }
    }

}

