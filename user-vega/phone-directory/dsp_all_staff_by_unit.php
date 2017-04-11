<?php 
//var_dump($arr_single_unit);
$v_count = sizeof($arr_all_staff_by_unit);
$v_current_style_name = "odd_row";
$v_next_style_name = "";
$v_unit_id = 0;
if (isset($_REQUEST['hdn_item_id'])){
	$v_unit_id = $_REQUEST['hdn_item_id'];
}
$v_unit_onclick_url = "";
if ($v_editing_mode==1){
	$v_unit_onclick_url = "unit_onclick(" .$arr_single_unit[0][0] . ")";
}	
?>
<table  width="100%" cellpadding="0" cellspacing="0">
	<col width="100%">
	<tr>
		<td>
			<form action="index.php" method="post" name="dsp_all_staff_by_unit">
				<input type="hidden" name="fuseaction" value="">		
				<input type="hidden" name="hdn_staff_name" value="">
				<input type="hidden" name="hdn_unit_name" value="">				
				<input type="hidden" name="hdn_tel_local" value="">
				<input type="hidden" name="hdn_tel_office" value="">
				<input type="hidden" name="hdn_tel_mobile" value="">
				<input type="hidden" name="hdn_tel_home" value="">
				<input type="hidden" name="hdn_item_id" value="<? echo $v_unit_id;?> "><?
				if ($v_editing_mode == 1){?>
					<input type="hidden" name="editing_mode" value="1"><?
				}?>
		</td>
	</tr>
	<tr>
		<td align="right" >
			<a href="#" style="color:Red" onClick="show_modal_onclick('phone-directory/index.php','DISPLAY_SINGLE_STAFF',document.all.hdn_staff_name,document.all.hdn_unit_name,document.all.hdn_tel_local,document.all.hdn_tel_office,document.all.hdn_tel_mobile,document.all.hdn_tel_home,document.all.fuseaction);"><?php echo "<B>"."Tra c&#7913;u"; ?></a>
		</td>			
	</tr>
</table>
<table class="list_table2" width="100%">
	<tr class="midle_row">
		<td align="center" onClick="<? echo $v_unit_onclick_url;?>"><?php echo "<B>".$arr_single_unit[0]['5']." ( ".$arr_single_unit[0]['3']." ) ";?> </td>
	</tr>
</table>
<table class="list_table1" width="100%" cellpadding="0" cellspacing="0">
	<col width="25%"><col width="5%"><col width="10%"><col width="10%"><col width="10%"><col width="15%">
	<tr class="header">
		<td><?php echo CONST_NAME_COL_LABEL;?></td>	
		<td><?php echo CONST_TEL_LOCAL_COL_LABEL;?></td>
		<td><?php echo CONST_TEL_OFFICE_COL_LABEL;?></td>
		<td><?php echo CONST_TEL_MOBILE_COL_LABEL;?></td>
		<td><?php echo CONST_TEL_HOME_COL_LABEL;?></td>
		<td><?php echo CONST_EMAIL_COL_LABEL;?></td>
	</tr>
