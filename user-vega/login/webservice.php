<?php
//Ket noi dich vu WebService cua NuSoap
require_once('../isa-lib/nusoap/nusoap.php');
include "../db_const.php";
switch($_ISA_DB_TYPE) {
	// Ket thuc ket noi toi MS SQL-SERVER
	case "SQL SEVER";
		$conn=@mssql_connect($_ISA_SERVER_NAME,$_ISA_DB_USER ,$_ISA_DB_PASSWORD) or die(_CONST_DB_CONNECT_ERROR);
		mssql_select_db($_ISA_DB_NAME);
		break;
	// Ket thuc ket noi toi Postgres
	case "POSTGRES";
		$conn = @pg_connect("host=" . $_ISA_SERVER_NAME . "user='" . $_ISA_DB_USER . "' password='" . $_ISA_DB_PASSWORD . "'dbname=" . $_ISA_DB_NAME) or die (_CONST_DB_CONNECT_ERROR);
		break;
}

//Dat quyen tren mot modul, mot function cho 1 STAFF
function set_permision_on_function($p_staff_id, $p_app_code, $p_modul_code, $p_function_code){
	global $conn;
	$cmd = @mssql_init("USER_SetPermisionOnFunction",$conn);
	@mssql_bind($cmd,"@p_staff_id",$p_staff_id,SQLINT4);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	@mssql_bind($cmd,"@p_modul_code",$p_modul_code,SQLVARCHAR);
	@mssql_bind($cmd,"@p_function_code",$p_function_code,SQLVARCHAR);
	$result = @mssql_execute($cmd);
	@mssql_free_result($result);
	return 'true';
}

//Kiem tra quyen den tung modul (tra ve gia tri True or False)
function check_permision_on_modul($p_staff_id, $p_app_code, $p_modul_code){
	global $conn;
	$cmd = @mssql_init("USER_CheckPermissionOnModul",$conn);
	@mssql_bind($cmd,"@p_staff_id",$p_staff_id,SQLINT4);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	@mssql_bind($cmd,"@p_modul_code",$p_modul_code,SQLVARCHAR);
	$result = mssql_execute($cmd);
	$arr = @mssql_fetch_array($result);
	if($arr['PERMISION_ON_MODUL'] >0){
		return 'true';
	}else{
		return 'false';	
	}
}
//Kiem tra quyen den tung chuc nang cua ung dung (tra ve gia tri True or False)
function check_permision_on_function($p_staff_id, $p_app_code, $p_function_code){
	global $conn;
	$cmd = mssql_init("USER_CheckPermissionOnFunction",$conn);
	@mssql_bind($cmd,"@p_staff_id",$p_staff_id,SQLINT4);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	@mssql_bind($cmd,"@p_function_code",$p_function_code,SQLVARCHAR);
	$result = mssql_execute($cmd);
	$arr = @mssql_fetch_array($result);
	if($arr['PERMISION_ON_FUNCTION'] >0){
		return 'true';
	}else{
		return 'false';	
	}
}

//Kiem tra  xem $staff_id co phai la ENDUSER cua $app_code hay khong
function is_app_enduser($p_staff_id,$p_app_code){
	global $conn;
	$cmd = mssql_init("USER_EndUserCheckBelongToApp",$conn);	
	@mssql_bind($cmd,"@p_staff_id",$p_staff_id,SQLINT4);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	$result = mssql_execute($cmd);
	$v_count = @mssql_num_rows($result);
	if($v_count >0){
		$arr = @mssql_fetch_array($result);
		return $arr['PK_STAFF'].'|&|'.$arr['PK_ENDUSER'].'|&|'.$arr['C_NAME'].'|&|'.$arr['C_ISA_USER_ADMIN'].'|&|'.$arr['C_ISA_APP_ADMIN'];
	}else{
		return 'false';
	}
}
// Lay danh sach tat ca cac modul thuoc mot ung dung ma $p_user_id co quyen thuc hien
function get_all_modul_granted_for_user($p_app_code,$p_staff_id){
	global $conn;
	$cmd = @mssql_init("USER_GetAllModulGrantedForUser",$conn);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	@mssql_bind($cmd,"@p_staff_id",$p_staff_id,SQLINT4);
	$result = mssql_execute($cmd);
	$v_count = @mssql_num_rows($result);
	$list_id = '';
	if ($v_count>0) {
		for ($i=1; $i<=$v_count; $i++){
			$arr=@mssql_fetch_array($result);
			$list_id = $list_id . htmlspecialchars($arr['C_CODE']);
			if ($i<$v_count){
				$list_id = $list_id . ',';
			}
		}
	}
	@mssql_free_result($result);
	return $list_id;
}
// Lay danh sach tat ca cac modul cong cong cua mot ung dung
function get_all_public_modul($p_app_code){
	global $conn;
	$cmd = @mssql_init("User_GetAllPublicModul",$conn);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	$result = mssql_execute($cmd);
	$v_count = @mssql_num_rows($result);
	$list_id = '';
	if ($v_count>0) {
		for ($i=1; $i<=$v_count; $i++){
			$arr=@mssql_fetch_array($result);
			$list_id = $list_id . htmlspecialchars($arr['C_CODE']);
			if ($i<$v_count){
				$list_id = $list_id . ',';
			}
		}
	}
	@mssql_free_result($result);
	return $list_id;
}

