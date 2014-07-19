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
require_once ROOT_PATH . '/lib/confs/Conf.php';
require_once ROOT_PATH . '/lib/common/CommonFunctions.php';
require_once ROOT_PATH . '/lib/models/eimadmin/Customer.php';
require_once ROOT_PATH . '/lib/common/UniqueIDGenerator.php';

/**
 * Project Class
 *
 * This class was introduced under Time & Attendance module. The HR-Admin wilkl be defining
 * the projects, which would have customers assigned to it.
 *
 */
class Projects {
    /**
     * class constants
     */
    const PROJECT_NOT_DELETED = 0;
    const PROJECT_DELETED = 1;

    const PROJECT_DB_TABLE = 'ohrm_project';
    const PROJECT_DB_FIELD_PROJECT_ID = 'project_id';
    const PROJECT_DB_FIELD_CUSTOMER_ID = 'customer_id';
    const PROJECT_DB_FIELD_NAME = 'name';
    const PROJECT_DB_FIELD_DESCRIPTION = 'description';
    const PROJECT_DB_FIELD_DELETED = 'is_deleted';

    /**
     * class attributes
     *
     */
    private $projectID;
    private $customerID;
    private $projectName;
    private $projectDescription;
    private $deleted;
    /**
     * Automatic id genaration
     *
     */
    private $singleField;
    private $maxidLength = '4';

    /**
     *  Table Name
     *
     */
    const TABLE_NAME = 'ohrm_project';

    /**
     * 	Setter method followed by getter method for each
     * 	attribute
     */
    public function setProjectId($projectid) {
        $this->projectID = $projectid;
    }

    public function getProjectId() {
        return $this->projectID;
    }

    public function setCustomerId($customerid) {
        $this->customerID = $customerid;
    }

    public function getCustomerId() {
        return $this->customerID;
    }

    public function setProjectName($projectname) {
        $this->projectName = $projectname;
    }

    public function getProjectName() {
        return $this->projectName;
    }

    public function getCustomerName() {
        $customer = new Customer();
        $customer = $customer->fetchCustomer($this->customerID);
        $customerName = $customer->getCustomerName();
        return $customerName;
    }

    public function setProjectDescription($projectDescription) {
        $this->projectDescription = $projectDescription;
    }

    public function getProjectDescription() {
        return $this->projectDescription;
    }

    public function setDeleted($deleted) {
        $this->deleted = $deleted;
    }

    public function getDeleted() {
        return $this->deleted;
    }

    /**
     * Add new project
     *
     * Deleted will be overwritten to NOT_DELETED
     */
    public function addProject() {

        if ($this->_isDuplicateName()) {
            throw new ProjectsException("Duplicate name", 1);
        }

        $this->projectID = UniqueIDGenerator::getInstance()->getNextID(self::TABLE_NAME, self::PROJECT_DB_FIELD_PROJECT_ID);

        $arrRecord[0] = "'" . $this->getProjectId() . "'";
        $arrRecord[1] = "'" . $this->getCustomerId() . "'";
        $arrRecord[2] = "'" . $this->getProjectName() . "'";
        $arrRecord[3] = "'" . $this->getProjectDescription() . "'";
        $arrRecord[4] = self::PROJECT_NOT_DELETED;

        $tableName = self::TABLE_NAME;

        $sql_builder = new SQLQBuilder();

        $sql_builder->table_name = $tableName;
        $sql_builder->flg_insert = 'true';
        $sql_builder->arr_insert = $arrRecord;

        $sqlQString = $sql_builder->addNewRecordFeature1();

        $dbConnection = new DMLFunctions();
        $message2 = $dbConnection->executeQuery($sqlQString); //Calling the addData() function

        if ($message2 && (mysql_affected_rows() > 0)) {
            return true;
        }
        return false;
    }

    /**
     * Wraaper for delete
     */
    public function deletewrapperProjects($arrList) {

        $i = 0;
        $array_count = count($arrList, COUNT_RECURSIVE) - 1;
        for ($i = 0; $i < $array_count; $i++) {

            $this->setProjectId($arrList[0][$i]);
            $res = $this->deleteProject();

            if (!$res) {
                return $res;
            }
        }

        return $res;
    }

    /**
     * Mark project deleted
     */
    public function deleteProject() {


            $this->setDeleted(self::PROJECT_DELETED);

            return $this->updateProject();

    }

