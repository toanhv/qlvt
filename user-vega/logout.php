<?php
	//Logout khoi cac ung dung
	//Xoa tat ca cac bien session cua ung dung
	//Xoa thong tin NSD da dang nhap khoi T_USER_LOGON
	require_once("isa-lib/nusoap/nusoap.php");
	include "app_global.php";
	include "const.php";
	include "isa-lib/isa-function/isa_public_function.php";

	global $_ISA_APP_CODE;
	
	if (!isset($_SESSION["staff_id"])){
		@session_start();
	}	
	
	// Xoa thong tin dang nhap trong T_USER_LOGON
	if (isset($_SESSION["staff_id"])){
		if (_is_sqlserver()) {
			$v_ip_address = _get_cookie('guid_cookie');
			$v_app_code = $_ISA_APP_CODE;
			$v_staff_id = $_SESSION["staff_id"];
			_delete_enduser_logged($v_ip_address,$v_app_code,$v_staff_id);
			//_create_cookie('guid_cookie',"");
		}
	}
	// Finally, destroy the session.
	@session_destroy();
?>
<script>
	window.location = "<? echo 'login/index.php';?>";
</script>

 