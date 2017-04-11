<?php
/*
--+Neu la ISA-USER-ADMIN Thi co quyen xem va xoa them moi tat ca cac application
--+Neu la Nguoi su dung ISA-USER thi cho no xem nhung ung dung ma NSD do quan tri va su dung
--+Nhung khong co quyen them ,xoa hoac chinh xua
*/
$v_goto_url	= "index.php";
$v_list_item_id = "";
if(isset($_REQUEST['hdn_list_item_id'])){
	$v_list_item_id = $_REQUEST['hdn_list_item_id'];
}?>
<!-- Bat cac phim: F12=false; Insert=true; Delete=true, ESC=false; Enter=false -->
<div id="hotkey" onKeyDown="javascript:process_hot_key(false,true,true,false,false);">
<!--bang chua tieu de cua form-->
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td width="100%" class="normal_title"><?php echo CONST_MODUL_LIST_TITLE;?></td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%">								
	<tr>
		<form action="index.php" name="f_dsp_all_modul" method="post">
		<input name="fuseaction" type="hidden" value="">
		<input name="hdn_item_id" type="hidden" value="">
		<input name="hdn_parent_id" type="hidden" value="">
		<input name="hdn_list_item_id" type="hidden" value="<?php echo $v_list_item_id; ?>">
		<input name="hdn_list_parent_id" type="hidden" value="<?php echo $v_list_parent_id;?>">		
	</tr>
	<tr><td height="5"></td></tr>
</table>
<div style="overflow: auto; width: 100%; height:<?php echo _CONST_HEIGHT_OF_LIST; ?>;padding-left:0px;margin:0px">
<table width="100%">
 	<tr>
	   	<td nowrap valign=top width="100%" colspan="10"><?php 
			//Thuc hien viec noi mang de tao treeview
			$arr_all_list = _attach_two_array($arr_all_application,$arr_all_modul,5);
			$v_current_id = -1;
			if (isset($_REQUEST['hdn_item_id'])) {
				$v_current_id = $_REQUEST['hdn_item_id'];
			}
			$show_object = 'false';
			$xml_str = built_xml_tree($arr_all_list,$v_current_id, $show_object,'open.bmp','close.bmp','user.bmp',$show_object);
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
					print("Error:".$xslt->getError());
				}
			}?>
   		</td>
   </tr>
</table>
</div>
</form>
<script language="JavaScript"> 
var show_object;
show_object = "<? echo $show_object; ?>";
if(show_object=='true'){
	set_checkbox_checked(document.forms(0).rad_item_id,document.forms(0).chk_item_id,document.forms(0).hdn_list_item_id.value);
}
</script>
