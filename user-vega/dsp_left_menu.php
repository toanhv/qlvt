<!-- Table chua menu ben trai cua application-->
<div id="ORGNIZATION" style=".display:none">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="left_menu_table">
	<tr><td height="3" bgcolor="#0A5EB4"></td></tr><?php
	if ($_SESSION['is_isa_user_admin']==1 Or $_SESSION['is_isa_app_admin']==1){?>		
		<tr>
			<td class="level1" background="<? echo $_ISA_IMAGE_URL_PATH; ?>left_menu_button.gif">
				<a href="<? echo $_ISA_WEB_SITE_PATH?>org/index.php?fuseaction=DISPLAY_ALL_STAFF"><? echo _CONST_LEFT_MENU_ORGNIZATION_UNIT ?></a>
			</td>
		</tr>
		<tr>
			<td class="level1" background="<? echo $_ISA_IMAGE_URL_PATH; ?>left_menu_button.gif">
				<a href="<? echo $_ISA_WEB_SITE_PATH?>org/index.php?fuseaction=DISPLAY_ALL_POSITION"><? echo _CONST_LEFT_MENU_ORGNIZATION_POSITION ?></a>
			</td>
		</tr><?php
	}else{?>	
		<tr>
			<td width="100%" class="level1" background="<? echo $_ISA_IMAGE_URL_PATH; ?>left_menu_button.gif">
				<a href="<? echo $_ISA_WEB_SITE_PATH?>org/index.php?fuseaction=DISPLAY_DETAIL_USER"><? echo _CONST_LEFT_MENU_DETAIL_USER ?></a>
			</td>
		</tr><?php
	}?>	
</table>
</div>

<div id="APPLICATION" style=".display:none">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="left_menu_table">
	<tr><td height="3" bgcolor="#0A5EB4"></td></tr><?php
	if ($_SESSION['is_isa_user_admin']==1 Or $_SESSION['is_isa_app_admin']==1){?>		
		<tr>
			<td class="level1" background="<? echo $_ISA_IMAGE_URL_PATH; ?>left_menu_button.gif">
				<a href="<? echo $_ISA_WEB_SITE_PATH?>application/index.php?fuseaction=DISPLAY_ALL_FUNCTION"><? echo _CONST_LEFT_MENU_APPLICATION_APP; ?></a>
			</td>
		</tr>
		<tr>
			<td class="level1" background="<? echo $_ISA_IMAGE_URL_PATH; ?>left_menu_button.gif">
				<a href="<? echo $_ISA_WEB_SITE_PATH?>export-import/index.php"><? echo _CONST_LEFT_MENU_EXPORT_IMPORT; ?></a>
			</td>
		</tr>
		<?php
	}?>	
</table>
</div>
<div id="ENDUSER" style=".display:none">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="left_menu_table">
	<tr><td height="3" bgcolor="#0A5EB4"></td></tr><?php
	if ($_SESSION['is_isa_user_admin']==1 Or $_SESSION['is_isa_app_admin']==1){?>
		<tr>
		   <td class="level1" background="<? echo $_ISA_IMAGE_URL_PATH; ?>left_menu_button.gif">
				<a href="<? echo $_ISA_WEB_SITE_PATH?>enduser/index.php?fuseaction=DISPLAY_ALL_GROUP"><? echo _CONST_LEFT_MENU_ENDUSER_GROUP ?></a>
			</td>
		</tr>
		<tr>
		   <td class="level1" background="<? echo $_ISA_IMAGE_URL_PATH; ?>left_menu_button.gif">
				<a href="<? echo $_ISA_WEB_SITE_PATH?>enduser/index.php?fuseaction=DISPLAY_ALL_ENDUSER"><? echo _CONST_LEFT_MENU_ENDUSER_USER ?></a>
			</td>
		</tr><?php
	}?>	
</table>
</div>
<script>
	try{
		<?
		if ($_ISA_CURRENT_MODUL_CODE == "APPLICATION"){ ?>
			document.all.APPLICATION.style.display = "block";
			document.all.td_app.className="visited";<?
		}else{?>
			document.all.APPLICATION.style.display = "none";<?
		}
		if ($_ISA_CURRENT_MODUL_CODE == "ORGNIZATION"){ ?>
			document.all.ORGNIZATION.style.display = "block";
			document.all.td_org.className="visited";<?
		}else{?>
			document.all.ORGNIZATION.style.display = "none";<?
		}
		if ($_ISA_CURRENT_MODUL_CODE == "ENDUSER"){ ?>
			document.all.ENDUSER.style.display = "block";
			document.all.td_enduser.className="visited";<?
		}else{?>
			document.all.ENDUSER.style.display = "none";<?
		}?>	
	}catch(e){;}
</script>
 