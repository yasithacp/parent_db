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
 * defineLeavePeriodAction
 */
class defineLeavePeriodAction extends sfAction {

    private $leavePeriodService;
    private $leaveRequestService;


    public function getLeavePeriodService() {

        if (is_null($this->leavePeriodService)) {
            $leavePeriodService = new LeavePeriodService();
            $leavePeriodService->setLeavePeriodDao(new LeavePeriodDao());
            $this->leavePeriodService = $leavePeriodService;
        }

        return $this->leavePeriodService;
    }

    /**
     * @return LeaveRequestService
     */
    public function getLeaveRequestService() {
        if(is_null($this->leaveRequestService)) {
            $this->leaveRequestService = new LeaveRequestService();
            $this->leaveRequestService->setLeaveRequestDao(new LeaveRequestDao());
        }
        return $this->leaveRequestService;
    }

    /**
     * @param sfForm $form
     * @return
     */
    public function setForm(sfForm $form) {
        if(is_null($this->form)) {
            $this->form	= $form;
        }
    }

    public function execute($request) {
        if (!Auth::instance()->hasRole(Auth::ADMIN_ROLE)) {
            $this->forward('leave', 'showLeavePeriodNotDefinedWarning');
        }

        $this->setForm(new LeavePeriodForm(array(), array(), true));
        $this->isLeavePeriodDefined = OrangeConfig::getInstance()->getAppConfValue(ConfigService::KEY_LEAVE_PERIOD_DEFINED);
        $this->currentLeavePeriod = $this->getLeavePeriodService()->getCurrentLeavePeriod();
        if ($this->isLeavePeriodDefined) {
            $endDateElements = explode(' ', $this->currentLeavePeriod->getEndDateFormatted('F d'));
            $endDate = __($endDateElements[0]) . ' ' . $endDateElements[1];
            $nextPeriodStartDateTimestamp = strtotime('+1 day', strtotime($this->currentLeavePeriod->getEndDate()));
            $startMonthValue = (int) date('m', $nextPeriodStartDateTimestamp);
            $startDateValue = (int) date('d', $nextPeriodStartDateTimestamp);
        } else {
            $endDate = '-';
            $startMonthValue = 0;
            $startDateValue = 0;
        }

        $this->endDate = $endDate;
        $this->startMonthValue = $startMonthValue;
        $this->startDateValue = $startDateValue;

        if ($this->getUser()->hasFlash('templateMessage')) {
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }

        // this section is for saving leave period
        if ($request->isMethod('post')) {
            $leavePeriodService = $this->getLeavePeriodService();

            $this->form->bind($request->getParameter($this->form->getName()));
            if($this->form->isValid()) {

                $this->_setLeapYearLeavePeriodDetails($this->form);
                $leavePeriodDataHolder = $this->_getPopulatedLeavePeriodDataHolder();
                $fullStartDate = $leavePeriodService->generateStartDate($leavePeriodDataHolder);

                $leavePeriodDataHolder->setLeavePeriodStartDate($fullStartDate);
                $fullEndDate = $leavePeriodService->generateEndDate($leavePeriodDataHolder);
                $currentLeavePeriod = $leavePeriodService->getCurrentLeavePeriod();
                
                $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));

                if (!is_null($currentLeavePeriod)) {
                    $leavePeriodService->adjustCurrentLeavePeriod($fullEndDate);
                } else {

                    $leavePeriod = new LeavePeriod();
                    $leavePeriod->setStartDate($fullStartDate);
                    $leavePeriod->setEndDate($fullEndDate);
                    $leavePeriodService->saveLeavePeriod($leavePeriod);
                }

                $this->redirect('leave/defineLeavePeriod');
            }
        }
    }

    private function _setLeapYearLeavePeriodDetails(sfForm $form) {

        $post   =	$form->getValues();
        if ($post['cmbStartMonth'] == 2 &&
                $post['cmbStartDate'] == 29) {

            $nonLeapYearLeavePeriodStartDate = $post['cmbStartMonthForNonLeapYears'];
            $nonLeapYearLeavePeriodStartDate .= '-';
            $nonLeapYearLeavePeriodStartDate .= $post['cmbStartDateForNonLeapYears'];

            ParameterService::setParameter('nonLeapYearLeavePeriodStartDate', $nonLeapYearLeavePeriodStartDate);
            ParameterService::setParameter('isLeavePeriodStartOnFeb29th', 'Yes');
            ParameterService::setParameter('leavePeriodStartDate', '');
        } else {

            $leavePeriodStartDate = $post['cmbStartMonth'];
            $leavePeriodStartDate .= '-';
            $leavePeriodStartDate .= $post['cmbStartDate'];

            ParameterService::setParameter('leavePeriodStartDate', $leavePeriodStartDate);
            ParameterService::setParameter('nonLeapYearLeavePeriodStartDate', '');
            ParameterService::setParameter('isLeavePeriodStartOnFeb29th', 'No');
        }
    }

    private function _getPopulatedLeavePeriodDataHolder() {

        $leavePeriodDataHolder = new LeavePeriodDataHolder();

        $isLeavePeriodStartOnFeb29th = ParameterService::getParameter('isLeavePeriodStartOnFeb29th');
        $nonLeapYearLeavePeriodStartDate = ParameterService::getParameter('nonLeapYearLeavePeriodStartDate');
        $leavePeriodStartDate = ParameterService::getParameter('leavePeriodStartDate');


        $leavePeriodDataHolder->setIsLeavePeriodStartOnFeb29th($isLeavePeriodStartOnFeb29th);
        $leavePeriodDataHolder->setNonLeapYearLeavePeriodStartDate($nonLeapYearLeavePeriodStartDate);
        $leavePeriodDataHolder->setStartDate($leavePeriodStartDate);
        $leavePeriodDataHolder->setDateFormat('Y-m-d');
        $leavePeriodDataHolder->setCurrentDate(date('Y-m-d'));

        return $leavePeriodDataHolder;
    }
}
?>