// Lay danh sach tat ca cac function thuoc mot ung dung ma $p_user_id co quyen thuc hien
function get_all_function_granted_for_user($p_app_code,$p_staff_id){
	global $conn;
	$cmd = @mssql_init("User_GetAllFunctionGrantedForUser",$conn);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	@mssql_bind($cmd,"@p_staff_id",$p_staff_id,SQLINT4);
	$result = mssql_execute($cmd);
	$v_count = @mssql_num_rows($result);
	$list_id = '';
	if ($v_count>0) {
		for ($i=1; $i<=$v_count; $i++){
			$arr=@mssql_fetch_array($result);
			$list_id = $list_id . htmlspecialchars($arr['C_CODE']);
			if ($i<$v_count){
				$list_id = $list_id . ',';
			}
		}
	}
	@mssql_free_result($result);
	return $list_id;
}

// Lay danh sach tat ca cac function cong cong cua mot ung dung
function get_all_public_function($p_app_code){
	global $conn;
	$cmd = @mssql_init("User_GetAllPublicFunction",$conn);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	$result = mssql_execute($cmd);
	$v_count = @mssql_num_rows($result);
	$list_id = '';
	if ($v_count>0) {
		for ($i=1; $i<=$v_count; $i++){
			$arr=@mssql_fetch_array($result);
			$list_id = $list_id . htmlspecialchars($arr['C_CODE']);
			if ($i<$v_count){
				$list_id = $list_id . ',';
			}
		}
	}
	@mssql_free_result($result);
	return $list_id;
}

//Kiem tra NSD da login vao truoc do chua (tra ve gia tri chuoi la danh sach id cua NSD da login)
function is_logged($p_ip_address, $p_app_code,$p_timeout){
	global $conn;
	global $_ISA_ALLOW_SSO;
	$list_id = '-1';
	$cmd = mssql_init("USER_IsLogged",$conn);
	@mssql_bind($cmd,"@p_ip_address",$p_ip_address,SQLVARCHAR);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	@mssql_bind($cmd,"@p_timeout",$p_timeout,SQLINT4);
	$result = mssql_execute($cmd);
	$arr=@mssql_fetch_array($result);
	if (sizeof($arr)>0){
		$list_id = $arr['FK_STAFF'] . '||' . $arr['C_NAME'];
	}	
	@mssql_free_result($result);
	//Neu khong dang nhap 1 lan
	if ($_ISA_ALLOW_SSO==0){
		$list_id = '-1';
	}
	return $list_id;
}

//Kiem tra NSD da login vao truoc do chua (tra ve gia tri chuoi XML chua danh sach id, name, username, password, app_code cua NSD da login)
function is_logged_from_app($p_ip_address, $p_timeout){
	global $conn;
	//$conn = @mssql_connect("svpdc","sa", "sa");
	//@mssql_select_db('[isa-user]');	
	$cmd = mssql_init("USER_IsLoggedFromApp",$conn);
	@mssql_bind($cmd,"@p_ip_address",$p_ip_address,SQLVARCHAR);
	@mssql_bind($cmd,"@p_timeout",$p_timeout,SQLINT4);
	$result = mssql_execute($cmd);
	$v_count = @mssql_num_rows($result);

	$strXML='<?xml version="1.0" encoding="UTF-8"?>';
	$strXML.="<enduser_group_list>";
	if ($v_count>0){
		while ($rs = @mssql_fetch_array($result)) {
			$strXML.= "<enduser_group>";
			$strXML.= "<id>" . $rs['FK_STAFF'] . "</id>";
			$strXML.= "<name>" . htmlspecialchars($rs['C_NAME']) . "</name>";
			$strXML.= "<username>" . htmlspecialchars($rs['C_USERNAME']) . "</username>";
			$strXML.= "<password>" . htmlspecialchars($rs['C_PASSWORD']) . "</password>";
			$strXML.= "<app_code>" . htmlspecialchars($rs['C_APP_CODE']) . "</app_code>";
			$strXML.= "</enduser_group>";
		}
	}else{
		$strXML.= "<enduser_group>";
		$strXML.= "<id></id>";
		$strXML.= "<name></name>";
		$strXML.= "<username></username>";
		$strXML.= "<password></password>";
		$strXML.= "<app_code></app_code>";
		$strXML.= "</enduser_group>";
	}
	$strXML.="</enduser_group_list>";
	@mssql_free_result($result);
	return $strXML;
}

