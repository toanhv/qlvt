<?php
$v_isa_user_change_personal_info_url = $_ISA_WEB_SITE_PATH . "org/index.php" . "?fuseaction=DISPLAY_DETAIL_USER&modal_dialog_mode=1";
//echo $v_isa_user_change_personal_info_url;
$v_txt_old_password = _replace_bad_char($_REQUEST['txt_old_password']);
$v_txt_old_password = md5($v_txt_old_password);
$v_hdn_password_old = _replace_bad_char($_REQUEST['hdn_password_old']);
if($v_txt_old_password != $v_hdn_password_old){ ?>
	<script language="JavaScript">
		alert("Mat khau cu cua ban khong dung, hay nhap lai!");
		window.location = "<? echo $v_isa_user_change_personal_info_url; ?>";
	</script>
<?php 
	exit;
}
$v_user_id = 0;
if(isset($_REQUEST['hdn_user_id'])){
	$v_user_id = intval($_REQUEST['hdn_user_id']);
}
$v_user_name = _replace_bad_char($_REQUEST['txt_name']);
$v_user_username = _replace_bad_char($_REQUEST['txt_username']);
$v_user_password = _replace_bad_char($_REQUEST['txt_password']);
$v_user_password = md5($v_user_password);
$v_user_address = _replace_bad_char($_REQUEST['txt_address']);
$v_user_tel = _replace_bad_char($_REQUEST['txt_tel']);
$v_user_email = _replace_bad_char($_REQUEST['txt_email']);
if(_is_sqlserver()){
	/*
	$cmd = mssql_init("USER_UserUpdate",$conn);
	@mssql_bind($cmd,"@p_user_id",$v_user_id,SQLINT4);
	@mssql_bind($cmd,"@p_name",$v_user_name,SQLVARCHAR);
	@mssql_bind($cmd,"@p_address",$v_user_address,SQLVARCHAR);
	@mssql_bind($cmd,"@p_tel",$v_user_tel,SQLVARCHAR);
	@mssql_bind($cmd,"@p_email",$v_user_email,SQLVARCHAR);
	@mssql_bind($cmd,"@p_username",$v_user_username,SQLVARCHAR);
	@mssql_bind($cmd,"@p_password",$v_user_password,SQLVARCHAR);
	$result = mssql_execute($cmd);
	$rs = @mssql_fetch_array($result);
	@mssql_free_result($result);
	*/
	$v_sql_string = "Exec USER_UserUpdate ";
	$v_sql_string.= "".$v_user_id."";
	$v_sql_string.= ",'".$v_user_name."'";
	$v_sql_string.= ",'".$v_user_address."'";
	$v_sql_string.= ",'".$v_user_tel."'";
	$v_sql_string.= ",'".$v_user_email."'";
	$v_sql_string.= ",'".$v_user_username."'";
	$v_sql_string.= ",'".$v_user_password."'";
	$rs = _adodb_exec_update_delete_sql($v_sql_string);
	$v_error = _replace_bad_char(trim($rs['RET_ERROR']));
}
sleep(0);
if (!is_null($v_error) and $v_error<>""){?>
	<script>
		alert("<? echo $v_error; ?>");
		window.close();
	</script><?
	exit;
}?>
<script>
	window.close();
</script>
 