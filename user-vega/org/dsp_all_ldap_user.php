<?php
//Luu lai vi tri hien thoi
$v_current_position = "0";
if(isset($_REQUEST['hdn_current_position'])){
	$v_current_position = $_REQUEST['hdn_current_position'];
}

$v_filter = "";
if(isset($_REQUEST['hdn_filter_enduser'])){
	$v_filter = $_REQUEST['hdn_filter_enduser'];
}
$v_application_id = $_SESSION['user_application_id'];
$v_application_code = $_SESSION['user_application_code'];

$v_current_style_name = "odd_row";
$v_next_style_name = "";
$v_goto_url = "enduser/index.php";
?>
<!--Bang chua tieu de-->
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="100%" class="normal_title">
			<?php echo "Danh s&#225;ch NSD trong CSDL LDAP";?>
			<a id="goto" href="" style=".color:white">#</a>
		</td>
	</tr>
</table>
<!-- bang chua cac tieu thuc loc-->
<table cellpadding="0" cellspacing="0" width="100%" style=".display:none">
	<col width="40%"><col width="10%"><col width="50%">
	<tr>
		<form action="index.php" name="f_dsp_all_enduser" method="post">
		<input name="hdn_item_id" type="hidden" value="0">
		<input name="hdn_list_item_id" type="hidden" value="0">
		<input name="hdn_filter_enduser" type="hidden" value="<?php echo $v_filter; ?>">
		<input name="fuseaction" type="hidden" value="DISPLAY_ALL_ENDUSER">
		<input name="hdn_application_id" type="hidden" value="<?php echo $v_application_id;?>">
		<!--Lay lai APP_CODE de kiem tra quyen(dung lai webservice is_app_admin())-->
		<input name="hdn_application_code" type="hidden" value="<?php echo $v_application_code;?>" >
		<!--Luu lai vi tri hien thoi de quay lai-->
		<input name="hdn_current_position" type="hidden" value="<?php echo $v_current_position ?>" >
		
		<td class="normal_label" align="left" ><?php echo CONST_ENDUSER_LIST_FILTER_LABEL;?>
			<input type="text" class="normal_textbox" size="20" name="txt_filter_value" value="<?php echo $v_filter; ?>" onKeyDown="txt_filter_keydown()">
			<input type="button" name="btn_filter" class="small_button" value="<?php echo _CONST_FILTER_BUTTON;?>" onClick="btn_filter_onclick(document.forms(0).hdn_filter_enduser,document.forms(0).txt_filter_value,'DISPLAY_ALL_ENDUSER')">
		</td>
		<td class="normal_label" align="left" style=".display:none"><?php echo CONST_APPLICATION_LABEL;?></td>
		<td class="normal_label" align="left" style=".display:none">
			<select class="normal_selectbox" name="sel_application" onChange="change_text_from_selected(document.forms(0).sel_application,document.forms(0).hdn_application_code,document.forms(0).hdn_application_id);document.forms(0).submit();">
		          <option id="" value="">--<?php echo CONST_NAME_APPLICATION;?>--</option>
			  <?php echo _generate_select_option($arr_all_application,'0','1','3', $v_application_id);?> 
	        </select>&nbsp;
		</td>
	</tr>
	<tr><td height="5"></td></tr>
</table>
<!-- Bat cac phim: F12=false; Insert=true; Delete=true, ESC=false; Enter=false -->
<div id="hotkey" onKeyDown="javascript:process_hot_key(false,true,true,false,false);">
<table class="list_table2" width="100%" cellpadding="0" cellspacing="0">
	<col width="5%"><col width="95%">
	<tr class="header">
		<td>#</td>	
		<td><?php echo CONST_ENDUSER_NAME_LABEL; ?></td>
	</tr>
