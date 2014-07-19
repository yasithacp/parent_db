<?php

class saveLeaveEntitlementsAction extends baseLeaveAction {

    public $form;

    public function execute($request) {

        $form = $this->getForm();
        $saveSuccess = true;
        
        $logger = Logger::getLogger('saveLeaveEntitlementsAction'); 

        if ($request->isMethod(sfRequest::POST)) {
            $form->bind($request->getParameter($form->getName()));

            $hdnEmpId = $request->getParameter('hdnEmpId');
            $hdnLeaveTypeId = $request->getParameter('hdnLeaveTypeId');
            $hdnLeavePeriodId = $request->getParameter('hdnLeavePeriodId');
            $txtLeaveEntitled = $request->getParameter('txtLeaveEntitled');
            
            $idCount = count($hdnEmpId);
            $leaveTypeCount = count($hdnLeaveTypeId);
            $count = count($txtLeaveEntitled);
            
            /*
             * Validate we have all input values for all rows.
             */
            if (($count != $idCount) || ($count != $leaveTypeCount)) {
                $logger->error("saveLeaveEntitlements: field count does not match: " . 
                        " employee ids={$idCount}, leaveTypeIds={$hdnLeaveTypeId}, entitlements={$txtLeaveEntitled}");
                $logger->error($hdnEmpId);
                $logger->error($hdnLeaveTypeId);
                $logger->error($txtLeaveEntitled);
                
                $saveSuccess = false;
            } else {            
                $leaveEntitlementService = $this->getLeaveEntitlementService();
                $leaveSummaryData = $request->getParameter('leaveSummary');

                for ($i = 0; $i < $count; $i++) {
                    $leavePeriodId = empty($hdnLeavePeriodId[$i]) ? $leaveSummaryData['hdnSubjectedLeavePeriod'] : $hdnLeavePeriodId[$i];
                    try {
                        $leaveEntitlementService->saveEmployeeLeaveEntitlement($hdnEmpId[$i], $hdnLeaveTypeId[$i], $leavePeriodId, $txtLeaveEntitled[$i], true);
                    } catch (Exception $e) {
                        $logger->error($e);
                        $saveSuccess = false;
                    }
                }
            }
            if ($saveSuccess) {
                $this->getUser()->setFlash('templateMessage', array('SUCCESS', __(TopLevelMessages::SAVE_SUCCESS)), false);
            } else {
                $this->getUser()->setFlash('templateMessage', array('FAILURE', __(TopLevelMessages::SAVE_FAILURE)), false);
            }

            $this->forwardToLeaveSummary();
        }
    }
    
    protected function forwardToLeaveSummary() {
        $this->forward('leave', 'viewLeaveSummary');
    }

    /**
     *
     * @return LeaveSummaryForm 
     */
    protected function getForm() {
        if (!($this->form instanceof LeaveSummaryForm)) {
            $formDefaults = array();
            $formOptions = $this->getLoggedInUserDetails();
            $this->form = new LeaveSummaryForm($formDefaults, $formOptions, true);
        }

        return $this->form;
    }

    /**
     *
     * @param LeaveSummaryForm $form 
     */
    protected function setForm(LeaveSummaryForm $form) {
        $this->form = $form;
    }

}

