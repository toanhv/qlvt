<?php
$v_goto_url	="index.php";
// ID cha cua node duoc NSD nhan chuot vao
$v_parent_item_id = 0;
if (isset($_REQUEST['hdn_parent_item_id'])){
	$v_parent_item_id = $_REQUEST['hdn_parent_item_id'];
}
// ID cua node se khong duoc hien thi trong cay (chi co tac dung trong che do modal dialog). Chu y: bien hdn_item_id duoc truyen qua URL
if ($_MODAL_DIALOG_MODE=="1"){
	$v_exception_id = -1;
	if (isset($_REQUEST['hdn_item_id'])){
		$v_exception_id = $_REQUEST['hdn_item_id'];
	}
	// Bien xac dinh xem co duoc chon UNIT CHA tren cua so modaldialog hay khong
	$v_select_parent = "true";
	if ($v_exception_id == -1){ // Neu =-1 thi khong cho chon UNIT cha
		$v_select_parent = "false";
	}
}else{	
	$v_exception_id = 0;
	$v_select_parent = "true";
}

$show_object = 'true';

?>
<!-- Bat cac phim: F12=false; Insert=true; Delete=true, ESC=false; Enter=false -->
<div id="hotkey" onKeyDown="javascript:process_hot_key(false,true,true,false,false);">
<!--bang chua tieu de cua form-->
<table width="100%" cellpadding="0" cellspacing="0">
	<tr class="normal_title">
		<td width="100%" class="normal_title"><?php echo CONST_UNIT_LIST_TITLE;?></td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%">								
	<tr>
		<form action="index.php" name="f_dsp_all_unit" method="post">
		<input name="hdn_item_id" type="hidden" value="-1">
		<input name="hdn_current_position" type="hidden" value="<?php echo $_MODAL_DIALOG_MODE;?>">
		<input name="hdn_list_unit_id" type="hidden" value="0">
		<input name="hdn_parent_item_id" type="hidden" value="<?php echo $v_parent_item_id;?>">
		<input name="hdn_list_parent_id" type="hidden" value="<?php echo $v_list_parent_id;?>">		
		<input name="fuseaction" type="hidden" value=""><?
		if(isset($_REQUEST['modal_dialog_mode'])){?>
			<input name="modal_dialog_mode" type="hidden" value="<?php echo $_MODAL_DIALOG_MODE;?>">
		<?php }?>
	</tr>
	<tr><td height="5"></td></tr>
</table>
<div style="overflow: auto; width: 100%; height:<?php echo _CONST_HEIGHT_OF_LIST?>;padding-left:0px;margin:0px">
<table width="100%">
 	<tr>
	   	<td nowrap valign=top width="100%" colspan="10"><?php 
			$xml_str = _built_XML_tree_by_order($arr_all_unit,$v_exception_id, $show_object,'open.bmp','close.bmp','user.bmp',$v_select_parent);
			if (PHP_VERSION >= 5) {								
				$xml = new DOMDocument;
				$xml->loadXML($xml_str);
				
				$xsl = new DOMDocument;
				$xsl->load("../isa-lib/isa-function/treeview.xsl");
				
				// Configure the transformer
				$proc = new XSLTProcessor;
				$proc->importStyleSheet($xsl); // attach the xsl rules				
				echo $proc->transformToXML($xml);
									
			} else {
				$xslt = new Xslt();
				$xslt->setXmlString($xml_str);
				$xslt->setXsl("../isa-lib/isa-function/treeview.xsl");
				if($xslt->transform()) {
					$ret=$xslt->getOutput();
					echo $ret;
				}else{
					print("Error: ".$xslt->getError());
				}			
			}
			?>
   		</td>
   </tr>
</table>
</div>
<table align="center">
	<tr><td height="10"></td></tr>
	<tr><?php
		if($_MODAL_DIALOG_MODE==0){
			if($v_is_granted_update==true){?>
				<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.jpg" width="104" height="23" align="center">
					<a onClick="btn_add_onclick(document.forms(0).hdn_item_id, 'DISPLAY_SINGLE_UNIT','<?php echo $v_goto_url; ?>');" class="small_link"><?php echo _CONST_ADD_BUTTON;?></a>&nbsp;&nbsp;
				</td><?php
			}
		}?>
		<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.jpg" width="104" height="23" align="center">
			<a onClick="goback()" class="small_link"><?php echo _CONST_BACK_BUTTON;?></a>
		</td>
	</tr>
</table>
</div id="hotkey">
</form>
<script>
	//set_root_node_to_open("<?php echo $_ISA_IMAGE_URL_PATH.'open.bmp';?>");
</script>

 