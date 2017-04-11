<?php
$v_item_id = 0;
if(isset($_REQUEST['hdn_item_id'])){
	$v_item_id = intval($_REQUEST['hdn_item_id']);
}
$v_item_filter= _replace_bad_char($_REQUEST['hdn_filter']);
$v_item_code = _replace_bad_char(strtoupper($_REQUEST['txt_code']));
$v_item_name = _replace_bad_char($_REQUEST['txt_name']);
$v_item_position_group = intval($_REQUEST['hdn_position_group_id']);
$v_item_order = intval($_REQUEST['txt_order']);
if($v_item_order == 0){
	$v_item_order = NULL;
}
$v_item_status = intval($_REQUEST['hdn_item_status']);
$v_save_and_add_new = intval($_REQUEST['hdn_save_and_add_new']);
$v_url = "index.php?modal_dialog_mode=1" . "&hdn_item_id=" . $v_item_id . "&fuseaction=DISPLAY_SINGLE_POSITION" ;
if (_is_sqlserver()){
	/*
	$cmd = @mssql_init("USER_PositionUpdate",$conn);
	@mssql_bind($cmd,"@p_item_id",$v_item_id,SQLINT4);
	@mssql_bind($cmd,"@p_code",$v_item_code,SQLVARCHAR);
	@mssql_bind($cmd,"@p_name",$v_item_name,SQLVARCHAR);
	@mssql_bind($cmd,"@p_position_group",$v_item_position_group,SQLINT4);
	@mssql_bind($cmd,"@p_order",$v_item_order,SQLINT1);
	@mssql_bind($cmd,"@p_status",$v_item_status,SQLINT4);
	$result = @mssql_execute($cmd);
	$rs = @mssql_fetch_array($result);
	@mssql_free_result($result);
	*/
	$v_sql_string = "Exec USER_PositionUpdate ";
	$v_sql_string.= "".$v_item_id."";
	$v_sql_string.= ",'".$v_item_code."'";
	$v_sql_string.= ",'".$v_item_name."'";
	$v_sql_string.= ",".$v_item_position_group."";
	$v_sql_string.= ",".$v_item_order."";
	$v_sql_string.= ",".$v_item_status."";
	//exit;
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
	<input type="hidden" name="fuseaction" value="DISPLAY_ALL_POSITION">
	<input name="hdn_filter" type="hidden" value="<?php echo $v_item_filter; ?>">
	<input Type="hidden" Name="hdn_item_id" value="<?php echo $v_item_id;?>">
</form>
<Script language="javascript">
	if ("<?php echo $v_save_and_add_new; ?>" != "1")		
		return_value = "<?php echo $v_item_id; ?>" + _LIST_DELIMITOR + "<?php echo $v_item_code; ?>"  + _LIST_DELIMITOR + "<?php echo $v_item_name; ?>"; 		
	else
		return_value = "";	
	goto_after_update_data(<?php echo $v_save_and_add_new; ?>, "DISPLAY_SINGLE_POSITION",return_value);
</Script>

 