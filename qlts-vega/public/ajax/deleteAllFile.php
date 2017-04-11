<?php
// Dinh nghia duong dan den thu vien cua Zend
	set_include_path('../../library/'
			. PATH_SEPARATOR . '../../application/models/'
			. PATH_SEPARATOR . '../../config/');			
	// Goi class Zend_Load
	include "../../library/Zend/Loader.php";	
	Zend_Loader::loadClass('Zend_Db');	
	Zend_Loader::loadClass('Efy_DB_Connection');
	$sConfig = new Efy_Init_Config;
	$conn = new Efy_DB_Connection();
	//Ket noi CSDL SQL theo kieu ADODB
	$connectSQL = new Zend_Config_Ini('../../config/config.ini','dbmssql');
	$registry = Zend_Registry::getInstance();
	$registry->set('connectSQL', $connectSQL);
	$connAdo = Efy_Db_Connection::connectADO($connectSQL->db->adapter,$connectSQL->db->config->toArray());
	//Lay ten file 
	$sListIdDoc     		= $_REQUEST['ListIdDoc'];
	$sDocType				= $_REQUEST['DocType'];
	$sTableName      		= $_REQUEST['TableName'];
	$sdelimitor      		= $_REQUEST['delimitor'];
	//Lay danh sach ten file dinh kem
	$sql = "Exec ECS_GetFileNameInDoc ";
		$sql .= "'" . $sListIdDoc . "'";
		$sql .= ",'" . $sDocType . "'";
		$sql .= ",'" . $sTableName . "'";
		$sql .= ",'" . $sdelimitor . "'";
	try {						
		$arrResult = $conn->adodbQueryDataInNameMode($sql);					
	}catch (Exception $e){
		echo $e->getMessage();
	};	
	if($arrResult != null && $arrResult != ''){
		for($index = 0;$index < sizeof($arrResult);$index++){
			//xoa file tren o cung
			$arrFileName = explode($sdelimitor, $arrResult[$index]['C_FILE_NAME']);
			$scriptUrl = $_SERVER['SCRIPT_FILENAME'];
			$scriptFileName = explode("/", $scriptUrl);
			$k = 3;
			$sWebsitePart = $sConfig ->_setWebSitePath();
			$sWebsitePart = substr($sWebsitePart,1,-1);
			$linkFile = "";
			for($i= 0;$i< sizeof($scriptFileName);$i++){
				if($scriptFileName[$i] == $sWebsitePart){
					$k = $i;
					break;
				}
			}
			for($j = 0; $j <=$k; $j++ ){
				$linkFile .= $scriptFileName[$j]."\\";
			}
			$linkFile .= "public\attach-file\\";
			for($i =0; $i < sizeof($arrFileName); $i ++){
				$fileId = explode("!~!", $arrFileName[$i]);
				$fileId = explode("_" ,$fileId[0]);
				$unlink = $linkFile . $fileId[0] . "\\" . $fileId[1] . "\\" . "\\" . $fileId[2] . "\\" . $arrFileName[$i];
				unlink($unlink);
			}
			$sql = "Exec [_deleteFileUpload] '" . $arrResult[$index]['C_FILE_NAME'] . "'";
			try {			
				$arrTempResult = $conn->adodbExecSqlString($sql); 
			}catch (Exception $e){
				echo $e->getMessage();
			}
		}
	}
?>