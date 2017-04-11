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
	//_create_cookie('guid_cookie',$v_value_guid);
	$v_ip_address = $v_value_guid;
}else{
	$v_value_guid = _generate_guid();
	//_create_cookie('guid_cookie',$v_value_guid);
	$v_ip_address = $v_value_guid;
	_write_file("test.txt","2:".$v_value_guid);
}

?>
<form action="index.php" method="post" Name="f_dsp_login">
<input type="hidden" name="fuseaction" id="fuseaction" value="">
<input type="hidden" name="app_code" id="app_code" value="<?php echo $v_app_code;?>" optional="false">
<input type="hidden" name="url_back" value="<?php echo $v_url_back;?>">
<table width="30%" align="center">
	<tr><td height="100"></td></tr>
	<tr bgcolor="#27559B" ><td>
		<table width="100%%" align="center" bgcolor="#FFFFFF" style="border-top: #27559B 1px solid; border-bottom:#27559B 1px solid">
			<col width="40%"><col width="60%">
			<tr>
				<td colspan="2"><img src="../images/img_dangnhap.gif">
				</td>
			</tr>																
			<!--Username-->
			<tr>
				<td class="label_login"><?php echo CONST_USERNAME_LABEL;?></td>
				<td class="inputtext_login"><input Name="txt_usename" id="txt_usename" type="text" class="input_login" style="width:95%" maxlength="<?php echo CONST_USERNAME_MAXLENGTH; ?>" message="<?php echo CONST_USERNAME_MESSAGE;?>" optional="<?php echo CONST_USERNAME_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)"></td>
			</tr>
			<tr>
				<td class="label_login" ><?php echo CONST_PASSWORD_LABEL;?></td>
				<td class="inputtext_login"><input Name="txt_password" id="txt_password" Type="password" class="input_login" style="width:95%" maxlength="<?php echo CONST_PASSWORD_MAXLENGTH; ?>" message="<?php echo CONST_PASSWORD_MESSAGE;?>" optional="<?php echo CONST_PASSWORD_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)"></td>
			</tr>	
			<tr><td colspan="2" height="10px"></td>
			</tr>
			<tr>
				<td ></td>
				<td class="btn_login" align="left" valign="bottom">
					<input Type="button" Name="btn_save" class="button_login" value="<?php echo _CONST_LOGON_BUTTON;?>" onClick="btn_save_onclick('CHECK_USER')" onKeyDown="change_focus(document.forms[0],this)">
					<input Type="button" Name="btn_back" class="button_logout" value="<?php echo _CONST_CANCEL_BUTTON;?>" onClick="window.location='<? echo $_ISA_HOMEPAGE_PATH;?>'" onKeyDown="change_focus(document.forms[0],this)">
				</td>
			</tr>
			<tr><td colspan="2" height="10px"></td>
		</table>
	</tr></td>
</table>					
</form>
</div>
<script language="JavaScript">
	set_focus(document.forms[0]);
	window.dialogHeight = "270pt";
	window.dialogWidth = "420pt";
	window.dialogTop = "80pt";
</script>