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

require_once ROOT_PATH . '/lib/dao/SQLQBuilder.php';
require_once ROOT_PATH . '/lib/dao/DMLFunctions.php';

class CompProperty {

    private $propName, $propId, $deleteList, $editPropIds, $editEmpIds, $editPropFlag=false;

    private $TABLE_NAME = 'hs_hr_comp_property';//Table name for company properties

    public function setPropName($value)
    {
        $this->propName=$value;
    }

    public function getPropName()
    {
        return $this->propName;
    }

    public function setEditPropIds($value)
    {
        $this->editPropIds=$value;
    }

    public function getEditPropIds()
    {
        return $this->editPropIds;
    }

    public function setEditEmpIds($value)
    {
        $this->editEmpIds=$value;
    }

    public function getEditEmpIds()
    {
        return $this->editEmpIds;
    }

    public function setPropId($value)
    {
        $this->propId=$value;
    }

    public function getPropId()
    {
        return $this->propId;
    }

    public function setDeleteList($value)
    {
        $this->deleteList = $value;
    }

    public function getDeleteList()
    {
        return $this->deleteList;
    }

    public function setEditPropFlag($value)
    {
        $this->editPropFlag = $value;
    }

    public function getEditPropFlag()
    {
        return $this->editPropFlag;
    }

/*
 * Adds a property.
 *
 * Before use set property name
 */
    public function addProperty()
    {
        $dbConnection = new DMLFunctions();

        $sqlB->arr_insert = array($this->getPropName());

        $sqlBuilder = new SQLQBuilder();
        $sqlBuilder->table_name = $this->TABLE_NAME;
        $sqlBuilder->flg_insert = 'true';
        $sqlBuilder->arr_insert = array($this->getPropName());
        $sqlBuilder->arr_insertfield = array("prop_name");

        $sql=$sqlBuilder->addNewRecordFeature2();

        $dbConnection->executeQuery($sql);

        return 1;//report success
     }
/*
 * This function edits properties
 */
    public function editProperty($id)
    {
        $sql_builder = new SQLQBuilder();

        $sql_builder->table_name = $this->TABLE_NAME;
        $sql_builder->flg_update = 'true';

        $dbConnection = new DMLFunctions();



        $sqlQString = $sql_builder->simpleUpdate($this->TABLE_NAME,array('prop_name'),array($this->propName), array("`prop_id`='$id'"));
        $message2 = $dbConnection -> executeQuery($sqlQString);

        return $message2;
    }

/*
 * Returns two dimentional array of list of properties
 */
    public function getPropertyList($pageNo = null, $belongsTo = null, $withUnallocated = false) {
        $dbConnection = new DMLFunctions();
		$sql = "SELECT * FROM " . $this->TABLE_NAME;

		if (isset($belongsTo) && is_array($belongsTo) && count($belongsTo) > 0) {
			$sql .= " WHERE (`emp_id` IN ('" . implode("', '", $belongsTo) . "'))";

			if ($withUnallocated) {
				$sql .= " OR (`emp_id` IS NULL || `emp_id` = -1)";
			}
		}

        if (isset($pageNo)) {
	        $selectLimit = ($pageNo*10-10).",".(10);
	        $sql .= " LIMIT $selectLimit";
        }

        $res = $dbConnection->executeQuery($sql);

        $cnt=0;
        $list=null;//The two dimentional array of the list

        while($row=mysql_fetch_array($res))
        {
            $list[$cnt]=$row;
            $cnt++;
        }

        return $list;
    }

    public function editPropertyList()
    {
        $sql_builder = new SQLQBuilder();

        $sql_builder->table_name = $this->TABLE_NAME;
        $sql_builder->flg_update = 'true';

        $dbConnection = new DMLFunctions();


        $i=0;
        foreach($this->editPropIds as $id)
        {
            $sqlQString = $sql_builder->simpleUpdate($this->TABLE_NAME,array('emp_id'),array($this->editEmpIds[$i]), array("`prop_id`='$id'"));
            $message2 = $dbConnection -> executeQuery($sqlQString);
            $i++;
        }


        return $message2;
    }

 /*
 * Adds a property.
 *
 * Before call this deleteList should be set with prop_id s which should be deleted
 */
    public function deleteProperties()
    {
        $arrFieldList[0] = 'prop_id';

        $sql_builder = new SQLQBuilder();

        $sql_builder->table_name = $this->TABLE_NAME;
        $sql_builder->flg_delete = 'true';
        $sql_builder->arr_delete = $arrFieldList;

        $dbConnection = new DMLFunctions();


        $sqlQString = $sql_builder->deleteRecord(array($this->deleteList));
        $message2 = $dbConnection -> executeQuery($sqlQString);

        return $message2;
    }
}
