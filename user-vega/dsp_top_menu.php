<!-- Table chua menu cua application-->
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="top_menu_table">
	<tr><?php 
		// Neu la quan tri ISA-USER hoac quan tri mot APP thi moi hien thi cac muc quan tri
		if ($_SESSION['is_isa_user_admin']==1 Or $_SESSION['is_isa_app_admin']==1){?>
			<td align="center" id="td_org">
				<a href="<? echo $_ISA_WEB_SITE_PATH?>org/index.php"><? echo _CONST_TOP_MENU_ORGNIZATION ?></a>
			</td>
			<td align="center" id="td_app">
				<a href="<? echo $_ISA_WEB_SITE_PATH?>application/index.php"><? echo _CONST_TOP_MENU_APPLICATION;?></a>
			</td>
			<td align="center" id="td_enduser">
				<a href="<? echo $_ISA_WEB_SITE_PATH?>enduser/index.php"><? echo _CONST_TOP_MENU_ENDUSER ?></a>
			</td><?php
		}?>	
		<td class=none_border align="right">
			<a href="<? echo $_ISA_WEB_SITE_PATH?>login/index.php"><? echo _CONST_TOP_MENU_LOGON?></a>
		</td>
	</tr>
</table>
 