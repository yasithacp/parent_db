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


class attachmentsComponent extends sfComponent {

    private $employeeService;

    /**
     * Get EmployeeService
     * @returns EmployeeService
     */
    public function getEmployeeService() {
        if(is_null($this->employeeService)) {
            $this->employeeService = new EmployeeService();
            $this->employeeService->setEmployeeDao(new EmployeeDao());
        }
        return $this->employeeService;
    }

    /**
     * Set EmployeeService
     * @param EmployeeService $employeeService
     */
    public function setEmployeeService(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }    
    
    /**
     * Execute method of component
     * 
     * @param type $request 
     */
    public function execute($request) {       

        $this->attEditPane = false;
        $this->attSeqNO = false;
        $this->attComments = '';
        $this->scrollToAttachments = false;
        
        if ($this->getUser()->hasFlash('attachmentMessage')) {  
            
            $this->scrollToAttachments = true;
            
            list($this->attachmentMessageType, $this->attachmentMessage) = $this->getUser()->getFlash('attachmentMessage');
                       
            if ($this->attachmentMessageType == 'warning') {
                $this->attEditPane = true;
                if ( $this->getUser()->hasFlash('attachmentComments') ) {
                    $this->attComments = $this->getUser()->getFlash('attachmentComments');
                }
                
                if ( $this->getUser()->hasFlash('attachmentSeqNo')) {
                    $tmpNo = $this->getUser()->getFlash('attachmentSeqNo');
                    $tmpNo = trim($tmpNo);
                    if (!empty($tmpNo)) {
                        $this->attSeqNO = $tmpNo;
                    }
                }
            }
        } else {
            $this->attachmentMessageType = '';
            $this->attachmentMessage = '';
        }

        
        $this->employee = $this->getEmployeeService()->getEmployee($this->empNumber);
        $this->attachmentList = $this->getEmployeeService()->getAttachments($this->empNumber, $this->screen);          
        $this->form = new EmployeeAttachmentForm(array(),  array(), true);  
        $this->deleteForm = new EmployeeAttachmentDeleteForm(array(), array(), true);
    }

}

