<?php
// Kiem tra dang nhap
function LDAP_GetParentOU($p_dn){
	global $_ISA_LDAP_UNIT_DN;
	$v_ret = "";
	$v_pos = strpos($p_dn,",");
	if (strcasecmp($_ISA_LDAP_UNIT_DN,$p_dn)==0){
		$v_ret="NULL";
	}else{	
		if ($v_pos>=0){
			$v_ret = substr($p_dn,$v_pos+1,strlen($p_dn)-$v_pos);
		}else{
		$v_ret="NULL";
		}	
	}	
	return $v_ret;
}
function LDAP_GetOU($p_dn){
	$v_ret = "";
	if ($p_dn!="" && !is_null($p_dn)){
		$v_array = explode(",",$p_dn);
		$v_ret = substr($v_array[0],3,strlen($v_array[0]));
	}
	return $v_ret;
}
// Lay CN tu DN
function LDAP_GetCN($p_dn){
	global $_ISA_LDAP_USERNAME_ATTRIBUTE;
	$v_ret = "";
	if (strpos($p_dn,$_ISA_LDAP_USERNAME_ATTRIBUTE."=")>=0){
		$arr_dn_item = explode(",",$p_dn);
		$cn = $arr_dn_item[0];
		$arr_cn_item = explode("=",$cn);
		$v_ret = $arr_cn_item[1];
	}	
	return $v_ret;
}

// Kiem tra dang nhap
function LDAP_CheckLogin($p_username, $p_password) {
	global $ldap_conn;
	$v_ldapbind = @ldap_bind($ldap_conn, $p_username, $p_password);
	return $v_ldapbind;
}
function LDAP_GetSingleUser($p_username){
	global $ldap_conn,$_ISA_LDAP_USER_OBJECTCLASS;
	// Tim tat ca cac Object thuoc object class inetOrgPerson - tat ca cac object chi User
	$v_filter = $_ISA_LDAP_USER_OBJECTCLASS;
	$v_search_result = @ldap_search($ldap_conn, $p_username, $v_filter);
	if ($v_search_result){
		$v_user_info = @ldap_get_entries($ldap_conn, $v_search_result);
		return ($v_user_info[0]);
	}else{
		return false;
	}
}
//Lay danh sach tat ca cac can bo trong LDAP server
function LDAP_GetAllUser($p_filter) {
	global $ldap_conn;
	global $_ISA_LDAP_ROOT_DN;
	global $_ISA_LDAP_USER_DN;
	global $_ISA_LDAP_USER_OBJECTCLASS;
	
	$dn = $_ISA_LDAP_USER_DN; //Tim kiem tren mot nhanh nhat dinh tuong uong voi user c?a 1 don vi
	
	$filter = $_ISA_LDAP_USER_OBJECTCLASS;
	$sr = @ldap_search($ldap_conn, $dn, $filter);
	
	if ($sr) {
		@ldap_sort($ldap_conn, $sr, "");
		$info = @ldap_get_entries($ldap_conn, $sr);
		return ($info);
	}else{
		return false;
	}
}
//Tim kiem nguoi su dung
function LDAP_SearchUser($p_dn){
	global $ldap_conn,$_ISA_LDAP_USER_OBJECTCLASS;
	// Tim tat ca cac Object thuoc object class inetOrgPerson - tat ca cac object chi User
	$v_filter = $_ISA_LDAP_USER_OBJECTCLASS;
	$result = @ldap_search($ldap_conn, $p_dn, $v_filter);
	if ($result){
		return true;
	}else{
		return false;
	}
}
function LDAP_UpdatePassword($p_user_dn,$p_password){
	global $ldap_conn;
	global $_ISA_LDAP_PASSWORD_ATTRIBUTE;
    $info[$_ISA_LDAP_PASSWORD_ATTRIBUTE]=$p_password;
	ldap_mod_replace($ldap_conn, $p_user_dn, $info);
	//exit;
	//LDAP_UpdateSingleAtribute($p_user_dn,$_ISA_LDAP_PASSWORD_ATTRIBUTE,$p_password);
	return ;
}

