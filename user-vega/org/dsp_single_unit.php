<!--Khai bao cac bien-->
<?php
if ($v_unit_id >0){
	$v_parent_id = $arr_single_unit[0][1];
	$v_parent_code = $arr_single_unit[0][2];	
	$v_parent_name = $arr_single_unit[0][3];	
	$v_item_code = $arr_single_unit[0][4];
	$v_item_name = $arr_single_unit[0][5];
	$v_item_address = $arr_single_unit[0][6];
	$v_item_tel = $arr_single_unit[0][7];
	$v_item_local = $arr_single_unit[0][8];
	$v_item_fax = $arr_single_unit[0][9];
	$v_item_email = $arr_single_unit[0][10];
	$v_item_order =  $arr_single_unit[0][11];
	$v_item_status =  $arr_single_unit[0][12];
	$v_save_and_add_new = 0;
}else{
	$v_parent_id = $_REQUEST['hdn_parent_item_id'];	
	$v_parent_code = "";
	$v_parent_name=$arr_single_unit[0][5];;
	$v_item_code = "";
	$v_item_name = "";
	$v_item_address = "";
	$v_item_tel = "";
	$v_item_email = "";
	if ($v_parent_id*1>0)
		$v_item_order = _get_next_value("T_USER_UNIT","C_ORDER"," FK_UNIT=".$v_parent_id);
	else
		$v_item_order = _get_next_value("T_USER_UNIT","C_ORDER"," FK_UNIT Is Null");
		
	$v_item_status = 1;
	$v_save_and_add_new = 0;
}
$v_str_status_checked="";
if ($v_item_status==1){
	$v_str_status_checked="checked";
}	
$v_str_save_and_add_new_checked = "";
if ($v_save_and_add_new==1){
	$v_str_save_and_add_new_checked="checked";
}
//In thu danh sach id cua cac thang cha cua no
$v_list_parent_id = $_REQUEST['hdn_list_parent_id'];
?>
<!--Table chua tieu de cua form-->
<table width="612" cellpadding="0" cellspacing="0">
	<tr>
		<td height="5"> </td>
	</tr>
	<tr>
		<td width="100%" class="normal_title"><?php echo CONST_UNIT_UPDATE_TITLE;?></td>
	</tr>	
