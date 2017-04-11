<!-- Phan khai bao bien cuc bo-->
<?php
$v_item_filter = "";
if(isset($_REQUEST['hdn_filter'])){
	$v_item_filter = $_REQUEST['hdn_filter'];
}
if($v_item_id >0){
	$v_item_code = $arr_single_position[0][1];
	$v_item_name = $arr_single_position[0][2];
	$v_item_position_group_id = $arr_single_position[0][3];
	$v_item_order = $arr_single_position[0][4];
	$v_item_status = $arr_single_position[0][5];
	$v_save_and_add_new = 0;
}else{
	$v_item_code = "";
	$v_item_name = "";
	$v_item_position_group = "";
	$v_item_order = _get_next_value("T_USER_POSITION","C_ORDER","");
	$v_item_status = 1;
	$v_save_and_add_new = 1;
}
$v_str_status_checked="";
if ($v_item_status==1){
	$v_str_status_checked="checked";
}	
$v_str_save_and_add_new_checked="";
if ($v_save_and_add_new==1){
	$v_str_save_and_add_new_checked="checked";
}	
?>
<!-- Bat cac phim: F12=true; Insert=false; Delete=false, ESC=true; Enter=true -->
<div id="hotkey" onKeyDown="javascript:process_hot_key(true,false,false,true,true);">
<!--bang chua tieu de cua form-->
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td height="5"> </td>
	</tr>
	<tr>
		<td width="100%" class="normal_title"><?php echo CONST_POSITION_UPDATE_TITLE;?></td>
	</tr>
</table>
<!--bang chua cac text box de nhap du lieu-->
<table width="100%" class="form_table1" align="center">
	<col width="25%"><col width="75%">
	<tr>
		<form action="index.php" method="post" Name="f_dsp_single_position">
		<input Type="hidden" Name="fuseaction" value="UPDATE_POSITION">
		<input Type="hidden" Name="hdn_item_id" value="<?php echo $v_item_id ?>">
		<input Type="hidden" Name="hdn_position_group_id" value="<?php echo $v_item_position_group_id ?>">
		<input Type="hidden" Name="hdn_item_status" value="<?php echo $v_item_status ?>">
		<input type="hidden" name="hdn_save_and_add_new" value="<?php echo $v_save_and_add_new; ?>"> 
		<input name="hdn_filter" type="hidden" value="<?php echo $v_item_filter; ?>">
		<td height="5">&nbsp;</td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_POSITION_CODE_LABEL; ?> <small class="normal_starmark">*</small></td>
		<td><input Name="txt_code" type="text" class="normal_textbox"  value="<?php echo $v_item_code; ?>" maxlength="<?php echo CONST_POSITION_CODE_MAXLENGTH; ?>" message="<?php echo CONST_POSITION_CODE_MESSAGE;?>" optional="<?php echo CONST_POSITION_CODE_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_POSITION_NAME_LABEL; ?> <small class="normal_starmark">*</small></td>
		<td><input Name="txt_name" Type="text" class="normal_textbox" size="50" value="<?php echo $v_item_name; ?>" maxlength="<?php echo CONST_POSITION_NAME_MAXLENGTH;?>" message="<?php echo CONST_POSITION_NAME_MESSAGE;?>" optional="<?php echo CONST_POSITION_NAME_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_POSITION_GROUP_LABEL; ?></td>
		<td>
			<select class="normal_selectbox"  name="sel_position_group" optional="<?php echo CONST_POSITION_GROUP_OPTIONAL;?>" message="<?php echo CONST_POSITION_GROUP_MESSAGE;?>" onKeyDown="change_focus(document.forms(0),this)" onChange="change_text_from_selected(document.forms(0).sel_position_group,document.forms(0).hdn_position_group_id,document.forms(0).hdn_position_group_id);">
				<option id="" value="">--<?php echo CONST_POSITION_GROUP_LABEL;?>--</option>
				<?php echo _generate_select_option($arr_all_position_group,'0','2','1', $v_item_position_group_id);?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_POSITION_ORDER_LABEL; ?></td>
		<td><input Name="txt_order" Type="text" class="short_number_textbox" value="<?php echo $v_item_order; ?>" maxlength="<?php echo CONST_POSITION_ORDER_MAXLENGTH;?>" message="<?php echo CONST_POSITION_ORDER_MESSAGE;?>" optional="<?php echo CONST_POSITION_ORDER_OPTIONAL;?>" isnumeric="<?php echo CONST_POSITION_ORDER_ISNUMERIC; ?>" min="<?php echo CONST_POSITION_ORDER_MIN; ?>" max="<?php echo CONST_POSITION_ORDER_MAX; ?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="chk_status" class="normal_checkbox" <?php echo $v_str_status_checked;?> onClick="btn_single_checkbox_onclick(document.forms(0).chk_status,document.forms(0).hdn_item_status)" onKeyDown="change_focus(document.forms(0),this)"> <?php echo _CONST_STATUS_COLUMN_ACTIVE_VALUE;?></td>
	</tr>
	<tr style="display:none">
		<td height="37"></td>
		<td><input type="checkbox" name="chk_save_and_add_new" class="normal_checkbox" <?php echo $v_str_save_and_add_new_checked; ?> onclick="btn_single_checkbox_onclick(document.forms(0).chk_save_and_add_new,document.forms(0).hdn_save_and_add_new)" onKeyDown="change_focus(document.forms(0),this)"><?php echo _CONST_SAVE_AND_ADD_NEW_LABEL;?></td>
	</tr>
	<tr><td height="5">&nbsp;</td></tr>
</table>
<!--bang chua cac button-->
<table align="center">
	<tr><td height="10"></td></tr>
	<tr><?php
		if($v_is_granted_update==true){?>
			<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
				<a onClick="btn_save_onclick('UPDATE_POSITION');" class="small_link"><?php echo _CONST_SAVE_BUTTON;?></a>&nbsp;&nbsp;
			</td><?php
		}?>
		<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
			<a onClick="btn_back_onclick('DISPLAY_ALL_POSITION');" class="small_link"><?php echo _CONST_BACK_BUTTON;?></a>
		</td>
	</tr>
</table>					
</form>
</div>
<script language="JavaScript">
	set_focus(document.forms(0));
	window.dialogHeight = "270pt";
	window.dialogWidth = "420pt";
	window.dialogTop = "80pt";
</script>
 