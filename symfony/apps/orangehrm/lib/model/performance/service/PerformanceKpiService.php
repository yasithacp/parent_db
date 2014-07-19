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
 * Service Class for Performance Review
 *
 * @author orange
 */
class PerformanceKpiService extends BaseService {
	
	/**
	 * Get XML String from Kpi List
	 * @param $kpiList
	 * @return String
	 */
	public function getXmlFromKpi( $kpiList )
	{
		$xmlString	=	'';
		
		$performanceKpiList	=	$this->getKpiToPerformanceKpi( $kpiList );
		$xmlString			=	$this->getXml( $performanceKpiList );
		return $xmlString;
		
	}
	
	/**
	 * Get XML from Performance Kpi
	 * @param $performanceKpiList
	 * @return unknown_type
	 */
	public function getXml( $performanceKpiList)
	{
		try {
			$xmlStr = '
			<xml>
			</xml>';
	
	 
			$xml = simplexml_load_string($xmlStr);
			
			$kpis	=	$xml->addChild('kpis');
			$escapeHtml = array("&#039;" => "\'", "&" => "&amp;", "<" => "&lt;", ">" => "&gt;", "&#034;" => '\"');
			foreach( $performanceKpiList as $performanceKpi){
				$xmlKpi	=	$kpis->addChild('kpi');
				$xmlKpi->addChild('id',$performanceKpi->getId());
            $desc = $performanceKpi->getKpi();
            foreach($escapeHtml as $char => $str) {
               $desc = str_replace($char, $str, $desc);
            }
				$xmlKpi->addChild('desc',$desc);
				$xmlKpi->addChild('min',$performanceKpi->getMinRate());
				$xmlKpi->addChild('max',$performanceKpi->getMaxRate());
				$xmlKpi->addChild('rate',($performanceKpi->getRate()=='')?' ':$performanceKpi->getRate());
				$xmlKpi->addChild('comment',($performanceKpi->getComment()=='')?' ':$performanceKpi->getComment());
			}
			return $xml->asXML();
		}catch (Exception $e) {
			    throw new PerformanceServiceException($e->getMessage());
		}	  
	}
	
	/**
	 * Get Performance List from XML
	 * @param $xmlString
	 * @return unknown_type
	 */
	public function getPerformanceKpiList( $xmlString )
	{
		try {
			$performanceKpiList	=	array();
			
			$xml = simplexml_load_string($xmlString);
			foreach( $xml->kpis->kpi	as $kpi){
				$performanceKpi	=	new PerformanceKpi();
				$performanceKpi->setId((int)$kpi->id);
				$performanceKpi->setKpi((string)$kpi->desc);
				$performanceKpi->setMinRate((string)$kpi->min);
				$performanceKpi->setMaxRate((string)$kpi->max);
				$performanceKpi->setRate((string)$kpi->rate);
				$performanceKpi->setComment((string)$kpi->comment);
				array_push($performanceKpiList,$performanceKpi);
			}
			return $performanceKpiList;
		}catch (Exception $e) {
			throw new PerformanceServiceException($e->getMessage());
		}	  
		
	}
	
	/**
	 * Get Performance Kpi 
	 * @return unknown_type
	 */
	private function getKpiToPerformanceKpi( $kpiList)
	{
		try {
			
			$performanceKpiList	=	array();
			foreach ($kpiList as $kpi) {
				$performanceKpi	=	new PerformanceKpi();
				$performanceKpi->setId( $kpi->getId());
		    	$performanceKpi->setKpi( $kpi->getDesc());
		    	$performanceKpi->setMinRate( $kpi->getMin());
		    	$performanceKpi->setMaxRate( $kpi->getMax());
		    	array_push($performanceKpiList,$performanceKpi);
			}
			return $performanceKpiList;
		} catch (Exception $e) {
		    throw new PerformanceServiceException($e->getMessage());
		}	    
	}


}