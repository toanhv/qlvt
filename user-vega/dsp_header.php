	<META http-equiv=Page-Exit content=progid:DXImageTransform.Microsoft.Fade(duration=.5)>
	<LINK href="<? echo $_ISA_LIB_URL_PATH; ?>isa-style/isa_style.css" type=text/css rel=stylesheet>
</head>
<body bgcolor="#D7D7D7" topmargin="0">
		<table width="780" cellpadding="0" cellspacing="0" align="center">
			<tr>
				 <td height="110" width="100%" align="left" valign="bottom" background="<? echo $_ISA_IMAGE_URL_PATH; ?>banner.gif""><?php
if (isset($_SESSION['staff_id']) && $_SESSION['staff_id']!=""){
	echo _show_info_by_enduser(0);
}?></td>
			</tr>
			<tr>	
				<td width="100%" align="left" valign="top">
					<? include "dsp_top_menu.php"; ?>
				</td>	
			</tr>
		</table>	
		<table width="780" border="0" align="center" cellpadding="0" cellspacing="0" class="left_menu_table">
			<tr>
				<td width="175" align="left" valign="top">
					<? include "dsp_left_menu.php";?>
				</td>
				<td width="1%" class="body1"></td>
		    	<td valign="top" bgcolor="#FFFFFF"> 
 