//Cap nhat NSD
function LDAP_UpdateUser($p_user_dn,$p_username,$p_password,$p_displayname,$p_employeetype,$p_registeredaddress,$p_mailaddress,$p_telephonenumber,$p_mobile,$p_homephone,$p_facsimiletelephonenumber,$p_ou,$p_o){
	global $_ISA_LDAP_PASSWORD_ATTRIBUTE;
	$v_message = "";
	if ($p_user_dn!=""){
		LDAP_UpdateSingleAtribute($p_user_dn,"uid",$p_username);
		LDAP_UpdateSingleAtribute($p_user_dn,$_ISA_LDAP_PASSWORD_ATTRIBUTE,$p_password);
		LDAP_UpdateSingleAtribute($p_user_dn,"displayname",$p_displayname);
		LDAP_UpdateSingleAtribute($p_user_dn,"employeetype",$p_employeetype);
		LDAP_UpdateSingleAtribute($p_user_dn,"registeredaddress",$p_registeredaddress);
		LDAP_UpdateSingleAtribute($p_user_dn,"mailaddress",$p_mailaddress);
		LDAP_UpdateSingleAtribute($p_user_dn,"telephonenumber",$p_telephonenumber);
		LDAP_UpdateSingleAtribute($p_user_dn,"mobile",$p_mobile);
		LDAP_UpdateSingleAtribute($p_user_dn,"homephone",$p_homephone);
		LDAP_UpdateSingleAtribute($p_user_dn,"facsimiletelephonenumber",$p_facsimiletelephonenumber);
		LDAP_UpdateSingleAtribute($p_user_dn,"ou",$p_ou);
		LDAP_UpdateSingleAtribute($p_user_dn,"o",$p_o);
	}else{
		$v_message = LDAP_AddUser($p_user_dn,$p_username,$p_password,$p_displayname,$p_employeetype,$p_registeredaddress,$p_mailaddress,$p_telephonenumber,$p_mobile,$p_homephone,$p_facsimiletelephonenumber,$p_ou,$p_o);
	}
	return $v_message;
}
//Them moi NSD
function LDAP_AddUser($p_user_dn,$p_username,$p_password,$p_displayname,$p_employeetype,$p_registeredaddress,$p_mailaddress,$p_telephonenumber,$p_mobile,$p_homephone,$p_facsimiletelephonenumber,$p_ou,$p_o){
	global $ldap_conn;	
	global $_ISA_LDAP_USER_DN;
	global $_ISA_LDAP_USER_OBJECTCLASS ;
	global $_ISA_LDAP_USERNAME_ATTRIBUTE;
	$v_dn = $_ISA_LDAP_USERNAME_ATTRIBUTE."=" . $p_username . "," . $_ISA_LDAP_USER_DN;
	$info[$_ISA_LDAP_USERNAME_ATTRIBUTE] =  (string)$p_username;
	$info["sn"] =  (string)$p_username;
	$info["uid"] = (string)$p_username;
	$info[$_ISA_LDAP_PASSWORD_ATTRIBUTE] = $p_password;
	$info["displayname"] = $p_displayname;	
	if ($p_employeetype!="" && !is_null($p_employeetype)){
		$info["employeetype"] = $p_employeetype;
	}
	if ($p_telephonenumber!="" && !is_null($p_telephonenumber)){
		$info["telephonenumber"] = $p_telephonenumber;
	}
	if ($p_mobile!="" && !is_null($p_mobile)){
		$info["mobile"] = $p_mobile;
	}
	if ($p_homephone!="" && !is_null($p_homephone)){
		$info["homephone"] = $p_homephone;
	}
	if ($p_facsimiletelephonenumber!="" && !is_null($p_facsimiletelephonenumber)){
		$info["facsimiletelephonenumber"] = $p_facsimiletelephonenumber;
	}
	if ($p_ou!="" && !is_null($p_ou)){
		$info["ou"] = $p_ou;
	}
	$info["o"] = $p_o;
	$info["objectClass"][0] = "top";
	$info["objectClass"][1] = $_ISA_LDAP_USER_OBJECTCLASS;
	if (!LDAP_SearchUser($v_dn)){
		$rs=@ldap_add($ldap_conn, $v_dn, $info);
		if (!$rs){
			return "Cap nhat nguoi su dung khong thanh cong";
		}
	}else{
		return "Ten dang nhap nay da ton tai";
	}
	return NULL;
}
// Xoa mot hoac nhieu NSD
function LDAP_DeleteUser($p_list_dn){
	global $ldap_conn;
	$v_dn = explode(_CONST_LIST_DELIMITOR,$p_list_dn);
	if ($p_list_dn!="" && !is_null($p_list_dn)){
		for ($i=0; $i<sizeof($v_dn);$i++){
			if (!ldap_delete($ldap_conn,$v_dn[$i])){
				return "Xoa nguoi su dung khong thanh cong";
			}
		}
	}	
	return NULL;
}
// Cap nhat mot thuoc tinh
function LDAP_UpdateSingleAtribute($p_dn,$p_atribute,$p_value){
	global $ldap_conn;
	if ($p_value=="" || is_null($p_value)){
		$info[$p_atribute] = array();
		@ldap_mod_del($ldap_conn, $p_dn, $info);
	}else{
	    $info[$p_atribute]=$p_value;
		@ldap_mod_add($ldap_conn, $p_dn, $info);
		@ldap_mod_replace($ldap_conn, $p_dn, $info);
	}	
}

