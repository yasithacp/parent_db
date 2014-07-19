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
 * Config Dao: Manages configuration entries in hs_hr_config
 *
 */
class ConfigDao extends BaseDao {

    /**
     * Get Logger instance. Creates if not already created.
     *
     * @return Logger
     */
    protected function getLogger() {
        if (is_null($this->logger)) {
            $this->logger = Logger::getLogger('core.ConfigDao');
        }

        return($this->logger);
    }
    
    /**
     * Set $key to given $value
     * @param type $key Key
     * @param type $value Value
     */
    public function setValue($key, $value) {
        try {
            $config = new Config();
            $config->key = $key;
            $config->value = $value;
            $config->replace();

        } catch (Exception $e) {
            $this->getLogger()->error("Exception in setValue:" . $e);
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }        
    }
    
    /**
     * Get value corresponding to given $key
     * @param type $key Key
     * @return String value
     */
    public function getValue($key) {
        try {
            $q = Doctrine_Query::create()
                 ->select('c.value')
                 ->from('Config c')
                 ->where('c.key = ?', $key);
            $value = $q->execute(array(), Doctrine::HYDRATE_SINGLE_SCALAR);
      
            return $value;
        } catch (Exception $e) {
            $this->getLogger()->error("Exception in getValue:" . $e);
            throw new DaoException($e->getMessage(), $e->getCode(), $e);
        }
       
    }
}