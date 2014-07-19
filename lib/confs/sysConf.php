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


class sysConf {

  var $itemsPerPage;

  /** $accessDenied is depreciated and no longer in use
         *  Please use the language files to change the access denied message.
         */
  var $accessDenied;
  var $viewDescLen;
  var $userEmail;
  var $maxEmployees;
  var $dateFormat;
  var $timeFormat;

  var $dateInputHint;
  var $timeInputHint;
  public $javascriptInputHint = "YYYY-MM-DD";
  var $styleSheet;

  /**
   * Following variable decides if admin users can edit the sendmail path through a web browser.
   * If set to false, the mailConf.php file has to be edited manually to set sendmail path.
   *
   * WARNING: Setting to true is not secure.
   */
  protected $allowSendmailPathEdit = false; // Set to true to edit sendmail path through a browser

  /**
   * Following variable limits sendmail path edit to a browser running on the same computer as OrangeHRM.
   * Set to false to allow editing from anywhere.
   *
   * WARNING: Setting to false is not secure.
   */
  protected $sendmailPathEditOnlyFromLocalHost = true; // Set to edit sendmail path from

  function sysConf() {

    $this->itemsPerPage=50;

    /* $accessDenied is depreciated and no longer in use
     *  Please use the language files to change the access denied message.
     */
    $this->accessDenied="Access Denied";

    $this->viewDescLen=60;
    $this->userEmail = 'youremail@mailhost.com';
    $this->maxEmployees = '4999';
    $this->dateFormat = "Y-m-d";
    $this->dateInputHint = "YYYY-mm-DD";
    $this->timeFormat = "H:i";
    $this->timeInputHint = "HH:MM";
    $this->styleSheet = "orange";
  }

  function getEmployeeIdLength() {
    return strlen($this->maxEmployees);
  }

  function getDateFormat() {
    return $this->dateFormat;
  }

  function getTimeFormat() {
    return $this->timeFormat;
  }

  function getDateInputHint() {
    return $this->dateInputHint;
  }

  function getTimeInputHint() {
    return $this->timeInputHint;
  }

  function getStyleSheet() {
    return $this->styleSheet;
  }

  public function allowSendmailPathEdit() {
      return $this->allowSendmailPathEdit;
  }

  public function sendmailPathEditOnlyFromLocalHost() {
    return $this->sendmailPathEditOnlyFromLocalHost;
  }
}

?>