    /**
     * Update project information
     */
    public function updateProject() {


        if ($this->_isDuplicateName(true)) {
            throw new ProjectsException("Duplicate name", 1);
        }

        $sql_builder = new SQLQBuilder();

        $updateTable = self::TABLE_NAME;

        if ($this->getCustomerId() != null) {
            $updateFields[] = "`" . self::PROJECT_DB_FIELD_CUSTOMER_ID . "`";
            $updateValues[] = $this->getCustomerId();
        }

        if ($this->getProjectName() != null) {
            $updateFields[] = "`" . self::PROJECT_DB_FIELD_NAME . "`";
            $updateValues[] = $this->getProjectName();
        }

        if ($this->getProjectDescription() != null) {
            $updateFields[] = "`" . self::PROJECT_DB_FIELD_DESCRIPTION . "`";
            $updateValues[] = $this->getProjectDescription();
        }

        if ($this->getDeleted() != null) {
            $updateFields[] = "`" . self::PROJECT_DB_FIELD_DELETED . "`";
            $updateValues[] = $this->getDeleted();
        }

        $updateConditions[] = "`" . self::PROJECT_DB_FIELD_PROJECT_ID . "` = {$this->getProjectId()}";

        if (is_array($updateFields)) {
            $sqlQString = $sql_builder->simpleUpdate($updateTable, $updateFields, $updateValues, $updateConditions, true);

            $dbConnection = new DMLFunctions();
            $message2 = $dbConnection->executeQuery($sqlQString); //Calling the addData() function
            // We don't check mysql_affected_rows here since the update may not have changed any
            // of the database fields.
            if ($message2) {
                return true;
            }
        }
        return false;
    }

    /**
     * Fetch project information, only one
     */
    public function fetchProject($projectId) {
        $this->setProjectId($projectId);
        $objArr = $this->fetchProjects();
        if (isset($objArr)) {
            return $objArr[0];
        }
        return null;
    }