</table>
<div style="overflow:scroll; height:410;">
<table class="list_table1" width="100%" cellpadding="0" cellspacing="0">
	<col width="25%"><col width="5%"><col width="10%"><col width="10%"><col width="10%"><col width="15%">
	<tr><?php
	if($v_count > 0){
		for($i = 0; $i< $v_count; $i++){
			$v_current_user_id = $arr_all_staff_by_unit[$i]['0'];
			$v_current_user_name = $arr_all_staff_by_unit[$i]['1'];
			$v_current_unit_name = $arr_all_staff_by_unit[$i]['2'];
			$v_current_possition = $arr_all_staff_by_unit[$i]['3'];
			
			$v_current_tel_local = $arr_all_staff_by_unit[$i]['4'];
			$v_current_tel_office = $arr_all_staff_by_unit[$i]['5'];
			$v_current_tel_mobile = $arr_all_staff_by_unit[$i]['6'];
			$v_current_tel_home = $arr_all_staff_by_unit[$i]['7'];
			$v_current_fax = $arr_all_staff_by_unit[$i]['8'];
			$v_current_email = $arr_all_staff_by_unit[$i]['9'];
			$v_current_email = str_replace('@','<br>@',$v_current_email);
			if($v_current_tel_local !="" or $v_current_tel_office !="" or $v_current_fax!="" or $v_current_tel_mobile !="" or $v_current_tel_home!=""){
				// Neu trong URL co tham so "editing_mode" thi khi NSD nhan chuot vao ten can bo se hien thi cua so de hieu chinh cac thong tin ca nhan
				$v_onclick_url = "";
				if ($v_editing_mode==1){
					$v_onclick_url = "staff_onclick(" .$v_current_user_id . ")";
				}
				if($v_current_possition!=""){
					$v_current_possition = "<B>".$v_current_possition."</B>"."</br>";
					$v_current_tel_local = '<br>'.$v_current_tel_local;
					$v_current_tel_office = '<br>'.$v_current_tel_office;
					$v_current_tel_mobile = '<br>'.$v_current_tel_mobile;
					$v_current_tel_home = '<br>'.$v_current_tel_home;
					//$v_current_email = '<br>'.$v_current_email;
				}else{
					$v_current_possition = "";
				}
				if ($v_current_style_name == "odd_row"){
					$v_current_style_name = "round_row";
				}else{
					$v_current_style_name = "odd_row";
				}
				$v_border = '1px';
				if ($v_current_fax != ""){
					$v_border = '0px';
				}?>	
				<tr class="<?php echo $v_current_style_name;?>" valign="top">
					<td style="BORDER-BOTTOM:<? echo $v_border;?> solid;" align="left" onClick="<? echo $v_onclick_url;?>"><?php echo $v_current_possition.$v_current_user_name;?>&nbsp;</td>
					<td style="BORDER-BOTTOM:<? echo $v_border;?> solid;" align="center" onClick="<? echo $v_onclick_url;?>"><?php echo $v_current_tel_local; ?>&nbsp;</td>
					<td style="BORDER-BOTTOM:<? echo $v_border;?> solid;" align="right" onClick="<? echo $v_onclick_url;?>">&nbsp;<?php echo $v_current_tel_office; ?>&nbsp;</td>
					<td style="BORDER-BOTTOM:<? echo $v_border;?> solid;" align="right" onClick="<? echo $v_onclick_url;?>">&nbsp;<?php echo $v_current_tel_mobile; ?></td>
					<td style="BORDER-BOTTOM:<? echo $v_border;?> solid;" align="right" onClick="<? echo $v_onclick_url;?>">&nbsp;<?php echo $v_current_tel_home; ?></td>
					<td style="BORDER-BOTTOM:<? echo $v_border;?> solid;" align="left" onClick="<? echo $v_onclick_url;?>">&nbsp;<?php echo $v_current_email; ?></td>
				</tr><?php
				if ($v_current_fax != ""){?>
					<tr class="<?php echo $v_current_style_name;?>" valign="top">
						<td align="left" onClick="<? echo $v_onclick_url;?>">FAX</td>
						<td align="center" onClick="<? echo $v_onclick_url;?>">&nbsp;</td>
						<td align="right" onClick="<? echo $v_onclick_url;?>">&nbsp;<?php echo $v_current_fax; ?>&nbsp;</td>
						<td align="right" onClick="<? echo $v_onclick_url;?>">&nbsp;</td>
						<td align="right" onClick="<? echo $v_onclick_url;?>">&nbsp;</td>
						<td align="left" onClick="<? echo $v_onclick_url;?>">&nbsp;</td>
					</tr><?php
				}
			}
		}//end for
	}//end if
	if($arr_single_unit[0]['8'] <>"" or $arr_single_unit[0]['7'] <>"" or $arr_single_unit[0]['9']<>""){
		if($arr_single_unit[0]['9'] != ""){
			$v_fax_label = "FAX - ".$arr_single_unit[0]['5'];
			$v_fax_number = $arr_single_unit[0]['9'];
		}else{
			$v_fax_label = $arr_single_unit[0]['5'];
			$v_fax_number = "";			
		}?>
		<tr class="<?php echo $v_current_style_name;?>">
			<td align="left"><?php echo $v_fax_label;?>&nbsp;</td>
			<td align="center" ><?php echo $arr_single_unit[0]['7']; ?>&nbsp;</td><!--dien thoai noi bo-->
			<td align="right" >&nbsp;<?php echo $arr_single_unit[0]['8'].$v_fax_number; ?></td><!--dien thoai cua phong-->
			<td align="center" >&nbsp;</td>
			<td align="center" >&nbsp;</td>
			<td align="center" >&nbsp;</td>
		</tr><?php	
	}
	if ($v_current_style_name == "odd_row"){
		$v_next_style_name = "round_row";
	}else{
		$v_next_style_name = "odd_row";
	}
	echo _add_empty_row($v_count,15,$v_current_style_name,$v_next_style_name,6);
	?>
</table>
</div>
</form>