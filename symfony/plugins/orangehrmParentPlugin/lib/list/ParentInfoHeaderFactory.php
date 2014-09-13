<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yasitha
 * Date: 7/23/14
 * Time: 11:05 PM
 * To change this template use File | Settings | File Templates.
 */

class ParentInfoHeaderFactory extends ohrmListConfigurationFactory {

    protected function init() {

        $header1 = new ListHeader();
        $header2 = new ListHeader();
        $header3 = new ListHeader();
        $header4 = new ListHeader();
        $header5 = new ListHeader();
        $header6 = new ListHeader();
        $header7 = new ListHeader();
        $header8 = new ListHeader();

        $header1->populateFromArray(array(
            'name' => 'Student Surname',
            'width' => '12%',
            'isSortable' => true,
            'sortField' => 'r.student_surname',
            'elementType' => 'link',
            'elementProperty' => array(
                'labelGetter' => 'getStuSurname',
                'placeholderGetters' => array('id' => 'getStudentId'),
                'urlPattern' => 'addParentInfo?studentId={id}'),
        ));

        $header2->populateFromArray(array(
            'name' => 'Student Other Names',
            'width' => '12%',
            'isSortable' => true,
            'sortField' => 'r.student_other_names',
            'elementType' => 'link',
            'elementProperty' => array(
                'labelGetter' => 'getStuOtherNames',
                'placeholderGetters' => array('id' => 'getStudentId'),
                'urlPattern' => 'addParentInfo?studentId={id}'),
        ));

        $header3->populateFromArray(array(
            'name' => 'Father\'s Name',
            'width' => '12%',
            'isSortable' => true,
            'sortField' => 'r.father_name',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getDadName'),
        ));

        $header4->populateFromArray(array(
            'name' => 'Father\'s Occupation',
            'width' => '12%',
            'isSortable' => true,
            'sortField' => 'r.father_occupation',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getDadOccupation'),
        ));

        $header5->populateFromArray(array(
            'name' => 'Father\'s Designation',
            'width' => '12%',
            'isSortable' => true,
            'sortField' => 'r.father_designation',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getDadDesignation'),
        ));

        $header6->populateFromArray(array(
            'name' => 'Mother\'s Name',
            'width' => '12%',
            'isSortable' => true,
            'sortField' => 'r.mother_name',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getMomName'),
        ));

        $header7->populateFromArray(array(
            'name' => 'Mother\'s Occupation',
            'width' => '12%',
            'isSortable' => true,
            'sortField' => 'r.mother_occupation',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getMomOccupation'),
        ));

        $header8->populateFromArray(array(
            'name' => 'Mother\'s Designation',
            'width' => '12%',
            'isSortable' => true,
            'sortField' => 'r.mother_designation',
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getMomDesignation'),
        ));

        $this->headers = array($header1, $header2, $header3, $header4, $header5, $header6, $header7, $header8);
    }

    public function getClassName() {
        return 'ParentInfo';
    }
}