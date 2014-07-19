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
 * Description of ohrmWidgetCheckboxGroup
 *
 */
class ohrmWidgetCheckboxGroup extends sfWidgetFormSelectCheckbox {

    protected $id;
    protected $allOptionId;

    protected function configure($options = array(), $attributes = array()) {
        parent::configure($options, $attributes);

        // option value for 'all' checkbox. Set to a valid option to enable the 'All'
        // checkbox.
        $this->addOption('show_all_option', false);

        $this->addOption('all_option_label', __('All'));

        $this->addOption('all_option_first', true);

        // Separator between label and input
        $this->addOption('label_separator', '&nbsp;');

        // Separator between inputs
        $this->addOption('separator', "\n");

        // Formatter class
        $this->addOption('formatter', array($this, 'formatter'));

        // Label first (if true)
        $this->addOption('label_first', true);

        // Container tag for one label : input pair
        $this->addOption('item_container', null);

        // Container tag for widget. Defaults to 'div' if not supplied or null.
        $this->addOption('widget_container', 'div');

        // Container tag for 
        $this->addOption('template', '%group% %options%');

        $this->addOption('class', 'checkbox_group');
    }

    /**
     * Renders the widget.
     *
     * @param  string $name        The element name
     * @param  string $value       The value selected in this widget
     * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
     * @param  array  $errors      An array of errors for the field
     *
     * @return string An HTML tag string
     *
     * @see sfWidgetForm
     */
    public function render($name, $value = null, $attributes = array(), $errors = array()) {

        if ('[]' != substr($name, -2)) {
            $name .= '[]';
        }

        if (isset($attributes['id'])) {
            $this->setId($attributes['id']);
        } else {
            $this->setId($this->generateId($name, 'checkboxgroup'));
        }

        $this->setAllCheckboxId($this->getId() . '_allcheck');

        $html = parent::render($name, $value, $attributes, $errors);

        // Add javascript (only if we have an 'All' checkbox
        if ($this->getOption('show_all_option')) {
            $allOptionId = $this->getAllCheckboxId();

            $template = <<< EOF
<script type="text/javascript">

$(document).ready(function() {

    $('#{all.checkbox.id}').click(function() {
       $('#{container.id} input[type="checkbox"]').attr('checked', $(this).attr('checked'));
    });
                                
    $('#{container.id} input[type="checkbox"]').click(function() {
        var check = $(this).attr('checked');
        if (!check) {
            $('#{all.checkbox.id}').attr('checked', false);
        }
    });
});

 </script>
EOF;

            $templateVars = array(
                '{all.checkbox.id}' => $allOptionId,
                '{container.id}' => $this->getId()
            );

            $javascript = strtr($template, $templateVars);
            $html .= $javascript;
        }

        return $html;
    }

    protected function formatChoices($name, $value, $choices, $attributes) {
        $inputs = array();
        $checkedCount = 0;

        foreach ($choices as $key => $option) {
            $baseAttributes = array(
                'name' => $name,
                'type' => 'checkbox',
                'value' => self::escapeOnce($key),
                'id' => $id = $this->generateId($name, self::escapeOnce($key)),
            );

            if ((is_array($value) && in_array(strval($key), $value)) || strval($key) == strval($value)) {
                $baseAttributes['checked'] = 'checked';
                $checkedCount++;
            }

            $inputs[$id] = array(
                'input' => $this->renderTag('input', array_merge($baseAttributes, $attributes)),
                'label' => $this->renderContentTag('label', self::escapeOnce($option), array('for' => $id)),
            );
        }

        $showAllOption = $this->getOption('show_all_option');

        // Add the "ALL" checkbox if requested.
        if ($showAllOption) {

            $allCheckboxId = $this->getAllCheckboxId();
            $allCheckboxLabel = self::escapeOnce($this->getOption('all_option_label'));

            $allCheckboxAttributes = array(
                'id' => $allCheckboxId,
                'type' => 'checkbox'
            );

            // If all checkboxes are checked
            if ($checkedCount == count($choices)) {
                $allCheckboxAttributes['checked'] = 'checked';
            }

            $allCheckbox = array(
                'input' => $this->renderTag('input', $allCheckboxAttributes),
                'label' => $this->renderContentTag('label', $allCheckboxLabel, array('for' => $allCheckboxId)),
            );

            $allOptionFirst = $this->getOption('all_option_first');
            
            if ($allOptionFirst) {
                $inputs = array($allCheckboxId => $allCheckbox) + $inputs;
            } else {
                $inputs[$allCheckboxId] = $allCheckbox;
            }
        }

        return call_user_func($this->getOption('formatter'), $this, $inputs);
    }

    public function formatter($widget, $inputs) {

        $rows = array();

        $labelFirst = $this->getOption('label_first');
        $itemContainer = $this->getOption('item_container');
        $widgetContainer = $this->getOption('widget_container');

        if (empty($widgetContainer)) {
            $widgetContainer = 'div';
        }

        foreach ($inputs as $input) {

            if ($labelFirst) {
                $row = $input['label'] . $this->getOption('label_separator') . $input['input'];
            } else {
                $row = $input['input'] . $this->getOption('label_separator') . $input['label'];
            }

            if (!empty($itemContainer)) {
                $rows[] = $this->renderContentTag($itemContainer, $row);
            } else {
                $rows[] = $row;
            }
        }

        $html = "";

        if ($rows) {
            $html = $this->renderContentTag('div', implode($this->getOption('separator'), $rows), array('class' => $this->getOption('class'),
                'id' => $widget->getId()));
        }

        return $html;
    }

    public function getStylesheets() {
        $styleSheets = parent::getStylesheets();
        $styleSheets['/orangehrmCorePlugin/css/ohrmWidgetCheckboxGroup.css'] = 'all';

        return($styleSheets);
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getAllCheckboxId() {
        return $this->allCheckboxId;
    }

    public function setAllCheckboxId($id) {
        $this->allCheckboxId = $id;
    }

}