//Cap nhat thoi gian cuoi cung STAFF_ID truy nhap mot trang bat ky cua ung dung dang thuc hien
function update_last_time($p_ip_address, $p_app_code,$p_staff_id){
	global $conn;
	$cmd = mssql_init("USER_UpdateLastTime",$conn);
	@mssql_bind($cmd,"@p_ip_address",$p_ip_address,SQLVARCHAR);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	@mssql_bind($cmd,"@p_staff_id",$p_staff_id,SQLINT4);
	$result = @mssql_execute($cmd);
	@mssql_free_result($result);
	return 'true';
}
//Lay thong tin ca nhan cua tat ca can bo (staff)
function get_personal_info_of_all_staff(){
	global $conn;
	$cmd = mssql_init("USER_GetPersonalInfoOfAllStaff",$conn);
	$result = mssql_execute($cmd);
	$strXML='<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$strXML.="<staff_list>" . "\n";
	while ($rs = @mssql_fetch_array($result)) {
		$strXML.= "<staff>" . "\n";
		$strXML.= "<id>" . $rs['PK_STAFF'] . "</id>" . "\n";
		$strXML.= "<name>" . htmlspecialchars($rs['C_NAME']) . "</name>" . "\n";
		$strXML.= "<code>" . htmlspecialchars($rs['C_CODE']) . "</code>" . "\n";
		$strXML.= "<unit_id>" . $rs['FK_UNIT'] . "</unit_id>" . "\n";
		$strXML.= "<position_code>" . htmlspecialchars($rs['C_POSITION_CODE']) . "</position_code>" . "\n";
		$strXML.= "<position_name>" . htmlspecialchars($rs['C_POSITION_NAME']) . "</position_name>" . "\n";
		$strXML.= "<position_group_code>" . htmlspecialchars($rs['C_POSITION_GROUP_CODE']) . "</position_group_code>" . "\n";
		$strXML.= "<address>" . htmlspecialchars($rs['C_ADDRESS']) . "</address>" . "\n";
		$strXML.= "<email>" . htmlspecialchars($rs['C_EMAIL']) . "</email>" . "\n";
		$strXML.= "<tel>" . htmlspecialchars($rs['C_TEL']) . "</tel>" . "\n";
		$strXML.= "<tel_mobile>" . htmlspecialchars($rs['C_TEL_MOBILE']) . "</tel_mobile>" . "\n";
		$strXML.= "<order>" . $rs['C_ORDER'] . "</order>" . "\n";
        $strXML.= "<permission>" . htmlspecialchars($rs['C_PERMISSION_LIST']) . "</permission>" . "\n";
		$strXML.= "</staff>" . "\n";
	}
	$strXML.="</staff_list>" . "\n";
	@mssql_free_result($result);
	return  $strXML;  
}

