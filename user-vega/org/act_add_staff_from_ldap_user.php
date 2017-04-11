<?php
$v_ldap_user_list = _replace_bad_char($_REQUEST['hdn_ldap_user_list']);
$v_unit_id = intval($_REQUEST['hdn_unit_id']);
$v_url = "index.php?modal_dialog_mode=1" . "&hdn_item_id=" . $v_item_id . "&fuseaction=DISPLAY_SINGLE_STAFF" ;
$v_delimitor = _CONST_LIST_DELIMITOR;
//echo $v_ldap_user_list."<br>"._CONST_LIST_DELIMITOR;
if(_is_sqlserver()){
	/*
	$cmd = @mssql_init("USER_StaffAddFromLDAPUser",$conn);
	@mssql_bind($cmd,"@p_ldap_user_list",$v_ldap_user_list,SQLVARCHAR);
	@mssql_bind($cmd,"@p_unit_id",$v_unit_id,SQLINT4);
	@mssql_bind($cmd,"@p_list_delimitor",$v_delimitor,SQLVARCHAR);
	$result = @mssql_execute($cmd);
	$rs = @mssql_fetch_array($result);
	//var_dump($rs);
	@mssql_free_result($result);
	*/
	
	$v_sql_string = "Exec USER_StaffAddFromLDAPUser";
	$v_sql_string.= "'".$v_ldap_user_list."'";
	$v_sql_string.= ",".$v_unit_id."";
	$v_sql_string.= ",'".$v_delimitor."'";
	$rs = _adodb_exec_update_delete_sql($v_sql_string);
}	
//exit;
if(isset($rs['RET_ERROR'])){
	$v_error = _replace_bad_char(trim($rs['RET_ERROR']));
	if (!is_null($v_error) and $v_error<>""){?>
		<script>
			alert("<? echo $v_error; ?>");
			if (_MODAL_DIALOG_MODE==1){
				window.location = "<? echo $v_url; ?>";
			}else{
				window.history.back();
			}	
		</script><?
		exit;
	}
}	

if(isset($_REQUEST['hdn_item_id'])){
	$v_item_id = $_REQUEST['hdn_item_id'];
}else{
	$v_item_id = 0;
}
$v_current_position = "";
if(isset($_REQUEST['hdn_current_position'])){
	$v_current_position = $_REQUEST['hdn_current_position'];
}

?>
<form action="index.php" method="post" name="f_back">
	<input type="hidden" name="fuseaction" value="DISPLAY_ALL_STAFF">
	<input type="hidden" name="hdn_item_id" value="<?php echo $v_unit_id;?>">
	<input type="hidden" name="hdn_current_position" value="<?php echo $v_current_position;?>">
	<input type="hidden" name="hdn_parent_item_id" value="<?php echo $v_unit_id;?>">
</form>
<Script language="javascript">
	if ("<? echo $v_save_and_add_new; ?>" != "1"){
		return_value = "<? echo $v_item_id; ?>" + _LIST_DELIMITOR + "<? echo $v_item_code; ?>"  + _LIST_DELIMITOR + "<? echo $v_item_name; ?>" + _LIST_DELIMITOR + "<? echo $v_unit_id; ?>"; 		
		if (_MODAL_DIALOG_MODE==1){
			if (return_value!="") window.returnValue = return_value;				
			window.close();
		}else{	
			document.forms(0).action = "index.php";
			document.forms(0).submit();
		}	
	}else{
		if (_MODAL_DIALOG_MODE==1){
			document.forms(0).action = "index.php?modal_dialog_mode=1";
		}else{
			document.forms(0).action = "index.php";
		}	
		document.forms(0).fuseaction.value = p_fuseaction;
		document.forms(0).submit();
	}
	//goto_after_update_data(<? echo $v_save_and_add_new; ?>, "DISPLAY_SINGLE_STAFF",return_value);
</Script>

 