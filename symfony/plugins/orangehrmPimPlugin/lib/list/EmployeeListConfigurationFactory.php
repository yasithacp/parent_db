<?php

class EmployeeListConfigurationFactory extends ohrmListConfigurationFactory {

    public function init() {
        sfContext::getInstance()->getConfiguration()->loadHelpers('OrangeDate');
        
        $header1 = new ListHeader();
        $header2 = new ListHeader();
        $header3 = new ListHeader();

        $header1->populateFromArray(array(
            'name' => 'Id',
            'width' => '20%',
            'isSortable' => true,
            'sortField' => 'employeeId',
            'elementType' => 'link',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array(
                'labelGetter' => array('getEmployeeId'),
                'placeholderGetters' => array('id' => 'getEmpNumber'),
                'urlPattern' => public_path('index.php/pim/viewPersonalDetails/empNumber/{id}'),
            ),
        ));

        $header2->populateFromArray(array(
            'name' => 'First (& Middle) Name',
            'width' => '40%',
            'isSortable' => true,
            'sortField' => 'firstMiddleName',
            'elementType' => 'link',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array(
                'labelGetter' => array('getFirstAndMiddleName'),
                'placeholderGetters' => array('id' => 'getEmpNumber'),
                'urlPattern' => public_path('index.php/pim/viewPersonalDetails/empNumber/{id}'),
            ),
        ));

        $header3->populateFromArray(array(
            'name' => 'Last Name',
            'width' => '40%',
            'isSortable' => true,
            'sortField' => 'lastName',
            'elementType' => 'link',
            'textAlignmentStyle' => 'left',
            'elementProperty' => array(
                'labelGetter' => array('getFullLastName'),
                'placeholderGetters' => array('id' => 'getEmpNumber'),
                'urlPattern' => public_path('index.php/pim/viewPersonalDetails/empNumber/{id}'),
            ),
        ));

        $this->headers = array($header1, $header2, $header3);
    }
    
    public function getClassName() {
        return 'Employee';
    }
}
