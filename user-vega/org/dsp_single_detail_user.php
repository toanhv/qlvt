<?php
$v_old_pass_str = "";
if($v_staff_id !="0"){
	if ($_ISA_INTEGRATE_LDAP == 1 && (substr($v_staff_id,0,3)== $_ISA_LDAP_USERNAME_ATTRIBUTE."=")){
		$v_user_name = _replace_bad_char(LDAP_GetCN($v_staff_id));
		$v_username_login = _replace_bad_char(LDAP_GetCN($v_staff_id));
		$v_user_old_password = _replace_bad_char($arr_single_user_detail[$_ISA_LDAP_PASSWORD_ATTRIBUTE][0]);
		$v_user_password = $v_user_old_password;
		$v_user_address = "";
		$v_user_tel = "";
		$v_user_email = "";
		$v_user_status = 1;
		$v_user_ldap_dn = $v_staff_id;
		$v_old_pass_str = "style='.display:none'";
	}else{
		$v_user_name = $arr_single_user_detail[0][0];
		$v_username_login = $arr_single_user_detail[0][1];
		$v_user_old_password = $arr_single_user_detail[0][2];
		$v_user_password = $arr_single_user_detail[0][2];
		//echo $v_user_password."<br>";
		//echo $v_user_old_password."<br>";
		$v_user_address = $arr_single_user_detail[0][3];
		$v_user_tel = $arr_single_user_detail[0][4];
		$v_user_email = $arr_single_user_detail[0][5];
		$v_user_status =  $arr_single_user_detail[0][6];
		$v_user_ldap_dn =  $arr_single_user_detail[0][7];
		$v_save_and_add_new = 0;
	}	
}else{
	$v_user_name = "";
	$v_username_login = "";
	$v_user_old_password = "";
	$v_user_password = "";
	$v_user_address = "";
	$v_user_tel = "";
	$v_user_email = "";
	$v_user_status = 1;
	$v_user_ldap_dn = "";
	$v_save_and_add_new = 1;
}
$v_change_username_str = "";
if ($_ISA_INTEGRATE_LDAP == 1){
	$v_staff_id = $v_user_ldap_dn;
	$arr_single_user = LDAP_GetSingleUser($v_staff_id);
	$v_user_old_password = $arr_single_user[$_ISA_LDAP_PASSWORD_ATTRIBUTE][0];
	$v_change_username_str = "readonly";
}
if ($_ISA_ALLOW_CHANGE_PASSWORD != 1){?>
<table width="400" cellpadding="0" cellspacing="0">
<tr>
	<td class="normal_title"><?php echo CONST_ENDUSER_NOCHANGE_INFO_TITLE; ?></td>
</tr>
</table><?php
exit;
}
?>	

<!-- Bat cac phim: F12=true; Insert=false; Delete=false, ESC=true; Enter=true -->
<div id="hotkey" onKeyDown="javascript:process_hot_key(true,false,false,true,true);">
<!--bang chua tieu de cua form-->
<table width="400" cellpadding="0" cellspacing="0">
	<tr>
       	<td class="normal_title"><?php echo CONST_ENDUSER_CHANGE_INFO_TITLE; ?></td>
	</tr>
