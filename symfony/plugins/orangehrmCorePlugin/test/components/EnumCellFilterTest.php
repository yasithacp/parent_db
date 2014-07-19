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
 * Test class for ohrmCellFilter abstract class
 * @group Core
 * @group ListComponent
 */
class EnumCellFilterTest extends PHPUnit_Framework_TestCase {

    /**
     * @var filter
     */
    protected $filter;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->filter = new EnumCellFilter();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    /**
     * Test the filter() method.
     */
    public function testFilter() {
        
        // Without enum. Should return default 'default': ''
        $value = 4;
        $this->assertEquals('', $this->filter->filter($value));
        
        // With enum, but without that value
        $this->filter->setEnum(array(1 => "Xyz", 2 => "basic"));        
        $this->assertEquals('', $this->filter->filter($value));
        
        // With enum, without that value, with default defined.
        $default = "-";
        $this->filter->setDefault($default);
        $this->assertEquals($default, $this->filter->filter($value));
        
        // With enum which includes given value
        $this->filter->setEnum(array(1 => "Xyz", 2 => "basic", 4 => 'OK', 5 => 'NOK'));
        $this->assertEquals('OK', $this->filter->filter($value));
        
    }
    
    /**
     * Test the get/set methods
     */
    public function testGetSetMethods() {
        $filter = array('2' => "test", "4" => "xyz");
        $this->filter->setEnum($filter);
        $this->assertEquals($filter, $this->filter->getEnum());
        
        $default = 'Z1';
        $this->filter->setDefault($default);
        $this->assertEquals($default, $this->filter->getDefault());        
    }

}