//Lay thong tin ca nhan cua tat ca enduser (nguoi su dung) cua mot ung dung
function get_personal_info_of_all_enduser($p_app_code){
	global $conn;
	$cmd = mssql_init("USER_GetPersonalInfoOfAllEndUser",$conn);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	$result = mssql_execute($cmd);
	$strXML='<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$strXML.="<staff_list>" . "\n";
	while ($rs = @mssql_fetch_array($result)) {
		$strXML.= "<staff>" . "\n";
		$strXML.= "<id>" . $rs['PK_STAFF'] . "</id>" . "\n";
		$strXML.= "<name>" . htmlspecialchars($rs['C_NAME']) . "</name>" . "\n";
		$strXML.= "<code>" . htmlspecialchars($rs['C_CODE']) . "</code>" . "\n";
		$strXML.= "<unit_id>" . $rs['FK_UNIT'] . "</unit_id>" . "\n";
		$strXML.= "<position_code>" . htmlspecialchars($rs['C_POSITION_CODE']) . "</position_code>" . "\n";
		$strXML.= "<position_name>" . htmlspecialchars($rs['C_POSITION_NAME']) . "</position_name>" . "\n";
		$strXML.= "<position_group_code>" . htmlspecialchars($rs['C_POSITION_GROUP_CODE']) . "</position_group_code>" . "\n";
		$strXML.= "<address>" . htmlspecialchars($rs['C_ADDRESS']) . "</address>" . "\n";
		$strXML.= "<email>" . htmlspecialchars($rs['C_EMAIL']) . "</email>" . "\n";
		$strXML.= "<tel>" . htmlspecialchars($rs['C_TEL']) . "</tel>" . "\n";
		$strXML.= "<order>" . $rs['C_ORDER'] . "</order>" . "\n";
		$strXML.= "</staff>" . "\n";
	}
	$strXML.="</staff_list>" . "\n";
	@mssql_free_result($result);
	return  $strXML;  
}
//Lay thong tin cua tat ca phong ban (unit)
function get_detail_info_of_all_unit(){
	global $conn;
	$cmd = mssql_init("USER_GetDetailInfoOfAllUnit",$conn);
	$result = mssql_execute($cmd);
	$strXML='<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$strXML.="<unit_list>" . "\n";
	while ($rs = @mssql_fetch_array($result)) {
		$strXML.= "<unit>" . "\n";
		$strXML.= "<id>" . $rs['PK_UNIT'] . "</id>" . "\n";
		$strXML.= "<parent_id>" . $rs['FK_UNIT'] . "</parent_id>" . "\n";
		$strXML.= "<name>" . htmlspecialchars($rs['C_NAME']) . "</name>" . "\n";
		$strXML.= "<code>" . htmlspecialchars($rs['C_CODE']) . "</code>" . "\n";
		$strXML.= "<address>" . htmlspecialchars($rs['C_ADDRESS']) . "</address>" . "\n";
		$strXML.= "<email>" . htmlspecialchars($rs['C_EMAIL']) . "</email>" . "\n";
		$strXML.= "<tel>" . htmlspecialchars($rs['C_TEL']) . "</tel>" . "\n";
		$strXML.= "<order>" . $rs['C_ORDER'] . "</order>" . "\n";
		$strXML.= "</unit>" . "\n";
	}
	$strXML.="</unit_list>" . "\n";
	@mssql_free_result($result);
	return  $strXML;  
}
//Lay danh sach tat cac cac nhom end-user cua mot ung dung
function get_all_group_of_application($p_app_code){
	global $conn;
	$cmd = mssql_init("USER_GetAllGroupOfApplication",$conn);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	$result = mssql_execute($cmd);
	$strXML='<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$strXML.="<group_list>" . "\n";
	while ($rs = @mssql_fetch_array($result)) {
		$strXML.= "<group>" . "\n";
		$strXML.= "<id>" . $rs['PK_GROUP'] . "</id>" . "\n";
		$strXML.= "<code>" . htmlspecialchars($rs['C_CODE']) . "</code>" . "\n";
		$strXML.= "<name>" . htmlspecialchars($rs['C_NAME']) . "</name>" . "\n";
		$strXML.= "<order>" . $rs['C_ORDER'] . "</order>" . "\n";
		$strXML.= "</group>" . "\n";
	}
	$strXML.="</group_list>" . "\n";
	@mssql_free_result($result);
	return  $strXML;  
}

// Lay danh sach tat ca cac Group thuoc mot ung dung ma $p_staff_id la thanh vien
function get_all_group_by_member($p_app_code,$p_staff_id){
	global $conn;
	$cmd = @mssql_init("USER_GroupGetAllByMember",$conn);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	@mssql_bind($cmd,"@p_staff_id",$p_staff_id,SQLINT4);
	$result = @mssql_execute($cmd);
	$v_count = @mssql_num_rows($result);
	$list_id = '';
	if ($v_count>0) {
		for ($i=1; $i<=$v_count; $i++){
			$arr=@mssql_fetch_array($result);
			$list_id = $list_id . $arr['PK_GROUP'];
			if ($i<$v_count){
				$list_id = $list_id . ',';
			}
		}
	}
	@mssql_free_result($result);
	return $list_id;
}

