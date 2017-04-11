<?php
$v_list_parent_id =  "";
if(isset($_REQUEST['hdn_list_parent_id'])){
	$v_list_parent_id  = _replace_bad_char($_REQUEST['hdn_list_parent_id']);
}
$v_current_position =  "";
if(isset($_REQUEST['hdn_current_position'])){
	$v_current_position  = intval($_REQUEST['hdn_current_position']);
}
$v_list_item_id = _replace_bad_char($_REQUEST['hdn_list_item_id']);

if(_is_sqlserver()){
	$v_sql_string = "Exec USER_FunctionDelete ";
	$v_sql_string.= "'".$v_list_item_id."'";
	$rs = _adodb_exec_update_delete_sql($v_sql_string);
	$v_error_name = _replace_bad_char(trim($rs['RET_ERROR_NAME']));
	$v_error_id = _replace_bad_char(trim($rs_id['RET_ERROR_ID']));
}
if (!is_null($v_error) and $v_error<>""){?>
	<script>
		alert("Khong the xoa chuc nang <?php echo $v_error_name; ?>");
		if (_MODAL_DIALOG_MODE==1){
			window.location = "<?php echo $v_url; ?>";
		}else{
			window.history.back();
		}	
	</script><?php
	exit;
}
//$v_current_position = '1_'.$v_modul_id;
?>
<form action="index.php"  Name="f_back" method="post">
	<input type="hidden" name="fuseaction" value="DISPLAY_ALL_FUNCTION">
	<input name="hdn_current_position" type="hidden" value="<?php echo $v_current_position;?>">
	<input name="hdn_list_parent_id" type="hidden" value="<?php echo $v_list_parent_id;?>">								
	<input type="hidden" name="hdn_list_item_id" value="<?php echo $v_error_id;?>">
</form>
<Script language="javascript">	
	document.forms(0).submit();
</Script>
