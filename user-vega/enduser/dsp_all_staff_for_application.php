<?
// Luu dieu kien loc hien thoi
if(isset($_REQUEST['hdn_enduser_filter'])){
	$v_filter = $_REQUEST['hdn_enduser_filter'];
}else{	
	$v_filter = "";
}
if(isset($_REQUEST['hdn_application_id'])){
	$v_application_id = $_REQUEST['hdn_application_id'];
}else{
	$v_application_id= 0;	
}
$v_current_style_name = "odd_row";
$v_next_style_name = "";
$v_count = sizeof($arr_all_staff);
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
		 <td width="100%" class="normal_title"><?php echo CONST_ENDUSER_LABEL;?></td>
	</tr>
</table>
<table width="100%">
	<col width="20%"><col width="80%">
	<tr>
		<form action="index.php" method="post" Name="f_name">
		<input type="hidden" name="fuseaction" value="INSERT_ENDUSER" >
		<input type="hidden" name="hdn_item_id" value="<? echo $v_item_id ?>" >
		<input type="hidden" name="hdn_enduser_filter" value="<? echo $v_filter ?>" >
		<input Type="hidden" name="hdn_enduser_status" value="<? echo $v_enduser_status ?>" >
		<input type="hidden" name="hdn_application_id" value="<? echo $v_application_id; ?>">
		<input type="hidden" name="hdn_staff_id_list" value="" >
	</tr>
</table>
<table class="list_table2" width="100%" cellpadding="0" cellspacing="0" align="center">
	<col width="5%"><col width="5%"><col width="35%"><col width="40%"><col width="15%">
	<tr class="header" style="font-family:Tahoma">
		<td></td>
		<td>#</td>	
		<td><?php echo CONST_ENDUSER_NAME_LABEL; ?></td>
		<td><?php echo CONST_UNIT_NAME_LABEL; ?></td>
		<td><?php echo _CONST_STATUS_COLUMN_HEADER; ?></td>
	</tr>
</table>	
<div style="overflow: auto; width: 100%; height:<? echo _CONST_HEIGHT_OF_LIST?>;padding-left:0px;margin:0px">
<table class="list_table2" cellpadding="0" cellspacing="0" width="100%">
	<col width="5%"><col width="5%"><col width="35%"><col width="40%"><col width="15%"><?php
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
			$v_url = "row_onclick(" . "document.forms(0).hdn_item_id"  . "," . $arr_all_user[$i][0] . "," . "'DISPLAY_SINGLE_ENDUSER','" . $v_goto_url . "')";
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
						<?php echo $v_unit_level_one_name; ?>&nbsp;
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
<!--bang chua cac button-->
<table align="center">
	<tr><td height="10"></td></tr>
	<tr><?php
		if ($v_is_granted_update==true){
			$v_url_onclick="save_hidden_list_item_id(document.forms(0).hdn_staff_id_list,document.forms(0).chk_item_id);";
			$v_url_onclick=$v_url_onclick."btn_save_onclick('INSERT_ENDUSER')";?>
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
