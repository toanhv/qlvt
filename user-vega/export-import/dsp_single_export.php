<?php
$v_filter_group = "";
if(isset($_REQUEST['hdn_filter_group'])){
	$v_filter_group = $_REQUEST['hdn_filter_group'];
}

$v_application_id = 0;
if(isset($_REQUEST['hdn_application_id'])){
	$v_application_id = intval($_REQUEST['hdn_application_id']);
}

$v_count = sizeof($arr_all_group);
$v_current_style_name = "odd_row";
$v_next_style_name = "";
$v_goto_url = "enduser/index.php";
if ($v_application_id>0){
	// Neu khong phai la QUAN TRI ISA-USER thi phai goi ham user_is_app_admin de kiem tra xem nguoi dang nhap co
	// phai la QUAN TRI cua ung dung hien tai hay khong
	if ($_SESSION['is_isa_user_admin']<>1){
		$v_is_granted_update = user_is_app_admin($v_application_id, $_SESSION['staff_id']);
		$v_is_granted_delete = $v_is_granted_update;
	}
}else{ // neu chua co ung dung nao duoc chon thi khong cho "them", "xoa" modul
	$v_is_granted_update = false;
	$v_is_granted_delete = false;
}

?>
<!--Bang chua tieu de-->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		 <td height="5"></td>
	</tr>
	<tr>
		 <td width="100%" class="normal_title"><?php echo CONST_EXPORT_IMPORT_TITLE;?></td>
	</tr>
</table>
<!-- bang chua cac tieu thuc loc-->
<table cellpadding="0" cellspacing="0" width="100%">
	<col width="25%"><col width="75%">
	<tr><td height="5"></td></tr>
	<tr>
		<form action="index.php" name="f_dsp_single" method="post" enctype="multipart/form-data">
		<input name="fuseaction" type="hidden" value="">
		<input name="hdn_item_id" type="hidden" value="0">
		<input name="hdn_list_item_id" type="hidden" value="0">
		<input name="hdn_application_id" type="hidden" value="" >
 	</tr>
 	<tr>
 	   <td>&nbsp;</td>
	   <td colspan="2" class="normal_label">
	       <input type="radio" name="rad_type" checked optional="<?php echo CONST_EXPORT_IMPORT_OPTIONAL;?>" message="<?php echo CONST_EXPORT_IMPORT_MESSAGE;?>" value="0" onclick="rad_onclick(document.forms(0).rad_type);" ><?php echo CONST_EXPORT_LABEL;?>&nbsp;&nbsp;&nbsp;
	       <input type="radio" name="rad_type" optional="<?php echo CONST_EXPORT_IMPORT_OPTIONAL;?>" message="<?php echo CONST_EXPORT_IMPORT_MESSAGE;?>" value="1" onclick="rad_onclick(document.forms(0).rad_type);"><?php echo CONST_IMPORT_LABEL;?>
	   </td>
	</tr>
	<tr>
	   <td class="normal_label" align="left"><?php echo CONST_APPLICATION_LABEL;?><small class="normal_starmark">*</small></td>
	   <td align="left">
	       <select class="normal_selectbox" name="sel_application" optional="<?php echo CONST_NAME_APPLICATION_OPTIONAL;?>" message="<?php echo CONST_NAME_APPLICATION_MESSAGE;?>" style="width:99%" onChange="document.forms(0).hdn_application_id.value = this.value;">
		      <option id="" value="">--<?php echo CONST_NAME_APPLICATION;?>--</option>
			  <?php echo _generate_select_option($arr_all_application,'0','0','3', $v_application_id);?>
	       </select>&nbsp;
	   </td>
	</tr>
	<tr id="tr_file_attach" style="display:none">
	   <td class="normal_label"><?php echo CONST_PATH_FILE_LABEL;?><small class="normal_starmark">*</small></td></td>
	   <td>
	       <input name="file_attach" type="file" style="width:99%" >
	   </td>
	</tr>
	<tr><td height="5"></td></tr>
</table>

<!--Table chua cac button-->
<table align="center">
	<tr><td height="10"></td></tr>
	<tr>
	   <td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
	       <a onClick="btn_continous_onclick(document.forms(0).rad_type,'act_update_export.php','IMPORT_DATA');" class="small_link"><?php echo CONST_CONTINOUS_BUTTON;?></a>&nbsp;&nbsp;
	   </td>
	</tr>
</table>
</div id="hotkey">
</form>