    /**
     * To get the number of projects
     */
    public function countprojectID($schStr, $schField) {

        $tableName = self::PROJECT_DB_TABLE;
        $arrFieldList[0] = self::PROJECT_DB_FIELD_PROJECT_ID;
        $arrFieldList[1] = self::PROJECT_DB_FIELD_DELETED;
        $arrFieldList[2] = self::PROJECT_DB_FIELD_CUSTOMER_ID;
        $arrFieldList[3] = self::PROJECT_DB_FIELD_NAME;
        $arrFieldList[4] = self::PROJECT_DB_FIELD_DESCRIPTION;

        $schField = 1;
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

    public function fetchProjects($withEmptyProjects = true) {
        $arrFieldList[0] = "`" . self::PROJECT_DB_FIELD_PROJECT_ID . "`";
        $arrFieldList[1] = "`" . self::PROJECT_DB_FIELD_CUSTOMER_ID . "`";
        $arrFieldList[2] = "`" . self::PROJECT_DB_FIELD_NAME . "`";
        $arrFieldList[3] = "`" . self::PROJECT_DB_FIELD_DESCRIPTION . "`";
        $arrFieldList[4] = "`" . self::PROJECT_DB_FIELD_DELETED . "`";

        $tableName = "`" . self::TABLE_NAME . "`";

        $sql_builder = new SQLQBuilder();

        $arrSelectConditions = null;

        if ($this->getProjectId() != null) {
            $arrSelectConditions[] = "`" . self::PROJECT_DB_FIELD_PROJECT_ID . "`= '" . $this->getProjectId() . "'";
        }

        if ($this->getCustomerId() != null) {
            $arrSelectConditions[] = "`" . self::PROJECT_DB_FIELD_CUSTOMER_ID . "`= '" . $this->getCustomerId() . "'";
        }

        if ($this->getProjectName() != null) {
            $arrSelectConditions[] = "`" . self::PROJECT_DB_FIELD_NAME . "`= '" . $this->getProjectName() . "'";
        }

        if ($this->getProjectDescription() != null) {
            $arrSelectConditions[] = "`" . self::PROJECT_DB_FIELD_DESCRIPTION . "`= '" . $this->getProjectDescription() . "'";
        }

        if (!is_null($this->getDeleted())) {
            $arrSelectConditions[] = "`" . self::PROJECT_DB_FIELD_DELETED . "`= " . $this->getDeleted() . "";
        }

        if (!$withEmptyProjects) {
            $subQuery = "SELECT COUNT(*) FROM `" . ProjectActivity::TABLE_NAME . "` pa WHERE " .
                    "pa.`" . ProjectActivity::DB_FIELD_PROJECT_ID . "` = " . self::TABLE_NAME . ".`" . self::PROJECT_DB_FIELD_PROJECT_ID . "`";
            $arrSelectConditions[] = "({$subQuery}) > 0";
        }

        $sqlQString = $sql_builder->simpleSelect($tableName, $arrFieldList, $arrSelectConditions, $arrFieldList[2], 'ASC');

        $dbConnection = new DMLFunctions();
        $message2 = $dbConnection->executeQuery($sqlQString); //Calling the addData() function

        $objArr = self::projectObjArr($message2);

        return $objArr;
    }

    /**
     * Fetch all projects with paging
     */
    public function getListOfProjectsStr($pageNO, $schStr, $schField, $sortField=0, $sortOrder='ASC') {

        $arrFieldList[0] = "a.`" . self::PROJECT_DB_FIELD_PROJECT_ID . "`";
        $arrFieldList[1] = "a.`" . self::PROJECT_DB_FIELD_NAME . "`";
        $arrFieldList[2] = "b.`" . Customer::CUSTOMER_DB_FIELDS_NAME . "`";
        $arrFieldList[3] = "a.`" . self::PROJECT_DB_FIELD_DESCRIPTION . "`";
        $arrFieldList[4] = "a.`" . self::PROJECT_DB_FIELD_DELETED . "`";

        $tableNames[0] = "`" . Customer::TABLE_NAME . "` b ";
        $tableNames[1] = "`" . self::PROJECT_DB_TABLE . "` a ";

        $joinConditions[1] = "b.`" . Customer::CUSTOMER_DB_FIELDS_ID . "` = a.`" . self::PROJECT_DB_FIELD_CUSTOMER_ID . "`";

        $sql_builder = new SQLQBuilder();

        $arrSelectConditions[0] = "a.`" . self::PROJECT_DB_FIELD_DELETED . "`= " . self::PROJECT_NOT_DELETED . "";
        $arrSelectConditions[1] = "a.`" . self::PROJECT_DB_FIELD_PROJECT_ID . "` != 0";

        if ($schField != -1) {
            $arrSelectConditions[2] = "" . $arrFieldList[$schField] . " LIKE '%" . $schStr . "%'";
        }

        $limitStr = null;

        if ($pageNO > 0) {
            $sysConfObj = new sysConf();
            $page = ($pageNO - 1) * $sysConfObj->itemsPerPage;
            $limit = $sysConfObj->itemsPerPage;
            $limitStr = "$page,$limit";
        }

        $sqlQString = $sql_builder->selectFromMultipleTable($arrFieldList, $tableNames, $joinConditions, $arrSelectConditions, null, $arrFieldList[$sortField], $sortOrder, $limitStr);

        $dbConnection = new DMLFunctions();
        $message2 = $dbConnection->executeQuery($sqlQString); //Calling the addData() function

        $arrDispArr = null;
        $i = 0;
        while ($row = mysql_fetch_row($message2)) {
            $arrDispArr[$i] = $row;
            $i++;
        }

        return $arrDispArr;
    }

    /**
     * If porject id is set, retrieves the data from the database and
     * populates the private data members
     */
    public function fetch() {
        if (!isset($this->projectID) || empty($this->projectID)) {
            throw new Exception('Project Id not set');
        }

        $selectTable = "`" . self::TABLE_NAME . "`";
        $selectFields[] = "`" . self::PROJECT_DB_FIELD_NAME . "`";
        $selectFields[] = "`" . self::PROJECT_DB_FIELD_CUSTOMER_ID . "`";
        $selectFields[] = "`" . self::PROJECT_DB_FIELD_DESCRIPTION . "`";
        $selectFields[] = "`" . self::PROJECT_DB_FIELD_DELETED . "`";

        $selectConditions[] = "`" . self::PROJECT_DB_FIELD_PROJECT_ID . "` = {$this->projectID}";

        $sqlBuilder = new SQLQBuilder();
        $query = $sqlBuilder->simpleSelect($selectTable, $selectFields, $selectConditions);

        $dbConnection = new DMLFunctions();
        $result = $dbConnection->executeQuery($query);

        $recordCount = $dbConnection->dbObject->numberOfRows($result);
        if ($recordCount != 1) {
            throw new Exception('No records or multiple records found');
        }

        $row = $dbConnection->dbObject->getArray($result);

        if (isset($row[0])) {
            $this->projectName = $row[self::PROJECT_DB_FIELD_NAME];
            $this->customerID = $row[self::PROJECT_DB_FIELD_CUSTOMER_ID];
            $this->projectDescription = $row[self::PROJECT_DB_FIELD_DESCRIPTION];
            $this->deleted = (bool) $row[self::PROJECT_DB_FIELD_DELETED];
        }
    }

    /**
     * Retrieves Project Name for a given project ID.
     * @param integer $projectId
     * @return string Project Name of $projectId
     */
    public function retrieveProjectName($projectId) {

        $selectTable = "`" . self::PROJECT_DB_TABLE . "`";
        $selectFields[0] = "`" . self::PROJECT_DB_FIELD_NAME . "`";
        $selectConditions[0] = "`" . self::PROJECT_DB_FIELD_PROJECT_ID . "` = $projectId";

        $sqlBuilder = new SQLQBuilder();
        $query = $sqlBuilder->simpleSelect($selectTable, $selectFields, $selectConditions);

        $dbConnection = new DMLFunctions();
        $result = $dbConnection->executeQuery($query);

        $row = $dbConnection->dbObject->getArray($result);

        if (isset($row[0])) {
            return $row[0];
        } else {
            return '';
        }
    }

    /**
     * Retrieves Customer Name for a given project ID.
     * @param integer $projectId
     * @return string Customer Name of $projectId
     */
    public function retrieveCustomerName($projectId) {

        $query = "SELECT `name` from `ohrm_customer` WHERE `customer_id` = (SELECT `customer_id` FROM `ohrm_project` WHERE `project_id` = $projectId)";

        $dbConnection = new DMLFunctions();
        $result = $dbConnection->executeQuery($query);

        $row = $dbConnection->dbObject->getArray($result);

        if (isset($row[0])) {
            return $row[0];
        } else {
            return '';
        }
    }

    /**
     * Build the project object array from the given result set
     *
     * @param resource $result Result set from the database
     * @return array   Array of Project objects
     */
    public static function projectObjArr($result) {

        $objArr = null;

        while ($row = mysql_fetch_assoc($result)) {

            $tmpcusArr = new Projects();

            $tmpcusArr->setProjectId($row[self::PROJECT_DB_FIELD_PROJECT_ID]);
            $tmpcusArr->setCustomerId($row[self::PROJECT_DB_FIELD_CUSTOMER_ID]);
            $tmpcusArr->setProjectName($row[self::PROJECT_DB_FIELD_NAME]);
            $tmpcusArr->setProjectDescription($row[self::PROJECT_DB_FIELD_DESCRIPTION]);
            $tmpcusArr->setDeleted($row[self::PROJECT_DB_FIELD_DELETED]);

            $objArr[] = $tmpcusArr;
        }

        return $objArr;
    }

    private function _isDuplicateName($update=false) {

        $projects = $this->filterExistingProjects();

        if (is_array($projects)) {
            if ($projects) {
                if ($projects[0][0] == $this->getProjectId()) {
                    return false;
                }
            }
            return true;
        }

        return false;
    }

    public function filterExistingProjects() {
        $sqlBuilder = new SQLQBuilder();
        $projectName = $sqlBuilder->quoteCorrectString($this->getProjectName(), true, true);

        $selectFields[] = "`" . self::PROJECT_DB_FIELD_PROJECT_ID . "`";
        $selectFields[] = "`" . self::PROJECT_DB_FIELD_CUSTOMER_ID . "`";
        $selectFields[] = "`" . self::PROJECT_DB_FIELD_NAME . "`";
        $selectTable = self::TABLE_NAME;

        $selectConditions[] = "`" . self::PROJECT_DB_FIELD_CUSTOMER_ID . "`= '" . $this->getCustomerId() . "'";
        $selectConditions[] = "`" . self::PROJECT_DB_FIELD_NAME . "`= '{$projectName}'";

        $query = $sqlBuilder->simpleSelect($selectTable, $selectFields, $selectConditions);

        $dbConnection = new DMLFunctions();
        $result = $dbConnection->executeQuery($query);

        $cnt = 0;

        while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
            $existingProjects[$cnt++] = $row;
        }

        if (isset($existingProjects)) {
            return $existingProjects;
        } else {
            $existingProjects = '';
            return $existingProjects;
        }
    }

    
    public function haveTimeItems($projectIds) {
        
        if (!empty($projectIds) && is_array($projectIds)) {
        
            $q = "SELECT * FROM `ohrm_timesheet_item` WHERE `project_id` IN(".implode(", ", $projectIds).")";

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

class ProjectsException extends Exception {
    
}

?>
