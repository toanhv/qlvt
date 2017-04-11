<?php
//Luu lai vi tri hien thoi
$v_current_position = "0";
if(isset($_REQUEST['hdn_current_position'])){
	$v_current_position = $_REQUEST['hdn_current_position'];
}
//echo $v_current_position;

$v_filter = "";
if(isset($_REQUEST['hdn_filter_enduser'])){
	$v_filter = $_REQUEST['hdn_filter_enduser'];
}
$v_application_id = $_SESSION['user_application_id'];
$v_application_code = $_SESSION['user_application_code'];

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
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td height="5"> </td>
	</tr>
	<tr>
		<td width="100%" class="normal_title">
			<?php echo CONST_ENDUSER_LIST_TITLE;?>
			<a id="goto" href="" style=".color:white">#</a>
		</td>
	</tr>
</table>
<!-- bang chua cac tieu thuc loc-->
<table cellpadding="0" cellspacing="0" width="100%">
	<tr><td height="5"></td></tr>
	<col width="15%"><col width="85%">
	<tr align="center">
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
		
		<td class="normal_label" align="left" >
			<?php echo CONST_ENDUSER_LIST_FILTER_LABEL;?>
		</td>
		<td class="normal_label" align="left" >
			<input type="text" class="normal_textbox" style="width:60%" name="txt_filter_value" value="<?php echo $v_filter; ?>" onKeyDown="txt_filter_keydown()">
			<input type="button" name="btn_filter" class="large_button" value="<?php echo _CONST_FILTER_BUTTON;?>" onClick="btn_filter_onclick(document.forms(0).hdn_filter_enduser,document.forms(0).txt_filter_value,'DISPLAY_ALL_ENDUSER')">
		</td>
	</tr>
	<tr>
		<td class="normal_label" align="left"><?php echo CONST_APPLICATION_LABEL;?></td>
		<td class="normal_label" align="left">
			<select class="normal_selectbox" name="sel_application" style="width:99%" onChange="change_text_from_selected(document.forms(0).sel_application,document.forms(0).hdn_application_code,document.forms(0).hdn_application_id);document.forms(0).submit();">
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
	<col width="5%"><col width="5%"><col width="25%"><col width="20%"><col width="30%"><col width="15%">
	<tr class="header" style="font-family:Tahoma">
		<td></td>
		<td>#</td>	
		<td><?php echo CONST_ENDUSER_NAME_LABEL; ?></td>
		<td><?php echo CONST_STAFF_POSITION_LABEL; ?></td>
		<td><?php echo CONST_UNIT_NAME_LABEL; ?></td>
		<td><?php echo _CONST_STATUS_COLUMN_HEADER; ?></td>
	</tr>
