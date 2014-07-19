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

class addEmployeeAction extends basePimAction {

    private $userService;

    /**
     * @param sfForm $form
     * @return
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    public function execute($request) {
        
        $this->showBackButton = true;
        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();

        //this is to preserve post value if any error occurs
        $postArray = array();
        $this->createUserAccount = 0;

        if($request->isMethod('post')) {
            $postArray = $request->getPostParameters();
            unset($postArray['_csrf_token']);
            $_SESSION['addEmployeePost'] = $postArray;
        }

        if(isset ($_SESSION['addEmployeePost'])) {
            $postArray = $_SESSION['addEmployeePost'];

            if(isset($postArray['chkLogin'])) {
                $this->createUserAccount = 1;
            }
        }
        
        $this->setForm(new AddEmployeeForm(array(), $postArray, true));

        if ($this->getUser()->hasFlash('templateMessage')) {
            unset($_SESSION['addEmployeePost']);
            list($this->messageType, $this->message) = $this->getUser()->getFlash('templateMessage');
        }

        if ($request->isMethod('post')) {

            $this->form->bind($request->getPostParameters(), $request->getFiles());
            $posts = $this->form->getValues();
            $photoFile = $request->getFiles();

            //in case if file size exceeds 1MB
            if($photoFile['photofile']['name'] != "" && ($photoFile['photofile']['size'] == 0 || $photoFile['photofile']['size'] > 1000000)) {
                $this->getUser()->setFlash('templateMessage', array('warning', __(TopLevelMessages::FILE_SIZE_SAVE_FAILURE)));
                $this->redirect('pim/addEmployee');
            }

            //in case a user already exists with same user name
            
            if ($this->createUserAccount) {

                $userService = $this->getUserService();
                $user = $userService->isExistingSystemUser($posts['user_name'],null);

                if($user instanceof SystemUser) {

                    $this->getUser()->setFlash('templateMessage', array('warning', __('Failed To Save: User Name Exists')));
                    $this->redirect('pim/addEmployee');
                }
            }
            
            //if everything seems ok save employee and create a user account
            if ($this->form->isValid()) {

                $this->_checkWhetherEmployeeIdExists($this->form->getValue('employeeId'));
                
                try {

                    $fileType = $photoFile['photofile']['type'];

                    $allowedImageTypes[] = "image/gif";
                    $allowedImageTypes[] = "image/jpeg";
                    $allowedImageTypes[] = "image/jpg";
                    $allowedImageTypes[] = "image/pjpeg";
                    $allowedImageTypes[] = "image/png";
                    $allowedImageTypes[] = "image/x-png";

                    if(!empty($fileType) && !in_array($fileType, $allowedImageTypes)) {
                        $this->getUser()->setFlash('templateMessage', array('warning', __(TopLevelMessages::FILE_TYPE_SAVE_FAILURE)));
                        $this->redirect('pim/addEmployee');
                        
                    } else {
                        unset($_SESSION['addEmployeePost']);
                        $this->form->createUserAccount = $this->createUserAccount;
                        $empNumber = $this->form->save();
                        
                        $this->redirect('pim/viewPersonalDetails?empNumber='. $empNumber);
                    }

                } catch(Exception $e) {
                    print($e->getMessage());
                }
            }
        }
    } 


    private function getUserService() {

        if(is_null($this->userService)) {
            $this->userService = new SystemUserService();
        }

        return $this->userService;
    }

    protected function _checkWhetherEmployeeIdExists($employeeId) {

        if (!empty($employeeId)) {

            $employee = $this->getEmployeeService()->getEmployeeByEmployeeId($employeeId);

            if ($employee instanceof Employee) {
                $this->getUser()->setFlash('templateMessage', array('warning', __('Failed To Save: Employee Id Exists')));
                $this->redirect('pim/addEmployee');
            }

        }

    }

}