//Xoa NSD khoi bang T_USER_LOGON khi NSD logout khoi ung dung
function delete_enduser_logged($p_ip_address,$p_app_code,$p_staff_id){
	global $conn;
	$cmd = mssql_init("USER_Logout",$conn);
	@mssql_bind($cmd,"@p_ip_address",$p_ip_address,SQLVARCHAR);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	@mssql_bind($cmd,"@p_staff_id",$p_staff_id,SQLINT4);
	$result = mssql_execute($cmd);
	@mssql_free_result($result);
	return 'true';
}
//Lay danh sach nhom chuc danh
function get_all_position_group(){
	global $conn;
	$v_filter = "%%";
	$v_status = 1;
	$cmd = mssql_init("USER_PositionGroupGetAll",$conn);
	@mssql_bind($cmd,"@p_filter",$v_filter,SQLVARCHAR);
	@mssql_bind($cmd,"@p_status",$v_status, SQLINT4);
	$result = mssql_execute($cmd);
	$strXML='<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$strXML.="<position_group_list>" . "\n";
	while ($rs = @mssql_fetch_array($result)) {
		$strXML.= "<position_group>" . "\n";
		$strXML.= "<id>" . $rs['PK_POSITION_GROUP'] . "</id>" . "\n";
		$strXML.= "<code>" . htmlspecialchars($rs['C_CODE']) . "</code>" . "\n";
		$strXML.= "<name>" . htmlspecialchars($rs['C_NAME']) . "</name>" . "\n";
		$strXML.= "<order>" . $rs['C_ORDER'] . "</order>" . "\n";
		$strXML.= "</position_group>" . "\n";
	}
	$strXML.="</position_group_list>" . "\n";
	@mssql_free_result($result);
	return $strXML;
}

//Kiem tra u/p de dang nhap vao ISA-USER va tra lai u/p de dang nhap vao ung dung khac
function check_user_from_app($p_username,$p_password,$p_app_code){
	global $conn;
	$cmd = mssql_init("USER_CheckUserFromApp",$conn);
	@mssql_bind($cmd,"@p_username",$p_username,SQLVARCHAR);
	@mssql_bind($cmd,"@p_password",$p_password,SQLVARCHAR);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	$result = mssql_execute($cmd);
	$strXML='<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$strXML.="<user_list>" . "\n";
	while ($rs = @mssql_fetch_array($result)) {
		$strXML.= "<user>" . "\n";
		$strXML.= "<staff_id>" . $rs['PK_STAFF'] . "</staff_id>" . "\n";
		$strXML.= "<enduser_id>" . $rs['PK_ENDUSER'] . "</enduser_id>" . "\n";
		$strXML.= "<name>" . htmlspecialchars($rs['C_NAME']) . "</name>" . "\n";
		$strXML.= "<app_username>" . htmlspecialchars($rs['C_APP_USERNAME']) . "</app_username>" . "\n";
		$strXML.= "<app_password>" . htmlspecialchars($rs['C_APP_PASSWORD']) . "</app_password>" . "\n";
		$strXML.= "</user>" . "\n";
	}
	$strXML.="</user_list>" . "\n";
	@mssql_free_result($result);
	return  $strXML;  
}

