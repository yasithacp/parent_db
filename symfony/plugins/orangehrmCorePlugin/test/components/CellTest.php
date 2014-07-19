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
 * Test class for Cell.
 * @group Core
 * @group ListComponent
 */
class CellTest extends PHPUnit_Framework_TestCase {

    /**
     * @var ListHeader
     */
    protected $cell;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->cell = new TestConcreteCell();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {

    }

    /** 
     * Tests the setHeader and getHeader methods.
     */
    public function testSetHeader() {
        
        // Simple object
        $header = new ListHeader();
        
        $this->cell->setHeader($header);       
        $this->assertEquals($header, $this->cell->getHeader());
        
        // decorated with sfOutputEscaperObjectDecorator
        $header2 = new ListHeader();
        $header2->setName("Test Header");
        $decoratedHeader = new sfOutputEscaperObjectDecorator(null, $header2);
        
        $this->cell->setHeader($decoratedHeader);
        $this->assertEquals($header2, $this->cell->getHeader());        

    }

    /**
     * Test the filterValue() method.
     */
    public function testFilterValue() {
        
        $value = "Test Value";
        $filteredValue = "XYZ Test";
        
        $mockHeader = $this->getMock('ListHeader', array('filterValue'));
        $mockHeader->expects($this->once())
                     ->method('filterValue')
                     ->with($value)                
                     ->will($this->returnValue($filteredValue));
        
        $this->cell->setHeader($mockHeader); 
        $this->assertEquals($filteredValue, $this->cell->publicFilter($value));         
    }
    
}

class TestConcreteCell extends Cell {
    
    /**
     * Expose the filterValue method for testing
     */
    public function publicFilter($value) {
        return $this->filterValue($value);
    }
    
}

