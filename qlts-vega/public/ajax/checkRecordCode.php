<?php
// Dinh nghia duong dan den thu vien cua Zend
	set_include_path('../../library/'
			. PATH_SEPARATOR . '../../application/models/'
			. PATH_SEPARATOR . '../../config/');			
	// Goi class Zend_Load
	include "../../library/Zend/Loader.php";	
	Zend_Loader::loadClass('Zend_Db');	
	Zend_Loader::loadClass('Efy_DB_Connection');
	$conn = new Efy_DB_Connection();
	//Ket noi CSDL SQL theo kieu ADODB
	$connectSQL = new Zend_Config_Ini('../../config/config.ini','dbmssql');
	$registry = Zend_Registry::getInstance();
	$registry->set('connectSQL', $connectSQL);
	$connAdo = Efy_Db_Connection::connectADO($connectSQL->db->adapter,$connectSQL->db->config->toArray());
	//Lay ma ho so
	$sRecordId = $_REQUEST['RecordId'];
	if($sRecordId != '' && $sRecordId != null){	
		$sql = "Select PK_RECORD From T_ECS_RECORD Where C_CODE = '" . $sRecordId . "'";
		//echo $sql; exit;
		try {			
			$arrTempResult = $conn->adodbExecSqlString($sql); 
		}catch (Exception $e){
			echo $e->getMessage();
		}
		if(!is_null($arrTempResult) && $arrTempResult <> '' && sizeof($arrTempResult) > 0){
			echo 'da ton tai ma ho so nay';
		}
	}
?>