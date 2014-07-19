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


class ModuleFilter extends sfFilter {

    public function execute($filterChain) {

        /* Populating enabled modules */
        
        $disabledModules = array();
        
        if ($this->getContext()->getUser()->hasAttribute("admin.disabledModules")) {
            
            $disabledModules = $this->getContext()->getUser()->getAttribute("admin.disabledModules");
            
        } else {
            
            $moduleService = new ModuleService();
            $disabledModuleList = $moduleService->getDisabledModuleList();
            
            foreach ($disabledModuleList as $module) {
                $disabledModules[] = $module->getName();
            }
            
            $this->getContext()->getUser()->setAttribute("admin.disabledModules", $disabledModules);
            
        }
        
        /* Checking request with disabled modules */
        
        $request = $this->getContext()->getRequest();
        
        if (in_array($request['module'], $disabledModules)) {
            header("HTTP/1.0 404 Not Found");
            die;
        }
        
        /* Continuing the filter chain */

        $filterChain->execute();
        
    }

}
