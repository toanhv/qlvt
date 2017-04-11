<?
$v_application_code = _replace_bad_char($_REQUEST['hdn_application_code']);
$v_item_id = intval($_REQUEST['hdn_item_id']);
$v_list_enduser_id = _replace_bad_char($_REQUEST['hdn_list_enduser_id_checked']);
$v_list_modul_id = _replace_bad_char($_REQUEST['hdn_list_modul_id_checked']);
$v_list_function_id = _replace_bad_char($_REQUEST['hdn_list_function_id_checked']);
$v_filter = _replace_bad_char($_REQUEST['hdn_filter_group']);
$v_application_id = _replace_bad_char($_REQUEST['hdn_application_id']);

$v_item_code = strtoupper(_replace_bad_char($_REQUEST['txt_code']));
$v_item_name = _replace_bad_char($_REQUEST['txt_name']);
$v_order = intval($_REQUEST['txt_order']);
if($v_order ==0){
	$v_order = NULL;
}	
$v_status = intval($_REQUEST['hdn_item_status']);
$v_save_and_add_new = intval($_REQUEST['hdn_save_and_add_new']);
$v_url = "index.php?modal_dialog_mode=1" . "&hdn_item_id=" . $v_item_id . "&fuseaction=DISPLAY_SINGLE_GROUP" ;
if (_is_sqlserver()){
/*	$cmd = @mssql_init("USER_GroupUpdate",$conn);
	@mssql_bind($cmd,"@p_item_id",$v_item_id,SQLINT4);
	@mssql_bind($cmd,"@p_application_id",$v_application_id,SQLINT4);
	@mssql_bind($cmd,"@p_code",$v_item_code,SQLVARCHAR);
	@mssql_bind($cmd,"@p_name",$v_item_name,SQLVARCHAR);
	@mssql_bind($cmd,"@p_order",$v_order,SQLINT1);
	@mssql_bind($cmd,"@p_status",$v_status,SQLINT4);
	@mssql_bind($cmd,"@p_list_enduser_id",$v_list_enduser_id,SQLVARCHAR);
	@mssql_bind($cmd,"@p_list_modul_id",$v_list_modul_id,SQLVARCHAR);
	@mssql_bind($cmd,"@p_list_function_id",$v_list_function_id,SQLVARCHAR);
	$result = @mssql_execute($cmd);

	$result = @mssql_query("Exec USER_GroupUpdate ".$v_item_id.",".$v_application_id.",'".$v_item_code."','".$v_item_name."',".$v_order.",".$v_status.",'".$v_list_enduser_id."','".$v_list_modul_id."','".$v_list_function_id."'",$conn);
	$rs = @mssql_fetch_array($result);
	@mssql_free_result($result);
*/	
	$v_sql_string = "Exec USER_GroupUpdate ".$v_item_id.",".$v_application_id.",'".$v_item_code."','".$v_item_name."',".$v_order.",".$v_status.",'".$v_list_enduser_id."','".$v_list_modul_id."','".$v_list_function_id."'";
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
$v_item_id = $rs['NEW_ID'];
?>
<form action="index.php" method="post" name="f_back">
	<input type="hidden" name="fuseaction" value="DISPLAY_ALL_GROUP">
	<input name="hdn_filter_group" type="hidden" value="<? echo $v_filter; ?>">
	<input name="hdn_application_id" type="hidden" value="<? echo $v_application_id;?>" >
	<input name="hdn_application_code" type="hidden" value="<?php echo $v_application_code;?>" >
</form>
<Script language="javascript">
	if ("<? echo $v_save_and_add_new; ?>" != "1")		
		return_value = "<? echo $v_item_id; ?>" + _LIST_DELIMITOR + "<? echo $v_item_code; ?>"  + _LIST_DELIMITOR + "<? echo $v_item_name; ?>"; 		
	else
		return_value = "";	
	goto_after_update_data(<? echo $v_save_and_add_new;?>, "DISPLAY_SINGLE_GROUP",return_value);
</Script>
