<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yasitha
 * Date: 7/23/14
 * Time: 10:14 PM
 * To change this template use File | Settings | File Templates.
 */

class ParentDao extends BaseDao {

    public function searchParents($srchParams) {

        $stuName = $srchParams['stuName'];
        $stuIndexNo = $srchParams['stuIndexNo'];
        $dadOccupation = $srchParams['dadOccupation'];
        $momOccupation = $srchParams['momOccupation'];
        $orderField = (!empty($srchParams['orderField'])) ? $srchParams['orderField'] : 'r.student_surname';
        $orderBy = (!empty($srchParams['orderBy'])) ? $srchParams['orderBy'] : 'ASC';
        $noOfRecords = $srchParams['noOfRecords'];
        $offset = $srchParams['offset'];

        $sortQuery = "";
        if ($orderField == 'r.student_surname') {
            $sortQuery = 'r.student_surname ' . $orderBy . ', ' . 'r.student_other_names ' . $orderBy;
        } else {
            $sortQuery = $orderField . " " . $orderBy;
        }

        $q = Doctrine_Query::create()
            ->from('StudentParentInformation r');

        if (!empty($stuName)) {
            $q->addwhere('r.stuSurname LIKE ? or r.stuOtherNames LIKE ?', array("%" . trim($stuName) . "%", "%" . trim($stuName) . "%"));
        }
        if (!empty($stuIndexNo)) {
            $q->addwhere('r.stuAdmissionNo = ?', $stuIndexNo);
        }
        if (!empty($dadOccupation)) {
            $q->addwhere('r.dadOccupation LIKE ? or r.dadOtherOccupation LIKE ?', array("%" . trim($dadOccupation) . "%", "%" . trim($dadOccupation) . "%"));
        }
        if ($momOccupation != "") {
            $q->addwhere('r.momOccupation LIKE ? or r.momOtherOccupation LIKE ?', array("%" . trim($momOccupation) . "%", "%" . trim($momOccupation) . "%"));
        }
        $q->orderBy($sortQuery);
        $q->offset($offset);
        $q->limit($noOfRecords);

        $records = $q->execute();
        return $records;
    }

    public function searchParentsCount($srchParams) {

        $stuName = $srchParams['stuName'];
        $stuIndexNo = $srchParams['stuIndexNo'];
        $dadOccupation = $srchParams['dadOccupation'];
        $momOccupation = $srchParams['momOccupation'];

        $q = Doctrine_Query::create()
            ->from('StudentParentInformation r');

        if (!empty($stuName)) {
            $q->addwhere('r.stuSurname LIKE ? or r.stuOtherNames LIKE ?', array("%" . trim($stuName) . "%", "%" . trim($stuName) . "%"));
        }
        if (!empty($stuIndexNo)) {
            $q->addwhere('r.stuAdmissionNo = ?', $stuIndexNo);
        }
        if (!empty($dadOccupation)) {
            $q->addwhere('r.dadOccupation LIKE ? or r.dadOtherOccupation LIKE ?', array("%" . trim($dadOccupation) . "%", "%" . trim($dadOccupation) . "%"));
        }
        if ($momOccupation != "") {
            $q->addwhere('r.momOccupation LIKE ? or r.momOtherOccupation LIKE ?', array("%" . trim($momOccupation) . "%", "%" . trim($momOccupation) . "%"));
        }

        $count = $q->execute()->count();
        return $count;
    }
}