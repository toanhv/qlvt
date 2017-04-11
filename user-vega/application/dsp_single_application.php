<!-- Phan khai bao bien cuc bo-->
<?php
error_reporting(0);
$v_list_parent_id =  "";
if(isset($_REQUEST['hdn_list_parent_id'])){
	$v_list_parent_id  = $_REQUEST['hdn_list_parent_id'];
}
$v_item_id = 0;
if(isset($_REQUEST['hdn_item_id'])){
	$v_item_id = $_REQUEST['hdn_item_id'];
}
if($v_item_id >0){
	$v_item_code = $arr_single_application[0][1];
	$v_item_name = $arr_single_application[0][2];
	$v_item_order = $arr_single_application[0][3];
	$v_item_status = $arr_single_application[0][4];
	$v_item_description = $arr_single_application[0][5];
	$v_item_web_based_app = $arr_single_application[0][6];
	$v_item_authenticate_by_isauser = $arr_single_application[0][7];
	$v_item_url_path = $arr_single_application[0][8];
	$v_username_var = $arr_single_application[0][9];
	$v_password_var = $arr_single_application[0][10];
	$v_varible_name_list = $arr_single_application[0][11];
	$v_varible_value_list = $arr_single_application[0][12];
	$v_save_and_add_new = 0;
}else{
	$v_item_code = "";
	$v_item_name = "";
	$v_item_order = _get_next_value("T_USER_APPLICATION","C_ORDER","");
	$v_item_status = 1;
	$v_item_description = "";
	$v_item_web_based_app = "";
	$v_item_authenticate_by_user = "";
	$v_item_url_path = "";
	$v_username_var = "";
	$v_password_var = "";
	$v_varible_name_list = "";
	$v_varible_value_list = "";
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
//So bien toi da 
$v_max_varible = 6;

?>
<!-- Bat cac phim: F12=true; Insert=false; Delete=false, ESC=true; Enter=true -->
<div id="hotkey" onKeyDown="javascript:process_hot_key(true,false,false,true,true);">
<!--bang chua tieu de cua form-->
<table width="100%" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td height="5"> </td>
	</tr>
	<tr>
		 <td width="100%" class="normal_title"><?php echo CONST_APPLICATION_UPDATE_TITLE;?></td>
	</tr>
</table>
<!--bang chua cac text box de nhap du lieu-->
<table width="100%" class="form_table1" align="center">
	<col width="20%"><col width="80%">
	<tr>
		<form action="index.php" method="post" Name="f_dsp_single_application">
		<input type="hidden" Name="fuseaction" value="UPDATE_APPLICATION" >
		<input type="hidden" Name="hdn_item_id" value="<?php echo $v_item_id ?>" >
		<input type="hidden" Name="hdn_current_position" value="<?php echo $v_current_position ?>" >
		<input type="hidden" Name="hdn_admin_id_list" value="" >
		<input type="hidden" Name="hdn_item_status" value="<?php echo $v_item_status ?>" >
		<input type="hidden" name="hdn_save_and_add_new" value="<?php echo $v_save_and_add_new; ?>"> 
		<input type="hidden" name="hdn_list_parent_id" value="<?php echo $v_list_parent_id;?>">
		<input type="hidden" name="hdn_varible_name_list" value="<?php echo $v_varible_name_list;?>">
		<input type="hidden" name="hdn_varible_value_list" value="<?php echo $v_varible_value_list;?>">
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_APPLICATION_CODE_LABEL; ?> <small class="normal_starmark">*</small></td>
		<td><input Name="txt_code" type="text" class="normal_textbox" size="40"  value="<?php echo $v_item_code; ?>" maxlength="<?php echo CONST_APPLICATION_CODE_MAXLENGTH; ?>" message="<?php echo CONST_APPLICATION_CODE_MESSAGE;?>" optional="<?php echo CONST_APPLICATION_CODE_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_APPLICATION_NAME_LABEL; ?> <small class="normal_starmark">*</small></td>
		<td><input Name="txt_name" Type="text" class="normal_textbox" size="50" value="<?php echo $v_item_name; ?>" maxlength="<?php echo CONST_APPLICATION_NAME_MAXLENGTH;?>" message="<?php echo CONST_APPLICATION_NAME_MESSAGE;?>" optional="<?php echo CONST_APPLICATION_NAME_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>	
	<tr>
		<td class="normal_label"><?php echo CONST_APPLICATION_DESCRIPTION_LABEL; ?></td>
		<td colspan="2"><textarea name="txt_description" class="normal_textarea" cols="55" rows="4" optional="<?php echo CONST_APPLICATION_DESCRIPTION_OPTIONAL;?>"><?php echo $v_item_description;?></textarea>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_APPLICATION_WEB_BASED_APP_LABEL; ?></td>
		<td>
			<select name="sel_web_based_app" class="normal_selectbox" style="width:60%" optional="<?php echo CONST_APPLICATION_WEB_BASED_APP_OPTIONAL;?>" >
				<option value="0"><?php echo CONST_WEB_APPLICATION;?></option>
				<option value="1"><?php echo CONST_DESTOP_APPLICATION;?></option>
			</select>			
	</tr>
	<tr><td colspan="5"><hr size="1"></td></tr>
	<tr>
		<td class="normal_label"><?php echo CONST_APPLICATION_AUTHENTICATE_BY_ISAUSER_LABEL; ?></td>
		<td>
			<select name="sel_authenticate_by_user" class="normal_selectbox" style="width:80%" optional="<?php echo CONST_APPLICATION_AUTHENTICATE_BY_ISAUSER_OPTIONAL;?>" onchange="authentication_type_onchange(this);" >
				<option value="0"><?php echo CONST_ISA_WEB_ONLY;?></option>
				<option value="1"><?php echo CONST_BOTH_APPLICATION;?></option>
			</select>
		</td>	
	</tr>
	<tr id="tr_application">
		<td class="normal_label"><?php echo CONST_APPLICATION_START_PATH_LABEL; ?></td>
		<td><input Type="text" Name="txt_url_path" class="normal_textbox" style="width:100%" value="<?php echo $v_item_url_path; ?>" optional="<?php echo CONST_APPLICATION_START_PATH_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr id="tr_application">
		<td class="normal_label"><?php echo CONST_APPLICATION_USERNAME_VAR_LABEL; ?></td>
		<td><input Type="text" Name="txt_username_var" class="normal_textbox" style="width:60%" value="<?php echo $v_username_var; ?>" optional="<?php echo CONST_APPLICATION_USERNAME_VAR_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr id="tr_application">
		<td class="normal_label"><?php echo CONST_APPLICATION_PASSWORD_VAR_LABEL; ?></td>
		<td><input Type="text" Name="txt_password_var" class="normal_textbox" style="width:60%" value="<?php echo $v_password_var; ?>" optional="<?php echo CONST_APPLICATION_PASSWORD_VAR_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr><?php

	$arr_varible_name_list = explode(_CONST_SUB_LIST_DELIMITOR,$v_varible_name_list,$v_max_varible);
	$arr_varible_value_list = explode(_CONST_SUB_LIST_DELIMITOR,$v_varible_value_list,$v_max_varible);
	for ($i=0; $i<$v_max_varible; $i++){?>
	<tr id="tr_application">
		<td class="normal_label"><?php if ($i==0) echo CONST_APPLICATION_VARIBLE_LABEL;?></td>
		<td>
			<input type="text" name="txt_varible_name_list" style="width:40%" value="<?php echo $arr_varible_name_list[$i]; ?>" optional="<?php echo CONST_APPLICATION_VARIBLE_OPTIONAL;?>" onClick="">
			<input type="text" name="txt_varible_value_list" style="width:40%" value="<?php echo $arr_varible_value_list[$i]; ?>" optional="<?php echo CONST_APPLICATION_VARIBLE_OPTIONAL;?>" onClick="">
		</td>
	</tr><?php
	}?>
	<tr><td colspan="5"><hr size="1"></td></tr>
	<tr>
		<td class="normal_label"><?php echo CONST_APPLICATION_ORDER_LABEL; ?> <small class="normal_starmark">*</small></td>
		<td><input Name="txt_order" Type="text" class="short_number_textbox" value="<?php echo $v_item_order; ?>" maxlength="<?php echo CONST_APPLICATION_ORDER_MAXLENGTH;?>" message="<?php echo CONST_APPLICATION_ORDER_MESSAGE;?>" optional="<?php echo CONST_APPLICATION_ORDER_OPTIONAL;?>" isnumeric="<?php echo CONST_APPLICATION_ORDER_ISNUMERIC; ?>" min="<?php echo CONST_APPLICATION_ORDER_MIN; ?>" max="<?php echo CONST_APPLICATION_ORDER_MAX; ?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>	
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="checkbox" name="chk_status" class="normal_checkbox" <?php echo $v_str_status_checked;?> onClick="btn_single_checkbox_onclick(document.forms(0).chk_status,document.forms(0).hdn_item_status)" onKeyDown="change_focus(document.forms(0),this)"> <?php echo _CONST_STATUS_COLUMN_ACTIVE_VALUE;?>
			<input type="checkbox" name="chk_save_and_add_new" class="normal_checkbox" <?php echo $v_str_save_and_add_new_checked; ?> onclick="btn_single_checkbox_onclick(document.forms(0).chk_save_and_add_new,document.forms(0).hdn_save_and_add_new)" onKeyDown="change_focus(document.forms(0),this)"><?php echo _CONST_SAVE_AND_ADD_NEW_LABEL;?>	
		</td>
	</tr>
	<tr>
		<td width="100%" class="small_title"><?php echo CONST_ADMIN_LIST_TITLE;?></td>		
	</tr>
	<tr>
		<td colspan="2">
			<!--danh sach nguoi quan tri application-->
			<div style="overflow: auto; width: 100%; height:100px;padding-left:5px;margin:0px">
				<table class="list_table2" cellpadding="0" cellspacing="0" width="100%" >
					<col width="5%"><col width="95%"><?php
					$v_enduser_url_onclick = "change_item_checked(this,'tr_enduser','rad_enduser')";				
					$v_count = sizeof($arr_all_admin_for_application);
					if($v_count >0){
						$v_current_style_name = "odd_row";
						$v_next_style_name = "";
						// Bien nay de kiem tra xem ung dung hien thoi da co nguoi quan tri nao chua
						$v_have_at_least_one_admin = 0;
						$v_all_staff_checked = "checked";
						$v_only_admin_checked = "";
						$i=0;		
						while($i < $v_count){
							$v_staff_id = $arr_all_admin_for_application[$i][0];					
							$v_staff_name = $arr_all_admin_for_application[$i][1];												
							$v_app_admin = $arr_all_admin_for_application[$i][2]; // Neu la 1 thi staff nay la NGUOI QUAN TRI cua ung dung hien thoi
							if ($v_current_style_name == "odd_row"){
								$v_current_style_name = "round_row";
							}else{
								$v_current_style_name = "odd_row";
							}
							$v_row_style = "";
							$v_staff_checked = "";
							if ($v_app_admin>0){
								$v_have_at_least_one_admin = 1;
								$v_all_staff_checked = "";
								$v_only_admin_checked = "checked";
								$v_staff_checked = "checked";											
							}
							// Neu nguoi dang nhap la QUAN TRI ISA-USER thi cho phep chon nguoi quan tri
							if ($_SESSION['is_isa_user_admin']==1){
								// Neu la hieu chinh mot ung dung thi ngam dinh chi hien thi danh sach NGUOI QUAN TRI UNG DUNG
								if ($v_item_id>0){
									if ($v_app_admin > 0){
										$v_row_style = ".display:block";
									}else{
										$v_row_style = ".display:none";
									}
								}else{ // Meu la them moi mot ung dung thi ngam dinh hien thi tat ca STAFF
									$v_row_style = ".display:block";
								}
							}else{ // Neu nguoi dang nhap KHONG PHAI la QUAN TRI ISA-USER thi chi cho xem danh sach
								$v_row_style = ".display:none";
							}?>	
							<tr id="tr_enduser" value="<?php echo $v_staff_id;?>" checked="<?php echo $v_staff_checked;?>" class="<?php echo $v_current_style_name ?>" style="<?php echo $v_row_style;?>">
								<td align="center">
									<input type="checkbox" name="chk_staff_id"  value="<?php echo $v_staff_id; ?>" <?php echo $v_staff_checked;?> onClick="<?php echo $v_enduser_url_onclick ;?>">
								</td>
								<td align="left"><?php echo $v_staff_name;?></td>
							</tr><?php
							if ($_SESSION['is_isa_user_admin']!=1 And $v_app_admin >0){?>
								<tr class="<?php echo $v_current_style_name ?>">
									<td colspan="2" align="left"><?php echo $v_staff_name;?></td>
								</tr><?php
							}
							$i++;
						}															
					}?>								
				</table>
			</div>
		</td>
	</tr><?php 
	if ($_SESSION['is_isa_user_admin']==1){?>
		<tr>
			<td colspan="10" width="100%" class="small_radiobutton" align="right">
				<input type="radio" id="rad_enduser" value="1" <?php echo $v_all_staff_checked;?>  onClick="show_row_all('rad_enduser','tr_enduser')"><?php echo CONST_ALL_USER_DISPLAY;?>
				<input type="radio" id="rad_enduser" value="2" <?php echo $v_only_admin_checked;?> onClick="show_row_selected('rad_enduser','tr_enduser')"><?php echo CONST_OWNER_USER_DISPLAY;?>
			</td>
		</tr><?php
	}?>
</table>
<!--bang chua cac button-->
<table align="center" >
	<tr><td height="10"></td></tr>
	<tr><?php
		if($v_is_granted_update_app==true){?>
			<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
				<a onClick="save_list_to_hidden(document.forms(0).hdn_varible_name_list,document.forms(0).hdn_varible_value_list,document.forms(0).txt_varible_name_list,document.forms(0).txt_varible_value_list);btn_save_onclick('UPDATE_APPLICATION');" class="small_link"><?php echo _CONST_SAVE_BUTTON;?></a>&nbsp;&nbsp;
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
	<?php 
	if ($_SESSION['is_isa_user_admin']==1){
		if ($v_have_at_least_one_admin==1){?>
			show_row_selected('rad_enduser','tr_enduser');<?php 
		}else{?>
			show_row_all('rad_enduser','tr_enduser');<?php 
		}
	}?>
	set_selected(document.forms(0).sel_web_based_app,"<?php echo $v_item_web_based_app; ?>")
	set_selected(document.forms(0).sel_authenticate_by_user,"<?php echo $v_item_authenticate_by_isauser; ?>")
	authentication_type_onchange(document.forms(0).sel_authenticate_by_user);
	set_focus(document.forms(0));
	window.dialogHeight = "370pt";
	window.dialogWidth = "420pt";
	window.dialogTop = "80pt";
</script>