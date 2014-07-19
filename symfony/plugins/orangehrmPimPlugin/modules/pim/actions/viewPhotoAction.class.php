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
 * Displays Employee Photo
 *
 */
class viewPhotoAction extends sfAction {

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
    
    public function execute($request) {
        $empNumber = $request->getParameter('empNumber');

        $employeeService = $this->getEmployeeService();
        $empPicture = $employeeService->getPicture($empNumber);

        if (!empty($empPicture)) {
            $contents = $empPicture->picture;
            $contentType = $empPicture->file_type;
            $fileSize = $empPicture->size;
            $fileName = $empPicture->filename;
        } else {
            $fileName = 'default_employee_image.gif';
            $tmpName = ROOT_PATH . '/themes/beyondT/pictures/' . $fileName;
            $fp = fopen($tmpName,'r');
            $fileSize = filesize($tmpName);
            $contents = fread($fp, $fileSize);
            $contentType = "image/gif";
            fclose($fp);
        }
        
        $checksum = md5($contents);
        
        // Allow client side cache image unless image checksum changes.
        $eTag = $request->getHttpHeader('If-None-Match');
        
        $response = $this->getResponse();

        if ($eTag == $checksum) {
            $response->setStatusCode('304');
        } else {
            $response->setContentType($contentType);
            $response->setContent($contents);            
        }
        
        // Setting "Pragra" header to null does not prevent it being added automatically
        // Therefore, we set this to "Public" to override "Pragma: no-cache" added because of settings in factory.yml        
        $response->setHttpHeader('Pragma', 'Public');
        
        $response->setHttpHeader('ETag', $checksum);        
        $response->addCacheControlHttpHeader('public, max-age=0, must-revalidate');            
            
        $response->send();

        return sfView::NONE;
    }
}