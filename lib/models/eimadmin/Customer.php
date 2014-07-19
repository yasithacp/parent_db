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

require_once ROOT_PATH . '/lib/dao/DMLFunctions.php';
require_once ROOT_PATH . '/lib/dao/SQLQBuilder.php';
require_once ROOT_PATH . '/lib/confs/sysConf.php';
require_once ROOT_PATH . '/lib/common/CommonFunctions.php';
require_once ROOT_PATH . '/lib/common/UniqueIDGenerator.php';

class Customer {
    /**
     * Customer status constants ..
     */
    const CUSTOMER_DELETED = 1;
    const CUSTOMER_NOT_DELETED = 0;

    /**
     * Table Name
     */
    const TABLE_NAME = 'ohrm_customer';

    //Table field names

    const CUSTOMER_DB_FIELDS_ID = 'customer_id';
    const CUSTOMER_DB_FIELDS_NAME = 'name';
    const CUSTOMER_DB_FIELDS_DESCRIPTION = 'description';
    const CUSTOMER_DB_FIELDS_DELETED = 'deleted';

    /**
     * Class Attributes
     */
    private $customerId;
    private $customerName;
    private $customerDescrption;
    private $customerStatus;
    /**
     * Automatic id genaration
     */
    private $singleField;
    private $maxidLength = '4';

