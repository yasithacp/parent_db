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
 * Extends sfValidatorSchemaCompare and Adds feature to only compare values if 
 * both are none empty.
 *
 */
class ohrmValidatorSchemaCompare extends sfValidatorSchemaCompare {

    /**
     * Constructor.
     *
     * Available options:
     * 
     * @see sfValidatorSchemaCompare for rest of options
     *  * skip_if_one_empty:  Skip validation if at least one value is empty.
     *  * skip_if_both_empty: Skip validation only if both values empty.
     *  * left_field:         The left field name
     *  * operator:           The comparison operator
     *                          * self::EQUAL
     *                          * self::NOT_EQUAL
     *                          * self::IDENTICAL
     *                          * self::NOT_IDENTICAL
     *                          * self::LESS_THAN
     *                          * self::LESS_THAN_EQUAL
     *                          * self::GREATER_THAN
     *                          * self::GREATER_THAN_EQUAL
     *  * right_field:        The right field name
     *  * throw_global_error: Whether to throw a global error (false by default) or an error tied to the left field
     *
     * @param string $leftField   The left field name
     * @param string $operator    The operator to apply
     * @param string $rightField  The right field name
     * @param array  $options     An array of options
     * @param array  $messages    An array of error messages
     *
     * 
     */
    public function __construct($leftField, $operator, $rightField, $options = array(), $messages = array()) {
        $this->addOption('skip_if_one_empty', false);
        $this->addOption('skip_if_both_empty', false);

        parent::__construct($leftField, $operator, $rightField, $options, $messages);
    }

    protected function doClean($values) {
        if (null === $values) {
            $values = array();
        }

        if (!is_array($values)) {
            throw new InvalidArgumentException('You must pass an array parameter to the clean() method');
        }

        $leftValueSet = $this->_isValueSet($values, $this->getOption('left_field'));
        $rightValueSet = $this->_isValueSet($values, $this->getOption('right_field'));

        $skipIfBothEmpty = $this->getOption('skip_if_both_empty');
        $skipIfOneEmpty = $this->getOption('skip_if_one_empty');

        $skip = false;

        if ($skipIfOneEmpty && (!$leftValueSet || !$rightValueSet)) {
            $skip = true;
        } else if ($skipIfBothEmpty && !$leftValueSet && !$rightValueSet) {
            $skip = true;
        }

        return $skip? $values : parent::doClean($values);

    }
    
    protected function _isValueSet($values, $key) {
        $isSet = false;
        if (isset($values[$key])) {
            $value = $values[$key];
            $isSet = !empty($value);
        }
        
        return $isSet;
    }

}

