<?php
$v_goto_url	= "index.php";
$v_list_item_id = "";
if(isset($_REQUEST['hdn_list_item_id'])){
	$v_list_item_id = $_REQUEST['hdn_list_item_id'];
}
$v_list_parent_id =  "";
if(isset($_REQUEST['hdn_list_parent_id'])){
	$v_list_parent_id  = $_REQUEST['hdn_list_parent_id'];
}
?>
<!-- Bat cac phim: F12=false; Insert=true; Delete=true, ESC=false; Enter=false -->
<div id="hotkey" onKeyDown="javascript:process_hot_key(false,true,true,false,false);">
<!--bang chua tieu de cua form-->
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td height="5"> </td>
	</tr>
	<tr>
		<td width="100%" class="normal_title">
			<?php echo CONST_APPLICATION_LIST_TITLE;?>
			<a id="goto" href="" style=".color:white">#</a>
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%">								
	<tr>
		<form action="index.php" name="f_dsp_all_unit" method="post">
		<input name="fuseaction" type="hidden" value="">
		<input name="fuseaction_back" type="hidden" value="">
		<input name="hdn_item_id" type="hidden" value="<?php echo $v_current_item_id;?>">
		<input name="hdn_current_position" type="hidden" value="<?php echo $v_current_position;?>">
		<input name="hdn_application_id" type="hidden" value="<?php echo $v_application_id;?>" >
		<input name="hdn_modul_id" type="hidden" value="<?php echo $v_modul_id;?>" >		
		<input name="hdn_list_item_id" type="hidden" value="<?php echo $v_list_item_id; ?>">
		<input name="hdn_list_parent_id" type="hidden" value="<?php echo $v_list_parent_id;?>">		
	</tr>
	<tr><td height="5"></td></tr>
</table>
<div style="overflow: auto; width: 100%; height:<?php echo _CONST_HEIGHT_OF_LIST; ?>;padding-left:0px;margin:0px">
<table width="100%" >
 	<tr>
	   	<td nowrap valign=top width="100%" colspan="10"><?php 
			//Thuc hien viec noi mang de tao treeview
			$arr_temp = _attach_two_array($arr_all_application,$arr_all_modul,5);
			$arr_all_list = _attach_two_array($arr_temp,$arr_all_function, 5);
			$v_current_id = -1;
			$v_show_control = 'true';
			$v_select_leaf_object_only = 'true';
			
			$xml_str = built_xml_tree($arr_all_list,$v_current_id,$v_show_control,'open.bmp','close.bmp','function.gif',$v_select_leaf_object_only);
			if (PHP_VERSION >= 5) {
				$xml = new DOMDocument;
				$xml->loadXML($xml_str);
				
				$xsl = new DOMDocument;
				$xsl->load("../isa-lib/isa-function/treeview.xsl");
				
				// Configure the transformer
				$proc = new XSLTProcessor;
				$proc->importStyleSheet($xsl); // attach the xsl rules				
				echo $proc->transformToXML($xml);
				
			}else{
				$xslt = new Xslt();
				$xslt->setXmlString($xml_str);
				$xslt->setXsl("../isa-lib/isa-function/treeview.xsl");
				if($xslt->transform()) {
					$ret=$xslt->getOutput();
					echo $ret;
				}else{
					print("Error:".$xslt->getError());
				}
			}?>
   		</td>
   </tr>
</table>
</div>
<?php
if($_MODAL_DIALOG_MODE ==0){?>
	<table align="center">
		<tr><td height="10"></td></tr>		
		<tr>
		<?php
			if($v_is_granted_update_app==true){?>
				<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
					<a onClick="btn_add_node_of_treeview('<?php echo $v_show_control;?>', 'DISPLAY_SINGLE_APPLICATION')" class="small_link"><?php echo CONST_ADD_APPLICATION_BUTTON;?></a>&nbsp;&nbsp;
				</td><?php
			}
			if($v_is_granted_update_modul==true){?>
				<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
					<a onClick="btn_add_node_of_treeview('<?php echo $v_show_control;?>', 'DISPLAY_SINGLE_MODUL')" class="small_link"><?php echo CONST_ADD_MODUL_BUTTON;?></a>&nbsp;&nbsp;
				</td><?php
			}
			if($v_is_granted_update_function==true){?>
				<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
					<a onClick="btn_add_node_of_treeview('<?php echo $v_show_control;?>', 'DISPLAY_SINGLE_FUNCTION')" class="small_link"><?php echo CONST_ADD_FUNCTION_BUTTON;?></a>&nbsp;&nbsp;
				</td><?php
			}
			if ($v_is_granted_delete==true){?>
				<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
					<a onClick="delete_nodes_of_treeview(document.forms(0).rad_item_id, document.forms(0).chk_item_id, document.forms(0).hdn_list_item_id);" class="small_link"><?php echo _CONST_DELETE_BUTTON;?></a>&nbsp;&nbsp;
				</td><?php
				}?>
			<!--td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
				<a onClick="goback()" class="small_link"><?php echo _CONST_BACK_BUTTON;?></a>
			</td-->
		</tr>
	</table><?php
}?>	
</div id="hotkey">
</form>
<script language="JavaScript"> 
var show_object;
show_object = "<?php echo $v_select_leaf_object_only;?>";
if(show_object=='true'){
	set_checkbox_checked(document.forms(0).rad_item_id,document.forms(0).chk_item_id,document.forms(0).hdn_list_item_id.value);
}
//alert('<?php echo $v_list_parent_id;?>');
keep_status_of_node('<?php echo $v_list_parent_id;?>','<?php echo $_ISA_IMAGE_URL_PATH."open.bmp";?>');

// Chuyen den vi tri hien tai
document.all.goto.href="#<? echo $v_current_position;?>";
document.all.goto.click();
</script>

