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
 * getAvailableCurrenciesJsonAction action
 */
class getAvailableCurrenciesJsonAction extends basePimAction {

    private $currencyService;
    

    /**
     * Get CurrencyService
     * @returns CurrencyService
     */
    public function getCurrencyService() {
        if(is_null($this->currencyService)) {
            $this->currencyService = new CurrencyService();
        }
        return $this->currencyService;
    }

    /**
     * Set CurrencyService
     * @param CurrencyService $currencyService
     */
    public function setCurrencyService(CurrencyService $currencyService) {
        $this->currencyService = $currencyService;
    }
    
    /**
     * List unassigned currencies for given employee and pay grade
     * @param sfWebRequest $request
     * @return void
     */
    public function execute($request) {
       $this->setLayout(false);
       sfConfig::set('sf_web_debug', false);
       sfConfig::set('sf_debug', false);

       $currencies = array();

       if ($this->getRequest()->isXmlHttpRequest()) {
           $this->getResponse()->setHttpHeader('Content-Type','application/json; charset=utf-8');
       }

       $payGrade = $request->getParameter('paygrade');
       $empNumber = $request->getParameter('empNumber');

       if (!empty($payGrade) && !empty($empNumber)) {

           $employeeService = $this->getEmployeeService();

           // TODO: call method that returns data in array format (or pass parameter)
           $currencies = $employeeService->getAssignedCurrencyList($payGrade, true);
       } else {
           
           // 
           // Return full currency list
           //
           $currencyService = $this->getCurrencyService();
           $currencies = $currencyService->getCurrencyList(true);           
       }
       $currencyArray = array();
       foreach ($currencies as $currency) {
           $currency['currency_name'] = __($currency['currency_name']);
           $currencyArray[] = $currency;
       }
       return $this->renderText(json_encode($currencyArray));
    }

}