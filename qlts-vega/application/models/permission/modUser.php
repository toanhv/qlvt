<?php
/**
* Nguoi tao: TOANHV
* Ngay tao: 13/09/2009
* Y nghia:Lấy đầu mục công việc cần thực hiện khi NSD đăng nhập hệ thống
*/

//class Sent_modSent extends Efy_DB_Connection {
class Permission_modUser extends Efy_DB_Connection {	
	public function sentDocumentGetAll($p_staff_id,$pSignType,$pSymbol,$pSubject, $pStatus, $pDetailStatus, $p_from_sent_date, $p_to_sent_date, $proles, $p_unit_id, $has_read_all_sent_doc_permission, $has_read_all_sent_doc_permission_without_secret, $pOwnerCode, $piPage, $piNumberRecordPerPage){		
		$sql = "Doc_SentDocumentGetAll";
		$sql = $sql . "'" . $p_staff_id . "'";
		$sql = $sql . ",'" . $pSignType . "'";
		$sql = $sql . ",'" . $pSymbol . "'";
		$sql = $sql . ",'" . $pSubject . "'";
		$sql = $sql . ",'" . $pStatus . "'";
		$sql = $sql . ",'" . $pDetailStatus . "'";
		$sql = $sql . ",'" . $p_from_sent_date . "'";
		$sql = $sql . ",'" . $p_to_sent_date . "'";
		$sql = $sql . ",'" . $proles . "'";
		$sql = $sql . ",'" . $p_unit_id . "'";
		$sql = $sql . ",'" . $has_read_all_sent_doc_permission . "'";
		$sql = $sql . ",'" . $has_read_all_sent_doc_permission_without_secret . "'";
		$sql = $sql . ",'" . $pOwnerCode . "'";
		$sql = $sql . ",'" . $piPage . "'";
		$sql = $sql . ",'" . $piNumberRecordPerPage . "'";
		//echo $sql; 
		try{
			$arrSent = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrSent;
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
	public function sentDocumentUpdate($arrParameter){
		$psSql = "Exec Doc_SentDocumentUpdate ";	
		$psSql .= "'" . $arrParameter['PK_SENT_DOCUMENT'] . "'";
		$psSql .= ",'" . $arrParameter['FK_DOC'] . "'";
		$psSql .= ",'" . $arrParameter['FK_UNIT'] . "'";
		$psSql .= ",'" .  $arrParameter['C_UNIT_NAME'] . "'";
		$psSql .= ",'" . $arrParameter['FK_SIGNER'] . "'";
		$psSql .= ",'" . $arrParameter['C_SIGNER_POSITION'] . "'";			
		$psSql .= ",'" . $arrParameter['C_DOC_TYPE'] . "'";
		$psSql .= ",'" . $arrParameter['C_DOC_CATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_SENT_DATE'] . "'";
		$psSql .= ",'" . $arrParameter['C_SUBJECT'] . "'";
		$psSql .= ",'" . $arrParameter['C_OWNER_CODE'] . "'";
		$psSql .= ",'" . $arrParameter['C_XML_DATA'] . "'";
		$psSql .= ",'" . $arrParameter['C_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['C_YEAR'] . "'";
		$psSql .= ",'" . $arrParameter['C_SYMBOL'] . "'";
		$psSql .= ",'" . $arrParameter['FK_STAFF_DRAFF'] . "'";
		$psSql .= ",'" . $arrParameter['C_DRAFF_POSITION'] . "'";
		$psSql .= ",'" . $arrParameter['C_SIGN_TYPE'] . "'";
		$psSql .= ",'" . $arrParameter['C_DETAIL_STATUS'] . "'";
		$psSql .= ",'" . $arrParameter['DELETED_EXIST_FILE_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['NEW_FILE_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['NEW_FILE_SUBJECT_LIST'] . "'";	
		//Thuc thi lenh SQL		
		echo htmlspecialchars($psSql);// exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
	public function getSentSing($sentID){
		$arrResult = null;
		$sql = "Exec Doc_SentgetSing";
		$sql .= "'" . $sentID . "'";
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		};
		return $arrResult;
	}
		/**
	 * @author : HUNGVM
	 * @since : 15/05/2009
	 * @see : Thuc hien xoa mot hoac nhieu doi tuong
	 * @param :
	 * 			$psObjectList: Danh sach ID cua ho so
	 * 			$psStatus   : Trang thai ho so can xoa
	 * 			$piHasDeleteAllPermission: Quyen nguoi nay co duoc xoa hay khong 
	 * 										= 1: co quyen xoa ho so voi moi trang thai
	 * 										= 0 : khong co quyen xoa (chi xoa nhung ho so co trang thai = $psStatus)
	 * @return : 
	 * 			$arrTempResult['RET_ERROR'] = 'Thong bao loi'-> Khong xoa duoc
	 * 			$arrTempResult['RET_ERROR'] = ''-> xoa duoc
	 * */
	public function deleteSent($psObjectList, $psStatus, $piHasDeleteAllPermission){
		// Bien luu trang thai
		$Result = null;		
		$sql = "Exec Doc_SentDelete '" . $psObjectList . "'";
		$sql .= ",'".$psStatus ."'";		
		$sql .= ",'".$piHasDeleteAllPermission ."'";	
		//echo $sql . '<br>'; exit;
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
}
?>