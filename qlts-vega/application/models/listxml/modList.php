<?php
/**
*Goi cac ham xu ly trong Controller SentReceiveDocument
*/
//include_once('../function/draftDocument/functions.php');

/**
* Nguoi tao: phongtd
* Ngay tao: 11/01/2009
* Y nghia:Tao lop modList xu ly du lieu doi tuong danh muc
*/

class listxml_modList extends Efy_DB_Connection {
	/**
	 * Nguoi tao: phongtd
	 * Ngay tao: 11/01/2009
	 * Y nghia: Tao phuong thuc getAllListType Lay tat ca thong loai danh muc
	 * Ten phuong thuc: getAllListType
	 * @param  $piStatus : Trang thai + 0 : hien thi -1 : khong hien thi
	 * 		   $psTypeName: Ten cua ListType dung cho Fillter
	 * 		   $psOwnerCode: Ma don vi su dung
	 * 			
	 * @return : Tra ve mot mang chua thong tin loai danh muc
	 * */
	//public  $adoConn;
	public function getAllListType($piStatus, $psTypeName, $psOwnerCode){	
		//Lay thong tin 						
		$psSql = "Exec EfyLib_ListtypeGetAll '" . $piStatus . "','" . $psTypeName . "','" . $psOwnerCode . "'";			
		//echo $psSql . '<br>';
		$arrAllListType = $this->adodbQueryDataInNameMode($psSql);	
		//Return result
		return $arrAllListType;
	}
	
