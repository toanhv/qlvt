<!--Table chua tieu de cua form-->
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="100%" class="normal_title" ><?php echo CONST_INFOR_STAFF_TITLE;?></td>
	</tr>
</table>
<!--Table chua cac textbox, combobox de nhap du lieu-->
<table width="98%" class="form_table1" align="center">
	<col width="30%"><col width="70%">
	<tr>
		<form name="f_dsp_single_staff" action="index.php" method="post">
			<input type="hidden" name="fuseaction" value="">
			<input type="hidden" name="hdn_unit_id" value="">
			<input type="hidden" name="hdn_unit_code" value="">
		<td height="5"></td>
	</tr>
	<tr class="normal_label">
		<td ><?php echo CONST_STAFF_NAME_LABEL; ?></td>
		<td>
			<input type="text" name="txt_name" class="normal_textbox" size="36" value="" maxlength="<?php echo CONST_STAFF_NAME_MAXLENGTH;?>" message="<?php echo CONST_STAFF_NAME_MESSAGE;?>" optional="<?php echo CONST_STAFF_NAME_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)">
		</td>
	</tr>
	<tr  class="normal_label">
		<td><?php echo CONST_UNIT_LABEL; ?></td>
		<td>
			<input type="text" name="txt_unit_name" size="36" value="" onKeyDown="change_focus(document.forms(0),this)">
		</td>	
	</tr>
	
	<tr class="normal_label" style="display:none">
		<td><?php echo CONST_TEL_LOCAL_LABEL;?></td>
		<td>
			<input type="text" name="txt_tel_local" class="normal_textbox" size="10" value="" optional="<?php echo CONST_STAFF_TEL_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)">
		</td>
	</tr>
	<tr class="normal_label"  style="display:none">
		<td><?php echo CONST_TEL_OFFICE_LABEL;?>&nbsp;</td>
		<td>
			<input type="text" name="txt_tel_office" class="normal_textbox" size="10" value="" optional="<?php echo CONST_STAFF_TEL_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)">
		</td>
	</tr>
	<tr class="normal_label"  style="display:none">
		<td><?php echo CONST_TEL_MOBILE_LABEL;?></td>
		<td>
			<input type="text" name="txt_tel_mobile" class="normal_textbox" size="10" value="" optional="<?php echo CONST_STAFF_TEL_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)">
		</td>
	</tr>
	<tr class="normal_label"  style="display:none">
		<td><?php echo CONST_TEL_HOME_LABEL;?></td>
		<td>
			<input type="text" name="txt_tel_home" class="normal_textbox" size="10" value="" optional="<?php echo CONST_STAFF_TEL_OPTIONAL;?>" onKeyDown="change_focus(document.forms(0),this)">
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
</table>
<table>
	<tr>
		<td  style="font-family:Times New Roman; color:#FF0000" height="40"><?php echo CONST_COMENT;?></td>
	</tr>
</table>
<table align="center">
	<tr><td height="10"></td></tr>
		<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.jpg" width="104" height="23" align="center">
			<a id="btn_find" onClick="btn_find_onclick();" onKeyDown="change_focus(document.forms(0),this)" class="small_link"><?php echo CONST_FIND_BUTTON;?></a>&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
		<td width="10%"></td>
		<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.jpg" width="104" height="23" align="center">
			<a onClick="window.close();" class="small_link"><?php echo CONST_CLOSE_BUTTON;?></a>
		</td>
	</tr>
</table>
</form>
<script language="JavaScript">
	set_focus(document.forms(0));
	window.dialogHeight = "200pt";
	window.dialogWidth = "350pt";
	window.dialogTop = "80pt";
</script>