</table>	
<div style="overflow: auto; width: 100%; height:<?php echo _CONST_HEIGHT_OF_LIST?>;padding-left:0px;margin:0px">
<table class="list_table2" width="100%" cellpadding="0" cellspacing="0">
	<col width="5%"><col width="95%"><?php
	$v_count = $arr_all_ldap_user["count"];
	if ($v_count >0){
		$i = 0;
		$v_old_unit_level_one_name = "";
		while($i < $v_count) {
			$arr_single_user = $arr_all_ldap_user[$i];
			$v_item_id = _replace_bad_char($arr_single_user["dn"]);
			$v_item_name = $arr_single_user[$_ISA_LDAP_USERNAME_ATTRIBUTE][0];
			//$v_item_mail = $arr_single_user["mailaddress"][0];
			$v_item_status = 1;
			$v_unit_level_one_id = "0";
			$v_unit_level_one_name = "";
			//$v_unit_name = $arr_single_user[$i][5];
			//if ($v_unit_name == $v_unit_level_one_name){
				$v_unit_name = "";
			//}
			if ($v_item_status==1){
				$v_item_status=_CONST_STATUS_COLUMN_ACTIVE_VALUE;
			}else{
				$v_item_status=_CONST_STATUS_COLUMN_INACTIVE_VALUE;
			}
			$v_img_url_onclick = "show_enduser_on_unit(this)";
			$v_chk_modul_id_onclick = "select_all_enduser_on_unit(this,document.all.chk_item_id)";
			$v_chk_enduser_id_onclick = "set_value_checked_checkbox_unit(this,document.all.chk_item_id,document.all.chk_unit_id)";
			$v_url = "enduser_onclick(" . "document.forms(0).hdn_item_id"  . ",'" . $v_item_id . "'," . $v_unit_level_one_id . "," . "'DISPLAY_SINGLE_ENDUSER','" . $v_goto_url . "')";
			if ($v_current_style_name == "odd_row"){
				$v_current_style_name = "round_row";
			}else{
				$v_current_style_name = "odd_row";
			}
			$v_disable_str = "";
			if (_get_array_value($arr_all_current_staff, 0, 0, $v_item_id)==$v_item_id){
				$v_disable_str = " disabled";
			}
			
			if ($v_item_name!=""){?>
				<tr id="tr_enduser" value="<?php echo $v_item_id;?>" unit="<?php echo $v_unit_level_one_id;?>" class="<?php echo $v_current_style_name; ?>">
					<td align="center">
						<input type="checkbox" name="chk_item_id" value="<?php echo $v_item_id; ?>" unit_id="<?php echo $v_unit_level_one_id;?>" <? echo $v_disable_str;?>>
					</td>				
					<td align="left">
						<a name="<?php echo $v_item_id; ?>"><?php echo $v_item_name; ?>&nbsp;</a>
					</td>
				</tr><?php 
			}	
			$i++;
		}	
	}
	if ($v_current_style_name == "odd_row"){
		$v_next_style_name = "round_row";
	}else{
		$v_next_style_name = "odd_row";
	}
	echo _add_empty_row($v_count,_CONST_NUMBER_OF_ROW_PER_LIST,$v_current_style_name,$v_next_style_name,5);?>
</table>
</div>
<!--Table chua cac nut Show_all va Hide_all-->
<table width="100%" cellpadding="0" cellspacing="0" style="display:none">
	<tr>
		<td class="small_radiobutton" colspan="10" width="50%" height="25">
			<input type="radio" id="rad_show_enduser" value="1" checked onClick="show_or_hide_all_enduser('SHOW_ALL',document.all.tr_enduser,document.all.img_unit,document.all.rad_show_enduser)" ><?php echo CONST_SHOW_ALL_ENDUSER;?>
		</td>
		<td class="small_radiobutton" colspan="10" width="50%" height="25">
			<input type="radio" id="rad_show_enduser" value="2" onClick="show_or_hide_all_enduser('HIDE_ALL',document.all.tr_enduser,document.all.img_unit,document.all.rad_show_enduser)"><?php echo CONST_HIDE_ALL_ENDUSER;?>
		</td>
	</tr>
</table>
<!--Table chua cac button-->
<table align="center">
	<tr><td height="10"></td></tr>
	<tr><?php
		if($v_is_granted_update==true){?>
			<td align="center">
				<input type="button" onClick="btn_add_staff_from_ldap_user();" class="normal_button" value="<?php echo _CONST_ADD_BUTTON;?>">&nbsp;&nbsp;
			</td><?php
		}
		if($v_is_granted_delete==true){?>
			<td align="center">
				<input type="button" onClick="window.close();" class="normal_button" value="<?php echo _CONST_CLOSE_BUTTON;?>">
			</td><?php
		}?>
	</tr>
</table>
</div id="hotkey">
</form>
<script language="JavaScript">
	// Chuyen den vi tri hien tai
	document.all.goto.href="#<? echo $v_current_position;?>";
	document.all.goto.click();
</script>
 