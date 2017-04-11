<?php
if(isset($_REQUEST['hdn_unit_id'])){
	$v_unit_id = intval($_REQUEST['hdn_unit_id']);
}else{
	$v_unit_id = 0;
}
$v_current_position = 0;
if(isset($_REQUEST['hdn_current_position'])){
	$v_current_position = intval($_REQUEST['hdn_current_position']);
}
$v_unit_code = _replace_bad_char($_REQUEST['txt_code']);
$v_unit_name = _replace_bad_char($_REQUEST['txt_name']);
$v_parent_id = _replace_bad_char($_REQUEST['hdn_parent_id']);
$v_unit_address = _replace_bad_char($_REQUEST['txt_address']);
$v_unit_tel = _replace_bad_char($_REQUEST['txt_tel']);
$v_unit_tel_local = _replace_bad_char($_REQUEST['txt_local']);
$v_unit_fax = _replace_bad_char($_REQUEST['txt_fax']);
$v_unit_email = _replace_bad_char($_REQUEST['txt_email']);
$v_unit_id = intval($_REQUEST['hdn_unit_id']);
$v_order =  intval($_REQUEST['txt_order']);
$v_status = intval($_REQUEST['hdn_item_status']);
$v_save_and_add_new = intval($_REQUEST['hdn_save_and_add_new']);
$v_url = "index.php?modal_dialog_mode=1" . "&hdn_unit_id=" . $v_unit_id . "&fuseaction=DISPLAY_SINGLE_UNIT";
if(_is_sqlserver()){
	/*
	$cmd = @mssql_init("USER_UnitUpdate",$conn);
	mssql_bind($cmd,"@p_unit_id",$v_unit_id,SQLINT4);
	mssql_bind($cmd,"@p_code",$v_unit_code,SQLVARCHAR);
	mssql_bind($cmd,"@p_name",$v_unit_name,SQLVARCHAR);
	mssql_bind($cmd,"@p_parent_id",$v_parent_id,SQLINT4);
	mssql_bind($cmd,"@p_address",$v_unit_address,SQLVARCHAR);
	mssql_bind($cmd,"@p_tel",$v_unit_tel,SQLVARCHAR);
	mssql_bind($cmd,"@p_local",$v_unit_tel_local,SQLVARCHAR);
	mssql_bind($cmd,"@p_fax",$v_unit_fax,SQLVARCHAR);
	mssql_bind($cmd,"@p_email",$v_unit_email,SQLVARCHAR);
	mssql_bind($cmd,"@p_order",$v_order,SQLINT4);
	mssql_bind($cmd,"@p_status",$v_status,SQLINT4);
	$result = mssql_execute($cmd);
	$rs = @mssql_fetch_array($result);
	*/
	$v_sql_string = "Exec USER_UnitUpdate ";
	$v_sql_string.= "".$v_unit_id."";
	$v_sql_string.= ",".$v_parent_id."";
	$v_sql_string.= ",'".$v_unit_code."'";
	$v_sql_string.= ",'".$v_unit_name."'";
	$v_sql_string.= ",'".$v_unit_address."'";
	$v_sql_string.= ",'".$v_unit_tel."'";
	$v_sql_string.= ",'".$v_unit_tel_local."'";
	$v_sql_string.= ",'".$v_unit_fax."'";
	$v_sql_string.= ",'".$v_unit_email."'";
	$v_sql_string.= ",".$v_order."";
	$v_sql_string.= ",".$v_status."";
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
$v_unit_id = $rs['NEW_ID'];
?>
<form action="index.php" method="post" name="f_back">
	<input type="hidden" name="fuseaction" value="DISPLAY_ALL_STAFF">
	<input type="hidden" name="hdn_item_id" value="<?php echo $v_unit_id;?>">
	<input type="hidden" name="hdn_current_position" value="<?php echo $v_current_position;?>">
	<input type="hidden" name="hdn_parent_item_id" value="<?php echo $v_parent_id;?>">
</form>
<Script language="javascript">
	if ("<?php echo $v_save_and_add_new; ?>" != "1")
		return_value = "<?php echo $v_unit_id; ?>" + _LIST_DELIMITOR + "<?php echo $v_unit_code; ?>"  + _LIST_DELIMITOR + "<?php echo $v_unit_name; ?>"; 		
	else
		return_value = "";	
	goto_after_update_data(<?php echo $v_save_and_add_new; ?>, 'DISPLAY_SINGLE_UNIT',return_value);
</Script>


 