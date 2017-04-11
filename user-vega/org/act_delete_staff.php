<?php
$v_parent_id =  "";
if(isset($_REQUEST['hdn_parent_item_id'])){
	$v_parent_id  = _replace_bad_char($_REQUEST['hdn_parent_item_id']);
}
$v_unit_id = intval($_REQUEST['hdn_item_id']);

$v_list_item_id = _replace_bad_char($_REQUEST['hdn_list_item_id']);
if(_is_sqlserver()){
	/*
	$cmd = @mssql_init("USER_StaffDelete",$conn); 
	@mssql_bind($cmd, "@p_list_item_id", $v_list_item_id, SQLVARCHAR);
	$result = @mssql_execute($cmd); 
	$rs = @mssql_fetch_array($result);
	*/
	$v_sql_string = "Exec USER_StaffDelete ";
	$v_sql_string.= "'".$v_list_item_id."'";
	$rs = _adodb_exec_update_delete_sql($v_sql_string);
	
	$v_error_name = _replace_bad_char(trim($rs['RET_ERROR_NAME']));
	$v_error_id = _replace_bad_char(trim($rs_id['RET_ERROR_ID']));
}	
sleep(0);
if (!is_null($v_error_name) and $v_error_name <> ""){?>
	<script>
		alert("Khong the xoa can bo <?php echo $v_error_name; ?>");
	</script><?php
}?>
<form action="index.php"  Name="f_back" method="post">
	<input type="hidden" name="fuseaction" value="DISPLAY_ALL_STAFF">
	<input type="hidden" name="hdn_item_id" value="<?php echo $v_unit_id;?>">
	<input type="hidden" name="hdn_parent_item_id" value="<?php echo $v_unit_id;?>">
</form>
<Script language="javascript">	
	document.forms(0).submit();
</Script>



 