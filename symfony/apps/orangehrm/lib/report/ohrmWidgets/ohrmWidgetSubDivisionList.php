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

class ohrmWidgetSubDivisionList extends sfWidgetForm implements ohrmEnhancedEmbeddableWidget {

    private $whereClauseCondition;
    private $subDivisionList;

    public function configure($options = array(), $attributes = array()) {

        $this->subDivisionList = $this->_getChoiceData();

        $this->addOption('choices', $this->subDivisionList);
    }

    public function render($name, $value = null, $attributes = array(), $errors = array()) {
        $value = $value === null ? 'null' : $value;

        $options = array();

        foreach ($this->getOption('choices') as $key => $option) {
            $attributes = array('value' => self::escapeOnce($key));

            if ($key == $value) {
                $attributes['selected'] = 'selected';
            }

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

        return $html;
    }

    /**
     * Retrieve employment status list.
     * @return array() $choice
     */
    private function _getChoiceData() {

        $choice = array();

        $choice['0'] = __('All');
        $companyStructureService = new CompanyStructureService();
        $treeObject = $companyStructureService->getSubunitTreeObject();

        $tree = $treeObject->fetchTree();

        foreach ($tree as $node) {
            if ($node->getId() != 1) {
                $value = $node->getId();
                $children = $node->getNode()->getChildren();

                if ($children !== false) {
                    foreach ($children as $childNode) {
                        $value = $value . "," . $childNode->getId();
                    }
                }
                $choice[$value] = str_repeat('&nbsp;&nbsp;', $node['level'] - 1) . $node['name'];
            }
        }
//        asort($choice);
        return $choice;
    }

    /**
     * Embeds this widget into the form. Sets label and validator for this widget.
     * @param sfForm $form
     */
    public function embedWidgetIntoForm(sfForm &$form) {

        $widgetSchema = $form->getWidgetSchema();
        $widgetSchema[$this->attributes['id']] = $this;
        $label = ucwords(str_replace("_", " ", $this->attributes['id']));
        $validator = new sfValidatorChoice(array('choices' => array_keys($this->subDivisionList)));
        if ($this->attributes['required'] == "true") {
            $label .= "<span class='required'> * </span>";
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
            $defaultCondition = "IN";
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

        if ($value == 0) {
            return null;
        } else {
            $whereClausePart = $fieldName . " " . $this->getWhereClauseCondition() . " " . "(" . $value . ")";
            return $whereClausePart;
        }
    }

    public function getDefaultValue(SelectedFilterField $selectedFilterField) {
        return $selectedFilterField->value1;
    }

}