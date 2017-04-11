<?php
/**
* Creater : HUNGVM
* Date: 18/09/2009
* Idea: Lay danh sach quyen thuoc nhom
*/

class permission_modUserPermission extends Efy_DB_Connection {
	
	/**
	 * Creater : HUNGVM
	 * Date : 18/09/2009
	 *
	 * @param $scode : Ma nhom quyen
	 * @param $sTagName : Ten then luu nhom quyen
	 * @return Mang luu thong tin nhom quyen
	 */
	public function PermissionGroupGetAll($scode, $sTagName){		
		$sql = "Doc_PermissionGroupGetAll";
		$sql = $sql . "'" . $scode . "'";
		$sql = $sql . ",'" . $sTagName . "'";		
		//echo $sql; 
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrResult;
	}

		/**
	 * Creater : HUNGVM
	 * Date : 21/09/2009
	 *
	 * @param $sStaffIdList : Danh sach NSD
	 * @return Mang luu thong tin quyen cua $sStaffIdList
	 */
	public function StaffPermissionGetAll($sStaffIdList, $sDelimitor = "!~~!"){		
		$sql = "Doc_StaffPermissionGetAll ";
		$sql = $sql . "'" . $sStaffIdList . "'";				
		$sql = $sql . ",'" . $sDelimitor . "'";				
		//echo $sql; 
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		return $arrResult;
	}
	
	/**
	 * Creater : HUNGVM
	 * Date : 21/09/2009
	 * Idea : Tao phuong thuc update quyen cho NSD
	 *
	 * @param $arrParameter : Mang luu thong tin cac tham so can update
	 * @return RET_ERROR : <>'' Neu loi xay ra; = '' Not Error
	 */
	public function StaffPermissionUpdate($arrParameter){
		$psSql = "Exec Doc_StaffPermissionUpdate ";	
		$psSql .= "'" . $arrParameter['PK_PERMISSION'] . "'";
		$psSql .= ",'" . $arrParameter['FK_STAFF_ID_LIST'] . "'";
		$psSql .= ",'" . $arrParameter['FK_PERMISSION_ID_LIST'] . "'";		
		$psSql .= ",'" . $arrParameter['CONST_LIST_DELIMITOR'] . "'";	
		//Thuc thi lenh SQL		
		//echo htmlspecialchars($psSql); exit;
		try {			
			$arrTempResult = $this->adodbExecSqlString($psSql) ; 
			$Result = $arrTempResult['RET_ERROR'];
		}catch (Exception $e){
			echo $e->getMessage();
		};
		//Return result
		return $Result;		
	}
	
	/**
	 * Creater : HUNGVM
	 * Date : 22/09/2009
	 * Idea : Tao phuong thuc lay tat ca doi tuong cua mot loai danh muc (vi du : Lay tat ca doi tuong cua loai danh muc quyen chuc nang)
	 *
	 * @param $listtypeCode : Ma loai danh muc
	 * @return Mang danh sach doi tuong
	 */
	public function objectOfListtypeGetAll($listtypeCode = ''){		 
		$sql = "EfyLib_ListGetAllbyCode ";
		$sql = $sql . "'" . $listtypeCode . "'";				
		$sql = $sql . ",'" . "'";				
		//echo $sql; 
		try{
			$arrResult = $this->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		
		//
		$arrTemp = array();
		for($index = 0;$index<sizeof($arrResult);$index++){
			$arrTemp[$arrResult[$index]['C_CODE']] = $arrResult[$index]['C_NAME'];
		}
		return $arrTemp;
	}
}
?>