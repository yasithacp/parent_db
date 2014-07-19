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


abstract class orangehrmLeaveMailer extends orangehrmMailer {

    protected $performer; // Type of Employee
    protected $performerType; // 'admin', 'supervisor' or 'ess'
    protected $recipient; // Type of Employee
    protected $leaveRequest; // Type of LeaveRequest
    protected $leaveList; // Type of Leave
    protected $requestType; // Either 'request' or 'single'
    protected $employeeService; // Type of EmployeeService

    public function getPerformer() {
        return $this->performer;
    }

    public function setPerformer($performer) {
        $this->performer = $performer;
    }

    public function getPerformerType() {
        return $this->performerType;
    }

    public function setPerformerType($performerType) {
        $this->performerType = $performerType;
    }

    public function getRecipient() {
        return $this->recipient;
    }

    public function setRecipient($recipient) {
        $this->recipient = $recipient;
    }

    public function getLeaveRequest() {
        return $this->leaveRequest;
    }

    public function setLeaveRequest($leaveRequest) {
        $this->leaveRequest = $leaveRequest;
    }

    public function getLeaveList() {
        return $this->leaveList;
    }

    public function setLeaveList($leaveList) {
        $this->leaveList = $leaveList;
    }

    public function getRequestType() {
        return $this->requestType;
    }

    public function setRequestType($requestType) {
        $this->requestType = $requestType;
    }

    public function getEmployeeService() {
        return $this->employeeService;
    }

    public function setEmployeeService($employeeService) {
        $this->employeeService = $employeeService;
    }

}
