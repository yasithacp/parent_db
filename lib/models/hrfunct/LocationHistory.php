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


require_once ROOT_PATH . '/lib/common/CommonFunctions.php';
require_once ROOT_PATH . '/lib/models/hrfunct/AbstractEmpHistory.php';

/**
 * Manages history of employee sub divisions
 */
class LocationHistory extends AbstractEmpHistory {

    protected $tableName = 'hs_hr_emp_location_history';
    protected $externalTable = 'hs_hr_location';
    protected $externalCodeField = 'loc_code';
    protected $externalNameField = 'loc_name';

    /* An employee can have multiple locations assigned */
    protected $allowMultipleCurrentItems = true;

    /**
     * Validate the code
     *
     * @param mixed $code Code
     * @return boolean true if code valid, false otherwise
     */
    protected function validateCode($code) {
        return CommonFunctions::isValidId($code, 'LOC');
    }

}

?>
