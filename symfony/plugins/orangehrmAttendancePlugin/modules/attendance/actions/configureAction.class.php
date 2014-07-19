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

class configureAction extends sfAction {
    const ADMIN_USER = "ADMIN";
    const ESS_USER = "ESS USER";
    const SUPERVISOR="SUPERVISOR";
    private $accessFlowStateMachineService;
    private $attendanceService;

    public function getAttendanceService() {

        if (is_null($this->attendanceService)) {

            $this->attendanceService = new AttendanceService();
        }

        return $this->attendanceService;
    }

    public function getAccessFlowStateMachineService() {

        if (is_null($this->accessFlowStateMachineService)) {

            $this->accessFlowStateMachineService = new AccessFlowStateMachineService();
        }

        return $this->accessFlowStateMachineService;
    }

    public function setAccessFlowStateMachineService(AccessFlowStateMachineService $accessFlowStateMachineService) {

        $this->accessFlowStateMachineService = $accessFlowStateMachineService;
    }

    public function execute($request) {

        $this->userObj = $this->getContext()->getUser()->getAttribute('user');
        $accessibleMenus = $this->userObj->getAccessibleAttendanceSubMenus();
        $hasRight = false;

        foreach ($accessibleMenus as $menu) {
            if ($menu->getDisplayName() === __("Configuration")) {
                $hasRight = true;
                break;
            }
        }

        if (!$hasRight) {
            return $this->renderText(__("You are not allowed to view this page")."!");
        }

        $this->form = new ConfigureForm();

        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }


        if ($request->isMethod('post')) {

            $this->form->bind($request->getParameter('attendance'));

            if ($this->form->isValid()) {

                $configuration1 = $this->form->getValue('configuration1');
                $configuration2 = $this->form->getValue('configuration2');
                $configuration3 = $this->form->getValue('configuration3');

                if ($configuration1 == 'on') {

                    $isPunchInEditable = $this->getAttendanceService()->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_INITIAL, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_TIME, AttendanceRecord::STATE_INITIAL);

                    if (!$isPunchInEditable) {
                        $this->saveConfigurartion(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_INITIAL, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_TIME, AttendanceRecord::STATE_INITIAL);
                    }
                    $isPunchOutEditable = $this->getAttendanceService()->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_TIME, AttendanceRecord::STATE_PUNCHED_IN);

                    if (!$isPunchOutEditable) {
                        $this->saveConfigurartion(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_TIME, AttendanceRecord::STATE_PUNCHED_IN);
                    }
                }
                if ($configuration2 == 'on') {

                    $isPunchInRecordEditable = $this->getAttendanceService()->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, AttendanceRecord::STATE_PUNCHED_IN);