</table>
<!--Table chua cac textbox, combobox de nhap du lieu-->
<table width="100%" class="form_table1" align="center">
	<col width="30%"><col width="70%">
	<tr>
		<form name="f_dsp_single_unit" action="index.php" method="post">
		<input type="hidden" name="fuseaction" value="">
		<!--Lay cac ID cua cac ComboBox-->
		<input type="hidden" name="hdn_parent_id" value="<?php echo $v_parent_id;?>">
		<input type="hidden" name="hdn_unit_id" value="<?php echo $v_unit_id;?>">
		<input name="hdn_current_position" type="hidden" value="<?php echo $v_current_position;?>">
		<!--Luu cac gia tri ghi lai va them moi khi nhan nut Chap nhan-->
		<input Type="hidden" Name="hdn_item_status" value="<?php echo $v_item_status ?>" >
		<input type="hidden" name="hdn_save_and_add_new" value="<?php echo $v_save_and_add_new;?>">
		<input name="hdn_filter" type="hidden" value="<?php echo $v_filter; ?>">
		<input name="hdn_list_parent_id" type="hidden" value="<?php echo $v_list_parent_id; ?>">
		<input name="hdn_parent_code" type="hidden" value="">
		<td height="5"></td>
	</tr>
	<!--Textbox -->
	<tr>
		<td class="normal_label"><?php echo CONST_UNIT_CODE_LABEL; ?></td>
		<td><input Name="txt_code" type="text" class="normal_textbox" size="40"  value="<?php echo $v_item_code; ?>" optional="<?php echo CONST_UNIT_CODE_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)"></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_UNIT_NAME_LABEL; ?> <small class="normal_starmark">*</small></td>
		<td><input Name="txt_name" Type="text" class="normal_textbox" size="40" value="<?php echo $v_item_name; ?>" maxlength="<?php echo CONST_UNIT_NAME_MAXLENGTH;?>" message="<?php echo CONST_UNIT_NAME_MESSAGE;?>" optional="<?php echo CONST_UNIT_NAME_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)"></td>
	</tr>
	<tr>
	<tr>
		<td class="normal_label"><?php echo CONST_WITHIN_UNIT_LABEL; ?></td>
		<td><input type="hidden" name="txt_parent_code" >
			<input type="text" name="txt_parent_name" readonly="" size="40" value="<?php echo $v_parent_name;?>" optional="<?php echo CONST_WITHIN_UNIT_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)">
			<img src="<?php echo $_ISA_IMAGE_URL_PATH;?>find.gif" class="normal_image" onClick="select_parent_unit();">
		</td>	
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_UNIT_ADDRESS_LABEL; ?></td>
		<td><input Name="txt_address" Type="text" class="normal_textbox" size="40" value="<?php echo $v_item_address; ?>" optional="<?php echo CONST_UNIT_ADDRESS_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)"></td>
	</tr>
	<!--SO NOI BO-->
	<tr>
		<td class="normal_label"><?php echo CONST_UNIT_TEL_LOCAL_LABEL; ?></td>
		<td><input Name="txt_tel" Type="text" class="normal_textbox" size="30" value="<?php echo $v_item_tel; ?>" optional="<?php echo CONST_UNIT_TEL_LOCAL_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)"></td>
	</tr>
	<!--SO CO DINH-->
	<tr>
		<td class="normal_label"><?php echo CONST_UNIT_TEL_LABEL; ?></td>
		<td><input Name="txt_local" Type="text" class="normal_textbox" size="30" value="<?php echo $v_item_local; ?>" optional="<?php echo CONST_UNIT_TEL_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)"></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_UNIT_FAX_LABEL; ?></td>
		<td><input Name="txt_fax" Type="text" class="normal_textbox" size="30" value="<?php echo $v_item_fax; ?>" optional="<?php echo CONST_UNIT_FAX_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)"></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_UNIT_EMAIL_LABEL; ?></td>
		<td><input Name="txt_email" Type="text" class="normal_textbox" size="30" value="<?php echo $v_item_email; ?>" isemail=<?php echo CONST_IS_EMAIL;?> message="<?php echo CONST_IS_EMAIL_MESSAGE;?>" optional="<?php echo CONST_UNIT_EMAIL_OPTIONAL;?>" onKeyDown="change_focus(document.forms[0],this)"></td>
	</tr>			
	<tr>
		<td class="normal_label"><?php echo CONST_UNIT_ORDER_LABEL; ?><small class="normal_starmark">*</small></td>
		<td><input Name="txt_order" Type="text" class="short_number_textbox" value="<?php echo $v_item_order; ?>" maxlength="<?php echo CONST_UNIT_ORDER_MAXLENGTH;?>" message="<?php echo CONST_UNIT_ORDER_MESSAGE;?>" optional="<?php echo CONST_UNIT_ORDER_OPTIONAL;?>" isnumeric="<?php echo CONST_UNIT_ORDER_ISNUMERIC; ?>" min="<?php echo CONST_UNIT_ORDER_MIN; ?>" max="<?php echo CONST_UNIT_ORDER_MAX; ?>" onKeyDown="change_focus(document.forms[0],this)"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="chk_status" class="normal_checkbox" <?php echo $v_str_status_checked;?> onClick="btn_single_checkbox_onclick(document.forms[0].chk_status,document.forms[0].hdn_item_status)" onKeyDown="change_focus(document.forms[0],this)"> <?php echo _CONST_STATUS_COLUMN_ACTIVE_VALUE;?></td>
	</tr>
	<tr style=".display:none">
		<td height="37"></td>
		<td><input type="checkbox" name="chk_save_and_add_new" class="normal_checkbox" <?php echo $v_str_save_and_add_new_checked; ?> onclick="btn_single_checkbox_onclick(document.forms[0].chk_save_and_add_new,document.forms[0].hdn_save_and_add_new)" onKeyDown="change_focus(document.forms[0],this)"><?php echo _CONST_SAVE_AND_ADD_NEW_LABEL;?></td>
	</tr>
	<tr><td height="5">&nbsp;</td></tr>
</table>
<!--bang chua cac button Chap nhan, Quay lai-->
<table align="center">
	<tr><td height="10"></td></tr>
	<tr><?php
		if($v_is_granted_update==true){?>
			<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
				<a onClick="btn_save_onclick('UPDATE_UNIT');" class="small_link"><?php echo _CONST_SAVE_BUTTON;?></a>&nbsp;&nbsp;
			</td><?php
		}?>	
		<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
			<a onClick="goback();" class="small_link"><?php echo _CONST_BACK_BUTTON;?></a>
		</td>
	</tr>
</table>
</form>
<script language="JavaScript">
	set_focus(document.forms[0]);
	window.dialogHeight = "270pt";
	window.dialogWidth = "420pt";
	window.dialogTop = "80pt";
</script>
 