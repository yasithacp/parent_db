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

class Company{
	
		public $comCode ;
		public $comapanyName;
        public $country ;
        public $street1;
        public $street2 ;
		public $taxId;
		public $phone;
		public $fax;
        public $naics;
        public $city;
        public $state;
        public $zipCode;
        public $comments;
        public $empCount ;
        
        function __construct() {
        }

        public function getComCode()
        {
        	return $this->comCode;
        }
        
        public function setComCode( $comCode)
        {
        	$this->comCode	=	$comCode ;
        }
        
        public function getComapanyName() {
            return $this->comapanyName;
        }

        public function setComapanyName($comapanyName) {
            $this->comapanyName = $comapanyName;
        }

        public function getCountry() {
            return $this->country;
        }

        public function setCountry($country) {
            $this->country = $country;
        }

        public function getStreet1() {
            return $this->street1;
        }

        public function setStreet1($street1) {
            $this->street1 = $street1;
        }

        public function getStreet2() {
            return $this->street2;
        }

        public function setStreet2($street2) {
            $this->street2 = $street2;
        }

        public function getTaxId() {
            return $this->taxId;
        }

        public function setTaxId($taxId) {
            $this->taxId = $taxId;
        }

        public function getPhone() {
            return $this->phone;
        }

        public function setPhone($phone) {
            $this->phone = $phone;
        }

        public function getFax() {
            return $this->fax;
        }

        public function setFax($fax) {
            $this->fax = $fax;
        }

        public function getNaics() {
            return $this->naics;
        }

        public function setNaics($naics) {
            $this->naics = $naics;
        }

        public function getCity() {
            return $this->city;
        }

        public function setCity($city) {
            $this->city = $city;
        }

        public function getState() {
            return $this->state;
        }

        public function setState($state) {
            $this->state = $state;
        }

        public function getZipCode() {
            return $this->zipCode;
        }

        public function setZipCode($zipCode) {
            $this->zipCode = $zipCode;
        }

        public function getComments() {
            return $this->comments;
        }

        public function setComments($comments) {
            $this->comments = $comments;
        }
        
        public function getEmpCount()
        {
        	return $this->empCount ;
        }
        
        public function setEmpCount( $count)
        {
        	$this->empCount	=	$count ;
        }


}