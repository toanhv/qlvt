<!--Khai bao cac bien-->
<?php
$v_item_id = $_REQUEST['hdn_item_id'];
if ($v_item_id >0){
	$v_item_name = $arr_single_staff[0][1];
	$v_item_code = $arr_single_staff[0][2];
	$v_unit_id = $arr_single_staff[0][3];
	$v_unit_name = $arr_single_staff[0][4];
	$v_position_id = $arr_single_staff[0][5];
	$v_position_code = $arr_single_staff[0][6];
	$v_position_name = $arr_single_staff[0][7];
	$v_item_address = $arr_single_staff[0][8];
	$v_item_email = $arr_single_staff[0][9];
	$v_item_tel_local = $arr_single_staff[0][10];
	$v_item_tel_ofice = $arr_single_staff[0][11];
	$v_item_tel_mobile = $arr_single_staff[0][12];
	$v_item_tel_home = $arr_single_staff[0][13];
	$v_item_fax = $arr_single_staff[0][14];
	$v_item_username = $arr_single_staff[0][15];
	$v_item_password = $arr_single_staff[0][16];
	$v_item_order =  $arr_single_staff[0][17];
	$v_item_status =  $arr_single_staff[0][18];
	$v_item_role = $arr_single_staff[0][19];
	$v_item_birthday = $arr_single_staff[0][20];
	$v_item_ldap_dn = $arr_single_staff[0][21];
	//echo 'ngay sinh '.$v_item_birthday;
	$v_save_and_add_new = 0;
}else{
	$v_item_code = "";
	$v_item_name = "";	
	$v_item_address = "";
	$v_item_tel_local = "";
	$v_item_tel_ofice = "";
	$v_item_tel_mobile = "";
	$v_item_tel_home = "";
	$v_item_fax = "";
	$v_item_email = "";
	$v_item_username = "";
	$v_item_password = "";
	$v_item_status = 1;
	$v_item_role = 0;
	$v_unit_id = $_REQUEST['hdn_parent_item_id'];
	$v_unit_name = $arr_single_unit[0][5];
	$v_position_id = "";
	$v_position_code = "";
	$v_position_name = "";	
	$v_save_and_add_new = 0;
	if ($v_unit_id*1>0)
		$v_item_order = _get_next_value("T_USER_STAFF","C_ORDER"," FK_UNIT=".$v_unit_id);
	else
		$v_item_order = _get_next_value("T_USER_STAFF","C_ORDER"," FK_UNIT Is Null");
	$v_item_birthday = "";
	$v_item_ldap_dn = "";
}
$v_str_status_checked = "";
if ($v_item_status == 1){
	$v_str_status_checked = "checked";
}	
$v_str_save_and_add_new_checked = "";
if ($v_save_and_add_new == 1){
	$v_str_save_and_add_new_checked = "checked";
}
$v_str_role_checked = "";
if($v_item_role == 1){
	$v_str_role_checked = "checked";
}
$v_list_parent_id = $_REQUEST['hdn_list_parent_id'];
// Neu NSD hieu chinh cac thong tin ca nhan tren giao dien TRA CUU (khong phai cua ISA-USER)
if (isset($_REQUEST['modal_dialog_mode'])){
	$v_display_str = "style='.display:none;'";
}else{
	$v_display_str = "style='.display:block;'";
}
if ($_ISA_INTEGRATE_LDAP == 1){
	$v_up_display_str = "style='.display:none;'";
	$v_username_optional = "true";
	$v_password_optional = "true";
}else{
	$v_up_display_str = "style='.display:block;'";
	$v_username_optional = "false";
	$v_password_optional = "false";
}
?>
<!--Table chua tieu de cua form-->
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td height="5"> </td>
	</tr>
	<tr>
		<td width="100%" class="normal_title"><?php echo CONST_STAFF_UPDATE_TITLE;?></td>
	</tr>	
	<tr>
		<form name="f_dsp_single_staff" action="index.php" method="post">
		<input type="hidden" name="fuseaction" value="">
		<input name="hdn_list_parent_id" type="hidden" value="<?php echo $v_list_parent_id; ?>">		
		<!--Lay cac ID cua cac ComboBox-->
		<input type="hidden" name="hdn_item_id" value="<?php echo $v_item_id; ?>">
		<input name="hdn_current_position" type="hidden" value="<?php echo $v_current_position ?>" >
		<input type="hidden" name="hdn_unit_id" value="<?php echo $v_unit_id;?>">
		<input name="hdn_position_id" type="hidden" value="<?php echo $v_position_id;?>">
		<input type="hidden" name="hdn_unit_code" value="<?php echo $v_unit_id;?>">
		<!--Luu cac gia tri ghi lai va them moi khi nhan nut Chap nhan-->
		<input type="hidden" name="hdn_item_status" value="<?php echo $v_item_status; ?>" >
		<input type="hidden" name="hdn_item_role" value="<?php echo $v_item_role; ?>" >
		<input type="hidden" name="hdn_save_and_add_new" value="<?php echo $v_save_and_add_new;?>">
		<input type="hidden" name="hdn_ldap_dn" value="<? echo $v_item_ldap_dn;?>">	
		<input type="hidden" name="hdn_password_old" value="<? echo $v_item_password;?>">		
	</tr>
