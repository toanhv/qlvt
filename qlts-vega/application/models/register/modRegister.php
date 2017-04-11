<?php
/**
 * Create: phongtd
 * idea: Class xu ly dang ky muon tai san
 *
 */
class register_modRegister extends Efy_DB_Connection {	
//Lap trinh lay du lieu	
	public function _registerGetAll($sStaffId,$sStatus,$textsearch, $piPage, $piNumberRecordPerPage){
		$sql = "Exec qlts_register_GetAll "; 
		$sql = $sql . "'" . $sStaffId . "'"; 
		$sql = $sql . ",'" . $sStatus . "'";
		$sql = $sql . ",'" . $textsearch . "'";
		$sql = $sql . ","  . $piPage;
		$sql = $sql . ","  . $piNumberRecordPerPage;
		//echo $sql; 
		try{
			$arrAsset = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrAsset;
	}
	public function _assetRegisterUpdate($arrParameter){
		$psSql = "Exec qlts_register_update ";	
		$psSql .= "'" . $arrParameter['FK_ASSETS'] . "'";
		$psSql .= ",'" . $arrParameter['FK_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['C_STAFF_NAME'] . "'";
		$psSql .= ",'" .  $arrParameter['STAFF_APPROVE_ID'] . "'";
		$psSql .= ",'" . $arrParameter['STAFF_APPROVE_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";			
		$psSql .= ",'" . $arrParameter['C_DETAIL_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['C_WORK_TYPE'] . "'";
		$psSql .= ",'" . $arrParameter['C_CONTENT'] . "'";
		$psSql .= ",'" . $arrParameter['C_DATE'] . "'";
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
	public function _approvedAsset($psObjectId,$pAssetId,$pStaffId,$pStaffPositionName,$pApprovedStatus){
		$sql = "Exec [qlts_approved_update] '" . $psObjectId . "'";
		$sql .= ",'".$pAssetId ."'";
		$sql .= ",'".$pStaffId ."'";
		$sql .= ",'".$pStaffPositionName ."'";
		$sql .= ",'".$pApprovedStatus ."'";
		//echo $sql . '<br>'; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($sql) ; 				
		}catch (Exception $e){
			echo $e->getMessage();
		};	
	}
	public function _approvedUserGetSingle($pAssetId){
		$arrResult = null;
		$sql = "Exec qlts_User_Approved_Getsing ";
		$sql .= "'" . $pAssetId . "'";
		//echo '<br>' . $sql . '<br>';
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}

}