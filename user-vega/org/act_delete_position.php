<?php
$v_filter = _replace_bad_char($_REQUEST['hdn_filter']);
$v_list_item_id = _replace_bad_char($_REQUEST['hdn_list_item_id']);
if (_is_sqlserver()){
	/*
	$cmd = @mssql_init("USER_PositionDelete",$conn); 
	@mssql_bind($cmd, "@p_id_list", $v_list_item_id, SQLVARCHAR);
	$result = @mssql_execute($cmd);
	$rs = @mssql_fetch_array($result);
	@mssql_free_result($result);
	*/
	
	$v_sql_string = "Exec USER_PositionDelete ";
	$v_sql_string.= "'".$v_list_item_id."'";
	$rs = _adodb_exec_update_delete_sql($v_sql_string);
	$v_error = _replace_bad_char(trim($rs['RET_ERROR']));
}
sleep(0);
if (!is_null($v_error) and $v_error<>""){?>
	<script>
		alert("<? echo $v_error; ?>");
		window.history.back();
	</script><?php
	exit;
}
?>
<form action="index.php"  Name="f_back" method="post">
	<input type="hidden" name="fuseaction" value="DISPLAY_ALL_POSITION">	
	<input type="hidden" name="hdn_filter" value="<?php echo $v_filter; ?>">
</form>		
<Script language="javascript">	
	document.forms(0).submit();
</Script>
 