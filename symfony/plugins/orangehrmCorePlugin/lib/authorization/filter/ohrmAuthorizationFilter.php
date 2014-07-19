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
 * Description of ohrmAuthorizationFilter
 *
 */
class ohrmAuthorizationFilter extends sfFilter {

    /**
     * Executes the authorization filter.
     *
     * @param sfFilterChain $filterChain A sfFilterChain instance
     */
    public function execute($filterChain) {
        
        $moduleName = $this->context->getModuleName();
        $actionName = $this->context->getActionName();
        
        // disable security on login and secure actions
        if ((sfConfig::get('sf_login_module') == $moduleName) 
                    && (sfConfig::get('sf_login_action') == $actionName)
                || (sfConfig::get('sf_secure_module') == $moduleName) 
                    && (sfConfig::get('sf_secure_action') == $actionName) 
                || ('auth' == $moduleName && 
                            (($actionName == 'retryLogin') || 
                             ($actionName == 'validateCredentials') || 
                             ($actionName == 'logout')))) {
            $filterChain->execute();

            return;
        }        
        

        $logger = Logger::getLogger('filter.ohrmAuthorizationFilter');
        
        // disable security on non-secure actions
        try {
            $secure = $this->context->getController()->getActionStack()->getLastEntry()->getActionInstance() ->getSecurityValue('is_secure');

            if (!$secure || ($secure === "false") || ($secure === "off")) {

                $filterChain->execute();
                return;            
            }
        } catch (Exception $e) {
            $logger->error('Error getting is_secure value for action: ' . $e);            
            $this->forwardToSecureAction();              
        }    

        try {
            $userRoleManager = UserRoleManagerFactory::getUserRoleManager();  
            $this->context->setUserRoleManager($userRoleManager);
            
            $permissions = $userRoleManager->getScreenPermissions($moduleName, $actionName);
        } catch (Exception $e) {                    
            $logger->error('Exception: ' . $e);            
            $this->forwardToSecureAction();                     
        }

        // user does not have read permissions
        if (!$permissions->canRead()) {

            $logger->warn('User does not have access read access to ' . $moduleName . ' - ' . $actionName);
                        
            // the user doesn't have access
            $this->forwardToSecureAction();
        } else {
            // set permissions in context
            $this->context->set('screen_permissions', $permissions);
        }

        // the user has access, continue
        $filterChain->execute();
    }

    /**
     * Forwards the current request to the secure action.
     *
     * @throws sfStopException
     */
    protected function forwardToSecureAction() {
        $this->context->getController()->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));

        throw new sfStopException();
    }

    /**
     * Forwards the current request to the login action.
     *
     * @throws sfStopException
     */
    protected function forwardToLoginAction() {
        $this->context->getController()->forward(sfConfig::get('sf_login_module'), sfConfig::get('sf_login_action'));

        throw new sfStopException();
    }

}

