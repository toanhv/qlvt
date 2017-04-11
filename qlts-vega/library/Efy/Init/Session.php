<?php 

/**
 * @see 
 * 
 */
//Goi xu ly webservice
require_once 'Efy/nusoap/nusoap.php';

//Goi xu ly kieu session
require_once 'Zend/Session/Namespace.php';

//Goi cac ham dung chung
//require_once 'Efy/Publib/Library.php';

/**
 * Nguoi tao: phongtd 
 * Ngay tao: 17/11/2008
 * Noi dung: Tao lop Efy_Init_Session khoi tao cac bien session
 */
class Efy_Init_Session extends Zend_Session_Namespace {	

	//********************************************************************************
	//Ten phuong thuc		:getPersonalInfoOfAllStaff()
	//Chuc nang	: Lay thong tin ca nhan cua tat ca can bo (staff)
	//********************************************************************************/
	public function SesGetPersonalInfoOfAllStaff($p_webservice_path = ""){
		//global $p_arr_items, $p_level1_tag_name, $p_level2_tag_name_list, $p_delimitor;
		$p_arr_items = array();
		//$p_level1_tag_name = "staff";
		//$p_level2_tag_name_list="id,name,code,unit_id,position_code,position_name,position_group_code,address,email,tel,order,password";
		//$p_delimitor = ",";		
		$arrResul = array();
		$sql = "Exec DBLink.[user].dbo.USER_GetPersonalInfoOfAllStaff ";
		try{
			$arrResul = Efy_DB_Connection::adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		if(sizeof($arrResul) > 0){
			foreach ($arrResul As $Resul){
				$arrStaff = array("id"=>$Resul['PK_STAFF']
								  ,"name"=>$Resul['C_NAME']
								  ,"code"=>$Resul['C_CODE']
								  ,"unit_id"=>$Resul['FK_UNIT']
								  ,"position_code"=>$Resul['C_POSITION_CODE']
								  ,"position_name"=>$Resul['C_POSITION_NAME']
								  ,"position_group_code"=>$Resul['C_POSITION_GROUP_CODE']
								  ,"address"=>$Resul['C_ADDRESS']
								  ,"email"=>$Resul['C_EMAIL']
								  ,"tel"=>$Resul['C_TEL']
								  ,"tel_mobile"=>$Resul['C_TEL_MOBILE']
								  ,"order"=>$Resul['C_ORDER']
								  ,"permission"=>$Resul['C_PERMISSION_LIST']
								  ,"position_code_name"=>$Resul['C_POSITION_CODE']." - ".$Resul['C_NAME']
								  );
				array_push($p_arr_items,$arrStaff);
			}
		}
		//Goi webservice
//		if($p_webservice_path != ""){
//			$obj_client = new soapclient($p_webservice_path);
//			$str_return = $obj_client->call('get_personal_info_of_all_staff');
//			if (is_null($str_return) or $str_return==""){
//				return;
//			}		
//		}else{
//			echo 'Khong tim thay dia chi cung cap thong tin Webservice!';
//			return;
//		}		
		return $p_arr_items;
	}	
	
	//********************************************************************************
	//Ten ham		:SesGetDetailInfoOfAllUnit()
	//Chuc nang	: Lay thong tin chi tiet cua tat ca phong ban (unit)	
	//********************************************************************************/
	public function SesGetDetailInfoOfAllUnit($p_webservice_path = ""){
		//global $p_arr_items, $p_level1_tag_name, $p_level2_tag_name_list, $p_delimitor;
		
		$p_arr_items = array();
		//$p_level1_tag_name = "unit";
		//$p_level2_tag_name_list="id,parent_id,name,code,address,email,tel,order";
		//$p_delimitor = ",";
		$arrResul = array();
		$sql = "Exec DBLink.[user].dbo.USER_GetDetailInfoOfAllUnit ";
		try{
			$arrResul = Efy_DB_Connection::adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		if(sizeof($arrResul) > 0){
			foreach ($arrResul As $Resul){
				$arrStaff = array("id"=>$Resul['PK_UNIT']
								  ,"parent_id"=>$Resul['FK_UNIT']
								  ,"name"=>$Resul['C_NAME']
								  ,"code"=>$Resul['C_CODE']
								  ,"address"=>$Resul['C_ADDRESS']
								  ,"email"=>$Resul['C_EMAIL']
								  ,"tel"=>$Resul['C_TEL']
								  ,"order"=>$Resul['C_ORDER']
								  );
				array_push($p_arr_items,$arrStaff);
			}
		}
		//Goi webservice
//		if($p_webservice_path != ""){
//			$obj_client = new soapclient($p_webservice_path);
//			$str_return = $obj_client->call('get_detail_info_of_all_unit');
//			if (is_null($str_return) or $str_return==""){
//				return;
//			}
//		}else{
//			echo 'Khong tim thay dia chi cung cap thong tin Webservice!';
//			return;
//		}	
		return $p_arr_items;
	}	
	
	/**
	 * Creater :phongtd
	 * Date : 24/09/2009
	 * Idea : Tao phuong thuc lay tat ca quyen cua NSD hien thoi
	 * @param $sStaffIdList : Id can bo hien thoi
	 * @param $sDelimitor : Ky tu phan tach
	 * @return Mang danh sach quyen
	 */
	public function SesGetAllPermissionForSession($sStaffIdList, $sDelimitor = "!~~!"){
		//
		$ojbConnect = new  Efy_DB_Connection();
		$sql = "Doc_StaffPermissionGetAll ";
		$sql = $sql . "'" . $sStaffIdList . "'";				
		$sql = $sql . ",'" . $sDelimitor . "'";				
		//echo 'SesGetAllPermissionForSession:' . $sql . '<br>'; //exit;
		try{
			$arrResult = $ojbConnect->adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		
		//Chuyen doi xau mo ta quyen -> mang mot chieu
		$arrElement = explode("!~~!",$arrResult[0]['C_PERMISSION_LIST']);	
		$arrPermission = array();
		for ($index = 0;$index<sizeof($arrElement);$index++){
			$arrTemp = explode("!*~*!",$arrElement[$index]);
			if (trim($arrTemp[0]) != ""){
				$arrPermission[trim($arrTemp[0])] = 1;
			}	
		}
		//var_dump($arrPermission);exit;
		return $arrPermission;
	}
	
	
	/**
	 * Creater : phongtd
	 * Date : 25/09/2009
	 * Idea : Tao phuong thuc lay quyen cua NSD hien thoi
	 *
	 */
	public function StaffPermisionGetAll($iLoginStaffId = 0){
		$iStaffid = intval($_SESSION['staff_id']);		
		if (!isset($iStaffid) || $iStaffid == 0 || $iStaffid == ""){
			$iStaffid = $iLoginStaffId;
		}
		//
		$arrPermission = self::SesGetAllPermissionForSession($iStaffid);
		if (is_array($arrPermission) || $arrPermission == ""){
			return $arrPermission;			
		}
		return '';
	}
	
	/**
	 * Creater : phongtd
	 * Date : 25/09/2009
	 * hieu chinh: phuongtt - 18/08/2010
	 * Idea : Tao phuong thuc lay thong tin don vi su dung
	 *
	 * @param unknown_type $UnitId
	 */
	public function SesGetAllOwner(){
		$arrOwner = array();
		$v_root_id = Efy_Library::_getRootUnitId($_SESSION['arr_all_unit_keep']);
		foreach($_SESSION['arr_all_unit_keep'] as $objUnit){
			if($objUnit['parent_id']== $v_root_id){
				$arr1Owner = array("id"=>$objUnit['id'],"name"=>$objUnit['name'],"code"=>$objUnit['code'],"address"=>$objUnit['address'],"email"=>$objUnit['email'],"order"=>$objUnit['order']);
				array_push($arrOwner,$arr1Owner);
			}
		}
		return $arrOwner;
	}
	
	/**
	 * Creater : phongtd
	 * Date : 26/09/2009
	 * Idea : Tao phuong thuc lay thong tin MA; TEN; DANH SACH ID cua can bo dang nhap hien thoi
	 *
	 * @return Mang luu thong tin Ma; Ten; list Id Staff cua can bo dang nhap hien thoi
	 */
	public function StaffOwnerInformationGet($iLoginStaffId = 0){		
		$iStaffId = intval($_SESSION['staff_id']);		
		//echo 'iLoginStaffIdxxxx:' . $iStaffId . '<br>';
		if (!isset($iStaffId) || $iStaffId == 0 || $iStaffId == ""){			
			$iStaffId = $iLoginStaffId;
		}		
		//echo 'staffId:' . $iStaffId . '<br>';exit;
		//Tao doi tuong trong thu vien dung chung
		$objLib = new Efy_Library();		
		//echo $iStaffId . '<test>';
		$arrTemp = $_SESSION['SesGetAllOwner'];
		if (is_null($arrTemp)){
			$arrTemp = self::SesGetAllOwner();
		}
		//var_dump($arrTemp);		
		//Lay thong tin Ma; Ten; List Staff ID cua NSD hien thoi
		for ($index = 0;$index<sizeof($arrTemp);$index++){
			if ($objLib->_listHaveElement($arrTemp[$index]['C_STAFF_ID_LIST'],$iStaffId,',')){
				$arrOwner['ID'] 					= $arrTemp[$index]['id'];
				$arrOwner['OWNER_CODE'] 			= $arrTemp[$index]['code'];
				$arrOwner['OWNER_NAME'] 			= $arrTemp[$index]['name'];
				$arrOwner['C_STAFF_ID_LIST'] 		= $arrTemp[$index]['C_STAFF_ID_LIST'];		
				$arrOwner['C_HAVE_TO_DISTRIBUTION'] = $arrTemp[$index]['C_HAVE_TO_DISTRIBUTION'];				
				return $arrOwner;
				break;
			}
		}
		//var_dump($arrOwner);
		return '';
	}
	
	/**
	 * Creter : phongtd
	 * Date : 24/06/2010
	 * Idea : Tra lai danh sach phong ban cua NSD hien thoi
	 *
	 * @param $arrListValue
	 * @return : Mang danh sach phong ban
	 */
	public function _getUnitsByRoomIdLevelOne($arrListValue){
	//var_dump($_SESSION['arr_all_unit']);
		//exit;
		if($arrListValue[0] != 0){
			$i = 0;
			foreach($_SESSION['arr_all_unit'] as $objUnit){//Lay phong ban cap 0
				if (is_null($objUnit['parent_id']) || $objUnit['parent_id']=="" || $objUnit['parent_id']=="NULL"){/*
					$arrChildUnitRoot[$i]['id'] 		= 	$objUnit['id'];
					$arrChildUnitRoot[$i]['parent_id'] 	= 	NULL;
					$arrChildUnitRoot[$i]['name'] 		= 	$objUnit['name'];
					$arrChildUnitRoot[$i]['code'] 		= 	$objUnit['code'];
					$arrChildUnitRoot[$i]['address'] 	= 	$objUnit['address'];
					$arrChildUnitRoot[$i]['email'] 		= 	$objUnit['email'];
					$arrChildUnitRoot[$i]['tel'] 		= 	$objUnit['tel'];
					$arrChildUnitRoot[$i]['order']		= 	$objUnit['order'];
					$i++; // Them thong tin cua phong ban cap 1
					*/
				
					$arrChildUnitRoot[$i]['id'] 		= 	$arrListValue[0];
					$arrChildUnitRoot[$i]['parent_id'] 	= 	NULL;
					$arrChildUnitRoot[$i]['name'] 		= 	$arrListValue[2];
					$arrChildUnitRoot[$i]['code'] 		= 	$arrListValue[3];
					$arrChildUnitRoot[$i]['address'] 	= 	$arrListValue[4];
					$arrChildUnitRoot[$i]['email'] 		= 	$arrListValue[5];
					$arrChildUnitRoot[$i]['tel'] 		= 	$arrListValue[6];
					$arrChildUnitRoot[$i]['order']		= 	$arrListValue[7];
					$i++;
				}else{// Lay cac phong van con cua phong ban cap 1					
					if($objUnit['parent_id'] == $arrListValue[0]){
						$arrChildUnitRoot[$i]['id'] 			= 	$objUnit['id'];
						$arrChildUnitRoot[$i]['parent_id'] 	= 	$objUnit['parent_id'];
						$arrChildUnitRoot[$i]['name'] 		= 	$objUnit['name'];
						$arrChildUnitRoot[$i]['code'] 		= 	$objUnit['code'];
						$arrChildUnitRoot[$i]['address'] 	= 	$objUnit['address'];
						$arrChildUnitRoot[$i]['email'] 		= 	$objUnit['email'];
						$arrChildUnitRoot[$i]['tel'] 		= 	$objUnit['tel'];
						$arrChildUnitRoot[$i]['order']		= 	$objUnit['order'];
	                    $arrChildUnitRoot[$i]['permission'] 	=   $objUnit['permission'];
						$i++;												
					}	
				}	
			}
		}	
		//var_dump($arrChildUnitRoot);
		//exit;
		return $arrChildUnitRoot;
	}
		
	/**
	 * Creater: phongtd
	 * Date : 24/06/2010
	 * Idea :  Lay toan bo thong tin(ID, NAME) cua don vi su dung
	 *
	 * @return unknown
	 */
	public function _getAllOwner(){
		//$sUnitLevel1Id="";
		$iRootId = _get_root_unit_id();
		$arrListOwner = array();
		$v_index = 0;
		foreach($_SESSION['arr_all_unit_keep'] as $objUnit){
			if (strcasecmp($objUnit['parent_id'], $iRootId) == 0){			
				$sParentName 		=  $objUnit['name'];
				$sParentCode 		=  $objUnit['code'];
				$arrListOwner[$v_index]['OWNER_CODE'] = $sParentCode;
				$arrListOwner[$v_index]['OWNER_NAME'] = $sParentName;
				$v_index++;
			}
		}
		return $arrListOwner;
	}

	/**
	 * Creater : phongtd
	 * Date : 24/06/2010
	 * Idea : Lay toan bo NSD cua mot don vi
	 *
	 * @param $arrUnit
	 * @return Mang danh sach can bo trong mang $arrUnit
	 */
	public function _getAllUser($arrUnit){
		$i = 0;
		$iCount = sizeof($arrUnit);
		for($j=0; $j < $iCount; $j++){
			foreach($_SESSION['arr_all_staff_keep'] as $objStaff){
				if($objStaff['unit_id'] == $arrUnit[$j]['id']){			
					$arrchildStaff[$i]['id'] 					= $objStaff['id'];
					$arrchildStaff[$i]['name'] 					= $objStaff['name'];
					$arrchildStaff[$i]['code'] 					= $objStaff['code'];				
					$arrchildStaff[$i]['unit_id'] 				= $objStaff['unit_id'];
					$arrchildStaff[$i]['position_code'] 		= $objStaff['position_code'];
					$arrchildStaff[$i]['position_name'] 		= $objStaff['position_name'];
					$arrchildStaff[$i]['position_group_code'] 	= $objStaff['position_group_code'];
					$arrchildStaff[$i]['address'] 				= $objStaff['address'];
					$arrchildStaff[$i]['email'] 				= $objStaff['email'];
					$arrchildStaff[$i]['tel'] 					= $objStaff['tel'];
					$arrchildStaff[$i]['order'] 				= $objStaff['order'];
	                $arrchildStaff[$i]['permission'] 			= $objStaff['permission'];
					$i++;
				}
			}
		}
		return $arrchildStaff;	
	}
	
	//********************************************************************************************************************
	//Nguoi tao	: Vu Manh Hung
	//Ngay tao	: 26/02/2008
	//Y nghia	: Lay TEN va ID cua don vi cap 1 cua mot nguoi bat ky trong don vi
	//********************************************************************************************************************
	
	/**
	 * Creater : phongtd
	 * Date : 24/06/2010
	 * Idea :  Lay TEN va ID cua don vi cap 1 cua mot nguoi bat ky trong don vi
	 *
	 * @param $userId : Id NSD hien thoi
	 * @return : Chuoi luu thong tin $sUnitLevel1Id . "|!~~!|" . $iParentId . "|!~~!|" . $sParentName . "|!~~!|" . $sParentCode . "|!~~!|" . $sParentAddress . "|!~~!|" . $sParentEmail. "|!~~!|" . $sParentOrder;	
	 */
	public function _getUnitLevelOneNameAndId($userId){
		$sUnitLevel1Id="";
		$iRootId = Efy_Library::_getRootUnitId($_SESSION['arr_all_unit']);
		$iParentId = Efy_Library::_getItemAttrById($_SESSION['arr_all_staff'],$userId,'unit_id');
		//echo $iRootId; exit;
		if ($iParentId!="" && !is_null($iParentId) && $iParentId!="NULL"){
			while(strcasecmp($iParentId,$iRootId)!=0){
				foreach($_SESSION['arr_all_unit'] as $objUnit){
					if (strcasecmp($objUnit['id'], $iParentId)==0){			
						$sUnitLevel1Id 		= $objUnit['id'];
						$iParentId 			=  $objUnit['parent_id'];
						$sParentName 		=  $objUnit['name'];
						$sParentCode 		=  $objUnit['code'];
						$sParentAddress 	=  $objUnit['address'];
						$sParentEmail 		=  $objUnit['email'];
						$sParentOrder		=  $objUnit['order'];
						break;
					}
				}
			}	
		}	
		return $sUnitLevel1Id . "|!~~!|" . $iParentId . "|!~~!|" . $sParentName . "|!~~!|" . $sParentCode . "|!~~!|" . $sParentAddress . "|!~~!|" . $sParentEmail. "|!~~!|" . $sParentOrder;	
	}
	/**
	 * Creater : phongtd
	 * Date : 02/05/2011
	 * Idea :  Lay toan bo ma chuc danh
	 *
	 */
	public function PositionGroupGetAll(){
		$p_arr_items = array();	
		$arrResul = array();
		$sql = "Exec DBLink.[user].dbo.USER_PositionGetAll '%%',-1";
		try{
			$arrResul = Efy_DB_Connection::adodbQueryDataInNameMode($sql);
		}catch (Exception $e){
			echo $e->getMessage();
		}
		if(sizeof($arrResul) > 0){
				foreach ($arrResul As $Resul){
					$arrStaff = array("id"=>$Resul['PK_POSITION']
									  ,"name"=>$Resul['C_NAME']
									  ,"code"=>$Resul['C_CODE']
									  );
					array_push($p_arr_items,$arrStaff);
				}
			}	
		return $p_arr_items;
	}
}