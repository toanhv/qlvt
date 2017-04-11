<?php
$v_item_filter = "";
if(isset($_REQUEST['hdn_filter'])){
	$v_item_filter = $_REQUEST['hdn_filter'];
}
$v_count = sizeof($arr_all_position);
$v_current_style_name = "odd_row";
$v_next_style_name = "";
$v_goto_url = "org/index.php"
?>
<!--Bang chua tieu de-->
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td height="5"> </td>
	</tr>
	<tr>
		 <td width="100%" class="normal_title">
		 	<?php echo CONST_POSITION_LIST_TITLE;?>
			<a id="goto" href="" style=".color:white">#</a>
		</td>
	</tr>	
</table>
<!-- bang chua cac tieu thuc loc-->
<table cellpadding="2" cellspacing="0" width="100%">								
	<tr bgcolor="#FFFFFF">
		<form action="index.php" name="f_dsp_all_position" method="post">
		<input Type="hidden" Name="fuseaction" value="">
		<input Type="hidden" Name="hdn_filter" value="<?php echo $v_item_filter; ?>">
		<input Type="hidden" Name="hdn_item_id" value="<?php echo $v_current_item_id;?>">
		<input Type="hidden" Name="hdn_list_item_id" value="">
		<td class="normal_label" align="right"><?php echo CONST_POSITION_LIST_FILTER_LABEL;?>	
			<input Type="text" Name="txt_filter_value" class="small_textbox" size="15" value="<?php echo $v_item_filter; ?>" onKeyDown="txt_filter_keydown()">
			<input type="button" name="btn_filter" class="large_button" value="<?php echo _CONST_FILTER_BUTTON;?>" onClick="btn_filter_onclick(document.forms[0].hdn_filter,document.forms[0].txt_filter_value,'DISPLAY_ALL_POSITION')">
		</td>	
	</tr>	
</table>
<table class="list_table2" width="100%" cellpadding="0" cellspacing="0">
	<col width="5%"><col width="18%"><col width="50%"><col width="10%"><col width="20%">
	<tr class="header" style="font-family:Tahoma">
		<td>#</td>
		<td align="center"><?php echo CONST_POSITION_CODE_LABEL; ?></td>
		<td align="center"><?php echo CONST_POSITION_NAME_LABEL; ?></td>
		<td align="center"><?php echo CONST_POSITION_ORDER_LABEL; ?></td>
		<td align="center"><?php echo _CONST_STATUS_COLUMN_HEADER; ?></td>
	</tr>
</table>
<div style="overflow: auto; width: 100%; height:<?php echo _CONST_HEIGHT_OF_LIST; ?>;padding-left:0px;margin:0px">
<table class="list_table2" width="100%" cellpadding="0" cellspacing="0">
	<col width="5%"><col width="20%"><col width="50%"><col width="10%"><col width="15%"><?php
	if ($v_count >0){
		$i=0;
		while($i < $v_count) {
			$v_item_id = $arr_all_position[$i][0];
			$v_item_name = $arr_all_position[$i][1];
			$v_item_code = $arr_all_position[$i][2];
			$v_item_order = $arr_all_position[$i][3];
			$v_item_status = $arr_all_position[$i][4];
			if ($v_item_status==1){
				$v_item_status=_CONST_STATUS_COLUMN_ACTIVE_VALUE;
			}else{
				$v_item_status=_CONST_STATUS_COLUMN_INACTIVE_VALUE;
			}			
			$v_url = "row_onclick(" . "document.forms[0].hdn_item_id"."," . $arr_all_position[$i][0] . "," . "'DISPLAY_SINGLE_POSITION','" . $v_goto_url . "')";
			if ($v_current_style_name == "odd_row"){
				$v_current_style_name = "round_row";
			}else{
				$v_current_style_name = "odd_row";
			}?>	
			<tr class="<?php echo $v_current_style_name ?>"> 
				<td align="center">
					<input Type="checkbox" Name="chk_item_id" value="<?php echo $v_item_id; ?>">
				</td>	
				<td align="left" onclick="<?php echo $v_url;?>">
					<a name="<?php echo $v_item_id;?>"><?php echo $v_item_code;?>&nbsp;</a>
				</td>
				<td align="left" onclick="<?php echo $v_url;?>">
					<?php echo $v_item_name; ?>&nbsp;
				</td>
				<td align="center" onclick="<?php  echo $v_url;?>">
					<?php echo $v_item_order; ?>&nbsp;
				</td>
				<td align="center" onclick="<?php  echo $v_url;?>">
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
	echo _add_empty_row($v_count,_CONST_NUMBER_OF_ROW_PER_LIST,$v_current_style_name,$v_next_style_name,5);
	?>
</table>
</div>
<!--Table chua cac button-->
<table align="center">
	<tr><td height="10"></td></tr>
	<tr><?php
		if($v_is_granted_update==true){?>
			<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
				<a onClick="btn_add_onclick(document.forms[0].hdn_item_id, 'DISPLAY_SINGLE_POSITION','<?php echo $v_goto_url; ?>');" class="small_link"><?php echo _CONST_ADD_BUTTON;?></a>&nbsp;&nbsp;
			</td><?php
		}
		if($v_is_granted_delete==true){?>
			<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
				<a onClick="btn_delete_onclick(document.forms[0].chk_item_id,document.forms[0].hdn_list_item_id,'DELETE_POSITION');" class="small_link"><?php echo _CONST_DELETE_BUTTON;?></a>&nbsp;&nbsp;
			</td><?php
		}?>
		<!--td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
			<a onClick="goback()" class="small_link"><?php echo _CONST_BACK_BUTTON;?></a>
		</td-->
	</tr>
</table>
</div id="hotkey">
</form>
<script language="JavaScript">
	set_focus(document.forms[0]);
	//alert('<? echo $v_current_item_id;?>');
	document.all.goto.href="#<? echo $v_current_item_id;?>";
	document.all.goto.click();
</script>
 