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

class SimplePager extends sfPager implements Serializable {


  protected $results;

  protected $offset;

  public function __construct($class, $maxPerPage = 10) {
    parent::__construct($class, $maxPerPage);
    $this->offset = null;
  }

  public function setResults($results) {
    $this->results = $results;
  }

  public function setNumResults($count) {
      $this->setNbResults($count);
  }
  
  public function getNumResults(){
  		return $this->getNbResults();
  }

  // function to be called after parameters have been set
  public function init() {

    if ($this->getPage() == 0 || $this->getMaxPerPage() == 0 || $this->getNbResults() == 0) {
      $this->setLastPage(0);
    } else {
      $this->offset = ($this->getPage() - 1) * $this->getMaxPerPage();
      $this->setLastPage(ceil($this->getNbResults() / $this->getMaxPerPage()));

    }
  }

  public function getOffset() {
      return $this->offset;
  }

  // main method: returns an array of result on the given page
  public function getResults() {
    return $results;
  }


  // used internally by getCurrent()
  protected function retrieveObject($offset) {
    return false;
  }

  /**
   * Serialize the pager object
   *
   * @return string $serialized
   */
  public function serialize() {
    $vars = get_object_vars($this);
    unset($vars['query']);
    return serialize($vars);
  }

  /**
   * Unserialize a pager object
   *
   * @param string $serialized
   * @return void
   */
  public function unserialize($serialized) {
    $array = unserialize($serialized);

    foreach($array as $name => $values)
    {
      $this->$name = $values;
    }
  }

}