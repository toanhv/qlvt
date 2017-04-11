<?php 
if ($_ISA_INTEGRATE_LDAP == 1){
	include "../connect_ldap.php";
	include "../ldap_functions.php";
}	
//$v_ip_address = _get_remote_ip_address();
$v_ip_address = "";
if(isset($_REQUEST['staff_id'])){
	$v_ip_address = _replace_bad_char($_REQUEST['ip_address']); // Lay theo cookie
}
$v_url_back = $_REQUEST['url_back'];
$v_app_code = _replace_bad_char($_REQUEST['app_code']);
$v_username = _replace_bad_char($_REQUEST['txt_usename']);
$v_password = _replace_bad_char($_REQUEST['txt_password']);
$v_password = md5($v_password);

$v_username_login = $v_username;
$v_password_login = $v_password;
if (is_null($v_url_back)) {
	$v_url_back = "../org/index.php";
}
// Neu tich hop voi LDAP
if ($_ISA_INTEGRATE_LDAP==1){
	if (strpos($v_username,$_ISA_LDAP_USER_DN)===false){
		if ($_ISA_HOST_TYPE=="AD"){
			$v_accountname = $v_username;
			$v_username = $v_username ."@" . $_ISA_LDAP_DOMAIN;			
		}elseif($_ISA_HOST_TYPE=="DOMINO"){
			$v_accountname = $v_username;
		}else{
			$v_username = $_ISA_LDAP_USERNAME_ATTRIBUTE."=" . $v_username . "," . $_ISA_LDAP_USER_DN;
		}
	}

	$v_checklogin = LDAP_CheckLogin($v_username, $v_password);
	if ($v_checklogin){	// Dang nhap thanh cong
		if ($_ISA_HOST_TYPE=="AD" || $_ISA_HOST_TYPE=="DOMINO"){
			$v_dn = LDAP_get_dn_value($v_accountname);
			$v_username = $v_dn[0]['dn'];
		}
		//echo $v_username; exit;
		$arr_staff_login = _get_staff_info_by_DN($v_username);
		//var_dump($arr_staff_login);
		if (sizeof($arr_staff_login)>0){
			$staff_id = $arr_staff_login[0];
			$user_id = $arr_staff_login[0];
			$user_name = $arr_staff_login[1];
			$is_isa_user_admin = $arr_staff_login[2];
			$is_isa_app_admin = $arr_staff_login[3];
		}else{?>
			<script>
				alert("Can phai xac dinh MOI QUAN HE giua 01 NSD trong CSDL LDAP voi 01 can bo cua ISA-USER");
				window.history.back();
			</script><?php	
			exit;	
		}	
	}else{?>
		<script>
			alert("Sai ten hoac mat khau dang nhap");
			window.history.back();
		</script><?php
		exit;	
	}
}else{
	if (_is_sqlserver()){
		$v_sql_string = "Exec USER_CheckLogin ";
		$v_sql_string.= "'".$v_username."'";
		$v_sql_string.= ",'".$v_password."'";
		$v_sql_string.= ",'".$_ISA_APP_CODE."'";
		//echo $v_sql_string; exit;
		$arr_staff_login = _adodb_query_data_in_number_mode($v_sql_string);
	}
	//echo sizeof($arr_staff_login); exit; 
	if (sizeof($arr_staff_login) == 0){?>
		<script>
			alert("Sai ten hoac mat khau hoac NSD nay khong co quyen thu hien ung dung nay");
			window.history.back();
		</script><?php
		exit;
	}else{
		// Dang nhap thanh cong
		$staff_id = $arr_staff_login[0][0];
		$user_id = $arr_staff_login[0][0];
		$user_name = $arr_staff_login[0][2];
		$is_isa_user_admin = $arr_staff_login[0][3];
		$is_isa_app_admin = $arr_staff_login[0][4];
	}
}
//Khoi tao cac gia tri cho nguoi dang nhap thanh cong
$_SESSION['staff_id'] = $staff_id;
$_SESSION['user_id'] = $staff_id;
$_SESSION['user_name'] = $user_name;
$_SESSION['is_isa_user_admin'] = $is_isa_user_admin;
$_SESSION['is_isa_app_admin'] = $is_isa_app_admin;
$_SESSION['is_isa_user_path'] = _get_current_http_and_host().$_ISA_WEB_SITE_PATH;
///////////////////////////////////////

$v_value_guid = _generate_guid();
_create_cookie('guid_cookie',$v_value_guid);
//exit;

if (_is_sqlserver()){

	$v_sql_string = "Exec USER_UpdateLastTime ";
	$v_sql_string.= "'".$v_value_guid."'";
	$v_sql_string.= ",'".$v_app_code."'";
	$v_sql_string.= ",".$staff_id."";
	//echo $v_sql_string;
	$result = _adodb_exec_update_delete_sql($v_sql_string);
	$v_sql_string = "Exec USER_ApplicationGetSingleByCode ";
	$v_sql_string.= "'".$v_app_code."'";
	$arr_single_application = _adodb_query_data_in_number_mode($v_sql_string);
	
	if (sizeof($arr_single_application)>0){
		$v_item_url_path = $arr_single_application[0][8];
		$v_username_var = $arr_single_application[0][9];
		$v_password_var = $arr_single_application[0][10];
		$v_varible_name_list = $arr_single_application[0][11];
		$v_varible_value_list = $arr_single_application[0][12];
		$arr_varible_name_list = explode(_CONST_SUB_LIST_DELIMITOR,$v_varible_name_list);
		$arr_varible_value_list = explode(_CONST_SUB_LIST_DELIMITOR,$v_varible_value_list);
		$v_url_back = $v_item_url_path;
	}else{
		$v_item_url_path = "";
		$v_username_var = "";
		$v_password_var = "";
		$v_varible_name_list = "";
		$v_varible_value_list = "";
		$arr_varible_name_list = array();
		$arr_varible_value_list = array();
	}
}

?>
<form action="<?php echo $v_url_back;?>" name="f_back" method="post">
	<input type="hidden" name="fuseaction" value="DISPLAY_LOGIN">
	<input type="hidden" name="logon_staff_id" value="<?php echo $staff_id;?>">
	<input type="hidden" name="logon_user_id" value="<?php echo $user_id;?>">
	<input type="hidden" name="logon_user_name" value="<?php echo $user_name;?>">
	<input type="hidden" name="logon_is_isa_user_admin" value="<?php echo $is_isa_user_admin;?>">
	<input type="hidden" name="logon_is_isa_app_admin" value="<?php echo $is_isa_app_admin;?>">
	<input type="hidden" name="url_back" value="<?php echo $v_url_back;?>">
	<?php
	if (sizeof($arr_single_application)>0){?>
		<input type="hidden" name="<? echo $v_username_var;?>" value="<?php echo $v_username_login;?>">
		<input type="hidden" name="<? echo $v_password_var;?>" value="<?php echo $v_password_login;?>">
		<?php 
			for ($i=0; $i< sizeof($arr_varible_name_list); $i++){?>
				<input type="hidden" name="<?php echo $arr_varible_name_list[$i];?>"  value="<?php echo $arr_varible_value_list[$i];?>"><?php
			}
	}?>
</form>		
<script language="javascript">
	document.forms[0].submit();
</script> 