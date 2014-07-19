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


abstract class orangehrmLeaveMailContent extends orangehrmMailContent {

    protected $subjectTemplate;
    protected $subjectTemplateForSupervisors;
    protected $subjectReplacements = array();
    protected $subjectReplacementsForSupervisors = array();
    protected $bodyTemplate;
    protected $bodyTemplateForSupervisors;
    protected $bodyReplacements = array();
    protected $bodyReplacementsForSupervisors = array();
    protected $subscriberSubjectTemplate;
    protected $subscriberSubjectReplacements = array();
    protected $subscriberBodyTemplate;
    protected $subscriberBodyReplacements = array();
    protected $performer; // Type of Employee
    protected $recipient; // Type of Employee
    protected $leaveRequest; // Type of LeaveRequest
    protected $leaveList; // Type of Leave
    protected $templateDirectoryPath;
    protected $requestType;
    protected $replacements = array('performerFirstName' => 'System Administrator',
                                    'performerFullName' => 'System Administrator',
                                    'recipientFirstName' => '',
                                    'recipientFullName' => ''
                                    );

    /* ========== Start of getters and setters ========== */

    public function setSubjectTemplate($subjectTemplate) {
        $this->subjectTemplate = $subjectTemplate;
    }

    public function setSubjectReplacements($subjectReplacements) {
        $this->subjectReplacements = $subjectReplacements;
    }

    public function setBodyTemplate($bodyTemplate) {
        $this->bodyTemplate = $bodyTemplate;
    }

    public function setBodyReplacements($bodyReplacements) {
        $this->bodyReplacements = $bodyReplacements;
    }

    public function setSubscriberSubjectTemplate($subscriberSubjectTemplate) {
        $this->subscriberSubjectTemplate = $subscriberSubjectTemplate;
    }

    public function setSubscriberSubjectReplacements($subscriberSubjectReplacements) {
        $this->subscriberSubjectReplacements = $subscriberSubjectReplacements;
    }

    public function setSubscriberBodyTemplate($subscriberBodyTemplate) {
        $this->subscriberBodyTemplate = $subscriberBodyTemplate;
    }

    public function setSubscriberBodyReplacements($subscriberBodyReplacements) {
        $this->subscriberBodyReplacements = $subscriberBodyReplacements;
    }

    public function getPerformer() {
        return $this->performer;
    }