	/**
	 * Creater: phongtd
	 * Date:
	 * Idea: Lay tat ca thong tin doi tuong danh muc cua mot loai danh muc
	 * @param $arrAllListType : Mang luu thong tin loai danh muc
	 * @param $piIdListType : Id loai danh muc
	 * @param $piPage : Trang hien thoi
	 * @param $piNumRecordOnPage : So record/page
	 * @param $psFilterXmlString : Xau mo ta dieu kien loc
	 * @param $psOwnerCode : Ma don vi su dung
	 * @return unknown
	 */
	public function getAllList($arrAllListType, $piIdListType, $piPage, $piNumRecordOnPage, $psFilterXmlString, $psOwnerCode){							
		
		//Goi lop config
		Zend_Loader::loadClass('Efy_Init_Config');
		$objConfig = new Efy_Init_Config();
		
		//Tao mang luu thong tin doi tuong danh muc		
		$arrAllList = array();
		
		//Tao mang luu ket qua
		$arrResult = array();
		
		//Neu ton tai Id Loai danh muc
		if ($piIdListType > 0){
			//Luu ten file XML
			$psXmlFileName = "";
			
			//So phan tu cua mang loai danh muc
			$countElementListType = sizeof($arrAllListType);
			for($index = 0;$index < $countElementListType; $index++){
				//Lay file XML
				if ($arrAllListType[$index]['PK_LISTTYPE'] == $piIdListType){
					$psXmlFileName = $objConfig->_setXmlFileUrlPath(1) . "list/" . $arrAllListType[$index]['C_XML_FILE_NAME'];
					break;
				}
			}
			
			//Neu loai danh muc hien thoi khong xac dinh file XML thi se lay file XML mac dinh
			if (trim($psXmlFileName) == "" || !is_file($psXmlFileName)){
				$psXmlFileName = $objConfig->_setXmlFileUrlPath(1) . "list/quan_tri_doi_tuong_danh_muc.xml";
			}
				
			//Tao doi tuong Efy_Library
			$objEfyLib = new Efy_Library();
			
			//Tao doi tuong Efy_Publib_Xml
			Zend_Loader::loadClass('Efy_Publib_Xml');
			$objEfyLibXml = new Efy_Publib_Xml();
			
			//Doc file XML
			$psXmlStringInFile = $objEfyLib->_readFile($psXmlFileName);
			$psSqlString = $objEfyLibXml->_xmlGetXmlTagValue($psXmlFileName,"list_of_object","list_sql");
			//Thay the gia tri trong xau SQL
			$psSqlString = str_replace("#listtype_type#", $piIdListType, $psSqlString);	
			$psSqlString = str_replace("#page#",$piPage,$psSqlString);
			$psSqlString = str_replace("#number_record_per_page#",$piNumRecordOnPage,$psSqlString);
			$psSqlString = $objEfyLibXml->_replaceTagXmlValueInSql($psSqlString, $psXmlFileName, 'list_of_object/table_struct_of_filter_form/filter_row_list/filter_row', $psFilterXmlString);			
			//echo htmlspecialchars($psSqlString); //exit;
			//Thuc thi lenh SQL
			$arrAllList = $this->adodbQueryDataInNameMode($psSqlString);				
			
			//Xu ly ket qua
			$countElement = sizeof($arrAllList);
			if ($countElement > 0){			
				for ($index = 0;$index < $countElement; $index++){													
					// Tinh trang
					if (intval($arrAllList[$index]['C_STATUS']) == 'HOAT_DONG'){
						$sStatus = 'Hoạt động';
					}else {
						$sStatus = 'Ngừng hoạt động';
					}
					$sStaffProcess =  $arrAllList[$index]['C_STAFF_ID'];
					if (!empty($sStaffProcess)){
						$sStaffName  = Efy_Function_RecordFunctions::getNamePositionStaffByIdList($sStaffProcess);
					}else{
						$sStaffName = "";
					} 
					
					$arrAllList[$index]['C_STAFF_NAME'] = $sStaffName ;						
					$arrAllList[$index]['C_STATUS'] = $sStatus ;	
				}			
			}
				
			//
			$arrResult = array( 'arrList'=>$arrAllList,
							'xmlFileName'=>$psXmlFileName
						  );				
		}
		//var_dump($arrResult);
		//Return result
		return $arrResult;
	}
	
	
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $piIdListType
	 * @param unknown_type $arrParameter
	 * @param unknown_type $psXmlStringInDb
	 * @return unknown
	 */
	public function updateList($piIdListType, $arrParameter, $psXmlStringInDb){							
		
		//Goi lop config
		//Zend_Loader::loadClass('Efy_Init_Config');
		$objConfig = new Efy_Init_Config();
		
		//Tao mang luu thong tin doi tuong danh muc		
		$arrAllList = array();
		
		//Tao mang luu ket qua
		$arrResult = array();
		
		//
		$Result = '';			
		//Neu ton tai Id danh muc
		if ($arrParameter['PK_LISTTYPE'] > 0){	
			
			
			//Neu loai danh muc hien thoi khong xac dinh file XML thi se lay file XML mac dinh
			if (trim($arrParameter['GET_XML_FILE_NAME']) == "" || !is_file($arrParameter['GET_XML_FILE_NAME'])){
				$psXmlFileName = $objConfig->_setXmlFileUrlPath(1) . "list/quan_tri_doi_tuong_danh_muc.xml";
			}else{
				$psXmlFileName = trim($arrParameter['GET_XML_FILE_NAME']);
			}
					
			//Tao doi tuong Efy_Library
			$objEfyLib = new Efy_Library();
			
			//Tao doi tuong Efy_Publib_Xml
			Zend_Loader::loadClass('Efy_Publib_Xml');
			$objEfyLibXml = new Efy_Publib_Xml();
			
			//Doc file XML
			$psXmlStringInFile = $objEfyLib->_readFile($psXmlFileName);
			$psSqlString = $objEfyLibXml->_xmlGetXmlTagValue($psXmlStringInFile,"update_object","update_sql");
						
			//Thay the gia tri trong xau SQL
			$psSqlString = str_replace("#listtype_type#", $piIdListType, $psSqlString);	
			$psSqlString = str_replace("#list_id#", $arrParameter['PK_LIST'], $psSqlString);
			$psSqlString = str_replace("#listtype_id#", $piIdListType, $psSqlString);
			$psSqlString = str_replace("#list_code#", $arrParameter['C_CODE'], $psSqlString);
			$psSqlString = str_replace("#list_name#", $arrParameter['C_NAME'], $psSqlString);
			$psSqlString = str_replace("#list_order#", $arrParameter['C_ORDER'], $psSqlString);
			$psSqlString = str_replace("#list_status#", $arrParameter['C_STATUS'], $psSqlString);
			$psSqlString = str_replace("#p_owner_code_list#", $arrParameter['C_OWNER_CODE_LIST'], $psSqlString);						
			$psSqlString = str_replace("#xml_data#", $psXmlStringInDb, $psSqlString);
			
			$psSqlString = str_replace("#deleted_exist_file_id_list#", $arrParameter['DELETED_EXIST_FILE_ID_LIST'], $psSqlString);
			$psSqlString = str_replace("#new_file_id_list#", $arrParameter['NEW_FILE_ID_LIST'], $psSqlString);
			//echo htmlspecialchars($psSqlString) . '<br>';exit;
			//Thuc thi lenh SQL			
			try {			
				$arrTempResult = $this->adodbExecSqlString($psSqlString) ; 
				$Result = $arrTempResult['RET_ERROR'];
			}catch (Exception $e){
				echo $e->getMessage();
			};							
		}	
				
		//Return result
		return $Result;
	}
	
