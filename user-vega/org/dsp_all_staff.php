<?php
//echo "ddDD";
$root_text = CONST_STAFF_LIST_TITLE;
$v_goto_url	= "index.php";
// ID cha cua node duoc NSD nhan chuot vao
$v_parent_item_id = 0;
if (isset($_REQUEST['hdn_parent_item_id'])){
	$v_parent_item_id = $_REQUEST['hdn_parent_item_id'];
}
// ID cua node se khong duoc hien thi trong cay (chi co tac dung trong che do modal dialog). Chu y: bien hdn_item_id duoc truyen qua URL
if ($_MODAL_DIALOG_MODE==1){
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
	<tr>
		<td height="5px">			
		</td>
	</tr>
	<tr>
		<td width="100%" class="normal_title">
			<?php echo CONST_STAFF_LIST_TITLE;?>
			<a id="goto" href="" style=".color:white">#</a>
		</td>
	</tr>	
</table>
<table cellpadding="0" cellspacing="0" width="100%">								
	<tr><td>
		<form action="index.php" name="f_dsp_all_unit" method="post">
		<input name="fuseaction" type="hidden" value="">
		<input name="fuseaction_back" type="hidden" value="DISPLAY_ALL_STAFF">
		<input name="hdn_item_id" type="hidden" value="<?php echo $v_current_item_id;?>">
		<input name="hdn_current_position" type="hidden" value="<?php echo $v_current_position ?>" >
		<input name="hdn_parent_item_id" type="hidden" value="<?php echo $v_parent_item_id;?>">
		<input name="hdn_list_item_id" type="hidden" value="">
		<input name="hdn_ldap_user_list" type="hidden" value="">
		<input name="hdn_unit_id" type="hidden" value="">
		<input name="hdn_type_obj" type="hidden" value=""><?php
		if(isset($_REQUEST['modal_dialog_mode'])){?>
			<input name="modal_dialog_mode" type="hidden" value="<?php echo $_MODAL_DIALOG_MODE;?>">
		<?php }?>
	</td></tr>	
</table>
<div style="overflow: auto; width: 100%; height:<?php echo _CONST_HEIGHT_OF_LIST; ?>;padding-left:0px;margin:0px">
<table width="100%" class="form_table1">
 	<tr>
	   	<td nowrap valign=top width="100%" colspan="10"><?php 
			error_reporting(E_ALL);
			//$xml_str = _built_XML_tree($arr_all_staff,$v_exception_id, $show_object,'open.bmp','close.bmp','user.bmp',$v_select_parent);
			$xml_str = _built_XML_tree_by_order($arr_all_staff,$v_exception_id, $show_object,'open.bmp','close.bmp','user.bmp',$v_select_parent);
			//_write_file('xml_cut.txt',$xml_str);
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
		if($_MODAL_DIALOG_MODE ==0){
			if($v_is_granted_update==true){?>
				<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
					<a onClick="btn_add_node_of_treeview('<?php echo $show_object;?>', 'DISPLAY_SINGLE_UNIT');" class="small_link"><?php echo CONST_ADD_UNIT_BUTTON;?></a>&nbsp;&nbsp;
				</td><?php
			}
			if($v_is_granted_update==true){?>
				<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
					<a onClick="btn_add_node_of_treeview('<?php echo $show_object;?>', 'DISPLAY_SINGLE_STAFF');" class="small_link"><?php echo CONST_ADD_STAFF_BUTTON;?></a>&nbsp;&nbsp;
				</td><?php
			}
			if($_ISA_INTEGRATE_LDAP==1 && $v_is_granted_update==true){?>
				<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
					<a onClick="btn_select_staff_from_LDAP();" class="small_link">L&#7845;y t&#7915; LDAP</a>&nbsp;&nbsp;
				</td><?php
			}
			if ($v_is_granted_delete==true){?>
				<!--//Neu Radio duoc chon thi xoa Unit-->
				<td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="26" align="center">
					<a onClick="delete_node_of_tree(document.forms[0].rad_item_id, document.forms[0].chk_item_id, document.forms[0].hdn_list_item_id);" class="small_link"><?php echo _CONST_DELETE_BUTTON;?></a>&nbsp;&nbsp;
				</td><?php
			}
		}?>
		<!--td background="<? echo $_ISA_IMAGE_URL_PATH; ?>button_bg.gif" width="104" height="27" align="center">
			<a onClick="goback()" class="small_link"><?php echo _CONST_BACK_BUTTON;?></a>
		</td-->
	</tr>
</table>
</div id="hotkey">
</form>
<script language="JavaScript"> 
	if ("<? echo $v_current_item_id;?>"=='0')
		set_root_node_to_open("<?php echo $_ISA_IMAGE_URL_PATH.'open.bmp';?>");
	else
		set_node_to_open("<? echo $v_parent_item_id;?>","<? echo $v_current_item_id;?>","<?php echo $_ISA_IMAGE_URL_PATH.'open.bmp';?>");

	select_parent_radio(document.forms(0).rad_item_id,document.forms[0].chk_item_id,"<? echo $v_current_item_id;?>");
	// Chuyen den vi tri hien tai
	document.all.goto.href="#<? echo $v_current_position;?>";
	document.all.goto.click();
</script>
 