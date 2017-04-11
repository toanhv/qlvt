<?php
class listxml_modRecordtype extends Efy_DB_Connection {	
	/** Nguoi tao: NGHIAT
		* Ngay tao: 25/10/2010
		* Y nghia: Lay ra cac TTHC
	*/
	public function eCSRecordTypeGetAll($sOwnerCode,$sFullTextSearch){
		$sql = "eCS_RecordTypeGetAll ";
		$sql = $sql . " '" . $sOwnerCode . "'";
		$sql = $sql . ",'" . $sFullTextSearch . "'";		
		//echo  "<br>". $sql . "<br>"; 
		//exit;
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrResult;		
	}
	/** Nguoi tao: NGHIAT
		* Ngay tao: 25/10/2010
		* Y nghia: Lay So TT lon nhat cac TTHC cua don vi su dung
		* adodbExecSqlString: lay mang 1 chieu
		* adodbQueryDataInNameMode: lay mang da chieu
	*/
	public function eCSRecordTypeMaxOrder($sOwnerCode){
		$sql = "eCS_RecordTypeMaxOrder ";
		$sql = $sql . " '" . $sOwnerCode . "'";	
		//echo  "<br>". $sql . "<br>"; 
		//exit;
		try{
			$arrResult = $this->adodbExecSqlString($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrResult;		
	}
	/** Nguoi tao: NGHIAT
		* Ngay tao: 27/10/2010
		* Y nghia: Update TTHC
		* adodbExecSqlString: lay mang 1 chieu
		* adodbQueryDataInNameMode: lay mang da chieu
	*/
	public function eCSRecordTypeUpdate($arrParameter){
		$psSql = "Exec eCS_RecordTypeUpdate  ";	
		$psSql .= "'"  . $arrParameter['PK_RECORDTYPE'] . "'";
		$psSql .= ",'" . $arrParameter['C_CODE'] . "'";
		$psSql .= ",'" . $arrParameter['C_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['C_CATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_ORDER'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";			
		$psSql .= ",'" . $arrParameter['C_RECORD_TYPE'] . "'";
		$psSql .= ",'" . $arrParameter['C_PROCESS_NUMBER_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_RESULT_DOC_TYPE'] . "'";
		$psSql .= ",'" . $arrParameter['C_COST_NEW'] . "'";
		$psSql .= ",'" . $arrParameter['C_COST_CHANGE'] . "'";
		$psSql .= ",'" . $arrParameter['C_BEGIN_RECORD_NUMBER'] . "'";
		$psSql .= ",'" . $arrParameter['C_BEGIN_LICENSE_NUMBER'] . "'";
		$psSql .= ",'" . $arrParameter['C_IS_VIEW_ON_NET'] . "'";
		$psSql .= ",'" . $arrParameter['C_IS_REGISTER_ON_NET'] . "'";
		$psSql .= ",'" . $arrParameter['C_AUTO_RESET'] . "'";
		$psSql .= ",'" . $arrParameter['C_MOVE_TO_RESULT'] . "'";
		$psSql .= ",'" . $arrParameter['C_APPROVE_LEVEL'] . "'";
		$psSql .= ",'" . $arrParameter['C_OWNER_CODE'] . "'";
		$psSql .= ",'" . $arrParameter['C_TRASITION_OWNER_CODE_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_PROCESS_DATE_NUMBER'] . "'";
		$psSql .= ",'" . $arrParameter['C_DEPARTMENT_ID_LIST'] . "'";	
		$psSql .= ",'" . $arrParameter['C_DEPARTMENT_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_RECEIVE_ID_LIST'] . "'";	
		$psSql .= ",'" . $arrParameter['C_RECEIVE_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_HANDLE_ID_LIST'] . "'";	
		$psSql .= ",'" . $arrParameter['C_HANDLE_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_LEADER_ID_LIST'] . "'";	
		$psSql .= ",'" . $arrParameter['C_LEADER_NAME_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_LEADER_ROLE_LIST'] . "'";	
		$psSql .= ",'" . $arrParameter['C_PERIOD_CODE_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['C_PERIOD_DATE_LIST'] . "'";	
		$psSql .= ",'" . $arrParameter['C_WORK_TYPE_LIST'] . "'";
		//Thuc thi lenh SQL		
		//echo $psSql; exit;
		try {			
			$arrResult = $this->adodbExecSqlString($psSql) ; 
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;		
	}	
	/** Nguoi tao: NGHIAT
		* Ngay tao: 27/10/2010
		* Y nghia: Lay chi tiet mot TTHC
		* adodbExecSqlString: lay mang 1 chieu
		* adodbQueryDataInNameMode: lay mang da chieu
	*/
	public function eCSRecordTypeGetSingle($sRecordTypePk){
		$arrResult = null;
		$sql = "Exec eCS_RecordTypeGetSingle ";
		$sql .= "'" . $sRecordTypePk . "'";
		//echo $sql; exit;
		try{
			$arrResult = $this->adodbExecSqlString($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
	/*
	* Nguoi tao: NGHIAT
	* Ngay tao: 28/10/2010
	* Y nghia:xoa danh sach TTHC
	*/
	public function eCSRecordTypeDelete($sRecordTypeIdList){
		// Bien luu trang thai
		$sql = "Exec eCS_RecordTypeDelete '" . $sRecordTypeIdList . "'";	
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
}
?>