	/**
	 * @creator: phongtd
	 * @see : Tao file xml trong csdl
	 * @param : 
	 * 			$piStatus:  0 - khong hoat dong ,1 - hoat dong
	 * 			$piListId:  Id cua loai danh muc
	 * @return :
	 * 			$arrResult: mang chua danh sach 	
 	*/	
	public function createXMLDb($piStatus,$piListTypeId){
		$sSql = 'Exec EfyLib_ListGenerateXMLByAllListType ';
		$sSql = $sSql . "'" . $piStatus . "'" . ',' . $piListTypeId ;
		//echo $sSql;exit;
		try {		
			$arrResult = $this->adodbQueryDataInNameMode($sSql);
		}catch (Exception  $e){
			$e->getMessage();
		}
		return  $arrResult;
	}

	/**
	 * Creater: phongtd
	 * Date: 02/02/2009
	 * Lay thong tin chi tiet cua mot doi tuong co Id = $iListId
	 *
	 * @param $iListId : Id danh muc doi tuong
	 */
	public function getSingleList($iListId = 0){
		$psSqlString = "Exec EfyLib_ListGetSingle  ";
		$psSqlString = $psSqlString . $iListId ;
		//echo $psSqlString . '<br>';
		//Thuc thi xau SQL va luu va mang
		$arrGetSingleList = $this->adodbExecSqlString($psSqlString);
		return $arrGetSingleList;
	}
	
	/**
	 * Creater: phongtd
	 * Date: 02/02/2009
	 * Xoa thong tin cua mot hoac nhieu doi tuong da chon
	 *
	 * @param unknown_type $psObjectIdList
	 */
	public function deleteList($psObjectIdList = ""){
		$psSqlString = "Exec EfyLib_ListDelete  '";
		$psSqlString = $psSqlString . $psObjectIdList . "'";
		//echo $psSqlString;exit;
		//Thuc thi xau SQL va luu va mang (Xoa du lieu)
		$arrGetSingleList = $this->adodbExecSqlString($psSqlString);
		$psReturnError = $arrGetSingleList['RET_ERROR'];
		return $psReturnError;
	}
		/**
	 * Nguoi tao: phongtd
	 * Ngay tao: 03/09/2010 
	 *  Lay danh Lay max so tuong ung loai VB
	 */
	public function getMaxNumber($sDoctype,$iOwnerId,$sTextBook){
		$sql = "Exec Doc_DocSentMaxNumber ";
		$sql .= "'" . $sDoctype . "'";
		$sql .= ",'" . $iOwnerId . "'";
		$sql .= ",'" . $sTextBook . "'";
		//echo $sql;exit;
			$arrResult = $this->adodbExecSqlString($sql);
			$sResult = $arrResult['C_MAX_NUMBER'];
		return $sResult;
	}
}	
?>