                    if (!$isPunchInRecordEditable) {
                        $this->saveConfigurartion(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, AttendanceRecord::STATE_PUNCHED_IN);
                    }
                    $isPunchOutRecordEditable = $this->getAttendanceService()->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_OUT_TIME, AttendanceRecord::STATE_PUNCHED_OUT);

                    if (!$isPunchOutRecordEditable) {
                        $this->saveConfigurartion(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_OUT_TIME, AttendanceRecord::STATE_PUNCHED_OUT);
                    }

                    $isPunchInTimeEditableWhenTheStateIsPunchedIn = $this->getAttendanceService()->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, AttendanceRecord::STATE_PUNCHED_OUT);

                    if (!$isPunchOutRecordEditable) {
                        $this->saveConfigurartion(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, AttendanceRecord::STATE_PUNCHED_OUT);
                    }



                    $isPunchInRecordDeletable = $this->getAttendanceService()->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_DELETE, AttendanceRecord::STATE_NA);

                    if (!$isPunchInRecordDeletable) {

                        $this->saveConfigurartion(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_DELETE, AttendanceRecord::STATE_NA);
                    }

                    $isPunchOutRecordDeletable = $this->getAttendanceService()->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_DELETE, AttendanceRecord::STATE_NA);

                    if (!$isPunchOutRecordDeletable) {

                        $this->saveConfigurartion(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_DELETE, AttendanceRecord::STATE_NA);
                    }
                }
                if ($configuration3 == 'on') {

                    $isPunchInEditable = $this->getAttendanceService()->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, AttendanceRecord::STATE_PUNCHED_IN);
                    if (!$isPunchInEditable) {

                        $this->saveConfigurartion(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, AttendanceRecord::STATE_PUNCHED_IN);
                    }

                    $isPunchInEditableInStatePunchedOut = $this->getAttendanceService()->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, AttendanceRecord::STATE_PUNCHED_OUT);
                    if (!$isPunchInEditableInStatePunchedOut) {

                        $this->saveConfigurartion(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, AttendanceRecord::STATE_PUNCHED_OUT);
                    }

                    $isPunchOutEditable = $this->getAttendanceService()->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_OUT_TIME, AttendanceRecord::STATE_PUNCHED_OUT);

                    if (!$isPunchOutEditable) {

                        $this->saveConfigurartion(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_OUT_TIME, AttendanceRecord::STATE_PUNCHED_OUT);
                    }


                    $isPunchIndeletable = $this->getAttendanceService()->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_DELETE, AttendanceRecord::STATE_NA);

                    if (!$isPunchIndeletable) {

                        $this->saveConfigurartion(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_DELETE, AttendanceRecord::STATE_NA);
                    }

                    $isPunchOutdeletable = $this->getAttendanceService()->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_DELETE, AttendanceRecord::STATE_NA);

                    if (!$isPunchOutdeletable) {

                        $this->saveConfigurartion(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_DELETE, AttendanceRecord::STATE_NA);
                    }



                    $isProxyPunchIn = $this->getAttendanceService()->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_INITIAL, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_IN, AttendanceRecord::STATE_PUNCHED_IN);

                    if (!$isProxyPunchIn) {

                        $this->saveConfigurartion(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_INITIAL, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_IN, AttendanceRecord::STATE_PUNCHED_IN);
                    }

                    $isProxyPunchOut = $this->getAttendanceService()->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_OUT, AttendanceRecord::STATE_PUNCHED_OUT);

                    if (!$isProxyPunchOut) {

                        $this->saveConfigurartion(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_OUT, AttendanceRecord::STATE_PUNCHED_OUT);
                    }
                }
                if ($configuration1 == null) {

                    $this->getAccessFlowStateMachineService()->deleteWorkflowStateMachineRecord(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_INITIAL, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_TIME, AttendanceRecord::STATE_INITIAL);
                    $this->getAccessFlowStateMachineService()->deleteWorkflowStateMachineRecord(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_TIME, AttendanceRecord::STATE_PUNCHED_IN);
                }

                if ($configuration2 == null) {

                    $this->getAccessFlowStateMachineService()->deleteWorkflowStateMachineRecord(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, AttendanceRecord::STATE_PUNCHED_IN);
                    $this->getAccessFlowStateMachineService()->deleteWorkflowStateMachineRecord(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_OUT_TIME, AttendanceRecord::STATE_PUNCHED_OUT);
                    $this->getAccessFlowStateMachineService()->deleteWorkflowStateMachineRecord(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, AttendanceRecord::STATE_PUNCHED_OUT);
                    $this->getAccessFlowStateMachineService()->deleteWorkflowStateMachineRecord(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_DELETE, AttendanceRecord::STATE_NA);
                    $this->getAccessFlowStateMachineService()->deleteWorkflowStateMachineRecord(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::ESS_USER, WorkflowStateMachine::ATTENDANCE_ACTION_DELETE, AttendanceRecord::STATE_NA);
                }

                if ($configuration3 == null) {

                    $this->getAccessFlowStateMachineService()->deleteWorkflowStateMachineRecord(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, AttendanceRecord::STATE_PUNCHED_IN);
                    $this->getAccessFlowStateMachineService()->deleteWorkflowStateMachineRecord(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_OUT_TIME, AttendanceRecord::STATE_PUNCHED_OUT);
                    $this->getAccessFlowStateMachineService()->deleteWorkflowStateMachineRecord(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, AttendanceRecord::STATE_PUNCHED_OUT);
                    $this->getAccessFlowStateMachineService()->deleteWorkflowStateMachineRecord(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_DELETE, AttendanceRecord::STATE_NA);
                    $this->getAccessFlowStateMachineService()->deleteWorkflowStateMachineRecord(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_DELETE, AttendanceRecord::STATE_NA);

                    $this->getAccessFlowStateMachineService()->deleteWorkflowStateMachineRecord(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_INITIAL, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_IN, AttendanceRecord::STATE_PUNCHED_IN);
                    $this->getAccessFlowStateMachineService()->deleteWorkflowStateMachineRecord(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, configureAction::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_OUT, AttendanceRecord::STATE_PUNCHED_OUT);
                }



                $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));

                $this->redirect('attendance/configure');
            }
        }
    }

    public function saveConfigurartion($flow, $state, $role, $action, $resultingState) {

        $workflowStateMachineRecord = new WorkflowStateMachine();
        $workflowStateMachineRecord->setWorkflow($flow);
        $workflowStateMachineRecord->setState($state);
        $workflowStateMachineRecord->setRole($role);
        $workflowStateMachineRecord->setAction($action);
        $workflowStateMachineRecord->setResultingState($resultingState);
        $this->getAccessFlowStateMachineService()->saveWorkflowStateMachineRecord($workflowStateMachineRecord);
    }

}

?>
