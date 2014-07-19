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
 * Description of HolidayListConfigurationFactory
 *
 */
class HolidayListConfigurationFactory extends ohrmListConfigurationFactory {

    protected function init() {

        $header1 = new ListHeader();

        $header1->populateFromArray(array(
            'name' => 'Name',
            'width' => '40%',
            'isSortable' => false,
            'sortField' => null,
            'elementType' => 'link',
            'elementProperty' => array(
                'labelGetter' => 'getDescription',
                'placeholderGetters' => array('id' => 'getId'),
                'urlPattern' => 'index.php/leave/defineHoliday?hdnEditId={id}'),
        ));

        $header2 = new ListHeader();

        $header2->populateFromArray(array(
            'name' => 'Date',
            'width' => '25%',
            'isSortable' => false,
            'sortField' => null,
            'filters' => array('DateCellFilter' => array()),            
            'elementType' => 'label',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array('getter' => 'getDate'),
        ));
        
        $header3 = new ListHeader();

        $header3->populateFromArray(array(
            'name' => 'Full Day/Half Day',
            'width' => '20%',
            'isSortable' => false,
            'sortField' => null,
            'filters' => array('EnumCellFilter' => array(
                                                    'enum' => PluginWorkWeek::getDaysLengthList(), 
                                                    'default' => ''),
                               'I18nCellFilter' => array()
                              ),
            'elementType' => 'label',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array('getter' => 'getLength'),
        ));

        $header4 = new ListHeader();

        $header4->populateFromArray(array(
            'name' => 'Repeats Annually',
            'width' => '15%',
            'isSortable' => false,
            'sortField' => null,
            'filters' => array('EnumCellFilter' => array(
                                                    'enum' => PluginWorkWeek::getYesNoList(), 
                                                    'default' => ''),
                               'I18nCellFilter' => array()
                              ),            
            'elementType' => 'label',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array('getter' => 'getRecurring'),
        ));
        
        $this->headers = array($header1, $header2, $header3, $header4);
    }

    public function getClassName() {
        return 'HolidayList';
    }

}

