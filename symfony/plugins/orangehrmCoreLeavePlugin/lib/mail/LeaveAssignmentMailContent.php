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


class LeaveAssignmentMailContent extends orangehrmLeaveMailContent {

    public function getSubjectTemplate() {

        if (empty($this->subjectTemplate)) {

            $this->subjectTemplate = trim($this->readFile($this->templateDirectoryPath . 'leaveAssignmentSubject.txt'));

        }

        return $this->subjectTemplate;

    }
    
    public function getSubjectTemplateForSupervisors() {
        
        if (empty($this->subjectTemplateForSupervisors)) {

            $this->subjectTemplateForSupervisors = trim($this->readFile($this->templateDirectoryPath . 'leaveAssignmentSubjectForSupervisors.txt'));

        }

        return $this->subjectTemplateForSupervisors;
        
    }

    public function getSubjectReplacements() {

        if (empty($this->subjectReplacements)) {

            $this->subjectReplacements = array('performerFullName' => $this->replacements['performerFullName'],
                                               'numberOfDays' => $this->replacements['numberOfDays'],
                                               'leaveType' => $this->replacements['leaveType']
                                               );

        }

        return $this->subjectReplacements;

    }
    
    public function getSubjectReplacementsForSupervisors() {
        
        if (empty($this->subjectReplacementsForSupervisors)) {

            $this->subjectReplacementsForSupervisors = array('performerFullName' => $this->replacements['performerFullName'],
                                                             'assigneeFullName' => $this->replacements['assigneeFullName']
                                                            );

        }

        return $this->subjectReplacementsForSupervisors;
        
    }

    public function getBodyTemplate() {

        if (empty($this->bodyTemplate)) {

            $this->bodyTemplate = $this->readFile($this->templateDirectoryPath . 'leaveAssignmentBody.txt');

        }

        return $this->bodyTemplate;

    }
    
    public function getBodyTemplateForSupervisors() {

        if (empty($this->bodyTemplateForSupervisors)) {

            $this->bodyTemplateForSupervisors = $this->readFile($this->templateDirectoryPath . 'leaveAssignmentBodyForSupervisors.txt');

        }

        return $this->bodyTemplateForSupervisors;

    }

    public function getBodyReplacements() {

        if (empty($this->bodyReplacements)) {

            $this->bodyReplacements = array('recipientFirstName' => $this->replacements['recipientFirstName'],
                                            'performerFullName' => $this->replacements['performerFullName'],
                                            'leaveDetails' => $this->replacements['leaveDetails']
                                            );

        }

        return $this->bodyReplacements;
        
    }
    
    public function getBodyReplacementsForSupervisors() {

        if (empty($this->bodyReplacementsForSupervisors)) {

            $this->bodyReplacementsForSupervisors = array('recipientFirstName' => $this->replacements['recipientFirstName'],
                                                          'performerFullName' => $this->replacements['performerFullName'],
                                                          'assigneeFullName' => $this->replacements['assigneeFullName'],
                                                          'leaveDetails' => $this->replacements['leaveDetails']
                                                         );

        }

        return $this->bodyReplacementsForSupervisors;
        
    }

    public function getSubscriberSubjectTemplate() {
        
        if (empty($this->subscriberSubjectTemplate)) {

            $this->subscriberSubjectTemplate = trim($this->readFile($this->templateDirectoryPath . 'leaveAssignmentSubscriberSubject.txt'));

        }

        return $this->subscriberSubjectTemplate;
        
    }

    public function getSubscriberSubjectReplacements() {
        
        if (empty($this->subscriberSubjectReplacements)) {

            $this->subscriberSubjectReplacements = array('performerFullName' => $this->replacements['performerFullName'],
                                                         'assigneeFullName' => $this->replacements['assigneeFullName']
                                                        );

        }

        return $this->subscriberSubjectReplacements;
        
    }

    public function getSubscriberBodyTemplate() {
        
        if (empty($this->subscriberBodyTemplate)) {

            $this->subscriberBodyTemplate = $this->readFile($this->templateDirectoryPath . 'leaveAssignmentSubscriberBody.txt');

        }

        return $this->subscriberBodyTemplate;
        
    }

    public function getSubscriberBodyReplacements() {
        
        if (empty($this->subscriberBodyReplacements)) {

            $this->subscriberBodyReplacements = array('performerFullName' => $this->replacements['performerFullName'],
                                                      'assigneeFullName' => $this->leaveRequest->getEmployee()->getFirstAndLastNames(),
                                                      'leaveDetails' => $this->replacements['leaveDetails']
                                                      );

        }

        return $this->subscriberBodyReplacements;
        
    }

    
}
