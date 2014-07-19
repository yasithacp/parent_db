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

class PerformanceKpi{
	
		public $id ;
		public $kpi;
        public $minRate ;
        public $maxRate;
        public $rate ;
		public $comment;

        
        function __construct() {
        }

        public function getId()
        {
        	return $this->id;
        }
        
        public function setId( $id)
        {
        	$this->id	=	$id ;
        }
        
        public function getKpi() {
            return $this->kpi;
        }

        public function setKpi($kpi) {
            $this->kpi = $kpi;
        }

        public function getMinRate() {
            return $this->minRate;
        }

        public function setMinRate($minRate) {
            $this->minRate = $minRate;
        }

        public function getMaxRate() {
            return $this->maxRate;
        }

        public function setMaxRate($maxRate) {
            $this->maxRate = $maxRate;
        }

        public function getRate() {
            return $this->rate;
        }

        public function setRate($rate) {
            $this->rate = $rate;
        }

        public function getComment() {
            return $this->comment;
        }

        public function setComment($comment) {
            $this->comment = htmlspecialchars($comment);
        }

       

}