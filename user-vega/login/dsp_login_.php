<!--bang chua cac text box de nhap du lieu-->
<?php
if (!isset($_REQUEST['url_back'])) {
	$v_url_back = "../org/index.php";
} else {
	$v_url_back = $_REQUEST['url_back'];
}
if (!isset($_REQUEST['app_code'])) {
	$v_app_code = $_ISA_APP_CODE;
} else {
	$v_app_code = $_REQUEST['app_code'];
}
// Lay cookie
if (!isset($_REQUEST['ip_address'])) {
	$v_value_guid = _generate_guid();
} else {
	$v_value_guid = $_REQUEST['ip_address'];
}
if ($v_value_guid!=""){
//	_create_cookie('guid_cookie',$v_value_guid);
	$v_ip_address = $v_value_guid;
}else{
	$v_value_guid = _generate_guid();
//	_create_cookie('guid_cookie',$v_value_guid);
	$v_ip_address = $v_value_guid;
	_write_file("test.txt","2:".$v_value_guid);
}
?>
<table width="300">
	<tr>
		<td height="100">&nbsp;</td>
	</tr>
</table>
<table width="300" class="form_table1" align="center" bgcolor="#F7F7F7">
	<col width="40%"><col width="70%">
	<tr>
		<form action="index.php" method="post" Name="f_dsp_login">
		<input type="hidden" name="fuseaction" value="">
		<input type="hidden" name="app_code" value="<?php echo $v_app_code;?>">
		<input type="hidden" name="url_back" value="<?php echo $v_url_back;?>">
		<input type="hidden" name="ip_address" value="<?php echo $v_ip_address;?>">
		<td colspan="2" height="10">&nbsp;</td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_USERNAME_LABEL;?></td>
		<td><input Name="txt_usename" type="text" class="normal_textbox" style="width:90%" maxlength="<?php echo CONST_USERNAME_MAXLENGTH; ?>" message="<?php echo CONST_USERNAME_MESSAGE;?>" optional="<?php echo CONST_USERNAME_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)"></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_PASSWORD_LABEL;?></td>
		<td><input Name="txt_password" Type="password" class="normal_textbox" style="width:90%" maxlength="<?php echo CONST_PASSWORD_MAXLENGTH; ?>" message="<?php echo CONST_PASSWORD_MESSAGE;?>" optional="<?php echo CONST_PASSWORD_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)"></td>
	</tr>
	<tr><td colspan="2" height="1"></td></tr>
	<tr>
		<td colspan="2" align="center" width="100%" height="30" style="padding-left:80px">
			<input Type="button" Name="btn_save" class="Small_button" value="<?php echo _CONST_LOGON_BUTTON;?>" onClick="btn_save_onclick('CHECK_USER')" onKeyDown="change_focus(document.forms[0],this)">
			<input Type="button" Name="btn_back" class="Small_button" value="<?php echo _CONST_CANCEL_BUTTON;?>" onClick="window.location='<? echo $_ISA_HOMEPAGE_PATH;?>'" onKeyDown="change_focus(document.forms[0],this)">
		</td>
	</tr>
</table>				
</form>
</div>
<script language="JavaScript">
	set_focus(document.forms[0]);
	window.dialogHeight = "250pt";
	window.dialogWidth = "300pt";
	window.dialogTop = "80pt";
</script>