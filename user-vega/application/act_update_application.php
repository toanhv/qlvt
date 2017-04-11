<?php
$v_list_parent_id = 0;
if(isset($_REQUEST['hdn_list_parent_id'])){
	$v_list_parent_id  = intval($_REQUEST['hdn_list_parent_id']);
}
$v_item_id = 0;
if(isset($_REQUEST['hdn_item_id'])){
	$v_item_id = intval($_REQUEST['hdn_item_id']);
}
$v_current_position = 0;
if(isset($_REQUEST['hdn_current_position'])){
	$v_current_position = intval($_REQUEST['hdn_current_position']);
}
$v_item_code = _replace_bad_char(trim(strtoupper($_REQUEST['txt_code'])));
$v_item_name = _replace_bad_char(trim($_REQUEST['txt_name']));
$v_item_order = $_REQUEST['txt_order'];
if($v_item_order == ""){
	$v_item_order = NULL;
}
$v_item_order = intval($v_item_order);
$v_admin_id_list = $_REQUEST['hdn_admin_id_list'];
$v_item_description = _replace_bad_char(trim($_REQUEST['txt_description']));
$v_item_web_based_app = intval($_REQUEST['sel_web_based_app']);
$v_item_authenticate_by_isauser = intval($_REQUEST['sel_authenticate_by_user']);
$v_item_url_path = _replace_bad_char(trim($_REQUEST['txt_url_path']));
$v_username_var = _replace_bad_char(trim($_REQUEST['txt_username_var']));
$v_password_var = _replace_bad_char(trim($_REQUEST['txt_password_var']));

$v_varible_name_list = _replace_bad_char(trim($_REQUEST['hdn_varible_name_list']));
$v_varible_value_list = _replace_bad_char(trim($_REQUEST['hdn_varible_value_list']));

$v_item_status = intval($_REQUEST['hdn_item_status']);
$v_save_and_add_new = intval($_REQUEST['hdn_save_and_add_new']);
$v_url = "index.php?modal_dialog_mode=1" . "&hdn_item_id=" . $v_item_id . "&fuseaction=DISPLAY_SINGLE_APPLICATION" ;
if(_is_sqlserver()){
	/*
	$cmd = @mssql_init("USER_ApplicationUpdate",$conn);
	mssql_bind($cmd,"@p_item_id",$v_item_id,SQLINT4);
	mssql_bind($cmd,"@p_code",$v_item_code,SQLVARCHAR);
	mssql_bind($cmd,"@p_name",$v_item_name,SQLVARCHAR);
	mssql_bind($cmd,"@p_order",$v_item_order,SQLINT1);
	mssql_bind($cmd,"@p_description",$v_item_description,SQLVARCHAR);
	mssql_bind($cmd,"@p_web_based_app",$v_item_web_based_app,SQLINT4);
	mssql_bind($cmd,"@p_authenticate_by_isauser",$v_item_authenticate_by_isauser,SQLINT4);
	mssql_bind($cmd,"@p_url_path",$v_item_url_path,SQLVARCHAR);
	mssql_bind($cmd,"@p_username_var",$v_username_var,SQLVARCHAR);
	mssql_bind($cmd,"@p_password_var",$v_password_var,SQLVARCHAR);
	mssql_bind($cmd,"@p_varible_name_list",$v_varible_name_list,SQLVARCHAR);
	mssql_bind($cmd,"@p_varible_value_list",$v_varible_value_list,SQLVARCHAR);
	mssql_bind($cmd,"@p_status",$v_item_status,SQLINT4);
	mssql_bind($cmd,"@p_app_admin_id_list",$v_admin_id_list,SQLVARCHAR);
	$result = mssql_execute($cmd);
	$rs = @mssql_fetch_array($result);
	mssql_free_result($result);
	*/
	$v_sql_string = "Exec USER_ApplicationUpdate ";
	$v_sql_string.= "".$v_item_id."";
	$v_sql_string.= ",'".$v_item_code."'";
	$v_sql_string.= ",'".$v_item_name."'";
	$v_sql_string.= ",".$v_item_order."";
	$v_sql_string.= ",".$v_item_status."";
	$v_sql_string.= ",'".$v_item_description."'";
	$v_sql_string.= ",'".$v_item_web_based_app."'";
	$v_sql_string.= ",".$v_item_authenticate_by_isauser."";
	$v_sql_string.= ",'".$v_item_url_path."'";
	$v_sql_string.= ",'".$v_username_var."'";
	$v_sql_string.= ",'".$v_password_var."'";
	$v_sql_string.= ",'".$v_varible_name_list."'";
	$v_sql_string.= ",'".$v_varible_value_list."'";
	$v_sql_string.= ",'".$v_admin_id_list."'";
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
$v_current_position = "0_".$v_item_id;?>
<form action="index.php" method="post" name="f_back">
	<input type="hidden" name="fuseaction" value="DISPLAY_ALL_FUNCTION">
	<input name="hdn_item_id" type="hidden" value="<?php echo $v_item_id;?>">
	<input name="hdn_current_position" type="hidden" value="<?php echo $v_current_position;?>">
	<input name="hdn_list_parent_id" type="hidden" value="<?php echo $v_list_parent_id;?>">							
</form>
<Script language="javascript">
	if ("<?php echo $v_save_and_add_new; ?>" != "1")		
		return_value = "<?php echo $v_item_id; ?>" + _LIST_DELIMITOR + "<?php echo $v_item_code; ?>"  + _LIST_DELIMITOR + "<?php echo $v_item_name; ?>"; 		
	else{
		return_value = "";	
		document.forms(0).hdn_item_id.value = 0;
	}
	goto_after_update_data(<?php echo $v_save_and_add_new; ?>, "DISPLAY_SINGLE_APPLICATION",return_value);
</Script>
