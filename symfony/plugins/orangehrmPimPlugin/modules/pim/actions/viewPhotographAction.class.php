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

class viewPhotographAction extends basePimAction {

    private $employeeService;

    /**
     * Get EmployeeService
     * @returns EmployeeService
     */
    public function getEmployeeService() {
        if (is_null($this->employeeService)) {
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
     * @param sfForm $form
     * @return
     */
    public function setForm(sfForm $form) {
        if (is_null($this->form)) {
            $this->form = $form;
        }
    }

    public function execute($request) {

        $loggedInEmpNum = $this->getUser()->getEmployeeNumber();
        $this->showBackButton = true;
        $picture = $request->getPostParameters();
        $empNumber = (isset($picture['emp_number'])) ? $picture['emp_number'] : $request->getParameter('empNumber');
        $this->empNumber = $empNumber;

        if (!$this->IsActionAccessible($empNumber)) {
            $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
        }

        //hiding the back button if its self ESS view
        if ($loggedInEmpNum == $empNumber) {

            $this->showBackButton = false;
        }

        //as part of making users childish by hiding delete button
        $employeeService = $this->getEmployeeService();
        $empPicture = $employeeService->getPicture($empNumber);
        $this->showDeleteButton = 1;

        if (!$empPicture instanceof EmpPicture) {
            $this->showDeleteButton = 0;
        }

        $param = array('empNumber' => $empNumber);
        $this->setForm(new EmployeePhotographForm(array(), $param, true));
        $this->fileModify = 0;
        $this->newWidth = 0;
        $this->newHeight = 0;

        //this is for saving a picture
        if ($request->isMethod('post')) {

            $this->form->bind($request->getPostParameters(), $request->getFiles());
            $photoFile = $request->getFiles();

            //in case if file size exceeds 1MB
            if ($photoFile['photofile']['size'] == 0 || $photoFile['photofile']['size'] > 1000000) {

                $this->messageType = "warning";
                $this->message = __(TopLevelMessages::FILE_SIZE_SAVE_FAILURE);
            }

            if ($this->form->isValid()) {

                $fileType = $photoFile['photofile']['type'];

                $allowedImageTypes[] = "image/gif";
                $allowedImageTypes[] = "image/jpeg";
                $allowedImageTypes[] = "image/jpg";
                $allowedImageTypes[] = "image/pjpeg";
                $allowedImageTypes[] = "image/png";
                $allowedImageTypes[] = "image/x-png";

                if (!in_array($fileType, $allowedImageTypes)) {

                    $this->messageType = "warning";
                    $this->message = __(TopLevelMessages::FILE_TYPE_SAVE_FAILURE);
                } else {

                    list($width, $height) = getimagesize($photoFile['photofile']['tmp_name']);

                    //flags from server
                    $this->fileModify = 1;
                    $this->showDeleteButton = 1;

                    $this->pictureSizeAdjust($height, $width);
                    $this->saveEmployeePicture($empNumber, $photoFile);
                    $this->messageType = "success";
                    $this->message = __('Successfully Uploaded');
                }
            }
        }

        //this is for deleting a picture
        if ($request->getParameter('option') == "delete") {

            $employeeService = $this->getEmployeeService();
            $employeeService->deletePhoto($empNumber);

            $this->showDeleteButton = 0;
            $this->fileModify = 1;

            //set default picture size
            $this->newWidth = 150;
            $this->newHeight = 176;

            $this->messageType = "success";
            $this->message = __(TopLevelMessages::DELETE_SUCCESS);
        }
    }

    private function saveEmployeePicture($empNumber, $file) {

        $employeeService = $this->getEmployeeService();
        $empPicture = $employeeService->getPicture($empNumber);

        if (!$empPicture instanceof EmpPicture) {
            $empPicture = new EmpPicture();
            $empPicture->emp_number = $empNumber;
        }

        $empPicture->picture = file_get_contents($file['photofile']['tmp_name']);
        $empPicture->filename = $file['photofile']['name'];
        $empPicture->file_type = $file['photofile']['type'];
        $empPicture->size = $file['photofile']['size'];
        $empPicture->width = $this->newWidth;
        $empPicture->height = $this->newHeight;
        $empPicture->save();
    }

    private function pictureSizeAdjust($imgHeight, $imgWidth) {

        if ($imgHeight > 180 || $imgWidth > 150) {
            $newHeight = 0;
            $newWidth = 0;

            $propHeight = floor(($imgHeight / $imgWidth) * 150);
            $propWidth = floor(($imgWidth / $imgHeight) * 180);

            if ($propHeight <= 180) {
                $newHeight = $propHeight;
                $newWidth = 150;
            }

            if ($propWidth <= 150) {
                $newWidth = $propWidth;
                $newHeight = 180;
            }
        } else {
            if($imgHeight <= 180)
                $newHeight = $imgHeight;
            
            if($imgWidth <= 150)
                $newWidth = $imgWidth;
        }

        $this->newWidth = $newWidth;
        $this->newHeight = $newHeight;
    }

}

?>
