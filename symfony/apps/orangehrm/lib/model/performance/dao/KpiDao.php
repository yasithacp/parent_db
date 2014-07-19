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
 * Kpi Dao class 
 *
 * @author Samantha Jayasinghe
 */
class KpiDao extends BaseDao {
	
	
	/**
	 * Save Kpi
	 * 
	 * @param DefineKpi $Kpi
	 * @return DefineKpi
	 */
	public function saveKpi(DefineKpi $Kpi) {
		try {
			if( $Kpi->getId() == ''){
				$idGenService = new IDGeneratorService ( );
				$idGenService->setEntity ( $Kpi );
				$Kpi->setId ( $idGenService->getNextID () );
			}
			$Kpi->save ();
			
			return $Kpi;
        } catch ( Doctrine_Validator_Exception $e ) {
            // propagate validator exceptions
            throw $e;
		} catch ( Exception $e ) {
			throw new DaoException ( $e->getMessage () );
		}
	}
	
	/**
	 * Read kpi 
	 * @param $defineKpiId
	 * @return DefineKpi Array
	 */
	public function readKpi($defineKpiId){
		try {
			$defineKpis = Doctrine::getTable ( 'DefineKpi' )
			->find ( $defineKpiId );
			return $defineKpis;
		} catch ( Exception $e ) {
			throw new DaoException ( $e->getMessage () );
		}
	}
	
	/**
	 * Delete Kpi
	 * @param $DefineKpiList
	 * @return none
	 */
	public function deleteKpi($DefineKpiList) {
		try {
			$q = Doctrine_Query::create ()
			->delete ( 'DefineKpi' )
			->whereIn ( 'id', $DefineKpiList );
			$numDeleted = $q->execute ();
			
			return true ;
			
		} catch ( Exception $e ) {
			throw new DaoException ( $e->getMessage () );
		}
	}
	
    /**
     * Get KPI List
     * @return unknown_type
     */
    public function getKpiList( $offset=0,$limit=10){
    	try{
	    	$q = Doctrine_Query::create()
			    ->from('DefineKpi kpi')
			    ->orderBy('kpi.desc');
			
			$q->offset($offset)->limit($limit);
			
			$kpiList = $q->execute();  
			return  $kpiList ;
			
        }catch( Exception $e){
            throw new DaoException ( $e->getMessage () );
        }
    }
    
    /**
     * Get KPI count list
     * @return unknown_type
     */
    public function getCountKpiList( ){
    	try{
	    	$count = Doctrine_Query::create()
			    		->from('DefineKpi kpi')
			    		->count();
			
			return  $count ;
			
        }catch( Exception $e){
            throw new DaoException ( $e->getMessage () );
        }
    }
    
	/**
	 * Get Kpi default rating scale
	 * 
	 * @return Int
	 */
	public function getKpiDefaultRate() {
		
		$defaultRate	=	array();
		try {
			$q = Doctrine_Query::create ()
			->select ( 'kpi.rate_min, kpi.rate_max' )
			->from ( "DefineKpi kpi" )
			->where ( "kpi.rate_default = 1" );
			
			$defaultRate = $q->fetchOne();
			
			return $defaultRate;
			
		} catch ( Exception $e ) {
			throw new DaoException ( $e->getMessage () );
		}
		
		
	}
	
	/**
	 * overrides kpi default rating scale
	 * 
	 * @return boolean
	 */
	public function overRideKpiDefaultRate( DefineKpi $Kpi) {
		try {
			
				$q = Doctrine_Query::create ()
				->update ( 'DefineKpi' )
				->set ( 'DefineKpi.default', '0' )
				->whereNotIn('DefineKpi.id',array($Kpi->getId()));
				$q->execute ();
			
			return true ;
			
		} catch ( Exception $e ) {
			throw new DaoException ( $e->getMessage () );
		}
	}
	
	/**
	 * Delete Kpi for job title
	 * 
	 * @return boolean
	 */
	public function deleteKpiForJobTitle( $toJobTitleCode ){
		try{
	    	
        	$q = Doctrine_Query::create ()
			->delete ( 'DefineKpi' )
			->where ( "jobtitlecode='$toJobTitleCode'"  );
			$numDeleted = $q->execute ();
			
			return true;
			   
        }catch( Exception $e){
            throw new DaoException($e->getMessage());
        }
	}
	
	/**
     * Get Kpi for Job Title
     * 
     * @param int $jobTitleId
     * @return DefineKpi KpiList
     */
    public function getKpiForJobTitle( $jobTitleId ){
    	try{
	    	
        	$q = Doctrine_Query::create( )
				    ->from('DefineKpi kpi') 
				    ->where("kpi.job_title_code='".$jobTitleId."' AND kpi.is_active = '1'" );
				    
			$kpiList = $q->execute();
			
			return $kpiList;
			   
        }catch( Exception $e){
            throw new DaoException($e->getMessage());
        }
    }
    

}