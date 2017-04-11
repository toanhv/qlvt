<?
//Luu lai vi tri hien thoi
$v_current_position = 0;
if(isset($_REQUEST['hdn_current_position'])){
	$v_current_position = intval($_REQUEST['hdn_current_position']);
}
//Luu lai dieu kien loc
if(isset($_REQUEST['hdn_filter_enduser'])){
	$v_filter = _replace_bad_char($_REQUEST['hdn_filter_enduser']);
}else{	
	$v_filter = "";
}
$v_item_id = intval($_REQUEST['hdn_item_id']);
$v_list_group_id = intval($_REQUEST['hdn_list_group_id_checked']);
$v_list_modul_id = intval($_REQUEST['hdn_list_modul_id_checked']);
$v_list_function_id = intval($_REQUEST['hdn_list_function_id_checked']);
$v_application_id = intval($_REQUEST['hdn_application_id']);
$v_application_code = _replace_bad_char($_REQUEST['hdn_application_code']);
$v_save_and_add_new = 0;
if (isset($_REQUEST['txt_username'])){
	$v_app_username = _replace_bad_char($_REQUEST['txt_username']);
}else{
	$v_app_username = NULL;
}	
if (isset($_REQUEST['txt_password'])){
	$v_app_password = _replace_bad_char($_REQUEST['txt_password']);
}else{
	$v_app_password = NULL;
}	
$v_enduser_status = intval($_REQUEST['hdn_enduser_status']);
$v_url = "index.php?modal_dialog_mode=1" . "&hdn_item_id=" . $v_item_id . "&fuseaction=DISPLAY_SINGLE_ENDUSER" ;
if (_is_sqlserver()) {
/*	$cmd = @mssql_init("USER_EnduserUpdate",$conn);
	@mssql_bind($cmd,"@p_item_id",$v_item_id,SQLINT4);
	@mssql_bind($cmd,"@p_app_username",$v_app_username,SQLVARCHAR);
	@mssql_bind($cmd,"@p_app_password",$v_app_password,SQLVARCHAR);
	@mssql_bind($cmd,"@p_app_status",$v_enduser_status,SQLINT4);
	@mssql_bind($cmd,"@p_list_group_id",$v_list_group_id,SQLVARCHAR);
	@mssql_bind($cmd,"@p_list_modul_id",$v_list_modul_id,SQLVARCHAR);
	@mssql_bind($cmd,"@p_list_function_id",$v_list_function_id,SQLVARCHAR);
	$result = @mssql_execute($cmd);
	$result = @mssql_query("Exec USER_EnduserUpdate ".$v_item_id.",'".$v_app_username."','".$v_app_password."',".$v_enduser_status.",'".$v_list_group_id."','".$v_list_modul_id."','".$v_list_function_id."'",$conn);
	$rs = @mssql_fetch_array($result);
	@mssql_free_result($result);
	*/
	$v_sql_string = "Exec USER_EnduserUpdate ".$v_item_id.",'".$v_app_username."','".$v_app_password."',".$v_enduser_status.",'".$v_list_group_id."','".$v_list_modul_id."','".$v_list_function_id."'";
	$rs = _adodb_exec_update_delete_sql($v_sql_string);
	$v_error = _replace_bad_char(trim($rs['RET_ERROR']));
}
sleep(0);
if (!is_null($v_error) and $v_error<>""){?>
	<script>
		alert("<?php echo $v_error; ?>");
		if (_MODAL_DIALOG_MODE==1){
			window.location = "<?php echo $v_url; ?>";
		}else{
			window.history.back();
		}	
	</script><?php
	exit;
}
?>
<form action="index.php" method="post" name="f_back">
	<input type="hidden" name="fuseaction" value="DISPLAY_ALL_ENDUSER">
	<input name="hdn_filter_enduser" type="hidden" value="<? echo $v_filter; ?>">
	<input name="hdn_application_id" type="hidden" value="<? echo $v_application_id;?>" >
	<input name="hdn_application_code" type="hidden" value="<?php echo $v_application_code;?>" >
	<!--Luu lai vi tri hien thoi de quay lai-->
	<input type="hidden" name="hdn_current_position" value="<?php echo $v_current_position ?>" >
</form>
<Script language="javascript">
	if ("<? echo $v_save_and_add_new; ?>" != "1")		
		return_value = "<? echo $v_item_id; ?>" + _LIST_DELIMITOR + "<? echo $v_app_username; ?>"  + _LIST_DELIMITOR + "<? echo $v_app_username; ?>"; 		
	else
		return_value = "";	
	goto_after_update_data(<? echo $v_save_and_add_new; ?>, "DISPLAY_SINGLE_ENDUSER",return_value);
</Script>
