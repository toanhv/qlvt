<?php
$v_parent_id =  "";
if(isset($_REQUEST['hdn_parent_item_id'])){
	$v_parent_id  = intval($_REQUEST['hdn_parent_item_id']);
}
$v_unit_id = intval($_REQUEST['hdn_list_item_id']);

if(_is_sqlserver()){
	/*
	$cmd = mssql_init("USER_UnitDelete",$conn); 
	mssql_bind($cmd, "@p_item_id", $v_unit_id, SQLINT4);
	$result = mssql_execute($cmd); 
	$rs = @mssql_fetch_array($result);
	*/
	$v_sql_string = "Exec USER_UnitDelete ";
	$v_sql_string.= "".$v_unit_id."";
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
	<input type="hidden" name="fuseaction" value="DISPLAY_ALL_STAFF">	
	<input type="hidden" name="hdn_item_id" value="<?php echo $v_unit_id;?>">
	<input type="hidden" name="hdn_parent_item_id" value="<?php echo $v_parent_id;?>">
</form>		
<Script language="javascript">	
	document.forms(0).submit();
</Script> 



 