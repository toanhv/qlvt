<?php
$v_filter_group = "";
if(isset($_REQUEST['hdn_filter_group'])){
	$v_filter_group = $_REQUEST['hdn_filter_group'];
}
$v_application_id = $_SESSION['user_application_id'];
$v_application_code = $_SESSION['user_application_code'];

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
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td height="5"> </td>
	</tr>
	<tr>
		 <td width="100%" class="normal_title"><?php echo CONST_GROUP_LIST_TITLE;?></td>
	</td>
	</tr>
</table>
<!-- bang chua cac tieu thuc loc-->
<table cellpadding="0" cellspacing="0" width="100%">
	<col width="15%"><col width="85%">
	<tr><td height="5"></td></tr>
	<tr>
		<form action="index.php" name="f_dsp_all_group" method="post">
		<input name="fuseaction" type="hidden" value="">
		<input name="hdn_item_id" type="hidden" value="0">
		<input name="hdn_list_item_id" type="hidden" value="0">
		<input name="hdn_application_id" type="hidden" value="<?php echo $v_application_id;?>" >
		<!--Lay lai APP_CODE de kiem tra quyen(dung lai ham webservice is_app_admin())-->
		<input name="hdn_application_code" type="hidden" value="<?php echo $v_application_code;?>" >		
		<input name="hdn_filter_group" type="hidden" value="<?php echo $v_filter_group; ?>">
		<td class="normal_label" align="left">
			<?php echo CONST_GROUP_LIST_FILTER_LABEL;?>
		</td>
		<td class="normal_label">
			<input type="text" class="normal_textbox" style="width:60%" name="txt_filter_value" value="<?php echo $v_filter_group; ?>" onKeyDown="txt_filter_keydown()">
			<input type="button" name="btn_filter" class="large_button" value="<?php echo _CONST_FILTER_BUTTON;?>" onClick="btn_filter_onclick(document.forms(0).hdn_filter_group,document.forms(0).txt_filter_value,'DISPLAY_ALL_FUNCTION')">
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
<table class="list_table2" width="100%" align="center" cellpadding="0" cellspacing="0">
	<col width="5%"><col width="75%"><col width="20%">
	<tr class="header" style="font-family:Tahoma">
		<td>#</td>
		<td><?php echo CONST_GROUP_NAME_LABEL; ?></td>
		<td><?php echo _CONST_STATUS_COLUMN_HEADER; ?></td>
	</tr>
</table>	
<div style="overflow: auto; width: 100%; height:<?php echo _CONST_HEIGHT_OF_LIST?>;padding-left:0px;margin:0px">
<table class="list_table2" width="100%" align="center" cellpadding="0" cellspacing="0">
	<col width="5%"><col width="75%"><col width="20%">
	<?php  
	if ($v_count >0){
	$i =0;
		while($i < $v_count) {
			$v_item_id = $arr_all_group[$i][0];
			$v_item_code = $arr_all_group[$i][2];
			$v_item_name = $arr_all_group[$i][3];
			$v_item_order = $arr_all_group[$i][4];
			$v_item_status = $arr_all_group[$i][5];
			if ($v_item_status==1) {
				$v_item_status=_CONST_STATUS_COLUMN_ACTIVE_VALUE;
			}else{
				$v_item_status=_CONST_STATUS_COLUMN_INACTIVE_VALUE;
			}
			$v_url = "row_onclick(" . "document.forms(0).hdn_item_id"  . "," . $v_item_id . "," . "'DISPLAY_SINGLE_GROUP','" . $v_goto_url . "')";
			if ($v_current_style_name == "odd_row"){
				$v_current_style_name = "round_row";
			}else{
				$v_current_style_name = "odd_row";
			}?>	
			<tr class="<?php echo $v_current_style_name ?>"> 
				<td class="center">
					<input type="checkbox" name="chk_item_id" value="<?php echo $v_item_id; ?>">
				</td>	
				<td class="left" onclick="<?php echo $v_url;?>">
					<?php echo $v_item_name; ?>&nbsp;
				</td>
				<td class="center" onclick="<?php echo $v_url;?>">
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
	echo _add_empty_row($v_count,_CONST_NUMBER_OF_ROW_PER_LIST,$v_current_style_name,$v_next_style_name,3);
	?>
</table>
</div>

<!--Table chua cac button-->
<table align="center">
	<tr><td height="10"></td></tr>
	<tr><?php
		if($v_is_granted_update==true){?>
			<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
				<a onClick="btn_add_onclick(document.forms(0).hdn_item_id, 'DISPLAY_SINGLE_GROUP','<?php echo $v_goto_url; ?>');" class="small_link"><?php echo _CONST_ADD_BUTTON;?></a>&nbsp;&nbsp;
			</td><?php
		}
		if($v_is_granted_delete==true){?>
			<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
				<a onClick="btn_delete_onclick(document.forms(0).chk_item_id,document.forms(0).hdn_list_item_id,'DELETE_GROUP');" class="small_link"><?php echo _CONST_DELETE_BUTTON;?></a>&nbsp;&nbsp;
			</td><?php
		}?>
		<!--td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
			<a onClick="goback()" class="small_link"><?php echo _CONST_BACK_BUTTON;?></a>
		</td-->
	</tr>
</table>
</div id="hotkey">
</form>

