<!-- Phan khai bao bien cuc bo-->
<?php
// Luu dieu kien loc hien thoi
$v_filter_group = $_REQUEST['hdn_filter_group'];
//$v_application_id = $_REQUEST['hdn_application_id'];
//$v_application_id = $_SESSION['user_application_id'];
$v_application_id = $_SESSION['user_application_id'];
$v_application_code = $_SESSION['user_application_code'];

if($v_item_id >0){
	$v_item_code = $arr_single_group[0]['1'];
	$v_item_name = $arr_single_group[0]['2'];
	$v_item_order = $arr_single_group[0]['3'];
	$v_item_status = $arr_single_group[0]['4'];
	$v_application_name =$arr_single_group[0][5];
	$v_save_and_add_new = 0;
}else{
	$v_item_code = "";
	$v_item_name = "";
	$v_item_order = _get_next_value("T_USER_GROUP","C_ORDER","");
	$v_item_status = 1;
	$v_application_name =$arr_single_group[0][5];
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
$v_count_enduser = sizeof($arr_all_enduser_for_group);
$v_count_modul = sizeof($arr_all_modul_for_group); 
$v_count_function = sizeof($arr_all_function_for_group);
// Luu ten style hien thoi
$v_current_style_name = "odd_row";
// Luu ten style tiep theo
$v_next_style_name = "";
$v_url_onclick ="";
// Neu khong phai la QUAN TRI ISA-USER thi phai goi ham user_is_app_admin de kiem tra xem nguoi dang nhap co
// phai la QUAN TRI cua ung dung hien tai hay khong
if ($_SESSION['is_isa_user_admin']<>1){
	$v_is_granted_update = user_is_app_admin($v_application_id, $_SESSION['staff_id']);
}
?>
<!--bang chua tieu de cua form-->
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td height="5"> </td>
	</tr>
	<tr>
		 <td width="100%" class="normal_title"><?php echo CONST_GROUP_UPDATE_TITLE;?></td>
	</tr>
</table>
<!--bang chua cac text box de nhap du lieu-->
<table width="100%" class="form_table1" cellpadding="0" cellspacing="0" border="0">
	<col width="30%"><col width="70%">
	<tr>
		<form action="index.php" method="post" name="f_dsp_single_group">
		<input Type="hidden" name="fuseaction" value="UPDATE_GROUP">
		<input Type="hidden" name="hdn_item_id" value="<?php echo $v_item_id ?>">
		<input type="hidden" name="hdn_application_id" value="<?php echo $v_application_id;?>">
		<!--Lay lai APP_CODE de kiem tra quyen(dung lai ham webservice is_app_admin())-->
		<input name="hdn_application_code" type="hidden" value="<?php echo $v_application_code;?>" >
		<input Type="hidden" name="hdn_item_status" value="<?php echo $v_item_status ?>">
		<input type="hidden" name="hdn_save_and_add_new" value="<?php echo $v_save_and_add_new; ?>"> 
		<input type="hidden" name="hdn_list_enduser_id_checked" value="">
		<input type="hidden" name="hdn_list_modul_id_checked" value="">
		<input type="hidden" name="hdn_list_function_id_checked" value="">
		<input name="hdn_filter_group" type="hidden" value="<?php echo $v_filter_group; ?>">
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_APPLICATION_LABEL; ?></td>
		<td class="normal_value"><?php echo $v_application_name;?></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_GROUP_CODE_LABEL; ?> <small class="normal_starmark">*</small></td>
		<td><input Name="txt_code" type="text" class="normal_textbox" style="width:100%" value="<?php echo $v_item_code; ?>" maxlength="<?php echo CONST_GROUP_CODE_MAXLENGTH; ?>" message="<?php echo CONST_GROUP_CODE_MESSAGE;?>" optional="<?php echo CONST_GROUP_CODE_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_GROUP_NAME_LABEL; ?> <small class="normal_starmark">*</small></td>
		<td><input Name="txt_name" Type="text" class="normal_textbox" style="width:100%" value="<?php echo $v_item_name; ?>" maxlength="<?php echo CONST_GROUP_NAME_MAXLENGTH;?>" message="<?php echo CONST_GROUP_NAME_MESSAGE;?>" optional="<?php echo CONST_GROUP_NAME_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr>
		<td class="normal_label"><?php echo CONST_GROUP_ORDER_LABEL; ?></td>
		<td><input Name="txt_order" Type="text" class="short_number_textbox" value="<?php echo $v_item_order; ?>" maxlength="<?php echo CONST_GROUP_ORDER_MAXLENGTH;?>" message="<?php echo CONST_GROUP_ORDER_MESSAGE;?>" optional="<?php echo CONST_GROUP_ORDER_OPTIONAL;?>" isnumeric="<?php echo CONST_GROUP_ORDER_ISNUMERIC; ?>" min="<?php echo CONST_GROUP_ORDER_MIN; ?>" max="<?php echo CONST_GROUP_ORDER_MAX; ?>" onKeyDown="change_focus(document.forms(0),this)"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="checkbox" name="chk_status" class="normal_checkbox" <?php echo $v_str_status_checked;?> onClick="btn_single_checkbox_onclick(document.forms(0).chk_status,document.forms(0).hdn_item_status)" onKeyDown="change_focus(document.forms(0),this)"> <?php echo _CONST_STATUS_COLUMN_ACTIVE_VALUE;?>
			<input type="checkbox" name="chk_save_and_add_new" class="normal_checkbox" <?php echo $v_str_save_and_add_new_checked; ?> onclick="btn_single_checkbox_onclick(document.forms(0).chk_save_and_add_new,document.forms(0).hdn_save_and_add_new)" onKeyDown="change_focus(document.forms(0),this)"><?php echo _CONST_SAVE_AND_ADD_NEW_LABEL;?>
		</td>
		<td height="37">&nbsp;</td>
	</tr><?php
	if ($v_count_enduser>0){?>
		<tr><td height="1" colspan="10"><hr color="#808080" size="1"></td></tr>
		<tr><td class="normal_label" colspan="2"><?php echo CONST_GROUP_USER_MEMBER;?></td></tr>
		<tr>
			<td colspan="2">
				<DIV id="enduser_list" STYLE="overflow:auto; width:100%; height:120pt;padding-left:5px;margin:0px">
				<table class="list_table2"  width="100%" cellpadding="0" cellspacing="0">
					<col width="5%"><col width="5%"><col width="25%"><col width="20%"><col width="30%"><col width="15%"><?php
					if ($v_count_enduser >0){
						$i = 0;
						$v_old_unit_level_one_name = "";
						while($i < $v_count_enduser) {
							$v_item_id = $arr_all_enduser_for_group[$i][0];
							$v_item_name = $arr_all_enduser_for_group[$i][1];
							$v_item_status = $arr_all_enduser_for_group[$i][2];
							$v_unit_level_one_id = $arr_all_enduser_for_group[$i][3];
							$v_unit_level_one_name = $arr_all_enduser_for_group[$i][4];
							$v_unit_name = $arr_all_enduser_for_group[$i][5];
							$v_staff_position = $arr_all_enduser_for_group[$i][7];

							$v_enduser_checked = "";
							if ($arr_all_enduser_for_group[$i][6] > 0 ){
								$v_enduser_checked = "checked";			
							}
							if ($v_unit_name == $v_unit_level_one_name){
								$v_unit_name = "";
							}
							if ($v_item_status==1){
								$v_item_status=_CONST_STATUS_COLUMN_ACTIVE_VALUE;
							}else{
								$v_item_status=_CONST_STATUS_COLUMN_INACTIVE_VALUE;
							}
							$v_img_url_onclick = "show_enduser_on_unit_for_uppdating_group(this,document.all.rad_group_enduser)";
							//$v_url = "row_onclick(" . "document.forms(0).hdn_item_id"  . "," . $arr_all_user[$i][0] . "," . "'DISPLAY_SINGLE_ENDUSER','" . $v_goto_url . "')";
							$v_enduser_url_onclick = "change_item_checked(this,'tr_enduser','rad_group_enduser')";
							if ($v_current_style_name == "odd_row"){
								$v_current_style_name = "round_row";
							}else{
								$v_current_style_name = "odd_row";
							}
							if ($v_unit_level_one_name != $v_old_unit_level_one_name){?>	
								<tr class="midle_row">
									<td align="center">
										<input type="checkbox" name="chk_unit_id" value="<?php echo $v_unit_level_one_id; ?>" disabled>
									</td>
									<td align="center">
										<img id="img_unit" src="<?php echo $_ISA_IMAGE_URL_PATH;?>open.gif" class="normal_image" unit="<?php echo $v_unit_level_one_id;?>" status="on" onClick="<?php echo $v_img_url_onclick;?>"></td>
									</td>
									<td align="left" colspan="10">
										<?php echo $v_unit_level_one_name; ?>&nbsp;
									</td>
								</tr><?php 
								$v_old_unit_level_one_name = $v_unit_level_one_name;
							}?>
							<tr id="tr_enduser" value="<?php echo $v_item_id;?>" checked="<?php echo $v_enduser_checked;?>" unit="<?php echo $v_unit_level_one_id;?>" class="<?php echo $v_current_style_name; ?>">
								<td>&nbsp;&nbsp;</td>
								<td align="center">
									<input type="checkbox" name="chk_enduser_id" value="<?php echo $v_item_id; ?>" <?php echo $v_enduser_checked;?>  onClick="<?php echo $v_enduser_url_onclick;?>">
								</td>				
								<td align="left" onclick="<?php echo $v_url;?>">
									<?php echo $v_item_name; ?>&nbsp;
								</td>
								<td align="center" onclick="<?php echo $v_url;?>">
									<?php echo $v_staff_position; ?>&nbsp;
								</td>
								<td align="center" onclick="<?php echo $v_url;?>">
									<?php echo $v_unit_name; ?>&nbsp;
								</td>
								<td align="center" onclick="<?php echo $v_url;?>">
									<?php echo $v_item_status; ?>&nbsp;
								</td>
							</tr><?php 
							$i++;
						}	
					}
					if ($v_current_style_name == "odd_row"){
						$v_next_style_name = "round_row";
					}else{
						$v_next_style_name = "odd_row";
					}
					echo _add_empty_row($v_count_enduser,_CONST_NUMBER_OF_ROW_PER_LIST,$v_current_style_name,$v_next_style_name,5);?>
				</table>
				</DIV>
			</td>
		</tr>
		<tr>
			<td class="small_radiobutton" colspan="10" align="right">
				<input type="radio" id="rad_group_enduser" value="1" checked onClick="show_row_all('rad_group_enduser','tr_enduser')"><?php echo CONST_GROUP_ALL_USER_DISPLAY;?>
				<input type="radio" id="rad_group_enduser" value="2" onClick="show_row_selected('rad_group_enduser','tr_enduser')"><?php echo CONST_GROUP_USER_DISPLAY;?>
			</td>
		</tr><?php
	}?>	
	<!-- Bang chua cac chuc nang cua nhom trong ung dung-->
	<?php
	if ($v_count_modul>0){?>
		<tr><td height="1" colspan="10"><hr color="#808080" size="1"></td></tr>	
		<tr>
			<td class="normal_label" colspan="10"><?php echo CONST_GROUP_USER_FUNCTION;?></td>
		</tr>
		<tr>
			<td colspan="2">
				<DIV id="modul_list" STYLE="overflow: auto; width: 100%; height:120pt;padding-left:5px;margin:0px">
				<table class="list_table2" width="100%" cellpadding="0" cellspacing="0">
					<col width="5%"><col width="5%"><col width="90%"><?php
					$i=0;
					$v_img_url_onclick = "show_function_on_modul(this)";
					$v_function_url_onclick = "change_item_checked(this,'tr_function','rad_group_function')";
					while ($i<$v_count_modul) {
						$v_modul_id = $arr_all_modul_for_group[$i][0];
						$v_modul_name = $arr_all_modul_for_group[$i][1];
						$v_modul_checked = "";
						if ($arr_all_modul_for_group[$i][2] > 0){
							$v_modul_checked = "checked";
						}?>
						<tr class="midle_row" id="tr_modul" value="<?php echo $v_modul_id;?>" checked="<?php echo $v_modul_checked;?>">
							<td>
								<input type="checkbox" disabled id="chk_modul_id" value="<?php echo $v_modul_id;?>" <?php echo $v_modul_checked;?>>
							</td>
							<td>
								<img id="img_modul" src="<?php echo $_ISA_IMAGE_URL_PATH;?>open.gif" class="normal_image" modul="<?php echo $v_modul_id;?>" status="on" onClick="<?php echo $v_img_url_onclick;?>">
							</td>
							<td><?php echo $v_modul_name;?>&nbsp;&nbsp;</td>
						</tr><?php
						$j = 0;
						while ($j < $v_count_function) {
							$v_current_modul_id = $arr_all_function_for_group[$j][0];
							$v_function_id = $arr_all_function_for_group[$j][1];
							$v_function_name = $arr_all_function_for_group[$j][2];
							$v_function_checked = "";
							if ($arr_all_function_for_group[$j][3] > 0)	{
								$v_function_checked = "checked";
							}
							if ($v_current_modul_id == $v_modul_id) {
								if ($v_current_style_name == "odd_row"){
									$v_current_style_name = "round_row";
								}else{
									$v_current_style_name = "odd_row";
								}?>
								<tr id="tr_function" value="<?php echo $v_function_id;?>" modul="<?php echo $v_modul_id;?>" checked="<?php echo $v_function_checked;?>" class="<?php echo $v_current_style_name; ?>">
									<td>&nbsp;&nbsp;</td>
									<td><input type="checkbox" id="chk_function_id" modul="<?php echo $v_modul_id;?>" value="<?php echo $v_function_id;?>" <?php echo $v_function_checked;?> onClick="<?php echo $v_function_url_onclick;?>"></td>
									<td>&nbsp;&nbsp;<?php echo $v_function_name;?>&nbsp;</td>
								</tr><?php
							}
							$j++;
						}
						$i++;
					}
					//echo _add_empty_row($v_count_function,CONST_HEIGHT_OF_LIST,$v_current_style_name,$v_next_style_name,3);?>
				</table>
				</DIV>
			</td>
		</tr>
		<tr>
			<td class="small_radiobutton" colspan="10" align="right">
				<input type="radio" id="rad_group_function" value="1" checked onClick="show_row_all('rad_group_function','tr_function')"><?php echo CONST_GROUP_ALL_FUNCTION_DISPLAY;?>
				<input type="radio" id="rad_group_function" value="2" onClick="show_row_selected('rad_group_function','tr_function')"><?php echo CONST_GROUP_FUNCTION_DISPLAY;?>
			</td>
		</tr><?php
	}?>
</table>
<!--bang chua cac button-->     
<table align="center">
	<tr><td height="10"></td></tr>
	<tr><?php
		if ($v_is_granted_update==true){
			$v_url_onclick = "";
			if ($v_count_enduser > 0) {
				$v_url_onclick="save_hidden_list_item_id(document.forms(0).hdn_list_enduser_id_checked,document.forms(0).chk_enduser_id);";
			}
			$v_url_onclick=$v_url_onclick."save_hidden_list_item_id(document.forms(0).hdn_list_modul_id_checked,document.forms(0).chk_modul_id);";
			if ($v_count_function > 0) {
				$v_url_onclick=$v_url_onclick."save_hidden_list_item_id(document.forms(0).hdn_list_function_id_checked,document.forms(0).chk_function_id);";
			}	
			$v_url_onclick=$v_url_onclick."btn_save_onclick('UPDATE_GROUP')";?>
			<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
				<a onClick="<?php echo $v_url_onclick;?>" class="small_link"><?php echo _CONST_SAVE_BUTTON;?></a>&nbsp;&nbsp;
			</td><?php				
		}?>	
		<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
			<a onClick="btn_back_onclick('DISPLAY_ALL_GROUP')" class="small_link"><?php echo _CONST_BACK_BUTTON;?></a>
		</td>
	</tr>
</table>
</form>

<script language="JavaScript">
	set_focus(document.forms(0));
	window.dialogHeight = "370pt";
	window.dialogWidth = "420pt";
	window.dialogTop = "80pt";
</script>