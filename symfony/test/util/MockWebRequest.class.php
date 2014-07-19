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


class MockWebRequest extends sfWebRequest {

    protected $getParameters = array();

    protected $postParameters = array();

    protected $method;

    /*
     * Methods to set up mock object
     */
    public function setMethod($method) {
        $this->method = strtoupper($method);
    }

    public function setPostParameters(array $postParameters) {
        $this->postParameters = $postParameters;
    }

    public function setGetParameters(array $getParameters) {
        $this->getParameters = $getParameters;
    }

  /**
   * Class constructor.
   *
   * @see initialize()
   */
    public function __construct() {
    }

    protected function fixParameters() {
    }

    public function getRequestContext() {
    }

    protected function parseRequestParameters() {
    }

    public function checkCSRFProtection() {
    }

    public function getForwardedFor() {
    }

    public function getRemoteAddress() {
    }

    public function getUrlParameter($name, $default = null) {
    }

    public function getPostParameter($name, $default = null) {

        $value = $default;
        if (isset($this->postParameters[$name])) {
            $value = $this->postParameters[$name];
        }
        return $value;
    }

    public function getGetParameter($name, $default = null) {
        $value = $default;
        if (isset($this->getParameters[$name])) {
            $value = $this->getParameters[$name];
        }
        return $value;
    }

    public function getFiles($key = null) {
    }

    public function getRequestFormat() {
    }

    public function setRequestFormat($format) {
    }

    public function setFormat($format, $mimeTypes) {
    }

    public function getFormat($mimeType) {
    }

    public function getMimeType($format) {
    }

    public function getPathInfoArray() {
    }

    public function splitHttpAcceptHeader($header) {
    }

    public function setRelativeUrlRoot($value) {
    }

    public function getRelativeUrlRoot() {
    }

    public function isSecure() {
    }

    public function getCookie($name, $defaultValue = null) {
    }

    public function getHttpHeader($name, $prefix = 'http') {
    }

    public function isXmlHttpRequest() {
    }

    public function getAcceptableContentTypes() {
    }

    public function getCharsets() {
    }

    public function getLanguages() {
    }

    public function getPreferredCulture(array $cultures = null) {
    }

    public function getMethodName() {
        return $this->method;
    }

    public function isMethod($method) {
        return strtoupper($method) == $this->method;
    }

    public function getScriptName() {
    }

    public function getHost() {
    }

    public function getReferer() {
    }

    public function addRequestParameters($parameters) {
    }

    public function getRequestParameters() {
        $requestParameters = array_merge($this->getParameters, $this->postParameters);
        return $requestParameters;
    }

    public function getPostParameters() {
        return $this->postParameters;
    }

    public function getGetParameters() {
        return $this->getParameters;
    }

    public function getPathInfoPrefix() {
    }

    public function getPathInfo() {
    }

    public function getUriPrefix() {
    }

    public function isAbsUri() {
    }

    public function getUri() {
    }

    public function initialize(sfEventDispatcher $dispatcher, $parameters = array(), $attributes = array(), $options = array()) {
    }

    public function getParameter($name, $default = null) {
        $value = $default;

        $requestParameters = array_merge($this->getParameters, $this->postParameters);

        if (isset($requestParameters[$name])) {
            $value = $requestParameters[$name];
        }
        return $value;
    }

}
