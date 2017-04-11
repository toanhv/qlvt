<?php
//var_dump($arr_all_function_belong_group);
// Luu dieu kien loc hien thoi
if(isset($_REQUEST['hdn_filter_enduser'])){
	$v_filter = $_REQUEST['hdn_filter_enduser'];
}else{	
	$v_filter = "";
}
$v_application_id = $_SESSION['user_application_id'];
$v_application_code = $_SESSION['user_application_code'];
//Luu lai vi tri hien thoi
$v_current_position = "0";
if(isset($_REQUEST['hdn_current_position'])){
	$v_current_position = $_REQUEST['hdn_current_position'];
}
//echo $v_current_position;
if($v_item_id >0){
	$v_fullname = $arr_single_enduser[0][3];
	$v_app_username = $arr_single_enduser[0][4];
	$v_app_password = $arr_single_enduser[0][5];
	$v_enduser_status =  $arr_single_enduser[0][6];
	$v_application_name = $arr_single_enduser[0][7];
	// Neu $v_authenticate_by_isauser=1 thi co nghia la su dung ca ISA-USER va ban than ung dung de xac minh NSD
	// Neu $v_authenticate_by_isauser=0 thi chi su dung ISA-USER de xac minh NSD
	$v_authenticate_by_isauser = $arr_single_enduser[0][8]; 
	$v_save_and_add_new = 0;
}else{
	$v_fullname = "";
	$v_app_username = "";
	$v_app_password ="";
	$v_enduser_status = 1;
	$v_application_name = "";
	// Ngam dinh la chi su dung ISA-USER de xac minh NSD
	$v_authenticate_by_isauser = 0;
	$v_save_and_add_new = 1;
}
$v_current_style_name = "odd_row";
$v_next_style_name = "";
$v_str_status_checked="";
if ($v_enduser_status==1){
	$v_str_status_checked="checked";
}
$v_url_onclick ="";

$v_group_count = sizeof($arr_all_group_for_enduser);
$v_function_count = sizeof($arr_all_function_for_enduser);
$v_modul_count = sizeof($arr_all_modul_for_enduser);

