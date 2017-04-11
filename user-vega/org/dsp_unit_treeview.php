<?
$Root_text = 'Danh m&#7909;c ph&#242;ng ban';	//' Dong chu tai goc cua treeview
$v_goto_url	="index.php";
?>
<!-- Bat cac phim: F12=false; Insert=true; Delete=true, ESC=false; Enter=false -->
<div id="hotkey" onKeyDown="javascript:process_hot_key(false,true,true,false,false);">
<!--bang chua tieu de cua form-->
<table width="100%" cellpadding="0" cellspacing="0">
	<tr class="normal_title">
		<td><? echo CONST_UNIT_LIST_TITLE; ?></td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%">								
	<tr>
		<form action="index.php" name="f_dsp_all_unit" method="post">
		<input name="hdn_item_id" type="hidden" value="0">
		<input name="hdn_list_unit_id" type="hidden" value="0">
		<input name="fuseaction" type="hidden" value="">
	</tr>
	<tr><td height="5"></td></tr>
</table>
<div style="overflow: auto; width: 100%; height:<? echo _CONST_HEIGHT_OF_LIST?>;padding-left:0px;margin:0px">
<table width="100%">
 	<tr>
	   	<td nowrap valign=top width="100%" colspan="10">
		<? 				
			$xslt=new Xslt();			
			$xslt->setXmlString(_built_XML_tree($arr_all_unit,$Root_text));	
			$xslt->setXsl("treeview1.xsl");
			if($xslt->transform()) {
			   $ret=$xslt->getOutput();
			echo $ret;
			} else {
			   print("Error:".$xslt->getError());
			}?>
   		</td>
   </tr>
</table>

</div>
<? if($_MODAL_DIALOG_MODE==0){?>
	<table align="center">
		<tr><td height="10"></td></tr>
		<tr>
			<td><?
				if ($v_allow_select){?>
					<input type="button" value="<? echo _CONST_SELECT_BUTTON; ?>" name="btn_select" class="normal_button" onClick="btn_select_onclick(document.forms(0).chk_item_id)"><?
				}
				if ($v_is_granted_update){?>
					<input type="button" value="<? echo _CONST_ADD_BUTTON; ?>" name="btn_add" class="normal_button" onClick="btn_add_onclick(document.forms(0).hdn_item_id, 'DISPLAY_SINGLE_UNIT','<? echo $v_goto_url; ?>')"><?
				}	
				if ($v_is_granted_delete){?>
					<input type="button" value="<? echo _CONST_DELETE_BUTTON; ?>" name="btn_delete" class="normal_button" onClick="btn_delete_onclick(document.forms(0).chk_item_id,document.forms(0).hdn_list_unit_id,'DELETE_UNIT')"><?
				}?>	
				<input type="button" value="<? echo _CONST_BACK_BUTTON; ?>" name="btn_back" class="normal_button" onClick="goback()">
			</td>
		</tr>
	</table><?
}?>	
</div id="hotkey">
</form>

 