    public function setPerformer($performer) {
        $this->performer = $performer;
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

    public function getTemplateDirectoryPath() {
        return $this->templateDirectoryPath;
    }

    public function setTemplateDirectoryPath($templateDirectoryPath) {
        $this->templateDirectoryPath = $templateDirectoryPath;
    }

    public function getRequestType() {
        return $this->requestType;
    }

    public function setRequestType($requestType) {
        $this->requestType = $requestType;
    }

    public function getReplacements() {
        return $this->replacements;
    }

    public function setReplacements($replacements) {
        $this->replacements = $replacements;
    }

    /* ========== End of getters and setters ========== */

    public function  __construct($performer, $recipient, $leaveRequest, $leaveList, $requestType = 'request') {

        $this->performer = $performer;
        $this->recipient = $recipient;
        $this->leaveRequest = $leaveRequest;
        $this->leaveList = $leaveList;
        $this->requestType = $requestType;

        // TODO: Pass template path as a parameter
        $directoryPathBase = sfConfig::get('sf_root_dir')."/plugins/orangehrmCoreLeavePlugin/modules/leave/templates/mail/";
        $this->templateDirectoryPath = $directoryPathBase . 'en_US/';
        $culture = sfContext::getInstance()->getUser()->getCulture();
        
        if (file_exists($directoryPathBase . $culture . '/')) {
            $this->templateDirectoryPath = $directoryPathBase . $culture . '/';
        }
        
        $this->populateReplacements();

    }

    public function populateReplacements() {

        if ($this->performer instanceof Employee) {
            $this->replacements['performerFirstName'] = $this->performer->getFirstName();
            $this->replacements['performerFullName'] = $this->performer->getFirstAndLastNames();
        }

        if ($this->recipient instanceof Employee) {
            $this->replacements['recipientFirstName'] = $this->recipient->getFirstName();
            $this->replacements['recipientFullName'] = $this->recipient->getFirstAndLastNames();
        }

        $this->_populateLeaveReplacements();    

    }

    public function generateSubject() {
        
        return $this->replaceContent($this->getSubjectTemplate(), $this->getSubjectReplacements());

    }
    
    public function generateSubjectForSupervisors() {
        return $this->replaceContent($this->getSubjectTemplateForSupervisors(), $this->getSubjectReplacementsForSupervisors());
    }

    public function generateBody() {

        return $this->replaceContent($this->getBodyTemplate(), $this->getBodyReplacements());

    }
    
    public function generateBodyForSupervisors() {
        return $this->replaceContent($this->getBodyTemplateForSupervisors(), $this->getBodyReplacementsForSupervisors());
    }

    public function generateSubscriberSubject() {

        return $this->replaceContent($this->getSubscriberSubjectTemplate(), $this->getSubscriberSubjectReplacements());

    }

    public function generateSubscriberBody() {

        return $this->replaceContent($this->getSubscriberBodyTemplate(), $this->getSubscriberBodyReplacements());

    }

    public function replaceContent($template, $replacements, $wrapper = '%') {

        $keys = array_keys($replacements);

        foreach ($keys as $value) {
            $needls[] = $wrapper . $value . $wrapper;
        }

        return str_replace($needls, $replacements, $template);

    }

    protected function _populateLeaveReplacements() {

        if ($this->leaveRequest instanceof LeaveRequest) {
            $this->replacements['leaveType'] = $this->leaveRequest->getLeaveTypeName();
            $this->replacements['assigneeFullName'] = $this->leaveRequest->getEmployee()->getFirstAndLastNames();
        }

        $numberOfDays = 0;

        foreach ($this->leaveList as $leave) {
            $numberOfDays += $leave->getLeaveLengthDays();
        }

        $this->replacements['numberOfDays'] = $numberOfDays;

        $this->replacements['leaveDetails'] = $this->_generateLeaveDetailsTable();

    }

    protected function _generateLeaveDetailsTable() {

        // Length of tab (4 spaces) : "    "

        $details = "Date(s)                Duration (Hours)";
        $details .= "\n";
        $details .= "=========================";
        $details .= "\n";

        foreach ($this->leaveList as $leave) {

            $leaveDate = set_datepicker_date_format($leave->getLeaveDate());
            $leaveDuration = round($leave->getLeaveLengthHours(), 2);

            if ($leaveDuration > 0) {

                $leaveDuration = $this->_fromatDuration($leaveDuration);
                $details .= "$leaveDate            $leaveDuration";
                $details .= "\n";

            }

        }

        $details .= "\n";
        $details .= "Leave type : " . $this->replacements['leaveType'];
        $details .= "\n";

        $leaveComment = '';

        if ($this->requestType == 'request') {
            $leaveComment = $this->leaveRequest->getLeaveComments();
        } elseif ($this->requestType == 'single') {
            $leaveComment = $this->leaveList[0]->getLeaveComments();
        }    

        if (!empty($leaveComment)) {
            $details .= "Comment : $leaveComment";
            $details .= "\n";
        }

        return $details;

    }

    private function _fromatDuration($duration) {

        $formattedDuration = number_format($duration, 2);

        return $formattedDuration;

    }
    
    // Use following if Leave Type needs to be displayed
    /*
    protected function _generateLeaveDetailsTable() {

        // Length of tab (4 spaces) : "    "

        $details = "Date(s)                Leave Type                Duration (Hours)";
        $details .= "\n";
        $details .= "========================================";
        $details .= "\n";

        $leaveTypeName = $this->leaveRequest->getLeaveType()->getLeaveTypeName();

        foreach ($this->leaveList as $leave) {

            $leaveDate = $leave->getLeaveDate();
            $leaveDuration = $leave->getLeaveLengthHours();

            if ($leaveDuration > 0) {

                $details .= "$leaveDate            $leaveTypeName                        $leaveDuration";
                $details .= "\n";

            }

        }

        $leaveComment = $this->leaveRequest->getLeaveComments();

        if (!empty($leaveComment)) {
            $details .= "\n";
            $details .= "Comment : $leaveComment";
            $details .= "\n";
        }

        return $details;

    }
    */
    
    abstract function getSubjectTemplate();
    abstract function getSubjectReplacements();
    abstract function getBodyTemplate();
    abstract function getBodyReplacements();
    abstract function getSubscriberSubjectTemplate();
    abstract function getSubscriberSubjectReplacements();
    abstract function getSubscriberBodyTemplate();
    abstract function getSubscriberBodyReplacements();

}
