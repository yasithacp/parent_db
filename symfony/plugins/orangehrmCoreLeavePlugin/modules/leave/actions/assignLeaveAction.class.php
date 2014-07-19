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
 * Displaying AssignLeave UI and saving data
 */
class assignLeaveAction extends baseLeaveAction {

    protected $leaveAssignmentService;

    /**
     * @param sfForm $form
     */
    public function setForm(sfForm $form) {
        if (is_null($this->assignLeaveForm)) {
            $this->assignLeaveForm = $form;
        }
    }

    /**
     *
     * @return LeaveAssignmentService
     */
    public function getLeaveAssignmentService() {
        if (!($this->leaveAssignmentService instanceof LeaveAssignmentService)) {
            $this->leaveAssignmentService = new LeaveAssignmentService();
        }
        return $this->leaveAssignmentService;
    }

    /**
     *
     * @param LeaveAssignmentService $service 
     */
    public function setLeaveAssignmentService(LeaveAssignmentService $service) {
        $this->leaveAssignmentService = $service;
    }
    
    public function execute($request) {

        $form = $this->getAssignLeaveForm();
        $this->setForm($form);
        $this->overlapLeave = 0;

        /* This section is to save leave request */
        if ($request->isMethod('post')) {
            $this->assignLeaveForm->bind($request->getParameter($this->assignLeaveForm->getName()));
            if ($this->assignLeaveForm->isValid()) {
                try {
                    $leaveParameters = $this->getLeaveParameterObject($form->getValues());
                    
                    $success = $this->getLeaveAssignmentService()->assignLeave($leaveParameters);
                    
                    if ($success) {
                        $this->templateMessage = array('SUCCESS', __('Successfully Assigned'));
                    } else {
                        $this->overlapLeave = $this->getLeaveAssignmentService()->getOverlapLeave();
                        $this->templateMessage = array('WARNING', __('Failed to Assign'));
                    }
                } catch (LeaveAllocationServiceException $e) {
                    $this->templateMessage = array('WARNING', __($e->getMessage()));
                }
            }
        }
    }
    
    protected function getLeaveParameterObject(array $formValues) {
        
        $empData = $formValues['txtEmployee'];
        $formValues['txtEmpID'] = $empData['empId'];
        
        return new LeaveParameterObject($formValues);
    }
    
    /**
     * Retrieve Leave Type List
     */
    protected function getElegibleLeaveTypes() {
        $leaveTypeList = $this->getLeaveTypeService()->getLeaveTypeList();
        return $leaveTypeList;
    }

    /**
     * Creating Assign Leave Form
     */
    protected function getAssignLeaveForm() {
        /* Making the optional parameters to create the form */
        $leaveTypes = $this->getElegibleLeaveTypes();

        if (count($leaveTypes) == 0) {
            $this->templateMessage = array('WARNING', __('No Leave Types with Leave Balance'));
        }
        $leaveFormOptions = array('leaveTypes' => $leaveTypes);
        $form = new AssignLeaveForm(array(), $leaveFormOptions, true);

        return $form;
    }

}
