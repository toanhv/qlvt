<?php
if(isset($_REQUEST['hdn_item_id'])){
	$v_item_id = intval($_REQUEST['hdn_item_id']);
}else{
	$v_item_id = 0;
}
$v_current_position = 0;
if(isset($_REQUEST['hdn_current_position'])){
	$v_current_position = intval($_REQUEST['hdn_current_position']);
}
$v_item_code = _replace_bad_char(strtoupper($_REQUEST['txt_code']));
$v_item_name = _replace_bad_char($_REQUEST['txt_name']);
$v_item_birthday = _replace_bad_char($_REQUEST['txt_birthday']);
$v_item_address = _replace_bad_char($_REQUEST['txt_address']);
$v_item_tel_local = _replace_bad_char($_REQUEST['txt_tel_local']);
$v_item_tel_office = _replace_bad_char($_REQUEST['txt_tel_office']);
$v_item_tel_mobile = _replace_bad_char($_REQUEST['txt_tel_mobile']);
$v_item_tel_home = _replace_bad_char($_REQUEST['txt_tel_home']);
$v_item_fax = _replace_bad_char($_REQUEST['txt_fax']);
$v_item_email = _replace_bad_char($_REQUEST['txt_email']);
$v_item_username = _replace_bad_char($_REQUEST['txt_username']);
$v_item_password = _replace_bad_char($_REQUEST['txt_password']);
//$v_item_password = md5($v_item_password);

$v_position_id = intval($_REQUEST['hdn_position_id']);
$v_unit_id = intval($_REQUEST['hdn_unit_id']);
$v_item_order =  intval($_REQUEST['txt_order']);
if($v_item_order == 0){
	$v_item_order = NULL;
}	
$v_item_status = intval($_REQUEST['hdn_item_status']);
$v_password_old = $_REQUEST['hdn_password_old'];
// Xu ly trong truong hop co thay doi password
if($v_item_password !=""){
	$v_item_password = md5($v_item_password);
}
// Xu ly trong truong hop khong thay doi password
else{
	$v_item_password = $v_password_old;
}

