<?php
/**
* Nguoi tao: phongtd
* Ngay tao: 06/01/2009
* Y nghia:Tao lop modListType dan xuat (extends) tu lop Efy_DB_Connection
*/

class Listxml_modListType extends Efy_DB_Connection {
	/**
	 * Nguoi tao: phongtd
	 * Ngay tao: 06/01/2009
	 * Y nghia: Tao phuong thuc getAllListType de lay thong tin ket qua xu ly Danh muc Doi tuong
	 * Ten phuong thuc: getAllListType
	 * @param  $piStatus : Trang thai + 0 : hien thi -1 : khong hien thi
	 * 		   $psTypeName: Ten cua ListType dung cho Fillter
	 * 		   $psOwnerCode: Ma don vi su dung
	 * 			
	 * @return : Tra ve mot mang 
	 * */
	//public  $adoConn;
	public function getAllListType($piStatus,$psTypeName,$psOwnerCode){							
		$sql = "Exec EfyLib_ListtypeGetAll '" . $piStatus . "','" . $psTypeName . "','" . $psOwnerCode . "'";			
		//echo $sql . '<br>';
		$arrTempResult = $this->adodbQueryDataInNameMode($sql);			
		
		//Xu ly ket qua
		$countElement = sizeof($arrTempResult);
		if($countElement > 0){			
			for($index = 0;$index < $countElement; $index++){								
				// Lay ID ListType			
				$arrResult[$index]['PK_LISTTYPE'] = $arrTempResult[$index]['PK_LISTTYPE'];
				// Ma cua ListType											 						 
				$arrResult[$index]['C_CODE'] = $arrTempResult[$index]['C_CODE'];			
				
				// Ten ListType
				$arrResult[$index]['C_NAME'] = $arrTempResult[$index]['C_NAME'];	
				
				// So thu tu
				$arrResult[$index]['C_ORDER'] = $arrTempResult[$index]['C_ORDER'];	
				// Tinh trang
				if(intval($arrTempResult[$index]['C_STATUS']) == 'HOAT_DONG'){
					$sStatus = 'Hoạt động';
				}else {
					$sStatus = 'Ngừng hoạt động';
				}	
				$arrResult[$index]['C_STATUS'] = $sStatus ;	
			}			
		}			
		return $arrResult;
	}
	
	
	/**
		@creator: phongtd
		@createdate: 08/01/2009
		@see :  Ham Them moi va Hieu chinh Loai danh muc
		@param : 
				$piListTypeId:   id cua Danh muc
				$psListTypeCode: Ma cua danh mcu 
				$psListTypeName: Ten danh muc
				$piListTypeOrder: so thu tu hien thi
				$psListTypeXml: Ten file xml
				$pbListTypeStatus: Tinh trang 1 - hoat dong 0 - khong hoat dong
				$psListTypeOwnerCodeList: Ma don vi su dung
		@return :
				$Result ='Thong bao loi'  - khong cap nhat duoc
				$Result = null 			  - cap nhat ok
				
	*/	
	public function updateListType($piListTypeId,$psListTypeCode,$psListTypeName,$piListTypeOrder,$psListTypeXml,$pbListTypeStatus,$psListTypeOwnerCodeList){
		// Bien luu trang thai
		$Result = null;		
		$sql = "Exec EfyLib_ListtypeUpdate " . $piListTypeId . ",'".$psListTypeCode."'";
		$sql = $sql . ",'".$psListTypeName."',".$piListTypeOrder;
		$sql = $sql . ",'".$psListTypeXml."','".$pbListTypeStatus;
		$sql = $sql . "','".$psListTypeOwnerCodeList."'";		
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
	 * @creator: phongtd
	 * @createdate: 10/01/2009
	 * @see : Thuc hien xoa Loai Danh muc
	 * @param : 
	 * 			$piListTypeIdList: Danh sach id  cua Loai Danh Muc
	 * @return : 
	 * 			$Result ='Thong bao loi'  - khong xoa duoc
	 * 			$Result = null 			- Xoa Ok
	 */
	public  function deleteListType($psListTypeIdList){
		// Bien luu trang thai
		$Result = null;		
		$sql = "Exec EfyLib_ListtypeDelete '" . $psListTypeIdList . "'";		
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
	 * @creator: phongtd
	 * @createdate: 12/01/2009
	 * @see : Lay thong tin cua mot Loai danh muc
	 * @param : piListTypeId: Id cua loai danh muc
	 * @return : $Result - Mang chua thong tin cua danh muc
	 */
	public function getSingleListType($piListTypeId){
		$sql = "Exec EfyLib_ListtypeGetSingle " . $piListTypeId ;
		//echo $sql . "<br>";
		try {
			$arrTempResult = $this->adodbQueryDataInNameMode($sql);	
			//Xu ly ket qua
		$countElement = sizeof($arrTempResult);
		if($countElement > 0){			
			for($index = 0;$index < $countElement; $index++){	
			//foreach ($arrTempResult as $key)								
				// Lay ID ListType			
				$arrResult[$index]['PK_LISTTYPE'] = $arrTempResult[$index]['PK_LISTTYPE'];
				
				// Ma cua ListType											 						 
				$arrResult[$index]['C_CODE'] = $arrTempResult[$index]['C_CODE'];			
				
				// Ten ListType
				$arrResult[$index]['C_NAME'] = $arrTempResult[$index]['C_NAME'];	
				
				// So thu tu
				$arrResult[$index]['C_ORDER'] = $arrTempResult[$index]['C_ORDER'];	
				
				// Tinh trang
				$arrResult[$index]['C_STATUS'] = $arrTempResult[$index]['C_STATUS'];				
				
				// file xml
				$arrResult[$index]['C_XML_FILE_NAME'] = $arrTempResult[$index]['C_XML_FILE_NAME'];	

				// Don vi su dung
				$arrResult[$index]['C_OWNER_CODE_LIST']	= $arrTempResult[$index]['C_OWNER_CODE_LIST'];						
				
			}			
		}	
		}catch (ErrorException   $e){
			$e->getMessage();
		}		
		return $arrResult;
	}	
	
}
?>