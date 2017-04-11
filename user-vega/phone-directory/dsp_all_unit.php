<body scroll = "yes">
<table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%'>	
	<tr id="tr_home" style="display:block">
		<td width="100%" align="center" height="15" valign="top">
			<a href="<? echo _CONST_VTVNET_URL_PATH;?>" target="_parent" class="small_link" style=".color:#993366"><? echo _CONST_TOP_MENU_GO_HOME;?></a>
		</td>
	</tr>
	<tr>
		<form action="index.php" method="post">
			<input type="hidden" name="hdn_application_id" value="<?php echo $v_application_id;?>">
		<td nowrap valign=top width="100%" colspan="10"><?php 
			$v_current_id = -1;
			$xslt=new Xslt();
			//Xu ly bo Dai THVN thay bang Thong tin chung
			$v_count = sizeof($arr_all_unit);
			$v_root_id = "";
			for ($i=0; $i<$v_count; $i++){
				if ($arr_all_unit[$i][1] == "0" or $arr_all_unit[$i][1] == ""){
					$v_root_id = $arr_all_unit[$i][0];
					$arr_all_unit[$i][0] = "0";
					$arr_all_unit[$i][1] = "";
					$arr_all_unit[$i][3] = "THÔNG TIN CHUNG";
				}
				break;
			}
			//Bo FK_UNIT cua cac don vi cap 1
			for ($i=0; $i<$v_count; $i++){
				if ($arr_all_unit[$i][1] == $v_root_id){
					$arr_all_unit[$i][1] = "";
				}
			}
			//Het doan xu ly bo Dai THVN
			$xslt->setXmlString(_built_XML_tree($arr_all_unit,$v_current_id,'false','home.jpg','home.jpg','user.bmp','false'));
			$xslt->setXsl("treeviewinfram.xsl");
			if($xslt->transform()) {
				$ret=$xslt->getOutput();
				echo $ret;
			}else{
				print("Error:".$xslt->getError());
			}?>
		</td>
   </tr>
</table>
</form>
</body>
<script language="JavaScript">
	if(_MODAL_DIALOG_MODE==1){
		document.all.tr_home.style.display = "none";
	}
	//http://vtv.net/isa-user-php/phone-directory/main.php?modal_dialog_mode&vtvnet_header
	window.dialogHeight = "300pt";
	window.dialogWidth = "250pt";
	window.dialogTop = "80pt";
</script>