$v_item_role = intval($_REQUEST['hdn_item_role']);
$v_item_ldap_dn = _replace_bad_char($_REQUEST['hdn_ldap_dn']);
$v_save_and_add_new = intval($_REQUEST['hdn_save_and_add_new']);
//Xu ly dat lai ngay sinh theo dang yyyy/mm/dd
if ($v_item_birthday!=""){
	$arr_birthday = explode('/',$v_item_birthday);
	if (strlen($arr_birthday[2])==2){
		$v_item_birthday = '20'.$arr_birthday[2].'/'.$arr_birthday[1].'/'.$arr_birthday[0];
	}else{
		$v_item_birthday = $arr_birthday[2].'/'.$arr_birthday[1].'/'.$arr_birthday[0];
	}
}else{
	$v_item_birthday = NULL;
}
$v_url = "index.php?modal_dialog_mode=1" . "&hdn_item_id=" . $v_item_id . "&fuseaction=DISPLAY_SINGLE_STAFF" ;
if(_is_sqlserver()){
	/*
	$cmd = @mssql_init("USER_StaffUpdate",$conn);
	@mssql_bind($cmd,"@p_item_id",$v_item_id,SQLINT4);
	@mssql_bind($cmd,"@p_position_id",$v_position_id,SQLINT4);
	@mssql_bind($cmd,"@p_unit_id",$v_unit_id,SQLINT4);
	@mssql_bind($cmd,"@p_code",$v_item_code,SQLVARCHAR);
	@mssql_bind($cmd,"@p_name",$v_item_name,SQLVARCHAR);
	@mssql_bind($cmd,"@p_birthday",$v_item_birthday,SQLVARCHAR);
	@mssql_bind($cmd,"@p_address",$v_item_address,SQLVARCHAR);
	@mssql_bind($cmd,"@p_tel_local",$v_item_tel_local,SQLVARCHAR);
	@mssql_bind($cmd,"@p_tel_office",$v_item_tel_office,SQLVARCHAR);
	@mssql_bind($cmd,"@p_tel_mobile",$v_item_tel_mobile,SQLVARCHAR);
	@mssql_bind($cmd,"@p_tel_home",$v_item_tel_home,SQLVARCHAR);
	@mssql_bind($cmd,"@p_fax",$v_item_fax,SQLVARCHAR);
	@mssql_bind($cmd,"@p_email",$v_item_email,SQLVARCHAR);
	@mssql_bind($cmd,"@p_username",$v_item_username,SQLVARCHAR);
	@mssql_bind($cmd,"@p_password",$v_item_password,SQLVARCHAR);
	@mssql_bind($cmd,"@p_order",$v_item_order,SQLINT4);
	@mssql_bind($cmd,"@p_status",$v_item_status,SQLINT4);
	@mssql_bind($cmd,"@p_role",$v_item_role,SQLINT4);
	@mssql_bind($cmd,"@p_ldap_dn",$v_item_ldap_dn,SQLVARCHAR);
	$result = @mssql_execute($cmd);
	$rs = @mssql_fetch_array($result);
	@mssql_free_result($result);
	*/
	$v_sql_string = "Exec USER_StaffUpdate ";
	$v_sql_string.= "".$v_item_id."";
	$v_sql_string.= ",".$v_unit_id."";
	$v_sql_string.= ",".$v_position_id."";
	$v_sql_string.= ",'".$v_item_code."'";
	$v_sql_string.= ",'".$v_item_name."'";
	$v_sql_string.= ",'".$v_item_birthday."'";
	$v_sql_string.= ",'".$v_item_address."'";
	$v_sql_string.= ",'".$v_item_tel_local."'";
	$v_sql_string.= ",'".$v_item_tel_office."'";
	$v_sql_string.= ",'".$v_item_tel_mobile."'";
	$v_sql_string.= ",'".$v_item_tel_home."'";
	$v_sql_string.= ",'".$v_item_fax."'";
	$v_sql_string.= ",'".$v_item_email."'";
	$v_sql_string.= ",'".$v_item_username."'";
	$v_sql_string.= ",'".$v_item_password."'";
	$v_sql_string.= ",".$v_item_order."";
	$v_sql_string.= ",".$v_item_status."";
	$v_sql_string.= ",".$v_item_role."";
	$v_sql_string.= ",'".$v_item_ldap_dn."'";
	//echo $v_sql_string; exit;
	$rs = _adodb_exec_update_delete_sql($v_sql_string);
	$v_error = _replace_bad_char(trim($rs['RET_ERROR']));
}	
sleep(0);
if (!is_null($v_error) and $v_error<>""){?>
	<script>
		alert("<? echo $v_error; ?>");
		if (_MODAL_DIALOG_MODE==1){
			window.location = "<? echo $v_url; ?>";
		}else{
			window.history.back();
		}	
	</script><?
	exit;
}

$v_item_id = $rs['NEW_ID'];
$v_current_position = "0_".$v_unit_id;
?>
<form action="index.php" method="post" name="f_back">
	<input type="hidden" name="fuseaction" value="DISPLAY_ALL_STAFF">
	<input type="hidden" name="hdn_item_id" value="<?php echo $v_unit_id;?>">
	<input type="hidden" name="hdn_current_position" value="<?php echo $v_current_position;?>">
	<input type="hidden" name="hdn_parent_item_id" value="<?php echo $v_unit_id;?>">
</form>
<Script language="javascript">
	if ("<? echo $v_save_and_add_new; ?>" != "1"){
		return_value = "<? echo $v_item_id; ?>" + _LIST_DELIMITOR + "<? echo $v_item_code; ?>"  + _LIST_DELIMITOR + "<? echo $v_item_name; ?>" + _LIST_DELIMITOR + "<? echo $v_unit_id; ?>"; 		
		if (_MODAL_DIALOG_MODE==1){
			if (return_value!="") window.returnValue = return_value;				
			window.close();
		}else{	
			document.forms(0).action = "index.php";
			document.forms(0).submit();
		}	
	}else{
		if (_MODAL_DIALOG_MODE==1){
			document.forms(0).action = "index.php?modal_dialog_mode=1";
		}else{
			document.forms(0).action = "index.php";
		}	
		document.forms(0).fuseaction.value = p_fuseaction;
		document.forms(0).submit();
	}
	//goto_after_update_data(<? echo $v_save_and_add_new; ?>, "DISPLAY_SINGLE_STAFF",return_value);
</Script>

 