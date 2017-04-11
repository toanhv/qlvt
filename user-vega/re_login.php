<?php
//Khai bao cac hang dung chung
//Ket noi dich vu WebService cua NuSoap
include "app_global.php";
include "const.php";
include "db_const.php";
include "connect_ado.php";
include "isa-lib/isa-function/isa_public_function.php";
if(_CONST_CALL_FUNCTION_PASS_WEBSERVICE){
	require_once('isa-lib/nusoap/nusoap.php');
}	
// Initialize the session.
@session_start();
//$v_ip_address = _get_remote_ip_address();
$v_ip_address = _get_cookie('guid_cookie');
$v_app_code = $_ISA_APP_CODE;
$v_staff_id = $_SESSION["staff_id"];
// Bo danh dau da dang nhap
if (isset($_SESSION["staff_id"])){
	if (_is_sqlserver()) {
		_delete_enduser_logged($v_ip_address,$v_app_code,$v_staff_id);
	}
}
// Finally, destroy the session.
@session_destroy();
?>
<script>
	window.location = '<?php echo _CONST_ISA_USER_LOGIN_URL;?>?app_code=<?php echo $v_app_code;?>' + '&ip_address=<?php echo $v_ip_address;?>';
	//window.location = "<? echo _CONST_ISA_USER_LOGIN_URL;?>";
</script>

 