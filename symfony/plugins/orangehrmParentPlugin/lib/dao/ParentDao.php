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

    public function getParentsMobileNumbers() {

        try {
            $q1 = Doctrine_Query::create()
                ->select('p.mother_contact_mobile')
                ->from('StudentParentInformation p')
                ->where('p.mother_contact_mobile != 0');

            $result1 = $q1->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

            $q2 = Doctrine_Query::create()
                ->select('p.father_contact_number_mobile')
                ->from('StudentParentInformation p')
                ->where('p.father_contact_number_mobile != 0');

            $result2 = $q2->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

            $result = array();

            foreach($result1 as $rec1) {
                array_push($result, $rec1['momMobileNo']);
            }

            foreach($result2 as $rec2) {
                array_push($result, $rec2['dadMobileNo']);
            }

            $result = array_unique($result);

            $final = array();

            array_push($final, '714807383');
            array_push($final, '717233322');
            array_push($final, '710933841');
            array_push($final, '711186844');
            array_push($final, '714755300');
            array_push($final, '715788340');
            array_push($final, '716791295');
            array_push($final, '777777264');
            array_push($final, '772946241');

            foreach($result as $rec) {
                array_push($final, $rec);
            }

            return $final;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }

    }

    public function getParentsMobileNumbersTest() {

        try {
            $q1 = Doctrine_Query::create()
                ->select('p.mother_contact_mobile')
                ->from('TestTable p')
                ->where('p.mother_contact_mobile != 0');

            $result1 = $q1->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

            $q2 = Doctrine_Query::create()
                ->select('p.father_contact_number_mobile')
                ->from('TestTable p')
                ->where('p.father_contact_number_mobile != 0');

            $result2 = $q2->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

            $result = array();

            foreach($result1 as $rec1) {
                array_push($result, $rec1['momMobileNo']);
            }

            foreach($result2 as $rec2) {
                array_push($result, $rec2['dadMobileNo']);
            }

            $result = array_unique($result);

            $final = array();
            foreach($result as $rec) {
                array_push($final, $rec);
            }

            return $final;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }

    }

//    public function getParentsEmails() {
//
//        try {
//            $q1 = Doctrine_Query::create()
//                ->select('p.mother_email')
//                ->from('StudentParentInformation p')
//                ->where('p.mother_email != \'\'');
//
//            $result1 = $q1->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
//
//            $q2 = Doctrine_Query::create()
//                ->select('p.father_email')
//                ->from('StudentParentInformation p')
//                ->where('p.father_email != \'\'');
//
//            $result2 = $q2->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
//
//            $result = array();
//
//            foreach($result1 as $rec1) {
//                array_push($result, $rec1['momEmail']);
//            }
//
//            foreach($result2 as $rec2) {
//                array_push($result, $rec2['dadEmail']);
//            }
//
//            print_r($result);die;
//            return $result;
//
//        } catch (Exception $e) {
//            throw new DaoException($e->getMessage());
//        }
//
//    }

    public function getParentsEmails() {

        try {
            $q1 = Doctrine_Query::create()
                ->select('p.mother_email')
                ->from('StudentParentInformation p')
                ->where('p.mother_email != \'\'');

            $result1 = $q1->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

            $q2 = Doctrine_Query::create()
                ->select('p.father_email')
                ->from('StudentParentInformation p')
                ->where('p.father_email != \'\'');

            $result2 = $q2->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

            $result = array();

            foreach($result1 as $rec1) {
                array_push($result, $rec1['momEmail']);
            }

            foreach($result2 as $rec2) {
                array_push($result, $rec2['dadEmail']);
            }

            $result = array_unique($result);

            $final = array();
            foreach($result as $rec) {
                array_push($final, $rec);
            }

            return $final;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }

    }

    public function getParentsEmailsTest() {

        try {
            $q1 = Doctrine_Query::create()
                ->select('p.mother_email')
                ->from('TestTable2 p')
                ->where('p.mother_email != \'\'');

            $result1 = $q1->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

            $q2 = Doctrine_Query::create()
                ->select('p.father_email')
                ->from('TestTable2 p')
                ->where('p.father_email != \'\'');

            $result2 = $q2->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

            $result = array();

            foreach($result1 as $rec1) {
                array_push($result, $rec1['momEmail']);
            }

            foreach($result2 as $rec2) {
                array_push($result, $rec2['dadEmail']);
            }

            $result = array_unique($result);

            $final = array();
            foreach($result as $rec) {
                array_push($final, $rec);
            }

            return $final;
        } catch (Exception $e) {
            throw new DaoException($e->getMessage());
        }

    }
}