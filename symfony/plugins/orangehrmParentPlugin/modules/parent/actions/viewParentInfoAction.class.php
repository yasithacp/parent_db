<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yasitha
 * Date: 7/20/14
 * Time: 12:09 AM
 * To change this template use File | Settings | File Templates.
 */

class viewParentInfoAction extends sfAction {

    private $parentDao;

    /**
     * Get CandidateService
     * @returns CandidateService
     */
    public function getParentDao() {
        if (is_null($this->parentDao)) {
            $this->parentDao = new ParentDao();
        }
        return $this->parentDao;
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

        $usrObj = $this->getUser()->getAttribute('user');

        if (!$usrObj->isAdmin()) {
            $this->redirect('recruitment/viewCandidates');
        }

        $isPaging = $request->getParameter('pageNo');
        $parentInfoId = $request->getParameter('parentInfoId');

        $pageNumber = $isPaging;
        if(!is_null($this->getUser()->getAttribute('parentInfoPageNumber')) && !($pageNumber >= 1)) {
            $pageNumber = $this->getUser()->getAttribute('parentInfoPageNumber');
        }
        $this->getUser()->setAttribute('parentInfoPageNumber', $pageNumber);

        $sortField = $request->getParameter('sortField');
        $sortOrder = $request->getParameter('sortOrder');
        $noOfRecords = StudentParentInformation::NUMBER_OF_RECORDS_PER_PAGE;
        $offset = ($pageNumber >= 1) ? (($pageNumber - 1)*$noOfRecords) : ($request->getParameter('pageNo', 1) - 1) * $noOfRecords;

        $this->setForm(new ViewParentInfoForm());

        $srchParams = array('stuName' => "", 'stuIndexNo' => "", 'dadOccupation' => "", 'momOccupation' => "");
        $srchParams['noOfRecords'] = $noOfRecords;
        $srchParams['offset'] = $offset;

        if (!empty($sortField) && !empty($sortOrder) || $parentInfoId > 0 || $isPaging > 0) {
            if ($this->getUser()->hasAttribute('searchParameters')) {
                $srchParams = $this->getUser()->getAttribute('searchParameters');
                $this->form->setDefaultDataToWidgets($srchParams);
            }
            $srchParams['orderField'] = $sortField;
            $srchParams['orderBy'] = $sortOrder;
        } else {
            $this->getUser()->setAttribute('searchParameters', $srchParams);
        }

        list($this->messageType, $this->message) = $this->getUser()->getFlash('parentDeletionMessageItems');
        $srchParams['offset'] = $offset;
        $parentInfoList = $this->getParentDao()->searchParents($srchParams);

        $this->_setListComponent($parentInfoList, $noOfRecords, $srchParams, $pageNumber);
        $params = array();
        $this->parmetersForListCompoment = $params;

        if (empty($isPaging)) {
            if ($request->isMethod('post')) {

                $pageNumber = 1;
                $this->getUser()->setAttribute('parentInfoPageNumber', $pageNumber);
                $this->form->bind($request->getParameter($this->form->getName()));

                if ($this->form->isValid()) {
                    $srchParams = $this->form->getSearchParamsBindwithFormData();
                    $srchParams['noOfRecords'] = $noOfRecords;
                    $srchParams['offset'] = 0;
                    $this->getUser()->setAttribute('searchParameters', $srchParams);
                    $parentInfoList = $this->getParentDao()->searchParents($srchParams);
                    $this->_setListComponent($parentInfoList, $noOfRecords, $srchParams, $pageNumber);
                }
            }
        }


    }

    /**
     * @param $parentInfoList
     * @param $noOfRecords
     * @param $srchParams
     * @param $pageNumber
     */
    private function _setListComponent($parentInfoList, $noOfRecords, $srchParams, $pageNumber) {
        $configurationFactory = new ParentInfoHeaderFactory();
        ohrmListComponent::setPageNumber($pageNumber);
        ohrmListComponent::setConfigurationFactory($configurationFactory);
        ohrmListComponent::setListData($parentInfoList);
        ohrmListComponent::setItemsPerPage($noOfRecords);
        ohrmListComponent::setNumberOfRecords($this->getParentDao()->searchParentsCount($srchParams));
    }
}