// Lay mot thuoc tinh
function LDAP_GetSingleAtribute($p_dn,$p_atribute){
	global $ldap_conn;
	global $_ISA_LDAP_ROOT_DN;
	$filter = "objectclass=*";
	$sr = @ldap_search($ldap_conn, $p_dn,$filter,array($p_atribute));
	if ($sr){
		$v_info = @ldap_get_entries($ldap_conn, $sr);
		return ($v_info[0][$p_atribute][0]);
	}else{
		return false;
	}
}

//Lay danh sach tat ca cac NHOM trong LDAP server
function LDAP_GetSingleGroup($p_group_dn) {
	global $ldap_conn,$_ISA_LDAP_GROUP_OBJECTCLASS;
	// Tim tat ca cac Object thuoc object class inetOrgPerson - tat ca cac object chi User
	$v_filter = "objectClass=" . $_ISA_LDAP_GROUP_OBJECTCLASS;
	$v_search_result = @ldap_search($ldap_conn, $p_group_dn, $v_filter);
	if ($v_search_result){
		$v_group_info = @ldap_get_entries($ldap_conn, $v_search_result);
		return ($v_group_info[0]);
	}else{
		return false;
	}
}

//Lay danh sach tat ca cac NHOM trong LDAP server
function LDAP_GetAllGroup($p_base_dn) {
	global $ldap_conn;
	global $_ISA_LDAP_ROOT_DN;
	global $_ISA_LDAP_UNIT_OBJECTCLASS;
	global $_ISA_LDAP_GROUP_OBJECTCLASS;
	
	$filter = "objectclass=$_ISA_LDAP_GROUP_OBJECTCLASS";
	$sr = @ldap_search($ldap_conn, $p_base_dn, $filter);
	if ($sr) {
		$info = @ldap_get_entries($ldap_conn, $sr);
		return ($info);
	} else {
		return false;
	}
}

// Lay danh sach NHOM maf $staff_id la thanh vien
function LDAP_GroupGetAllByMember($p_member_dn) {
	global $ldap_conn;
	global $_ISA_LDAP_ROOT_DN;
	global $_ISA_LDAP_UNIT_OBJECTCLASS;
	global $_ISA_LDAP_GROUP_DN;
	$dn = $_ISA_LDAP_ROOT_DN; //Tim kiem tren toan bo du lieu trong subtree _ISA_LDAP_UNIT_DN
	
	$filter = "member=$p_member_dn";
	$sr = @ldap_search($ldap_conn, $dn, $filter);
	if ($sr) {
		$info = @ldap_get_entries($ldap_conn, $sr);
		return ($info);
	} else {
		return false;
	}
}

