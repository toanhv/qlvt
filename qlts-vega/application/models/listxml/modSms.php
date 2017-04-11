<?php

class Sms_modSms extends Efy_DB_Connection {
	
	/**
	 * Nguoi tao: NGHIAT
	 * Ngay tao: 13/09/2010 
	 *  Lay danh sach cac can bo va cong viec tuong ung
	 */
	public function docSmsReminderGetAll($sStaffIdList,$sDepartmentIdList,$iOwnerId,$sRoleLeaderList,$piCurrentPage,$piNumRowOnPage){
		$sql = "Doc_DocSmsReminderGetAll  ";
		$sql = $sql . "'" . $sStaffIdList . "'";
		$sql = $sql . ",'" . $sDepartmentIdList . "'";
		$sql = $sql . ",'" . $iOwnerId . "'";
		$sql = $sql . ",'" . $sRoleLeaderList . "'";
		$sql = $sql . ",'" . $piCurrentPage . "'";
		$sql = $sql . ",'" . $piNumRowOnPage . "'";
		//echo $sql; //exit;
		try{
			$arrSms = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSms;
	}
	/**
	 * Nguoi tao: NGHIAT
	 * Ngay tao: 13/09/2010 
	 *  Lay danh sach cac tin nhan da gui
	 */
	public function docSmsSendGetAll($sFullTextSearch,$piCurrentPage,$piNumRowOnPage){
		$sql = "Doc_DocSmsSendGetAll  ";
		$sql = $sql . "'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $piCurrentPage . "'";
		$sql = $sql . ",'" . $piNumRowOnPage . "'";
		//echo $sql; //exit;
		try{
			$arrSmsSend = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSmsSend;
	}
	/**
	 * Nguoi tao: NGHIAT
	 * Ngay tao: 13/09/2010 
	 *  Lay danh sach cac tin nhan da gui
	 */
	public function docSmsReceivedGetAll($piCurrentPage,$piNumRowOnPage){
		$sql = "Doc_DocSmsReceivedGetAll  ";
		$sql = $sql . "'" . $piCurrentPage . "'";
		$sql = $sql . ",'" . $piNumRowOnPage . "'";
		//echo $sql; //exit;
		try{
			$arrSmsReceived = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSmsReceived;
	}
	/**
	 * Nguoi tao: NGHIAT
	 * Ngay tao: 13/09/2010 
	 *  Update SMS
	 */
	public function docSmsSendUpdate($sTelMobileList,$sMsgList,$sStatus,$sPositionNameList,$sUnitNameList){
		$psSql = "Exec Doc_DocSmsListSendUpdate ";	
		$psSql .= "'"  . $sTelMobileList . "'";
		$psSql .= ",'" . $sMsgList . "'";
		$psSql .= ",'" . $sStatus . "'";
		$psSql .= ",'" . $sPositionNameList . "'";
		$psSql .= ",'" . $sUnitNameList . "'";		
		//echo $psSql; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}
	/**
	 * Nguoi tao: NGHIAT
	 * Ngay tao: 14/09/2010 
	 *  Xoa tin SMS da gui/phan hoi
	 */
	public function docSmsDelete($sListId,$sStatus){
		// Bien luu trang thai
		$sql = "Exec Doc_DocSmsDelete '" . $sListId . "'";	
		$sql .= ",'" . $sStatus . "'";	
		//echo $sql;exit;
		// thuc hien cap nhat du lieu vao csdl
		try {			
			$arrTempResult = $this->adodbExecSqlString($sql) ; 			
			$Result= $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $Result;	
	}
		/**
	 * Nguoi tao: NGHIAT
	 * Ngay tao: 16/09/2010 
	 *  Lay danh sach cac tin nhan da gui
	 */
	public function docSmsUserGetAll($sFullTextSearch,$piCurrentPage,$piNumRowOnPage){
		$sql = "Doc_DocSmsUserGetAll  ";
		$sql = $sql . "'" . $sFullTextSearch . "'";
		$sql = $sql . ",'" . $piCurrentPage . "'";
		$sql = $sql . ",'" . $piNumRowOnPage . "'";
		//echo $sql; //exit;
		try{
			$arrSmsUser = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSmsUser;
	}
	/**
	* Nguoi tao: NGHIAT
	* Ngay tao: 6/09/2010 
	* Y nghia:xem Chi tiet mot SMS USER
	*/
	public function docSmsUserGetSingle($sentID){
		$arrResult = null;
		$sql = "Exec Doc_DocSmsUserGetSingle";
		$sql .= "'" . $sentID . "'";
		//echo $sql;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	/**
	* Nguoi tao: NGHIAT
	* Ngay tao: 16/09/2010
	* Y nghia:Update thong tin NSD can gui tin SMS
	*/
	public function docSmsUserUpdate($arrParameter){
		$psSql = "Exec Doc_DocSmsUserUpdate ";	
		$psSql .= "'"  . $arrParameter['PK_DOC_SMS_USER'] . "'";
		$psSql .= ",'" . $arrParameter['FK_STAFF'] . "'";
		$psSql .= ",'" . $arrParameter['C_UNIT_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_POSITON_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_ORDER'] . "'";
		$psSql .= ",'" . $arrParameter['C_AUTO_SMS'] . "'";
		$psSql .= ",'" . $arrParameter['C_TEL_MOBILE'] . "'";			
		//Thuc thi lenh SQL		
		//echo $psSql; exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrTempResult;		
	}
		/**
	 * Nguoi tao: NGHIAT
	 * Ngay tao: 16/09/2010 
	 *  Xoa nguoi gui tin SMS
	 */
	public function docSmsUserDelete($sListId){
		// Bien luu trang thai
		$sql = "Exec Doc_DocSmsUserDelete '" . $sListId . "'";	
		//echo $sql;exit;
		// thuc hien cap nhat du lieu vao csdl
		try {			
			$arrTempResult = $this->adodbExecSqlString($sql) ; 			
			$Result= $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};				
		return $Result;	
	}
		/**
	* Nguoi tao: NGHIAT
	* Ngay tao: 6/09/2010 
	* Y nghia:xem Danh sach ID SMS USER
	*/
	public function docSmsUserIdList(){
		$arrResult = null;
		$sql = "Exec Doc_DocSmsUserIdList";
		//echo $sql;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
}?>