</table>	
<div style="overflow: auto; width: 100%; height:<?php echo _CONST_HEIGHT_OF_LIST?>;padding-left:0px;margin:0px">
<table class="list_table2" width="100%" cellpadding="0" cellspacing="0">
	<col width="5%"><col width="5%"><col width="25%"><col width="20%"><col width="30%"><col width="15%"><?php
	$v_count = sizeof($arr_all_user);
	if ($v_count >0){
		$i = 0;
		$v_old_unit_level_one_name = "";
		while($i < $v_count) {
			$v_item_id = $arr_all_user[$i][0];
			$v_item_name = $arr_all_user[$i][1];
			$v_item_status = $arr_all_user[$i][2];
			$v_unit_level_one_id = $arr_all_user[$i][3];
			$v_unit_level_one_name = $arr_all_user[$i][4];
			$v_unit_name = $arr_all_user[$i][5];
			$v_staff_position = $arr_all_user[$i][6];

			if ($v_unit_name == $v_unit_level_one_name){
				$v_unit_name = "";
			}
			if ($v_item_status==1){
				$v_item_status=_CONST_STATUS_COLUMN_ACTIVE_VALUE;
			}else{
				$v_item_status=_CONST_STATUS_COLUMN_INACTIVE_VALUE;
			}
			$v_img_url_onclick = "show_enduser_on_unit(this)";
			$v_chk_modul_id_onclick = "select_all_enduser_on_unit(this,document.all.chk_item_id)";
			$v_chk_enduser_id_onclick = "set_value_checked_checkbox_unit(this,document.all.chk_item_id,document.all.chk_unit_id)";
			$v_url = "enduser_onclick(" . "document.forms(0).hdn_item_id"  . "," . $v_item_id . "," . $v_unit_level_one_id . "," . "'DISPLAY_SINGLE_ENDUSER','" . $v_goto_url . "')";
			if ($v_current_style_name == "odd_row"){
				$v_current_style_name = "round_row";
			}else{
				$v_current_style_name = "odd_row";
			}
			if ($v_unit_level_one_name != $v_old_unit_level_one_name){?>	
				<tr class="midle_row">
					<td align="center">
						<input type="checkbox" name="chk_unit_id" value="<?php echo $v_unit_level_one_id; ?>" onClick="<?php echo $v_chk_modul_id_onclick;?>">
					</td>
					<td align="center">
						<img id="img_unit" src="<?php echo $_ISA_IMAGE_URL_PATH;?>open.gif" class="normal_image" unit="<?php echo $v_unit_level_one_id;?>" status="on" onClick="<?php echo $v_img_url_onclick;?>"></td>
					</td>
					<td align="left" colspan="10">
						<a name="<?php echo $v_unit_level_one_id;?>"><?php echo $v_unit_level_one_name; ?>&nbsp;</a>
					</td>
				</tr><?php 
				$v_old_unit_level_one_name = $v_unit_level_one_name;
			}?>
			<tr id="tr_enduser" value="<?php echo $v_item_id;?>" unit="<?php echo $v_unit_level_one_id;?>" class="<?php echo $v_current_style_name; ?>">
				<td>&nbsp;&nbsp;</td>
				<td align="center">
					<input type="checkbox" name="chk_item_id" value="<?php echo $v_item_id; ?>" unit_id="<?php echo $v_unit_level_one_id;?>" onClick="<?php echo $v_chk_enduser_id_onclick;?>">
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
	echo _add_empty_row($v_count,_CONST_NUMBER_OF_ROW_PER_LIST,$v_current_style_name,$v_next_style_name,5);?>
</table>
</div>
<!--Table chua cac nut Show_all va Hide_all-->
<table width="100%" cellpadding="0" cellspacing="0">
	<tr><?php 
		if ($v_count>0){?>
		<td class="small_radiobutton" colspan="10" width="50%" height="25">
			<input type="radio" id="rad_show_enduser" value="1" checked onClick="show_or_hide_all_enduser('SHOW_ALL',document.all.tr_enduser,document.all.img_unit,document.all.rad_show_enduser)" ><?php echo CONST_SHOW_ALL_ENDUSER;?>
		</td>
		<td class="small_radiobutton" colspan="10" width="50%" height="25">
			<input type="radio" id="rad_show_enduser" value="2" onClick="show_or_hide_all_enduser('HIDE_ALL',document.all.tr_enduser,document.all.img_unit,document.all.rad_show_enduser)"><?php echo CONST_HIDE_ALL_ENDUSER;?>
		</td><?php
		}else{?>
			<td>&nbsp;</td>
		<?php
		}?>
	</tr>
</table>
<!--Table chua cac button-->
<table align="center">
	<tr><td height="10"></td></tr>
	<tr><?php
		if($v_is_granted_update==true){?>
			<td background="<?php echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
				<a onClick="document.forms(0).fuseaction.value ='DISPLAY_STAFF_FOR_APPLICATION';document.forms(0).submit();" class="small_link"><?php echo _CONST_ADD_BUTTON;?></a>&nbsp;&nbsp;
			</td><?php
		}
		if($v_is_granted_delete==true){?>
			<td background="<?php echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
				<a onClick="btn_delete_onclick(document.forms(0).chk_item_id,document.forms(0).hdn_list_item_id,'DELETE_ENDUSER');" class="small_link"><?php echo _CONST_DELETE_BUTTON;?></a>&nbsp;&nbsp;
			</td><?php
		}?>
		<!--td background="<?php echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
			<a onClick="goback()" class="small_link"><?php echo _CONST_BACK_BUTTON;?></a>
		</td-->
	</tr>
</table>
</div id="hotkey">
</form>
<script language="JavaScript">
	// Chuyen den vi tri hien tai
	document.all.goto.href="#<? echo $v_current_position;?>";
	document.all.goto.click();
</script>