//Them member vao mot nhom
function LDAP_AddMemberToGroup($p_group_dn,$p_member_dn) {
	global $ldap_conn;
    $info["member"]=$p_member_dn;
	if (@ldap_mod_add($ldap_conn, $p_group_dn, $info)){
		return true;
	}else{
		return false;
	}
}
//Them member vao mot nhom
function LDAP_DeleteMemberFromGroup($p_group_dn,$p_member_dn) {
	global $ldap_conn;
    $info["member"]=$p_member_dn;
	if (@ldap_mod_del($ldap_conn, $p_group_dn, $info)){
		return true;
	}else{
		return false;
	}
}
function LDAP_GetAllUnit() {
	global $ldap_conn;
	global $_ISA_LDAP_ROOT_DN;
	global $_ISA_LDAP_UNIT_OBJECTCLASS;
	global $_ISA_LDAP_UNIT_DN;
	global $_ISA_LDAP_USERNAME_ATTRIBUTE;
	$v_base_dn = $_ISA_LDAP_UNIT_DN;  //Tim kiem tren toan bo du lieu trong subtree _ISA_LDAP_UNIT_DN
	
	$filter = "(&(objectclass=$_ISA_LDAP_UNIT_OBJECTCLASS)(!(ou=*resources*))(!(ou=*roles*)))";
	$sr = @ldap_search($ldap_conn, $v_base_dn, $filter,array("dn","ou",$_ISA_LDAP_USERNAME_ATTRIBUTE));
	if ($sr) {
		$info = @ldap_get_entries($ldap_conn, $sr);
		return ($info);
	} else {
		return false;
	}
}
//Lay cac thong tin chi tiet ve phong ban
function LDAP_GetSingleUnit($p_unitname){
	global $ldap_conn;
	global $_ISA_LDAP_UNIT_OBJECTCLASS;
	// Tim tat ca cac Object thuoc object class inetOrgPerson - tat ca cac object chi User
	$v_filter = "objectClass=" . $_ISA_LDAP_UNIT_OBJECTCLASS;
	$v_search_result = @ldap_search($ldap_conn, $p_unitname, $v_filter);
	if ($v_search_result){
		$v_user_info = @ldap_get_entries($ldap_conn, $v_search_result);
		return ($v_user_info[0]);
	}else{
		return false;
	}
}
//Them moi mot phong ban
function LDAP_AddUnit($p_unit_dn,$p_unit_parent,$p_unitname,$p_registeredaddress){
	global $ldap_conn;	
	global $_ISA_LDAP_UNIT_OBJECTCLASS ;
	$v_dn = "ou=" . $p_unitname . "," . $p_unit_parent;
	$info["ou"] =  (string)$p_unitname;
	//$info["registeredAddress"] =  (string)$p_registeredaddress;
	$info["objectClass"][0] = "top";
	$info["objectClass"][1] = $_ISA_LDAP_UNIT_OBJECTCLASS;
	if (!LDAP_SearchUnit($v_dn)){
		$rs=@ldap_add($ldap_conn, $v_dn, $info);
		if (!$rs){
			return "Cap nhat don vi khong thanh cong";
		}
	}else{
		return "Don vi nay da ton tai";
	}
	return NULL;
}
//Cap nhat Phong ban
function LDAP_UpdateUnit($p_unit_dn,$p_unit_parent,$p_unitname,$p_registeredaddress){
	global $ldap_conn;
	$v_message = "";
	if ($p_unit_dn!="" && !is_null($p_unit_dn)){
		if ($p_unitname=="" || is_null($p_unitname)){
			$info[$p_atribute] = array();
			@ldap_mod_del($ldap_conn, $p_unit_dn, $info);
		}else{
			$info["ou"] = (string)$p_unitname;
			ldap_modify($ldap_conn, $p_unit_dn, $info);
		}	
		//LDAP_UpdateSingleAtribute($p_unit_dn,"ou",$p_unitname);
	}else{
		$v_message = LDAP_AddUnit($p_unit_dn,$p_unit_parent,$p_unitname,$p_registeredaddress);
	}
	return $v_message;
}
// Xoa mot hoac nhieu Phong ban
function LDAP_DeleteUnit($p_list_dn){
	global $ldap_conn;
	$v_dn = explode(_CONST_LIST_DELIMITOR,$p_list_dn);
	if ($p_list_dn!="" && !is_null($p_list_dn)){
		for ($i=0; $i<sizeof($v_dn);$i++){
			if (!ldap_delete($ldap_conn,$v_dn[$i])){
				return "Khong duoc xoa phong ban nay";
			}
		}
	}	
	return NULL;
}
//Tim kiem phong ban
function LDAP_SearchUnit($p_dn){
	global $ldap_conn,$_ISA_LDAP_UNIT_OBJECTCLASS;
	$v_filter = "objectClass=" . $_ISA_LDAP_UNIT_OBJECTCLASS;
	$result = ldap_search($ldap_conn, $p_dn, $v_filter);
	if ($result){
		return true;
	}else{
		return false;
	}
}
// Lay DN cua 1 OU ma $p_member_dn la thanh vien
function LDAP_GetUnitIDOfMember($p_member_dn) {
	global $ldap_conn;
	global $_ISA_LDAP_ROOT_DN;
	global $_ISA_LDAP_UNIT_DN;
	global $_ISA_LDAP_UNIT_OBJECTCLASS;
	$dn = $_ISA_LDAP_UNIT_DN; //Tim kiem tren toan bo du lieu trong subtree _ISA_LDAP_UNIT_DN
	
	$filter = "(&(member=$p_member_dn)(&(!(ou=*resources*))(!(ou=*roles*))))";

	$sr = @ldap_search($ldap_conn, $dn, $filter);
	if ($sr) {
		$info = @ldap_get_entries($ldap_conn, $sr);
		//var_dump($info[0]["dn"]);
		return $info[0]["dn"];
	} else {
		return false;
	}
}

// Lay thong tin cua mot entry bat ky
function LDAP_get_entry_info($dn){
	global $ldap_conn;
	global $_ISA_LDAP_ROOT_DN;

	// Tim tat ca cac Object thuoc object class inetOrgPerson - tat ca cac object chi User
	$filter = "objectclass=*";
	
	$sr = @ldap_search($ldap_conn, $dn, $filter);
	if ($sr) {
		$info = @ldap_get_entries($ldap_conn, $sr);
		return ($info);
	} else {
		return false;
	}
}
function LDAP_get_dn_value($username){
	global $ldap_conn;
	global $_ISA_LDAP_USER_DN,$_ISA_LDAP_USERNAME_ATTRIBUTE;

	$filter = $_ISA_LDAP_USERNAME_ATTRIBUTE."=".$username;
	
	$sr = @ldap_search($ldap_conn, $_ISA_LDAP_USER_DN, $filter);
	
	if ($sr) {
		$info = @ldap_get_entries($ldap_conn, $sr);
		return ($info);
	} else {
		return false;
	}	
}
?>
 