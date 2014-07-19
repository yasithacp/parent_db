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
 * Test class ohrmValidatorSchemaCompare
 * @group core
 */
class ohrmValidatorSchemaCompareTest extends PHPUnit_Framework_TestCase {
    
    public function testDoClean() {
        
        // Check default functionality (same as base class)
        $validator = new ohrmValidatorSchemaCompare('from', 
                        sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'to',
                        array('throw_global_error' => true));
        
        $values = array('from' => 100, 'to' => 200, 'a' => 100, 'toto' => 239);
        $cleaned = $validator->clean($values);
        $this->assertEquals($cleaned, $values);
        
        $values = array('from' => 201, 'to' => 200, 'a' => 100, 'toto' => 239);
        try {
            $cleaned = $validator->clean($values);
            $this->fail("Validation error expected");
        } catch (sfValidatorError $error) {
            // expected.
        }        
    }
    
    public function testDoCleanSkipIfOneEmpty() {
        // Check default functionality (same as base class)
        $validator = new ohrmValidatorSchemaCompare('from', 
                        sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'to',
                        array('throw_global_error' => true));
        
        $values = array('from' => 100, 'to' => '', 'a' => 100, 'toto' => 239);
        try {
            $cleaned = $validator->clean($values);
            $this->fail("Validation error expected");
        } catch (sfValidatorError $error) {
            // expected.
        }        
        
        $validator = new ohrmValidatorSchemaCompare('from', 
                        sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'to',
                        array('throw_global_error' => true,
                              'skip_if_one_empty' => true));
        $cleaned = $validator->clean($values);
        $this->assertEquals($cleaned, $values);                     
    }
    
    public function testDoCleanSkipIfBothEmpty() {
        
        // Check default functionality (same as base class)
        $validator = new ohrmValidatorSchemaCompare('from', 
                        sfValidatorSchemaCompare::IDENTICAL, 'to',
                        array('throw_global_error' => true));
        
        $values = array('to' => '', 'a' => 100, 'toto' => 239);
        try {
            $cleaned = $validator->clean($values);
            $this->fail("Validation error expected");
        } catch (sfValidatorError $error) {
            // expected.
        }        
        
        $validator = new ohrmValidatorSchemaCompare('from', 
                        sfValidatorSchemaCompare::IDENTICAL, 'to',
                        array('throw_global_error' => true,
                              'skip_if_both_empty' => true));
        $cleaned = $validator->clean($values);
        $this->assertEquals($cleaned, $values);         
    }
}