//Lay danh sach enduser moi cua 1 ung dung
// p_app_code: ma cua ung dung
// p_current_staff_id_list: danh sach ID cua cac can bo (staff) la enduser hien thoi cua ung dung
// CHU Y: Cac enduser moi co ID khong thuoc danh sach p_current_staff_id_list
function get_new_enduser_of_single_app($p_app_code, $p_current_staff_id_list){
	global $conn;
	$cmd = mssql_init("USER_GetNewEndUserOfSingleApp",$conn);
	@mssql_bind($cmd,"@p_app_code",$p_app_code,SQLVARCHAR);
	@mssql_bind($cmd,"@p_current_staff_id_list",$p_current_staff_id_list,SQLVARCHAR);
	$result = mssql_execute($cmd);
	$strXML='<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$strXML.="<user_list>" . "\n";
	while ($rs = @mssql_fetch_array($result)) {
		$strXML.= "<user>" . "\n";
		$strXML.= "<staff_id>" . $rs['PK_STAFF'] . "</staff_id>" . "\n";
		$strXML.= "<enduser_id>" . $rs['PK_ENDUSER'] . "</enduser_id>" . "\n";
		$strXML.= "<app_username>" . htmlspecialchars($rs['C_APP_USERNAME']) . "</app_username>" . "\n";
		$strXML.= "<app_password>" . htmlspecialchars($rs['C_APP_PASSWORD']) . "</app_password>" . "\n";
		$strXML.= "<username>" . htmlspecialchars($rs['C_USERNAME']) . "</username>" . "\n";
		$strXML.= "<password>" . htmlspecialchars($rs['C_PASSWORD']) . "</password>" . "\n";
		$strXML.= "<name>" . htmlspecialchars($rs['C_NAME']) . "</name>" . "\n";
		$strXML.= "<code>" . htmlspecialchars($rs['C_CODE']) . "</code>" . "\n";
		$strXML.= "<unit_id>" . $rs['FK_UNIT'] . "</unit_id>" . "\n";
		$strXML.= "<unit_name>" . $rs['C_UNIT_NAME'] . "</unit_name>" . "\n";
		$strXML.= "<position_code>" . htmlspecialchars($rs['C_POSITION_CODE']) . "</position_code>" . "\n";
		$strXML.= "<position_name>" . htmlspecialchars($rs['C_POSITION_NAME']) . "</position_name>" . "\n";
		$strXML.= "<position_group_code>" . htmlspecialchars($rs['C_POSITION_GROUP_CODE']) . "</position_group_code>" . "\n";
		$strXML.= "<position_group_name>" . htmlspecialchars($rs['C_POSITION_GROUP_NAME']) . "</position_group_name>" . "\n";
		$strXML.= "<address>" . htmlspecialchars($rs['C_ADDRESS']) . "</address>" . "\n";
		$strXML.= "<email>" . htmlspecialchars($rs['C_EMAIL']) . "</email>" . "\n";
		$strXML.= "<tel>" . htmlspecialchars($rs['C_TEL']) . "</tel>" . "\n";
		$strXML.= "<tel_local>" . htmlspecialchars($rs['C_TEL_LOCAL']) . "</tel_local>" . "\n";
		$strXML.= "<tel_mobile>" . htmlspecialchars($rs['C_TEL_MOBILE']) . "</tel_mobile>" . "\n";
		$strXML.= "<tel_home>" . htmlspecialchars($rs['C_TEL_HOME']) . "</tel_home>" . "\n";
		$strXML.= "<fax>" . htmlspecialchars($rs['C_FAX']) . "</fax>" . "\n";
		$strXML.= "<sex>" . $rs['C_SEX'] . "</sex>" . "\n";
		$strXML.= "<birdthday>" . $rs['C_BIRTHDAY'] . "</birdthday>" . "\n";
		$strXML.= "<order>" . $rs['C_ORDER'] . "</order>" . "\n";
		$strXML.= "<status>" . $rs['C_STATUS'] . "</status>" . "\n";
		$strXML.= "</user>" . "\n";
	}
	$strXML.="</user_list>" . "\n";
	@mssql_free_result($result);
	return  $strXML;  
}

//Lay danh sach nguoi sinh nhat trong ngay hien thoi
function get_all_birthday_on_currendate(){
	global $conn;
	$cmd = mssql_init("USER_GetAllPersonalHaveBirthdayOnCurrenday",$conn);
	$result = mssql_execute($cmd);
	$strXML='<?xml version="1.0" encoding="UTF-8"?>';
	$strXML.="<birthday_group_list>";
	$v_count = @mssql_num_rows($result);
	if ($v_count>0){
		while ($rs = @mssql_fetch_array($result)) {
			$strXML.= "<birthday_group>";
			$strXML.= "<name>" . htmlspecialchars($rs['C_NAME']) . "</name>";
			$strXML.= "<position_code>" . htmlspecialchars($rs['C_POSITION_CODE']) . "</position_code>";
			$strXML.= "<position_name>" . htmlspecialchars($rs['C_POSITION_NAME']) . "</position_name>";
			$strXML.= "<unit_name>" . htmlspecialchars($rs['C_UNIT_NAME']) . "</unit_name>";
			$strXML.= "</birthday_group>";
		}
	}else{
		$strXML.= "<birthday_group>";
		$strXML.= "<name></name>";
		$strXML.= "<position_code></position_code>";
		$strXML.= "<position_name></position_name>";
		$strXML.= "<unit_name></unit_name>";
		$strXML.= "</birthday_group>";
	}
	$strXML.="</birthday_group_list>";
	@mssql_free_result($result);
	return $strXML;
}
//Lay danh sach cac ung dung tich hop trong ISA-USER
function get_all_application_on_isa_user(){
	global $conn;
	$cmd = mssql_init("User_GetAllApplication",$conn);
	$result = mssql_execute($cmd);
	$strXML='<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$strXML.="<application_list>" . "\n";
	$v_count = @mssql_num_rows($result);
	if ($v_count>0){
		while ($rs = @mssql_fetch_array($result)) {
			$strXML.= "<application>" . "\n";
			$strXML.= "<id>" . $rs['PK_APPLICATION'] . "</id>" . "\n";
			$strXML.= "<code>" . $rs['C_CODE'] . "</code>" . "\n";
			$strXML.= "<name>" . $rs['C_NAME'] . "</name>" . "\n";
			$strXML.= "<status>" . $rs['C_STATUS'] . "</status>" . "\n";
			$strXML.= "</application>" . "\n";
		}
	}
	$strXML.="</application_list>" . "\n";
	@mssql_free_result($result);
	return $strXML;
}

