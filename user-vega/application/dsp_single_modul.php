<!-- Phan khai bao bien cuc bo-->
<?php
$v_list_parent_id =  "";
if(isset($_REQUEST['hdn_list_parent_id'])){
	$v_list_parent_id  = $_REQUEST['hdn_list_parent_id'];
}
//Luu lai ID cua ung dung hien thoi
if($v_item_id >0){
	$v_application_id = $arr_single_modul[0][1];
	$v_item_code = $arr_single_modul[0][2];
	$v_item_name = $arr_single_modul[0][3];	
	$v_item_order = $arr_single_modul[0][4];
	$v_item_status = $arr_single_modul[0][5];
	$v_item_public = $arr_single_modul[0][6];
	$v_parent_name = $arr_single_modul[0][8];
	$v_save_and_add_new = 0;
}else{
	$v_item_code = "";
	$v_item_name = "";
	$v_application_id = $_REQUEST['hdn_application_id'];	
	$v_application_name = $arr_single_application[0][2];
	$v_item_order = _get_next_value("T_USER_MODUL","C_ORDER","");
	$v_item_status = 1;
	$v_item_public = 0;
	$v_save_and_add_new = 1;
}	
$v_str_status_checked="";
if ($v_item_status==1){
	$v_str_status_checked="checked";
}	
$v_str_public_checked="";
if ($v_item_public==1){
	$v_str_public_checked="checked";
}	
$v_str_save_and_add_new_checked="";
if ($v_save_and_add_new==1){
	$v_str_save_and_add_new_checked="checked";
}?>
<!-- Bat cac phim: F12=true; Insert=false; Delete=false, ESC=true; Enter=true -->
<div id="hotkey" onKeyDown="javascript:process_hot_key(true,false,false,true,true);">
<!--bang chua tieu de cua form-->
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td height="5"> </td>
	</tr>
	<tr>
		 <td width="100%" class="normal_title"><?php echo CONST_MODUL_UPDATE_TITLE;?></td>
	</tr>
</table>
<!--bang chua cac text box de nhap du lieu-->
<table width="100%" class="form_table1" align="center">
	<col width="30%"><col width="70%">
	<tr>
		<form action="index.php" method="post" Name="f_dsp_single_modul">
		<input Type="hidden" Name="fuseaction" value="UPDATE_MODUL" >
		<input name="hdn_list_parent_id" type="hidden" value="<?php echo $v_list_parent_id;?>">				
		<input Type="hidden" Name="hdn_item_id" value="<?php echo $v_item_id ?>" >
		<input Type="hidden" Name="hdn_current_position" value="<?php echo $v_current_position ?>" >
		<input type="hidden" name="hdn_application_id" value="<?php echo $v_application_id; ?>">		
		<input Type="hidden" Name="hdn_item_status" value="<?php echo $v_item_status ?>">
		<input Type="hidden" Name="hdn_item_public" value="<?php echo $v_item_public ?>" >
		<input type="hidden" name="hdn_save_and_add_new" value="<?php echo $v_save_and_add_new; ?>"> 
		<td height="5">&nbsp;</td>
	</tr>	
	<tr>
		<td class="normal_label"><?php echo CONST_MODUL_CODE_LABEL; ?> <small class="normal_starmark">*</small></td>
		<td><input Name="txt_code" type="text" class="normal_textbox" size="40"  value="<?php echo $v_item_code; ?>" maxlength="<?php echo CONST_MODUL_CODE_MAXLENGTH; ?>" message="<?php echo CONST_MODUL_CODE_MESSAGE;?>" optional="<?php echo CONST_MODUL_CODE_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_MODUL_NAME_LABEL; ?> <small class="normal_starmark">*</small></td>
		<td><input Name="txt_name" Type="text" class="normal_textbox" size="50" value="<?php echo $v_item_name; ?>" maxlength="<?php echo CONST_MODUL_NAME_MAXLENGTH;?>" message="<?php echo CONST_MODUL_NAME_MESSAGE;?>" optional="<?php echo CONST_MODUL_NAME_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_MODUL_OF_APPLICATION_LABEL;?></td>
		<td>
			<select class="normal_selectbox" name="sel_application" style="width:340" optional="<?php echo $v_application_name;?>" onChange="change_text_from_selected(document.forms(0).sel_application,document.forms(0).hdn_application_id,document.forms(0).hdn_application_id);">
				<option id="" value=""><?php echo CONST_APPLICATION; ?></option>
				<?php echo _generate_select_option($arr_all_application,0,0,3, $v_application_id);?>
			</select>&nbsp;&nbsp;
		</td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_MODUL_ORDER_LABEL; ?><small class="normal_starmark">*</small></td>
		<td><input Name="txt_order" Type="text" class="short_number_textbox" value="<?php echo $v_item_order; ?>" maxlength="<?php echo CONST_MODUL_ORDER_MAXLENGTH;?>" message="<?php echo CONST_MODUL_ORDER_MESSAGE;?>" optional="<?php echo CONST_MODUL_ORDER_OPTIONAL;?>" isnumeric="<?php echo CONST_MODUL_ORDER_ISNUMERIC; ?>" min="<?php echo CONST_MODUL_ORDER_MIN; ?>" max="<?php echo CONST_MODUL_ORDER_MAX; ?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="chk_status" class="normal_checkbox" <?php echo $v_str_status_checked;?> onClick="btn_single_checkbox_onclick(document.forms(0).chk_status,document.forms(0).hdn_item_status)" onKeyDown="change_focus(document.forms(0),this)"> <?php echo _CONST_STATUS_COLUMN_ACTIVE_VALUE;?></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="chk_public" class="normal_checkbox" <?php echo $v_str_public_checked;?> onClick="btn_single_checkbox_onclick(document.forms(0).chk_public,document.forms(0).hdn_item_public)" onKeyDown="change_focus(document.forms(0),this)"> <?php echo CONST_MODUL_PUBLIC_LABEL;?></td>
	</tr>
	<tr>
		<td height="37"></td>
		<td><input type="checkbox" name="chk_save_and_add_new" class="normal_checkbox" <?php echo $v_str_save_and_add_new_checked; ?> onclick="btn_single_checkbox_onclick(document.forms(0).chk_save_and_add_new,document.forms(0).hdn_save_and_add_new)" onKeyDown="change_focus(document.forms(0),this)"><?php echo _CONST_SAVE_AND_ADD_NEW_LABEL;?></td>
	</tr>
	<tr><td height="5">&nbsp;</td></tr>
</table>
<!--bang chua cac button-->
<table align="center">
	<tr><td height="10"></td></tr>
	<tr><?php
		if($v_is_granted_update_modul==true){?>
			<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
				<a onClick="btn_save_onclick('UPDATE_MODUL')" class="small_link"><?php echo _CONST_SAVE_BUTTON;?></a>&nbsp;&nbsp;
			</td><?php
		}?>
		<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
			<a onClick="btn_back_onclick('DISPLAY_ALL_FUNCTION')" class="small_link"><?php echo _CONST_BACK_BUTTON;?></a>
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