</table>
<table class="form_table1" width="100%" cellpadding="0" cellspacing="0" border="0">
	<col width="25%"><col width="25%"><col width="25%"><col width="25%">
	<tr><td style=".height:10px"></td></tr>
	<tr <? echo $v_display_str;?> class="normal_label">
		<td><?php echo CONST_STAFF_CODE_LABEL; ?></td>
		<td>
			<input type="text" name="txt_code" class="normal_textbox" style="width:100%" value="<?php echo $v_item_code; ?>" optional="<?php echo CONST_STAFF_CODE_OPTIONAL;?>" message="<?php echo CONST_STAFF_CODE_MESSAGE;?>" onKeyDown="change_focus(document.forms[0],this)">
		</td>
	</tr>
	<tr class="normal_label">
		<td><?php echo CONST_STAFF_NAME_LABEL; ?> <small class="normal_starmark">*</small></td>
		<td>
			<input type="text" name="txt_name" style="width:100%" class="normal_textbox" value="<?php echo $v_item_name; ?>" maxlength="<?php echo CONST_STAFF_NAME_MAXLENGTH;?>" message="<?php echo CONST_STAFF_NAME_MESSAGE;?>" optional="<?php echo CONST_STAFF_NAME_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)">
		</td>
		<td colspan="10"><?php echo CONST_STAFF_BIRTHDAY_LABEL;?>
			<input type="text" name="txt_birthday" value="<?php echo $v_item_birthday;?>" isdate ="true" class="very_small_date_textbox" style="width:30%" optional="<?php echo CONST_STAFF_BIRTHDAY_OPTIONAL;?>" message="<?php echo CONST_STAFF_BIRTHDAY_MESSAGE;?>" maxlength="<?php echo CONST_STAFF_BIRTHDAY_MAXLENGTH;?>" onKeyDown="JavaScript:change_focus(document.forms[0],this)">
			<!--img src="<?php echo $_ISA_IMAGE_URL_PATH;?>calendar.gif" border="0" onclick="DoCal('<?php echo $_ISA_LIB_URL_PATH; ?>isa-calendar/',document.forms[0].txt_birthday)" style="cursor:hand;"-->
		</td>
	</tr><? 
	if ($_ISA_INTEGRATE_LDAP == 1){?>
		<tr class="normal_label">
			<td><?php echo CONST_SELECT_LDAPUSER_LABEL; ?><small class="normal_starmark">*</small></td>
			<td colspan="10">
				<select name="sel_ldap_dn" class="normal_selectbox" style="width:100%;" optional="<? echo CONST_SELECT_LDAPUSER_OPTIONAL ?>" message="<? echo CONST_SELECT_LDAPUSER_MESSAGE?>" onChange="change_text_from_selected(document.forms[0].sel_ldap_dn,document.forms[0].hdn_ldap_dn,document.forms[0].hdn_ldap_dn);">
					<option value="">---<? echo CONST_SELECT_LDAPUSER_SELECTBOX ?>---</option>			
					<?
					$v_count = $arr_all_ldap_user["count"];
					if ($v_count >0){
						$i = 0;
						while($i < $v_count) {
							$arr_single_ldap_user = $arr_all_ldap_user[$i];
							$v_user_dn = _replace_bad_char($arr_single_ldap_user["dn"]);?>
							<option value="<? echo $v_user_dn;?>" id="<? echo $v_user_dn;?>"><? echo $v_user_dn;?></option><?
							$i = $i+1;
						}
					}	
					?>	
				</select>
			</td>
		</tr><?
	}?>	
	<tr <? echo $v_up_display_str;?> class="normal_label">
		<td><?php echo CONST_STAFF_USERNAME_LABEL; ?><small class="normal_starmark">*</small></td>
		<td><input type="text" name="txt_username" style="width:100%" class="normal_textbox" value="<?php echo $v_item_username; ?>" optional="<?php echo $v_username_optional;?>" onKeyDown="change_focus(document.forms[0],this)"></td>
	</tr>
	<tr <? echo $v_up_display_str;?> class="normal_label">
		<td><?php echo CONST_STAFF_PASSWORD_LABEL; ?><small class="normal_starmark">*</small></td>
		<td><input type="password" name="txt_password" style="width:100%" class="normal_textbox" value="" optional="true" onKeyDown="change_focus(document.forms[0],this)"></td>
	</tr>
	<!--Textbox nhap chuc danh-->
	<tr class="normal_label">
		<td><?php echo CONST_STAFF_POSITION_LABEL;?></td>
		<td colspan="2">
			<input type="text" name="txt_position" style="width:20%" class="normal_textbox" value="<? echo $v_position_code;?>" optional="<? echo CONST_POSITION_OF_STAFF_OPTIONAL;?>"  onKeyDown="change_focus(document.forms[0],this)" onChange="change_selected_from_text(document.forms[0].sel_position,document.forms[0].txt_position,document.forms[0].hdn_position_id);">
			<select class="normal_selectbox" name="sel_position" style="width:78%" optional="<?php echo CONST_POSITION_OF_STAFF_OPTIONAL;?>" onChange="change_text_from_selected(document.forms[0].sel_position,document.forms[0].txt_position,document.forms[0].hdn_position_id);">
				<option id="" value="">--<?php echo CONST_POSITION_OF_STAFF;?>--</option>
				<?php echo _generate_select_option($arr_all_position,'0','2','1', $v_position_id);?>
			</select><?php
			if ($_ISA_USER_ADMIN or ($_ISA_USER_LOGIN_USER_ID == $v_item_id)){?>
				<img border="0" src="<?php echo $_ISA_IMAGE_URL_PATH;?>AddEdit.gif" class="normal_image" onClick="show_modal_dialog_onclick('org/index.php','DISPLAY_SINGLE_POSITION',document.forms[0].sel_position,document.forms[0].txt_position,document.forms[0].hdn_position_id)"><?php
			}?>
		</td>
	</tr>
	<!--Textbox co quan-->
	<tr class="normal_label">
		<td><?php echo CONST_STAFF_OF_UNIT_LABEL;?></td>
		<td colspan="2">
		    <input type="text" name="txt_unit_name" style="width:100%" readonly="" value="<?php echo $v_unit_name;?>" optional="<?php echo CONST_WITHIN_UNIT_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)">
		</td>
		<td colspan="10">
			<img src="<?php echo $_ISA_IMAGE_URL_PATH;?>find.gif" class="normal_image" onClick="show_modal_dialog_treeview_onclick('org/index.php','DISPLAY_ALL_UNIT',document.forms[0].txt_unit_name,document.forms[0].hdn_unit_code,document.forms[0].hdn_unit_id,0)">
		</td>
	</tr>
	<tr class="normal_label">
		<td><?php echo CONST_STAFF_ADDRESS_LABEL; ?></td>
		<td colspan="10"><input type="text" name="txt_address" style="width:98%" class="normal_textbox" value="<?php echo $v_item_address; ?>"optional="<?php echo CONST_STAFF_ADDRESS_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)"></td>
	</tr>
	<!--danh sach cac so dien thoai-->
	<tr class="normal_label">
		<td><?php echo CONST_TEL_LOCAL_LABEL;?></td>
		<td>
			<input type="text" name="txt_tel_local" style="width:100%" class="normal_textbox" value="<?php echo $v_item_tel_local; ?>" optional="<?php echo CONST_STAFF_TEL_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)">
		</td>
		<td><?php echo CONST_TEL_OFFICE_LABEL;?></td>
		<td>
			<input type="text" name="txt_tel_office" style="width:100%" class="normal_textbox" value="<?php echo $v_item_tel_ofice; ?>" optional="<?php echo CONST_STAFF_TEL_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)">
		</td>
	</tr>
	<tr class="normal_label">
		<td><?php echo CONST_TEL_MOBILE_LABEL;?></td>
		<td>
			<input type="text" name="txt_tel_mobile" style="width:100%" class="normal_textbox" value="<?php echo $v_item_tel_mobile; ?>" optional="<?php echo CONST_STAFF_TEL_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)">
		</td>
		<td><?php echo CONST_TEL_HOME_LABEL;?></td>
		<td>
			<input type="text" name="txt_tel_home" style="width:100%" class="normal_textbox" value="<?php echo $v_item_tel_home; ?>" optional="<?php echo CONST_STAFF_TEL_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)">
		</td>
	</tr>
	
	<tr class="normal_label">
		<td><?php echo CONST_FAX_LABEL;?></td>
		<td>
			<input type="text" name="txt_fax" style="width:100%" class="normal_textbox" value="<?php echo $v_item_fax; ?>" optional="<?php echo CONST_STAFF_TEL_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)">
		</td>
		<td><?php echo CONST_STAFF_EMAIL_LABEL; ?></td>
		<td>
			<input type="text" name="txt_email" style="width:100%" class="normal_textbox" value="<?php echo $v_item_email; ?>"  optional="<?php echo CONST_STAFF_EMAIL_OPTIONAL;?>" isemail=<?php echo CONST_IS_EMAIL;?> message="<?php echo CONST_IS_EMAIL_MESSAGE;?>" onKeyDown="change_focus(document.forms[0],this)">
		</td>
	</tr>
	<tr <? echo $v_display_str;?> class="normal_label">
		<td><?php echo CONST_STAFF_ORDER_LABEL; ?><small class="normal_starmark">*</small></td>
		<td colspan="10">
			<input type="text" name="txt_order" class="short_number_textbox" value="<?php echo $v_item_order; ?>" maxlength="<?php echo CONST_STAFF_ORDER_MAXLENGTH;?>" message="<?php echo CONST_STAFF_ORDER_MESSAGE;?>" optional="<?php echo CONST_STAFF_ORDER_OPTIONAL;?>" isnumeric="<?php echo CONST_STAFF_ORDER_ISNUMERIC; ?>" min="<?php echo CONST_STAFF_ORDER_MIN; ?>" max="<?php echo CONST_STAFF_ORDER_MAX; ?>" onKeyDown="change_focus(document.forms[0],this)">
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="chk_role" class="normal_checkbox" <?php echo $v_str_role_checked; ?> onClick="btn_single_checkbox_onclick(document.forms[0].chk_role,document.forms[0].hdn_item_role)" onKeyDown="change_focus(document.forms[0],this)"> <?php echo CONST_IS_ADMIN_LABEL;?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="checkbox" name="chk_status" class="normal_checkbox" <?php echo $v_str_status_checked;?> onClick="btn_single_checkbox_onclick(document.forms[0].chk_status,document.forms[0].hdn_item_status)" onKeyDown="change_focus(document.forms[0],this)"> <?php echo _CONST_STATUS_COLUMN_ACTIVE_VALUE;?>
		</td>
	</tr>
	<tr style=".display:none">
		<td height="37"></td>
		<td><input type="checkbox" name="chk_save_and_add_new" class="normal_checkbox" <?php echo $v_str_save_and_add_new_checked; ?> onclick="btn_single_checkbox_onclick(document.forms[0].chk_save_and_add_new,document.forms[0].hdn_save_and_add_new)" onKeyDown="change_focus(document.forms[0],this)"><?php echo _CONST_SAVE_AND_ADD_NEW_LABEL;?></td>
	</tr>
	<tr><td style=".height:10px"></td></tr>
</table>
<!--bang chua cac button Chap nhan, Quay lai-->
<table align="center">
	<tr><td height="10"></td></tr>
	<tr><?php
		if($v_is_granted_update==true){?>
			<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
				<a onClick="btn_save_staff_onclick('UPDATE_STAFF');" class="small_link"><?php echo _CONST_SAVE_BUTTON;?></a>&nbsp;&nbsp;
			</td><?php
		}?>	
		<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
			<a onClick="goback();" class="small_link"><?php echo _CONST_BACK_BUTTON;?></a>
		</td>
	</tr>
</table>
</form>
<script language="JavaScript">
	<?
	if ($_ISA_INTEGRATE_LDAP == 1){?>
		set_selected(document.forms[0].sel_ldap_dn, "<? echo $v_item_ldap_dn;?>");<?
	}?>
	set_focus(document.forms[0]);
	window.dialogHeight = "280pt";
	window.dialogWidth = "480pt";
	window.dialogTop = "80pt";
</script>
 