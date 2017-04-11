<?php 

/**
 * @see adodb.inc.php
 * Call Adodb library
 */
require_once 'Efy/adodb/adodb.inc.php';

/**
 *
 */
class Efy_DB_Connection extends Zend_Db {	
	/**
	 * 
	 */
	public static function connectADO($adapter, $config = array()){		
		global $adoConn, $ADODB_CACHE_DIR, $ADODB_CACHE_TIMEOUT;
		if($adapter == "MSSQL"){//Ket noi MS SQL server
			//Tao doi tuong ADODB
			$adoConn = NewADOConnection("ado_mssql");  // create a connection
			$connStr = "Provider=SQLOLEDB; Data Source=" . $config['host'] . ";Initial Catalog='" . $config['dbname'] . "'; User ID=" . $config['username'] . "; Password=" .$config['password'];
			//call connect adodb
			$adoConn->Connect($connStr) or die("Hien tai he thong khong the ket noi vao CSDL duoc!");
		}
		$ADODB_CACHE_DIR  		= $config['pathAdoCache'];
		$ADODB_CACHE_TIMEOUT 	= $config['cachetimeout'];
		return $adoConn;
	}
	
	/**
	 * Creater: HUNGVM
	 * Date: 
	 * Thuc thi hanh dong update / delete / getsingle / ...
	 * @param $sql : Xau SQL can thuc thi
	 * @return unknown
	 */
	public function adodbExecSqlString($sql){
		global $adoConn;
		$adoConn->SetFetchMode(ADODB_FETCH_ASSOC);
		$ArrSingleData = $adoConn->GetRow($sql); 
		return $ArrSingleData;
	}
	
	/**
	 * Creater: HUNGVM
	 * date:
	 * Lay tat ca thong tin trong CSDL, phan tu dang chi so bat dau tu 0,1,2,...
	 * @param $sql : Xau SQL can thuc thi
	 * @param $optCache : Tuy chon co cache hay khong? <> "" thi thuc hien cache
	 * @return Mang luu thong tin du lieu
	 */
	public function adodbQueryDataInNumberMode($sql, $optCache = ""){
		global $adoConn;
		//Thoi gian Cache
		$adoConn->SetFetchMode(ADODB_FETCH_NUM);
		if ($optCache == ""){
			//echo "1";
			$ArrAllData = $adoConn->GetArray($sql); 
		}else{
			global $ADODB_CACHE_TIMEOUT;
			$cacheTime = $ADODB_CACHE_TIMEOUT;
			$ArrAllData = $adoConn->CacheGetAll($cacheTime,$sql); 
		}
		return $ArrAllData;
	}
	
	/**
	 * Creater: HUNGVM
	 * date:
	 * Lay tat ca thong tin trong CSDL, phan tu dang ten cot
	 * @param $sql : Xau SQL can thuc thi
	 * @param $optCache : Tuy chon co cache hay khong? <> "" thi thuc hien cache
	 * 			
	 * @return Mang luu thong tin du lieu
	 */
	public function adodbQueryDataInNameMode($sql, $optCache = ""){
		global $adoConn;
		//Thoi gian Cache
		$adoConn->SetFetchMode(ADODB_FETCH_ASSOC);
		if ($optCache == ""){
			$ArrAllData = $adoConn->GetArray($sql); 
		}else{
			global $ADODB_CACHE_TIMEOUT;
			$cacheTime = $ADODB_CACHE_TIMEOUT;
			$ArrAllData = $adoConn->CacheGetAll($cacheTime,$sql); 
		}
		return $ArrAllData;
	}
}