<?
if(isset($_REQUEST['hdn_item_id'])){
	$v_item_id = intval($_REQUEST['hdn_item_id']);
}else{
	$v_item_id = 0;
}
if(isset($_REQUEST['hdn_staff_id_list'])){
	$v_list_staff_id = _replace_bad_char($_REQUEST['hdn_staff_id_list']);
}else{
	$v_list_staff_id = "";
}
if(isset($_REQUEST['hdn_filter_group'])){
	$v_filter = _replace_bad_char($_REQUEST['hdn_filter_group']);
}else{	
	$v_filter = "";
}
if(isset($_REQUEST['hdn_application_id'])){
	$v_application_id = intval($_REQUEST['hdn_application_id']);
}else { 
	$v_application_id = 0;
}
if (_is_sqlserver()) {
/*	$cmd = @mssql_init("USER_EnduserInsert",$conn);
	@mssql_bind($cmd,"@p_application_id",$v_application_id,SQLINT4);
	@mssql_bind($cmd,"@p_staff_id_list",$v_list_staff_id,SQLVARCHAR);
	$result = @mssql_execute($cmd);
	$result = @mssql_query("Exec USER_EnduserInsert ".$v_application_id.",'".$v_list_staff_id."'",$conn);
	@mssql_free_result($result);
	*/
	$v_sql_string = "Exec USER_EnduserInsert ".$v_application_id.",'".$v_list_staff_id."'";
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
	<input name="hdn_item_id" type="hidden" value="<? echo $v_item_id;?>">
	<input name="hdn_filter_group" type="hidden" value="<? echo $v_filter; ?>">
	<input name="hdn_application_id" type="hidden" value="<? echo $v_application_id;?>" >
</form>
<Script language="javascript">
	document.forms(0).fuseaction.value = 'DISPLAY_ALL_ENDUSER';
	document.forms(0).submit();
</Script>
