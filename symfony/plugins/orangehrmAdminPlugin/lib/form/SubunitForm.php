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


class SubunitForm extends ohrmFormComponent {

    public function configure() {
        $properties = new ohrmFormComponentProperty();

        $properties->setService(new CompanyStructureService());
        $properties->setMethod('getSubunitById');
        $properties->setParameters(array(1));
        $properties->setFields(array(
            'Id' => 'getId',
            'Unit Id' => 'getUnitId',
            'Name' => 'getName',
            'Description' => 'getDescription',
            'Parent' => ''
        ));

        $properties->setIdField('Id');

        $properties->setFormStyle('width: auto; max-width: 600px;');

        $properties->setFieldTypes(array(
            'Parent' => 'hidden',            
            'Id' => 'hidden',     
            'Description' => 'textarea'
        ));

        $properties->setRequiredFields(array('Name'));

        $this->setPropertyObject($properties);

        $this->hasFormNavigatorBar(false);
    }

}