    /**
     * 	Setter method followed by getter method for each
     * 	attribute
     */
    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
    }

    public function getCustomerId() {
        return $this->customerId;
    }

    public function setCustomerName($customerName) {
        $this->customerName = $customerName;
    }

    public function getCustomerName() {
        return $this->customerName;
    }

    public function setCustomerDescription($customerDescrption) {
        $this->customerDescrption = $customerDescrption;
    }

    public function getCustomerDescription() {
        return $this->customerDescrption;
    }

    public function setCustomerStatus($customerStatus) {
        $this->customerStatus = $customerStatus;
    }

    public function getCustomerStatus() {
        return $this->customerStatus;
    }

    /**
     *
     */
    public function addCustomer() {

        if ($this->_isDuplicateName()) {
            throw new CustomerException("Duplicate name", 1);
        }

        $this->customerId = UniqueIDGenerator::getInstance()->getNextID(self::TABLE_NAME, self::CUSTOMER_DB_FIELDS_ID);

        $arrRecord[0] = "'" . $this->getCustomerId() . "'";
        $arrRecord[1] = "'" . $this->getCustomerName() . "'";
        $arrRecord[2] = "'" . $this->getCustomerDescription() . "'";
        $arrRecord[3] = self::CUSTOMER_NOT_DELETED;

        $tableName = self::TABLE_NAME;

        $sql_builder = new SQLQBuilder();

        $sql_builder->table_name = $tableName;
        $sql_builder->flg_insert = 'true';
        $sql_builder->arr_insert = $arrRecord;


        $sqlQString = $sql_builder->addNewRecordFeature1();

        $dbConnection = new DMLFunctions();
        $message2 = $dbConnection->executeQuery($sqlQString); //Calling the addData() function

        return $message2;
    }

    /**
     *
     */
    public function updateCustomer() {

        if ($this->_isDuplicateName(true)) {
            throw new CustomerException("Duplicate name", 1);
        }

        $arrRecord[0] = "'" . $this->getCustomerId() . "'";
        $arrRecord[1] = "'" . $this->getCustomerName() . "'";
        $arrRecord[2] = "'" . $this->getCustomerDescription() . "'";
        $arrRecord[3] = self::CUSTOMER_NOT_DELETED;


        $tableName = self::TABLE_NAME;

        $arrFieldList[0] = self::CUSTOMER_DB_FIELDS_ID;
        $arrFieldList[1] = self::CUSTOMER_DB_FIELDS_NAME;
        $arrFieldList[2] = self::CUSTOMER_DB_FIELDS_DESCRIPTION;
        $arrFieldList[3] = self::CUSTOMER_DB_FIELDS_DELETED;

        return $this->updateRecord($tableName, $arrFieldList, $arrRecord);
    }

    /**
     *
     *
     *
     */
    public function deletewrapperCustomer($arrList) {


        $i = 0;
        $array_count = count($arrList, COUNT_RECURSIVE) - 1;
        for ($i = 0; $i < $array_count; $i++) {

            $this->setCustomerId($arrList[0][$i]);
            $res = $this->deleteCustomer();
            if (!$res) {
                return $res;
            }
        }

        return $res;
    }

    public function deleteCustomer() {
        $sql = sprintf("UPDATE ohrm_customer c LEFT JOIN ohrm_project p ON (c.customer_id = p.customer_id) " .
                "SET c.is_deleted = 1, p.is_deleted = 1 " .
                "WHERE c.customer_id = %s", $this->getCustomerId());

        $dbConnection = new DMLFunctions();
        $message = $dbConnection->executeQuery($sql);
        return $message;
    }

    /**
     * To update the records reuse this function
     */
    private function updateRecord($tableName, $arrFieldList, $arrRecordsList) {

        $sql_builder = new SQLQBuilder();

        $sql_builder->table_name = $tableName;
        $sql_builder->flg_update = 'true';
        $sql_builder->arr_update = $arrFieldList;
        $sql_builder->arr_updateRecList = $arrRecordsList;

        $sqlQString = $sql_builder->addUpdateRecord1(0);

        $dbConnection = new DMLFunctions();
        $message2 = $dbConnection->executeQuery($sqlQString); //Calling the addData() function

        return $message2;
    }

    /**
     *
     */
    public function getListofCustomers($pageNO, $schStr, $mode, $sortField = 0, $sortOrder = 'ASC') {

        $customerArr = $this->fetchCustomers($pageNO, $schStr, $mode, $sortField, $sortOrder);

        $arrDispArr = null;
        for ($i = 0; count($customerArr) > $i; $i++) {

            $arrDispArr[$i][0] = $customerArr[$i]->getCustomerId();
            $arrDispArr[$i][1] = $customerArr[$i]->getCustomerName();
            $arrDispArr[$i][2] = $customerArr[$i]->getCustomerDescription();
        }

        return $arrDispArr;
    }

    /**
     *
     */
    public function fetchCustomers($pageNO=0, $schStr='', $schField=-1, $sortField=0, $sortOrder='ASC') {

        $arrFieldList[0] = self::CUSTOMER_DB_FIELDS_ID;
        $arrFieldList[1] = self::CUSTOMER_DB_FIELDS_NAME;
        $arrFieldList[2] = self::CUSTOMER_DB_FIELDS_DESCRIPTION;
        $arrFieldList[3] = self::CUSTOMER_DB_FIELDS_DELETED;

        $tableName = "`" . self::TABLE_NAME . "`";

        $sql_builder = new SQLQBuilder();

        $arrSelectConditions[0] = "`" . self::CUSTOMER_DB_FIELDS_DELETED . "`= " . self::CUSTOMER_NOT_DELETED . "";
        $arrSelectConditions[1] = "`" . self::CUSTOMER_DB_FIELDS_ID . "` != 0";

        if ($schField != -1) {
            $arrSelectConditions[2] = "`" . $arrFieldList[$schField] . "` LIKE '%" . $schStr . "%'";
        }

        $limitStr = null;

        if ($pageNO > 0) {
            $sysConfObj = new sysConf();
            $page = ($pageNO - 1) * $sysConfObj->itemsPerPage;
            $limit = $sysConfObj->itemsPerPage;
            $limitStr = "$page,$limit";
            //echo $limitStr;
        }
        $sqlQString = $sql_builder->simpleSelect($tableName, $arrFieldList, $arrSelectConditions, $arrFieldList[$sortField], $sortOrder, $limitStr);

        $dbConnection = new DMLFunctions();
        $message2 = $dbConnection->executeQuery($sqlQString); //Calling the addData() function

        return $this->customerObjArr($message2);
    }

    /**
     *
     */
    public function fetchCustomer($cusId, $includeDeleted = false) {

        $selectTable = "`" . self::TABLE_NAME . "`";

        $arrFieldList[0] = self::CUSTOMER_DB_FIELDS_ID;
        $arrFieldList[1] = self::CUSTOMER_DB_FIELDS_NAME;
        $arrFieldList[2] = self::CUSTOMER_DB_FIELDS_DESCRIPTION;
        $arrFieldList[3] = self::CUSTOMER_DB_FIELDS_DELETED;

        $arrSelectConditions[0] = "`" . self::CUSTOMER_DB_FIELDS_ID . "` = $cusId";

        if (!$includeDeleted) {
            $arrSelectConditions[1] = "`" . self::CUSTOMER_DB_FIELDS_DELETED . "`= " . self::CUSTOMER_NOT_DELETED . "";
        }

        $sqlBuilder = new SQLQBuilder();
        $query = $sqlBuilder->simpleSelect($selectTable, $arrFieldList, $arrSelectConditions, null, null, 1);
        $dbConnection = new DMLFunctions();
        $result = $dbConnection->executeQuery($query);

        $tempArr = $this->customerObjArr($result);
        return $tempArr[0];
    }

    /**
     *
     */
    public function countcustomerID($schStr, $schField) {

        $tableName = self::TABLE_NAME;
        $arrFieldList[0] = self::CUSTOMER_DB_FIELDS_ID;
        $arrFieldList[1] = self::CUSTOMER_DB_FIELDS_NAME;
        $arrFieldList[2] = self::CUSTOMER_DB_FIELDS_DELETED;

        $schField = 2;
        $schStr = 0;

        $sql_builder = new SQLQBuilder();

        $sql_builder->table_name = $tableName;
        $sql_builder->flg_select = 'true';
        $sql_builder->arr_select = $arrFieldList;

        $sqlQString = $sql_builder->countResultset($schStr, $schField);

        //echo $sqlQString;
        $dbConnection = new DMLFunctions();
        $message2 = $dbConnection->executeQuery($sqlQString); //Calling the addData() function

        $line = mysql_fetch_array($message2, MYSQL_NUM);

        return $line[0];
    }

    /**
     *
     */
    public function customerObjArr($result) {

        $objArr = null;
        $tableName = self::TABLE_NAME;


        while ($row = mysql_fetch_assoc($result)) {

            $tmpcusArr = new Customer();

            $tmpcusArr->setCustomerId($row[self::CUSTOMER_DB_FIELDS_ID]);
            $tmpcusArr->setCustomerName($row[self::CUSTOMER_DB_FIELDS_NAME]);
            $tmpcusArr->setCustomerDescription($row[self::CUSTOMER_DB_FIELDS_DESCRIPTION]);
            $tmpcusArr->setCustomerStatus($row[self::CUSTOMER_DB_FIELDS_DELETED]);

            $objArr[] = $tmpcusArr;
        }

        return $objArr;
    }

    private function _isDuplicateName($update=false) {
        $cutomers = $this->filterExistingCustomers();

        if (is_array($cutomers)) {
            if ($cutomers) {
                if ($cutomers[0][0] == $this->getCustomerId()) {
                    return false;
                }
            }
            return true;
        }

        return false;
    }

    public function filterExistingCustomers() {
        $sqlBuilder = new SQLQBuilder();
        $customerName = $sqlBuilder->quoteCorrectString($this->getCustomerName(), true, true);

        $selectFields[] = '`customer_id`';
        $selectFields[] = '`name`';
        $selectTable = self::TABLE_NAME;

        $selectConditions[] = "`name` = '{$customerName}'";

        $query = $sqlBuilder->simpleSelect($selectTable, $selectFields, $selectConditions);

        $dbConnection = new DMLFunctions();
        $result = $dbConnection->executeQuery($query);

        $cnt = 0;

        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            $existingCustomers[$cnt++] = $row;
        }

        if (isset($existingCustomers)) {
            return $existingCustomers;
        } else {
            $existingCustomers = '';
            return $existingCustomers;
        }
    }

    public function haveTimeItems($customerIds) {


        $q = "(SELECT `project_id` FROM `ohrm_project` WHERE `customer_id` IN(" . implode(", ", $customerIds) . "))";
        $dbConnection = new DMLFunctions();
        $result = $dbConnection->executeQuery($q);
        $projectIds=$dbConnection->dbObject->getArray($result);


        if (!empty($projectIds) && is_array($projectIds)) {

            $q = "SELECT * FROM `ohrm_timesheet_item` WHERE `project_id` IN(" . implode(", ", $projectIds) . ")";

            $dbConnection = new DMLFunctions();
            $result = $dbConnection->executeQuery($q);

            if (mysql_num_rows($result) > 0) {
                return true;
            }

            return false;
        }

        return false;
    }

}

class CustomerException extends Exception {
    
}

?>