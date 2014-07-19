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

class ConfigureForm extends sfForm {
    const ADMIN_USER = "ADMIN";
    const ESS_USER = "ESS USER";
    const SUPERVISOR="SUPERVISOR";

    public function configure() {



        $this->setWidgets(array(
            'configuration1' => new sfWidgetFormInputCheckbox(array(), array('class' => 'configuration')),
            'configuration2' => new sfWidgetFormInputCheckbox(array(), array('class' => 'configuration')),
            'configuration3' => new sfWidgetFormInputCheckbox(array(), array('class' => 'configuration')),
        ));

        $this->widgetSchema->setNameFormat('attendance[%s]');

        $arrayOfSavedConfigurations = $this->getSavedConfigurationSettings();


        if ($arrayOfSavedConfigurations['configuration1']) {
            $this->setDefault('configuration1', 'on');
        }
        if ($arrayOfSavedConfigurations['configuration2']) {
            $this->setDefault('configuration2', 'on');
        }
        if ($arrayOfSavedConfigurations['configuration3']) {
            $this->setDefault('configuration3', 'on');
        }


        $this->setValidators(array(
            'configuration1' => new sfValidatorPass(),
            'configuration2' => new sfValidatorPass(),
            'configuration3' => new sfValidatorPass(),
        ));
    }

    public function getSavedConfigurationSettings() {


        $savedConfigurationSettingArray = array();
        $attendanceService = new AttendanceService();

        $recordExists1 = $attendanceService->getSavedConfiguration(PluginWorkflowStateMachine::FLOW_ATTENDANCE, PluginAttendanceRecord::STATE_INITIAL, ConfigureForm::ESS_USER, PluginWorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_TIME, PluginAttendanceRecord::STATE_INITIAL);
        $recordExists2 = $attendanceService->getSavedConfiguration(PluginWorkflowStateMachine::FLOW_ATTENDANCE, PluginAttendanceRecord::STATE_PUNCHED_IN, ConfigureForm::ESS_USER, PluginWorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_TIME, PluginAttendanceRecord::STATE_PUNCHED_IN);

        $recordExists3 = $attendanceService->getSavedConfiguration(PluginWorkflowStateMachine::FLOW_ATTENDANCE, PluginAttendanceRecord::STATE_PUNCHED_IN, ConfigureForm::ESS_USER, PluginWorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, PluginAttendanceRecord::STATE_PUNCHED_IN);
        $recordExists4 = $attendanceService->getSavedConfiguration(PluginWorkflowStateMachine::FLOW_ATTENDANCE, PluginAttendanceRecord::STATE_PUNCHED_OUT, ConfigureForm::ESS_USER, PluginWorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_OUT_TIME, PluginAttendanceRecord::STATE_PUNCHED_OUT);
        $recordExists13 = $attendanceService->getSavedConfiguration(PluginWorkflowStateMachine::FLOW_ATTENDANCE, PluginAttendanceRecord::STATE_PUNCHED_OUT, ConfigureForm::ESS_USER, PluginWorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, PluginAttendanceRecord::STATE_PUNCHED_OUT);
        
        $recordExists5 = $attendanceService->getSavedConfiguration(PluginWorkflowStateMachine::FLOW_ATTENDANCE, PluginAttendanceRecord::STATE_PUNCHED_IN, ConfigureForm::ESS_USER, PluginWorkflowStateMachine::ATTENDANCE_ACTION_DELETE, PluginAttendanceRecord::STATE_NA);
        $recordExists6 = $attendanceService->getSavedConfiguration(PluginWorkflowStateMachine::FLOW_ATTENDANCE, PluginAttendanceRecord::STATE_PUNCHED_OUT, ConfigureForm::ESS_USER, PluginWorkflowStateMachine::ATTENDANCE_ACTION_DELETE, PluginAttendanceRecord::STATE_NA);

        $recordExists7 = $attendanceService->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_IN, ConfigureForm::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME, AttendanceRecord::STATE_PUNCHED_IN);
        $recordExists8 = $attendanceService->getSavedConfiguration(WorkflowStateMachine::FLOW_ATTENDANCE, AttendanceRecord::STATE_PUNCHED_OUT, ConfigureForm::SUPERVISOR, WorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_OUT_TIME, AttendanceRecord::STATE_PUNCHED_OUT);

        $recordExists9 = $attendanceService->getSavedConfiguration(PluginWorkflowStateMachine::FLOW_ATTENDANCE, PluginAttendanceRecord::STATE_PUNCHED_IN, ConfigureForm::SUPERVISOR, PluginWorkflowStateMachine::ATTENDANCE_ACTION_DELETE, PluginAttendanceRecord::STATE_NA);
        $recordExists10 = $attendanceService->getSavedConfiguration(PluginWorkflowStateMachine::FLOW_ATTENDANCE, PluginAttendanceRecord::STATE_PUNCHED_OUT, ConfigureForm::SUPERVISOR, PluginWorkflowStateMachine::ATTENDANCE_ACTION_DELETE, PluginAttendanceRecord::STATE_NA);

        $recordExists11 = $attendanceService->getSavedConfiguration(PluginWorkflowStateMachine::FLOW_ATTENDANCE, PluginAttendanceRecord::STATE_INITIAL, ConfigureForm::SUPERVISOR, PluginWorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_IN, PluginAttendanceRecord::STATE_PUNCHED_IN);
        $recordExists12 = $attendanceService->getSavedConfiguration(PluginWorkflowStateMachine::FLOW_ATTENDANCE, PluginAttendanceRecord::STATE_PUNCHED_IN, ConfigureForm::SUPERVISOR, PluginWorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_OUT, PluginAttendanceRecord::STATE_PUNCHED_OUT);



        if (($recordExists1) || ($recordExists2)) {
            $savedConfigurationSettingArray['configuration1'] = true;
        } else {
            $savedConfigurationSettingArray['configuration1'] = false;
        }

        if ($recordExists3 || $recordExists4 || $recordExists5 || $recordExists6 || $recordExists13) {
            $savedConfigurationSettingArray['configuration2'] = true;
        } else {
            $savedConfigurationSettingArray['configuration2'] = false;
        }


        if ($recordExists7 || $recordExists8 || $recordExists9 || $recordExists10 || $recordExists11 || $recordExists12) {
            $savedConfigurationSettingArray['configuration3'] = true;
        } else {
            $savedConfigurationSettingArray['configuration3'] = false;
        }

        return $savedConfigurationSettingArray;
    }

}

?>