// Lay thong tin chi tiet ve mot NSD
function get_personal_info_of_user($p_user_id){
	global $conn;
	$cmd = mssql_init("USER_StaffGetSingle",$conn);
	@mssql_bind($cmd,"@p_item_id",$p_user_id,SQLVARCHAR);
	$result = mssql_execute($cmd);
	$v_count = @mssql_num_rows($result);

	$strXML='<?xml version="1.0" encoding="UTF-8"?>';
	$strXML.="<user_info>";
	if ($v_count>0){
		$rs = @mssql_fetch_array($result);
		$strXML.="<user>";
		$strXML.= "<id>" . $rs['PK_STAFF'] . "</id>";
		$strXML.= "<name>" . htmlspecialchars($rs['C_NAME']) . "</name>";
		$strXML.= "<username>" . htmlspecialchars($rs['C_USERNAME']) . "</username>";
		$strXML.= "<password>" . htmlspecialchars($rs['C_PASSWORD']) . "</password>";
		$strXML.= "<unit_name>" . htmlspecialchars($rs['C_UNIT_NAME']) . "</unit_name>";
		$strXML.= "<user_position>" . htmlspecialchars($rs['C_POSITION_NAME']) . "</user_position>";
		$strXML.= "<user_address>" . htmlspecialchars($rs['C_ADDRESS']) . "</user_address>";
		$strXML.= "<user_tel>" . htmlspecialchars($rs['C_TEL']) . "</user_tel>";
		$strXML.= "<user_tel_home>" . htmlspecialchars($rs['C_TEL_HOME']) . "</user_tel_home>";
		$strXML.= "<user_tel_mobile>" . htmlspecialchars($rs['C_TEL_MOBILE']) . "</user_tel_mobile>";
		$strXML.= "<user_email>" . htmlspecialchars($rs['C_EMAIL']) . "</user_email>";
		$strXML.= "<unit_id>" . htmlspecialchars($rs['C_UNIT_LEVEL1']) . "</unit_id>";
		$strXML.="</user>";
	}else{
		$strXML.="<user>";
		$strXML.= "<id></id>";
		$strXML.= "<name></name>";
		$strXML.= "<username></username>";
		$strXML.= "<password></password>";
		$strXML.= "<unit_name></unit_name>";
		$strXML.= "<user_position></user_position>";
		$strXML.= "<user_address></user_address>";
		$strXML.= "<user_tel></user_tel>";
		$strXML.= "<user_tel_home></user_tel_home>";
		$strXML.= "<user_tel_mobile></user_tel_mobile>";
		$strXML.= "<user_email></user_email>";
		$strXML.= "<unit_id></unit_id>";
		$strXML.="</user>";
	}
	$strXML.="</user_info>";
	@mssql_free_result($result);
	return $strXML;
}