</table>
<!--bang chua cac text box de nhap du lieu-->
<table width="400" class="form_table1" align="center">
	<col width="35%"><col width="65%">
	<tr>
		<form action="index.php" method="post" Name="f_dsp_single_user"> 
		<input type="hidden" name="fuseaction" value="UPDATE_USER">
		<input Type="hidden" name="hdn_user_id" value="<?php echo $v_staff_id; ?>" >
		<input Type="hidden" name="hdn_password_old" value="<?php echo $v_user_old_password; ?>" >
		<?php if(isset($_REQUEST['modal_dialog_mode'])){?>
			<input name="modal_dialog_mode" type="hidden" value="<?php echo $_MODAL_DIALOG_MODE;?>">
		<?php }?>
		<td height="5">&nbsp;</td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_ENDUSER_NAME_LABEL; ?> <small class="normal_starmark">*</small></td>
		<td><input Name="txt_name" type="text" class="normal_textbox" readonly="" size="30" value="<?php echo $v_user_name; ?>" maxlength="<?php echo CONST_STAFF_NAME_MAXLENGTH; ?>" message="<?php echo CONST_ENDUSER_MESSAGE;?>" optional="<?php echo CONST_ENDUSER_OPTIONAL;?>"></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_ENDUSER_USERNAME_LABEL;?> <small class="normal_starmark">*</small></td>
		<td><input type="text" name="txt_username" class="normal_textbox" <?php echo $v_change_username_str?>  size="30" value="<?php echo $v_username_login;?>" maxlength="<?php echo CONST_STAFF_USERNAME_MAXLENGTH; ?>" message="<?php echo CONST_NAME_LOGIN_MESSAGE;?>" optional="<?php echo CONST_STAFF_USERNAME_OPTIONAL;?>"></td>
	</tr>
	<tr <? echo $v_old_pass_str;?>>
		<td class="normal_label"><?php echo CONST_STAFF_OLD_PASSWORD_LABEL;?> <small class="normal_starmark">*</small></td>
		<td><input type="password" name="txt_old_password" class="normal_textbox"  size="30" value="" maxlength="<?php echo CONST_STAFF_PASSWORD_MAXLENGTH; ?>" message="<?php echo CONST_STAFF_PASSWORD_MESSAGE;?>" optional="<?php echo CONST_STAFF_PASSWORD_OPTIONAL;?>"></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_STAFF_PASSWORD_LABEL;?> <small class="normal_starmark">*</small></td>
		<td><input type="password" name="txt_password" class="normal_textbox"  size="30" value="" maxlength="<?php echo CONST_STAFF_PASSWORD_MAXLENGTH; ?>" message="<?php echo CONST_STAFF_PASSWORD_MESSAGE;?>" optional="<?php echo CONST_STAFF_PASSWORD_OPTIONAL;?>"></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_STAFF_RE_PASSWORD_LABEL;?> <small class="normal_starmark">*</small></td>
		<td><input type="password" name="txt_re_password" class="normal_textbox"  size="30" value="" maxlength="<?php echo CONST_STAFF_PASSWORD_MAXLENGTH; ?>" message="<?php echo CONST_STAFF_PASSWORD_MESSAGE;?>" optional="<?php echo CONST_STAFF_PASSWORD_OPTIONAL;?>"></td>
	</tr>
	<tr style="display:none">
		<td class="normal_label"><?php echo CONST_STAFF_ADDRESS_LABEL; ?></td>
		<td><input type="text" name="txt_address" class="normal_textbox" size="40" value="<?php echo $v_user_address; ?>"optional="<?php echo CONST_STAFF_ADDRESS_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr style="display:none">
		<td class="normal_label"><?php echo CONST_STAFF_TEL_LABEL; ?></td>
		<td><input type="text" name="txt_tel" class="normal_textbox" size="30" value="<?php echo $v_user_tel; ?>" optional="<?php echo CONST_STAFF_TEL_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr style="display:none">
		<td class="normal_label"><?php echo CONST_STAFF_EMAIL_LABEL; ?></td>
		<td><input type="text" name="txt_email" class="normal_textbox" size="30" value="<?php echo $v_user_email; ?>"  optional="<?php echo CONST_STAFF_EMAIL_OPTIONAL;?>" isemail=<?php echo CONST_IS_EMAIL;?> message="<?php echo CONST_IS_EMAIL_MESSAGE;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr><td height="5">&nbsp;</td></tr>
</table>
<!--bang chua cac button-->
<table width="400" align="center">
	<tr>
		<td align="center" width="100%" height="30" valign="bottom">
			<input type="button" onClick="check_password()" class="Small_button" value="<?php echo CONST_CONFIR_BUTTON;?>">&nbsp;&nbsp;
			<input type="button" onClick="btn_back_onclick('DISPLAY_DETAIL_USER');" class="Small_button" value="<?php echo CONST_CANCEL_BUTTON;?>">
		</td>
	</tr>
</table>			
</form>
</div>
<script language="JavaScript">
	set_focus(document.forms(0));
	window.dialogHeight = "300px";
	window.dialogWidth = "444px";
	window.dialogTop = "100px";
	function check_password(){
		var old_pass = document.forms(0).txt_old_password.value;
		var pass_word = document.forms(0).txt_password.value;
		var re_password = document.forms(0).txt_re_password.value;
		<?php
		/*if ($_ISA_INTEGRATE_LDAP != 1){?>
			if (old_pass != '<?php echo $v_user_old_password;?>'){
				alert('Mat khau cu cua ban khong dung, hay nhap lai');
				return;
			}
		<?
		}else{?>
			if (('{MD5}'=='<?php echo substr($v_user_old_password,0,5);?>' && b64_md5(old_pass) != '<?php echo $v_user_old_password;?>')
			||('{MD5}' != '<?php echo substr($v_user_old_password,0,5);?>' && old_pass != '<?php echo $v_user_old_password;?>')){
				alert('Mat khau cu cua ban khong dung, hay nhap lai');
				return;
			} 
		<?
		}*/?>
		
		if (pass_word != re_password){
			alert('Xac nhan lai mat khau moi khong dung, hay nhap lai');
			return;
		}else{
			if (verify(document.forms(0))){
				<?
				if ($_ISA_INTEGRATE_LDAP == 1 && '{MD5}' == substr($v_user_old_password,0,5)){?>
					document.forms(0).txt_password.value = b64_md5(document.forms(0).txt_password.value);
				<?
				}?>
				document.forms(0).fuseaction.value = 'UPDATE_USER';
				document.forms(0).submit();
			}			
			//btn_save_onclick('UPDATE_USER');
		}
	}
	function sel_onchange(p_select_obj,p_hdn_app_obj,p_hdn_enduser_obj,p_fuseaction){
		p_hdn_app_obj.value = p_select_obj(p_select_obj.selectedIndex).id;
		p_hdn_enduser_obj.value = p_select_obj.value;
		document.forms(0).fuseaction.value = p_fuseaction;
		document.forms(0).submit();
	}
</script>
 