// Neu khong phai la QUAN TRI ISA-USER thi phai goi ham user_is_app_admin de kiem tra xem nguoi dang nhap co
// phai la QUAN TRI cua ung dung hien tai hay khong
if ($_SESSION['is_isa_user_admin']<>1){
	$v_is_granted_update = user_is_app_admin($v_application_id, $_SESSION['staff_id']);
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
		 <td width="100%" class="normal_title"><?php echo CONST_ENDUSER_UPDATE_TITLE;?></td>
	</tr>
</table>
<!--bang chua cac text box de nhap du lieu-->
<table width="100%" class="form_table1" align="center">
	<col width="30%"><col width="70%">
	<tr>
		<form action="index.php" method="post" Name="f_dsp_single_enduser">
		<input type="hidden" name="fuseaction" value="UPDATE_ENDUSER" >
		<input type="hidden" name="hdn_item_id" value="<?php echo $v_item_id ?>" >
		<input Type="hidden" name="hdn_enduser_status" value="<?php echo $v_enduser_status; ?>" >
		<input type="hidden" name="hdn_application_id" value="<?php echo $v_application_id; ?>">
		<!--Lay lai APP_CODE de kiem tra quyen(dung lai ham webservice is_app_admin())-->
		<input name="hdn_application_code" type="hidden" value="<?php echo $v_application_code;?>" >
		<input type="hidden" name="hdn_list_group_id_checked" value="">
		<input type="hidden" name="hdn_list_modul_id_checked" value="">
		<input type="hidden" name="hdn_list_function_id_checked" value="">
		<input type="hidden" name="hdn_filter_enduser" value="<?php echo $v_filter; ?>">
		<!--Luu lai vi tri hien thoi de quay lai-->
		<input type="hidden" name="hdn_current_position" value="<?php echo $v_current_position ?>" >
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_APPLICATION_LABEL;?></td>
		<td class="normal_value"><? echo $v_application_name;?></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_ENDUSER_NAME_LABEL; ?> <small class="normal_starmark">*</small></td>
		<td><input Name="txt_username" type="text" class="normal_textbox"  size="40" readonly="<?php echo CONST_USERNAME_READONLY_VALUE;?>"  value="<?php echo $v_fullname; ?>" maxlength="<?php echo CONST_ENDUSER_MAXLENGTH; ?>" message="<?php echo CONST_ENDUSER_MESSAGE;?>" optional="<?php echo CONST_ENDUSER_OPTIONAL;?>"></td>
	</tr><?php
	// Neu su dung ca ISA-USER va ung dung de xac minh NSD thi cho phep hien thi o "Ten truy nhap", "Mat khau"
	if ($v_authenticate_by_isauser==1){ ?>
		<tr>
			<td class="normal_label"><?php echo CONST_ENDUSER_APP_USERNAME_LABEL; ?> <small class="normal_starmark">*</small></td>
			<td><input type="text" name="txt_username" class="normal_textbox" size="30" value="<?php echo $v_app_username; ?>" optional="<?php echo CONST_APP_USERNAME_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
		</tr>
		<tr>
			<td class="normal_label"><?php echo CONST_ENDUSER_APP_PASSWORD_LABEL; ?> <small class="normal_starmark">*</small></td>
			<td><input type="password" name="txt_password" class="normal_textbox" size="30" value="<?php echo $v_app_password; ?>" optional="<?php echo CONST_APP_PASSWORD_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
		</tr><?php
	}?>
	<tr>
		<td>&nbsp;</td>
		<td><input type="checkbox" name="chk_status" class="normal_checkbox" <?php echo $v_str_status_checked;?> onClick="btn_single_checkbox_onclick(document.forms(0).chk_status,document.forms(0).hdn_enduser_status)" onKeyDown="change_focus(document.forms(0),this)"> <?php echo _CONST_STATUS_COLUMN_ACTIVE_VALUE;?></td>
	</tr><?php
	if ($v_group_count>0){?>
		<tr><td height="1" colspan="10"><hr color="#808080" size="1"></td></tr>
		<tr>
			<td class="normal_label" colspan="10" width="100%"><?php echo CONST_USER_LIST;?></td>
		</tr>
		<tr>
			<!--Bang hien nhung nhom thuoc ung dung hien thoi ma User hien thoi thuoc nhom do-->
			<td colspan="2">
				<div style="overflow: auto; width: 100%; height:100pt;padding-left:5px;margin:0px">
				<table class=list_table2 width="100%"  cellpadding="0" cellspacing="0">
					<col width="2%"><col width="98%"><?php
					$i = 0;
					$v_group_url_onclick = "checkbox_group_onclick(this,document.all.chk_group_id,document.all.tr_group,document.all.chk_modul_id,document.all.tr_modul,document.all.chk_function_id,document.all.tr_function,'rad_group_filter','rad_group_function')";
					while ($i < $v_group_count ){
						$v_group_id = $arr_all_group_for_enduser[$i][0];
						$v_group_name = $arr_all_group_for_enduser[$i][1];
						$v_group_checked = "";
						if ($arr_all_group_for_enduser[$i][2] > 0){
							$v_group_checked = "checked";
						}
						if ($v_current_style_name == "odd_row"){
							$v_current_style_name = "round_row";
						}else{
							$v_current_style_name = "odd_row";
						}?>
						<tr id="tr_group" value="<?php echo $v_group_id;?>" checked="<?php echo $v_group_checked;?>" class="<?php echo $v_current_style_name;?>">
							<td><input type="checkbox" name="chk_group_id" value="<?php echo $v_group_id;?>" <?php echo $v_group_checked;?> onClick="<?php echo $v_group_url_onclick;?>"></td>
							<td>&nbsp;&nbsp;<?php echo $v_group_name;?>&nbsp;</td>
						</tr><?php 
						$i++;
					}?>
				</table>
				</div>
			</td>
		</tr>
		<tr><td height="5"></td></tr>
		<tr>
			<td class="small_radiobutton" colspan="10" align="right" width="100%">
				<input type="radio" id="rad_group_filter" value="1" checked onClick="show_row_all('rad_group_filter','tr_group')"><?php echo CONST_DISPLAY_ALL_GROUP_LIST;?>
				<input type="radio" id="rad_group_filter" value="2" onClick="show_row_selected('rad_group_filter','tr_group')" ><?php echo CONST_DISPLAY_ALL_GROUP_AS_USER_LIST;?>
			</td>
		</tr><?php
	}
	if ($v_modul_count>0){?>
		<tr><td height="1" colspan="10"><hr color="#808080" size="1"></td></tr>
		<tr>
			<td class="normal_label" colspan="10" width="100%"><?php echo CONST_FUNCTION_LIST;?></td>
		</tr>
		<tr>
			<!--Bang hien nhung chuc nang thuoc ung dung hien thoi ma User hien thoi duoc ban quyen-->
			<td colspan="2">
				<div style="overflow: auto; width: 100%; height:110pt;padding-left:5px;margin:0px">
				<table class=list_table2 width="100%"  cellpadding="0" cellspacing="0">
					<col width="5%"><col width="5%"><col width="90%"><?php
					$i=0;
					$v_img_url_onclick = "show_function_on_modul(this)";
					$v_function_url_onclick = "change_item_checked(this,'tr_function','rad_group_function')";
					while ($i<$v_modul_count) {
						$v_modul_id = $arr_all_modul_for_enduser[$i][0];
						$v_modul_name = $arr_all_modul_for_enduser[$i][1];
						$v_modul_checked = "";
						if ($arr_all_modul_for_enduser[$i][2] > 0) {
							$v_modul_checked = "checked";
						}?>
						<tr class="midle_row" id="tr_modul" value="<?php echo $v_modul_id;?>" checked="<?php echo $v_modul_checked;?>">
							<td><input type="checkbox" disabled name="chk_modul_id" value="<?php echo $v_modul_id;?>" <?php echo $v_modul_checked;?>></td>
							<td><img id="img_modul" src="<?php echo $_ISA_IMAGE_URL_PATH;?>open.gif" class="normal_image" modul="<?php echo $v_modul_id;?>" status="on" onClick="<?php echo $v_img_url_onclick;?>"></td>
							<td><?php echo $v_modul_name;?>&nbsp;&nbsp;</td>
						</tr><?php
						$j = 0;
						while ($j < $v_function_count) {
							$v_current_modul_id = $arr_all_function_for_enduser[$j][0];
							$v_function_id = $arr_all_function_for_enduser[$j][1];
							$v_function_name = $arr_all_function_for_enduser[$j][2];
							//Lay danh sach nhom ma chuc nang da duoc chon thuoc vao cac nhom do
							$v_group_list = get_group_list_of_function($v_function_id,$arr_all_function_belong_group);
							//Neu chuc nang thuoc vao nhom da ban quyen cho enduser thi dat thuoc tinh cua chk_function_id la disabled
							$v_function_disabled = get_attr_disabled_for_function($v_group_list,$arr_all_group_for_enduser);
							//Lay thuoc tinh checked cho chuc nang neu no thuoc nhom da chon hoac duoc ban quyen truc tiep
							$v_function_checked = "";
							if (($arr_all_function_for_enduser[$j][3] > 0) or ($v_function_disabled == "disabled")){
								$v_function_checked = "checked";
							}
							//$v_function_name = $v_function_name . '<br>thuoc tinh: ' . $v_function_disabled;
							if ($v_current_modul_id == $v_modul_id) {
								if ($v_current_style_name == "odd_row"){
									$v_current_style_name = "round_row";
								}else{
									$v_current_style_name = "odd_row";
								}?>
								<tr id="tr_function" value="<?php echo $v_function_id;?>" modul="<?php echo $v_modul_id;?>" checked="<?php echo $v_function_checked;?>" class="<?php echo $v_current_style_name; ?>">
									<td>&nbsp;&nbsp;</td>
									<td><input type="checkbox" name="chk_function_id" group_list="<?php echo $v_group_list;?>" modul="<?php echo $v_modul_id;?>" value="<?php echo $v_function_id;?>" <?php echo $v_function_disabled;?> <?php echo $v_function_checked;?> onClick="<?php echo $v_function_url_onclick;?>"></td>
									<td>&nbsp;&nbsp;<?php echo $v_function_name;?>&nbsp;</td>
								</tr><?php
							}
							$j++;
						}
						$i++;
					}?>
				</table>
				</div>
			</td>
		</tr>
		<tr>
			<td class="small_radiobutton" colspan="10" width="100%" align="right" height="25">
				<input type="radio" id="rad_group_function" value="1" checked onClick="show_row_all('rad_group_function','tr_function')" ><?php echo CONST_DISPLAY_ALL_FUNCTION;?>
				<input type="radio" id="rad_group_function" value="2" onClick="show_row_selected('rad_group_function','tr_function')"><?php echo CONST_DISPLAY_ALL_FUNCTION_AS_USER_GRANTED;?>
			</td>
		</tr><?php
	}?>	
</table>
<!--bang chua cac button-->
<table align="center">
	<tr><td height="10"></td></tr>
	<tr><?php
		if ($v_is_granted_update==true){
			if(sizeof($arr_all_group_for_enduser) > 0){
				$v_url_onclick="save_hidden_list_item_id(document.forms(0).hdn_list_group_id_checked,document.forms(0).chk_group_id);";
			}
			$v_url_onclick=$v_url_onclick."save_hidden_list_item_id(document.forms(0).hdn_list_modul_id_checked,document.forms(0).chk_modul_id);";
			if(sizeof($arr_all_function_for_enduser) > 0){
			$v_url_onclick=$v_url_onclick."save_hidden_list_item_id(document.forms(0).hdn_list_function_id_checked,document.forms(0).chk_function_id);";
			}
			$v_url_onclick=$v_url_onclick."btn_save_onclick('UPDATE_ENDUSER')";?>
			<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
				<a onClick="<?php echo $v_url_onclick;?>" class="small_link"><?php echo _CONST_SAVE_BUTTON;?></a>&nbsp;&nbsp;
			</td><?php				
		}?>	
		<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
			<a onClick="btn_back_onclick('DISPLAY_ALL_ENDUSER')" class="small_link"><?php echo _CONST_BACK_BUTTON;?></a>
		</td>
	</tr>
</table>
</form>
</div>
<script language="JavaScript">
	set_modul_checked_by_function_checked(document.all.chk_modul_id,document.all.chk_function_id,document.all.tr_modul);
	set_focus(document.forms(0));
	window.dialogHeight = "270pt";
	window.dialogWidth = "420pt";
	window.dialogTop = "80pt";
</script>