//Lay thong tin can bo ung voi ma don vi p_owner_code
function webservice_get_all_staff_by_owner_code($p_owner_code=""){
	global $conn;
	//Lay ID cua don vi ung voi ma don vi
	if(trim($p_owner_code) != ""){
		$unit_id = 0;
		$cmd = mssql_init("USER_GetDetailInfoOfAllUnit",$conn);
		$result = mssql_execute($cmd);	
		while ($rs = @mssql_fetch_array($result)) {
			if(trim($rs['C_CODE']) == trim($p_owner_code)){
				$unit_id = $rs['PK_UNIT'];
				break;
			}
		}
		@mssql_free_result($result);
	}
	$cmd = mssql_init("USER_GetPersonalInfoOfAllStaff",$conn);
	$result = mssql_execute($cmd);
	$strXML='<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	$strXML.="<staff_list>" . "\n";
	while ($rs = @mssql_fetch_array($result)) {
		if($rs['FK_UNIT'] == $unit_id && $rs['C_STATUS'] == 1){ //Lay can bo dang hoat dong cua mot don vi
			$strXML.= "<staff>" . "\n";
			$strXML.= "<id>" . $rs['PK_STAFF'] . "</id>" . "\n";
			$strXML.= "<name>" . htmlspecialchars($rs['C_NAME']) ."</name>" . "\n";
			$strXML.= "<code>" . htmlspecialchars($rs['C_CODE']) . "</code>" . "\n";
			$strXML.= "<unit_id>" . $rs['FK_UNIT'] . "</unit_id>" . "\n";
			$strXML.= "<position_code>" . htmlspecialchars($rs['C_POSITION_CODE']) . "</position_code>" . "\n";
			$strXML.= "<position_name>" . htmlspecialchars($rs['C_POSITION_NAME']) . "</position_name>" . "\n";
			$strXML.= "<position_group_code>" . htmlspecialchars($rs['C_POSITION_GROUP_CODE']) . "</position_group_code>" . "\n";
			$strXML.= "<address>" . htmlspecialchars($rs['C_ADDRESS']) . "</address>" . "\n";
			$strXML.= "<email>" . htmlspecialchars($rs['C_EMAIL']) . "</email>" . "\n";
			$strXML.= "<tel>" . htmlspecialchars($rs['C_TEL']) . "</tel>" . "\n";
			$strXML.= "<tel_mobile>" . htmlspecialchars($rs['C_TEL_MOBILE']) . "</tel_mobile>" . "\n";
			$strXML.= "<order>" . $rs['C_ORDER'] . "</order>" . "\n";
			$strXML.= "<user_name>" . $rs['C_USERNAME'] . "</user_name>" . "\n";
			$strXML.= "<password>" . md5($rs['C_PASSWORD']) . "</password>" . "\n";
			$strXML.= "<status>" . htmlspecialchars($rs['C_STATUS']) . "</status>" . "\n";
			$strXML.= "</staff>" . "\n";
		}	
	}
	$strXML.="</staff_list>" . "\n";
	@mssql_free_result($result);
	return  $strXML;  
}
// Cap nhat thong tin Setpas cua mot NSD 
function update_ifo_setpass_user($p_user_id,$p_user_name,$p_user_username,$p_user_password){
	global $conn;
	
	//echo 'p_user_id:' . $p_user_id . '<br>';
	$cmd = mssql_init("USER_SetpasUpdate",$conn);
	@mssql_bind($cmd,"@p_user_id",$p_user_id,SQLVARCHAR);
	@mssql_bind($cmd,"@p_name",$p_user_name,SQLVARCHAR);
	@mssql_bind($cmd,"@p_username",$p_user_username,SQLVARCHAR);
	@mssql_bind($cmd,"@p_password",$p_user_password,SQLVARCHAR);	
	$result = @mssql_execute($cmd);
	@mssql_free_result($result);	
	return 'true';	
}

$s = new soap_server;
$s->register('get_all_application_on_isa_user');
$s->register('get_all_birthday_on_currendate');
$s->register('set_permision_on_function');
$s->register('check_permision_on_modul');
$s->register('check_permision_on_function');
$s->register('is_app_enduser');
$s->register('is_logged');
$s->register('get_all_modul_granted_for_user');
$s->register('get_all_public_modul');
$s->register('get_all_function_granted_for_user');
$s->register('get_all_public_function');
$s->register('update_last_time');
$s->register('get_personal_info_of_all_staff');
$s->register('get_personal_info_of_all_enduser');
$s->register('get_detail_info_of_all_unit');
$s->register('get_all_group_of_application');
$s->register('get_all_group_by_member');
$s->register('delete_enduser_logged');
$s->register('get_all_position_group');
$s->register('check_user_from_app');
$s->register('get_new_enduser_of_single_app');
$s->register('is_logged_from_app');
$s->register('get_personal_info_of_user');
$s->register('webservice_get_all_staff_by_owner_code');
$s->register('update_ifo_setpass_user');
$s->service($HTTP_RAW_POST_DATA);
?>