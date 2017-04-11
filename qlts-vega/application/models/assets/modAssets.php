<?php
/**
 * Enter description here...
 *
 */
class assets_modAssets extends Efy_DB_Connection {	
	public function _getAll($staffId, $departmentId, $unitId, $textsearch, $assetId, $piPage, $piNumberRecordPerPage, $status = '', $leftMenu = ''){
		$sql = "Exec qlts_Manager_GetAll ";
		$sql = $sql . "'"  . $staffId . "'";
		$sql = $sql . ",'" . $departmentId . "'";
		$sql = $sql . ",'" . $unitId . "'";
		$sql = $sql . ",'" . $textsearch . "'";
		$sql = $sql . ",'" . $assetId . "'";
		$sql = $sql . ","  . $piPage;
		$sql = $sql . ","  . $piNumberRecordPerPage;
		$sql = $sql . ",'"  . $status . "'";
		//$sql = $sql . ",'"  . $leftMenu . "'";
		//echo $sql; 
		try{
			$arrAsset = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrAsset;
	}
	public function _assetComponetGetAll($assetid){
		$arrResult = null;
		$sql = "Exec [qlts_Componet_GetAll] ";
		$sql .= "'" . $assetid . "'";
		//echo $sql;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function getPropertiesDocument($code){
		$sql = "EfyLib_ListGetAllbyCode ";
		$sql = $sql . "'" . $code . "', ''";
		try {
			$arrSel = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSel;
	}
	public function _assetManageUpdate($arrParameter){
		$psSql = "Exec qlts_Manager_Update ";	
		$psSql .= "'" . $arrParameter['PK_FIXED_ASSETS'] . "'";
		$psSql .= ",'" . $arrParameter['C_CODE'] . "'";
		$psSql .= ",'" . $arrParameter['C_NAME'] . "'";
		$psSql .= ",'" .  $arrParameter['C_TYPE'] . "'";
		$psSql .= ",'" . $arrParameter['C_GROUP'] . "'";
		$psSql .= ",'" . $arrParameter['C_INFO'] . "'";			
		$psSql .= ",'" . $arrParameter['C_VALUE'] . "'";
		$psSql .= ",'" . $arrParameter['C_BEGIN_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_DEPRECIATION_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['C_REGISTER_USERID'] . "'";
		$psSql .= ",'" . $arrParameter['C_REGISTER_USERNAME'] . "'";	
		$psSql .= ",'" . $arrParameter['C_USE_INFO'] . "'";	
		$psSql .= ",'" . $arrParameter['C_USE_INFO_NAME_LIST'] . "'";	
		$psSql .= ",'" . $arrParameter['C_IMAGE'] . "'";	
		$psSql .= ",'" . $arrParameter['C_XML_DATA'] . "'";	
		$psSql .= ",'" . $arrParameter['C_STATUS_ASSETS'] . "'";			
		$psSql .= ",'" . $arrParameter['C_DETAIL_ASSETS'] . "'";				
		$psSql .= ",'" . $arrParameter['WORKTYPE'] . "'";						
		$psSql .= ",'" . $arrParameter['APPROVED_ID'] . "'";					
		$psSql .= ",'" . $arrParameter['APPROVED_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['REGISTER_DATE'] . "'";	
		$psSql .= ",'" . $arrParameter['REGISTER_ID'] . "'";					
		$psSql .= ",'" . $arrParameter['REGISTER_NAME'] . "'";	
		$psSql .= ",'" . $arrParameter['C_NUMBER'] . "'";	
		//Thuc thi lenh SQL		
		//echo '<br><br>' . $psSql . '<br><br>'; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql); 
			$Result = $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
	public function _assetFluctuationsUpdate($arrParameter){
		$psSql = "Exec [qlts_Asset_Fluctuations] ";	
		$psSql .= "'" . $arrParameter['TABLE'] . "'";
		$psSql .= ",'" . $arrParameter['TABLE_PK'] . "'";
		$psSql .= ",'" . $arrParameter['ASSETPK'] . "'";
		$psSql .= ",'" .  $arrParameter['ASSETFK'] . "'";
		$psSql .= ",'" . $arrParameter['C_VALUE'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";			
		$psSql .= ",'" . $arrParameter['C_INFO'] . "'";
		$psSql .= ",'" . $arrParameter['C_BEGIN_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_DEPRECIATION_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_USE_INFO'] . "'";
		$psSql .= ",'" . $arrParameter['C_USE_INFO_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['STAFF_ID'] . "'";	
		$psSql .= ",'" . $arrParameter['STAFF_NAME'] . "'";	
		$psSql .= ",'" . $arrParameter['C_WORK'] . "'";	
		$psSql .= ",'" . $arrParameter['C_CONTENT'] . "'";		
		$psSql .= ",'" . $arrParameter['FILENAME'] . "'";			
		//Thuc thi lenh SQL		
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql); 
			$Result = $arrTempResult['NEW_ID'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;	
	}
	public function _assetComponetUpdate($arrParameter){
		$psSql = "Exec qlts_Asset_Component_Update ";	
		$psSql .= "'" . $arrParameter['PK_SUB_ASSETS'] . "'";
		$psSql .= ",'" . $arrParameter['C_CODE'] . "'";
		$psSql .= ",'" . $arrParameter['C_NAME'] . "'";
		$psSql .= ",'" .  $arrParameter['C_TYPE'] . "'";
		$psSql .= ",'" . $arrParameter['C_GROUP'] . "'";
		$psSql .= ",'" . $arrParameter['C_INFO'] . "'";			
		$psSql .= ",'" . $arrParameter['C_VALUE'] . "'";
		$psSql .= ",'" . $arrParameter['C_BEGIN_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_DEPRECIATION_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['STAFF_ID'] . "'";
		$psSql .= ",'" . $arrParameter['STAFF_NAME'] . "'";	
		$psSql .= ",'" . $arrParameter['FK_FIXED_ASSETS'] . "'";
		$psSql .= ",'" . $arrParameter['C_IMAGE'] . "'";	
		$psSql .= ",'" . $arrParameter['C_XML_DATA'] . "'";	
		$psSql .= ",'" . $arrParameter['C_NUMBER'] . "'";	
		//Thuc thi lenh SQL		
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql); 
			$Result = $arrTempResult['NEW_ID'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
	public function _assetGetSingle($assetid, $table, $key){
		$arrResult = null;
		$sql = "Exec qlts_Manager_GetSingle ";
		$sql .= "'" . $assetid . "'";
		$sql .= ",'" . $table . "'";
		$sql .= ",'" . $key . "'";
		//echo '<br>' . $sql . '<br>';
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function _assetGetStaffRegister($assetid){
		$arrResult = null;
		$sql = "Exec qlts_Shopping_staff_register ";
		$sql .= "'" . $assetid . "'";
		//echo '<br>' . $sql . '<br>';
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function _deleteAsset($psObjectList, $table, $id){
		$Result = null;		
		$sql = "Exec [qlts_Delete] '" . $psObjectList . "'";
		$sql .= ",'".$table ."'";		
		$sql .= ",'".$id ."'";	
		//echo $sql . '<br>'; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($sql) ; 				
		}catch (Exception $e){
			echo $e->getMessage();
		};	
	}
	/**
	 * Creater : TOANHV
	 * Date : 16/09/2009
	 * Idea : Tao phuong thuc lay danh sach file dinh kem cua mot ho so thuoc giai doan
	 *
	 * @param $pRecord : Id cua ho so
	 * @param $pFileTyle : loai van ban
	 * @param $pTableObject : Ten bang chua ID (PK) 
	 * @return Mang chua file dinh kem cua ho so
	 */
	public function DOC_GetAllDocumentFileAttach($pSent, $pFileTyle, $pTableObject){
		$sql = "Exec Doc_GetAllDocumentFileAttach '" . $pSent . "'";
		$sql .= ",'".$pFileTyle ."'";		
		$sql .= ",'".$pTableObject ."'"; 
		//echo $sql . '<br>';
		try {						
			$arrResult = $this->adodbQueryDataInNameMode($sql);					
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $arrResult;			
	}
	public function _getAll_Asset(){
		$arrResult = null;
		$sql = "Exec [qlts_getAll_Asset] ";		
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function _assetFluctuationsGetall($assetid){
		$arrResult = null;
		$sql = "Exec [qlts_Fluctuations_getAll] ";
		$sql .= "'" . $assetid . "'";
		//echo $sql;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function _assetShoppingUpdate($arrParameter){
		$psSql = "Exec qlts_Manager_Update ";	
		$psSql .= "'" . $arrParameter['PK_FIXED_ASSETS'] . "'";
		$psSql .= ",'" . $arrParameter['C_CODE'] . "'";
		$psSql .= ",'" . $arrParameter['C_NAME'] . "'";
		$psSql .= ",'" .  $arrParameter['C_TYPE'] . "'";
		$psSql .= ",'" . $arrParameter['C_GROUP'] . "'";
		$psSql .= ",'" . $arrParameter['C_INFO'] . "'";			
		$psSql .= ",'" . $arrParameter['C_VALUE'] . "'";
		$psSql .= ",'" . $arrParameter['C_BEGIN_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_DEPRECIATION_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['C_REGISTER_USERID'] . "'";
		$psSql .= ",'" . $arrParameter['C_REGISTER_USERNAME'] . "'";	
		$psSql .= ",'" . $arrParameter['C_USE_INFO'] . "'";	
		$psSql .= ",'" . $arrParameter['C_USE_INFO_NAME_LIST'] . "'";	
		$psSql .= ",'" . $arrParameter['C_IMAGE'] . "'";	
		$psSql .= ",'" . $arrParameter['C_XML_DATA'] . "'";	
		//Thuc thi lenh SQL		
		//echo '<br><br>' . htmlspecialchars($psSql) . '<br><br>'; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql); 
			$Result = $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
	public function _assetUserGetAll($assetid){
		$arrResult = null;
		$sql = "Exec [qlts_Search_User_GetAll] ";
		$sql .= "'" . $assetid . "'";
		//echo '<br>' . $sql . '<br>';
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function _progressGetAll($assetid){
		$arrResult = null;
		$sql = "Exec qlts_Shopping_progress_getall ";
		$sql .= "'" . $assetid . "'";
		//echo '<br>' . $sql . '<br>';
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	public function _assetShoppingApprove($staff_id_list, $staff_name_list, $hdn_asset_id, $status_list, $detail_list, $workType, $content, $staffId, $staffName, $date, $dilimiter){
		$psSql = "Exec [qlts_shopping_update] ";	
		$psSql .= "'" . $staff_id_list . "'";
		$psSql .= ",'" . $staff_name_list . "'";
		$psSql .= ",'" . $hdn_asset_id . "'";
		$psSql .= ",'" . $status_list . "'";
		$psSql .= ",'" . $detail_list . "'";
		$psSql .= ",'" . $workType . "'";			
		$psSql .= ",'" . $content . "'";
		$psSql .= "," . $staffId;
		$psSql .= ",'" . $staffName . "'";
		$psSql .= ",'" . $date . "'";
		$psSql .= ",'" . $dilimiter . "'";
		//echo $psSql; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql); 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}
}