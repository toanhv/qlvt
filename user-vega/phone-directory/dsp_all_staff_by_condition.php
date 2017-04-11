<table  width="100%" cellpadding="0" cellspacing="0">
	<col width="100%">
	<tr>
		<td>
			<form action="index.php" method="post">
				<input type="hidden" name="fuseaction" value="">		
				<input type="hidden" name="hdn_staff_name" value="">
				<input type="hidden" name="hdn_tel_local" value="">
				<input type="hidden" name="hdn_tel_office" value="">
				<input type="hidden" name="hdn_tel_mobile" value="">
				<input type="hidden" name="hdn_tel_home" value="">
				<input type="hidden" name="hdn_unit_name" value="">
		</td>
	</tr>
	<tr>
		<td width="100%" align="right" >
			<a href="#" style="color:Red" onClick="show_modal_onclick('phone-directory/index.php','DISPLAY_SINGLE_STAFF',document.all.hdn_staff_name,document.all.hdn_unit_name,document.all.hdn_tel_local,document.all.hdn_tel_office,document.all.hdn_tel_mobile,document.all.hdn_tel_home,document.all.fuseaction);"><?php echo "<B>"."Tra c&#7913;u"; ?></a>
		</td>			
	</tr>
</table>
<?php 
$v_count = sizeof($arr_all_staff_by_condition);
$v_current_style_name = "odd_row";
$v_next_style_name = "";
?>
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
		$v_old_unit_id = "";
		for($i = 0; $i< $v_count; $i++){
			$v_current_user_id = $arr_all_staff_by_condition[$i]['0'];
			$v_current_user_name = $arr_all_staff_by_condition[$i]['1'];
			$v_current_unit_name = $arr_all_staff_by_condition[$i]['2'];
			$v_current_possition = $arr_all_staff_by_condition[$i]['3'];
			$v_current_tel_local = $arr_all_staff_by_condition[$i]['4'];
			$v_current_tel_office = $arr_all_staff_by_condition[$i]['5'];
			$v_current_tel_mobile = $arr_all_staff_by_condition[$i]['6'];
			$v_current_tel_home = $arr_all_staff_by_condition[$i]['7'];
			$v_current_fax = $arr_all_staff_by_condition[$i]['8'];
			$v_current_email = $arr_all_staff_by_condition[$i]['9'];
			$v_current_email = str_replace('@','<br>@',$v_current_email);
			$v_current_unit_id = $arr_all_staff_by_condition[$i]['11'];
			
			if($v_current_tel_local !="" or $v_current_tel_office !=""){
				if($v_current_unit_id != $v_old_unit_id){
					$v_unit_level1_name = get_unit_level1_name($v_current_user_id,$v_current_unit_id,$arr_all_unit);
					if($v_unit_level1_name == $v_current_unit_name){
						$v_unit_level1_name = "";
					}else{
						$v_unit_level1_name = "  ( ".get_unit_level1_name($v_current_user_id,$v_current_unit_id,$arr_all_unit)." )";
					}?>
					<tr class="midle_row">
						<td align="center" colspan="10"><?php echo "<B>".$v_current_unit_name.$v_unit_level1_name;?> </td>
					</tr><?php
				}
				if($v_current_possition!=""){
					$v_current_possition = "<B>".$v_current_possition."</B>"."</br>";
					$v_current_tel_local = '<br>'.$v_current_tel_local;
					$v_current_tel_office = '<br>'.$v_current_tel_office;
					$v_current_tel_mobile = '<br>'.$v_current_tel_mobile;
					$v_current_tel_home = '<br>'.$v_current_tel_home;
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
				$v_old_unit_id = $v_current_unit_id;
			}//End if (chi lay nhung nguoi co dien thoai)
		}//end for
	}//end if
	if ($v_current_style_name == "odd_row"){
		$v_next_style_name = "round_row";
	}else{
		$v_next_style_name = "odd_row";
	}	
	echo _add_empty_row($v_count,15,$v_current_style_name,$v_next_style_name,6);
	?>
</table>
</div>