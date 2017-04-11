<?
if(isset($_REQUEST['hdn_filter_group'])){
	$v_filter = _replace_bad_char($_REQUEST['hdn_filter_group']);
}else{	
	$v_filter = "";
}
$v_application_id = intval($_REQUEST['hdn_application_id']);
$v_application_code = _replace_bad_char($_REQUEST['hdn_application_code']);

$v_list_item_id = _replace_bad_char($_REQUEST['hdn_list_item_id']);
if (_is_sqlserver()) {
/*	$cmd = @mssql_init("USER_EnduserDelete",$conn); 
	@mssql_bind($cmd, "@p_list_id", $v_list_item_id, SQLVARCHAR);    
	$result = @mssql_execute($cmd); 
	$result = @mssql_query("Exec USER_EnduserDelete '".$v_list_item_id."'",$conn);
	$rs = @mssql_fetch_array($result);
	@mssql_free_result($result);
*/	
	$v_sql_string = "Exec USER_EnduserDelete '".$v_list_item_id."'";
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
<form action="index.php"  Name="f_back" method="post">
	<input type="hidden" name="fuseaction" value="DISPLAY_ALL_ENDUSER">	
	<input name="hdn_item_id" type="hidden" value="<? echo $v_item_id;?>">
	<input name="hdn_filter_group" type="hidden" value="<? echo $v_filter; ?>">
	<input name="hdn_application_id" type="hidden" value="<? echo $v_application_id;?>" >
	<input name="hdn_application_code" type="hidden" value="<?php echo $v_application_code;?>" >
</form>		
<script language="javascript">	
	document.forms(0).submit();
</script> 
