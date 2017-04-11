<HTML>
<HEAD>
	<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Control" content="public; max-age=3600">
	<META name="description" content="EFY-USER">
	<TITLE>QUAN TRI NGUOI SU DUNG</TITLE>
<?php
// Dat che do khong cho phep them ky tu / truoc ki tu ' va "
stripslashes("");

// Include cac hang so lien quan den ket noi CSDL
include "db_const.php";
include "webservice_isa_user.php";

$_ISA_APP_CODE = "EFY-USER";

// 1- Co tich hop voi LDAP
$_ISA_INTEGRATE_LDAP = 0;

// 1 - Cho phep doi password trong ung dung
$_ISA_ALLOW_CHANGE_PASSWORD = 1;

// 1- Hien thi cua so ModalDialog khi them moi/hieu chinh mot doi tuong danh muc
$_MODAL_DIALOG_MODE = 0;

// Chua duong dan (vitual directory) cua ung dung
$_ISA_WEB_SITE_PATH = "/user-vega/";

// Chua duong dan de quay lai khi NSD nhan vao nut "huy" tren man hinh dang nhap
$_ISA_HOMEPAGE_PATH = "/";


// Chua ma cua don vi su dung phan mem. Ma nay lay trong bang danh muc don vi 
$_ISA_OWNER_CODE = "1";

// Chua ten cua don vi su dung phan mem
$_ISA_OWNER_NAME = "";

// Chua cap cua don vi su dung phan mem: 1-Trung uong; 2-Tinh thanh;3-Quan huyen;4-Phuong xa
$_ISA_OWNER_LEVEL = 3;

// Xac dinh ngon ngu su dung
// $_ISA_USED_LANGUAGE = "E"; 

$_ISA_LANGUAGE = "V";

// Xac dinh ma modul hien thoi
$_ISA_CURRENT_MODUL_CODE = "";

// Xac dinh ma chuc nang hien thoi
$_ISA_CURRENT_FUNCTION_CODE = "";

// Xac dinh duong dan URL chua cac file trinh bay giao dien trang chu cua ung dung: dsp_header, dsp_footer, dsp_menu, ...
$_ISA_HOMEPAGE_FILE_URL_PATH = $_ISA_WEB_SITE_PATH;

// Xac dinh duong dan URL toi thu muc chua ISA-LIB
$_ISA_LIB_URL_PATH = $_ISA_WEB_SITE_PATH . "isa-lib/";

// Xac dinh duong dan URL toi thu muc chua anh dung chung
$_ISA_IMAGE_URL_PATH = $_ISA_WEB_SITE_PATH . "images/";

// Xac dinh duong dan URL toi file dsp_file_content.php 
$_ISA_DSP_FILE_CONTENT_URL_PATH = $_ISA_WEB_SITE_PATH . "dsp_file_content.php";

// Xac dinh duong dan URL toi file dsp_modal_dialog.php 
$_ISA_DSP_MODAL_DIALOG_URL_PATH = $_ISA_WEB_SITE_PATH . "dsp_modal_dialog.php";

// Xac dinh duong dan URL toi thu muc chua log file 
$_ISA_LOGFILE_URL_PATH = "../isa_user.log";

//Xac dinh ung dung hien thoi va luu vao bien sesion
@session_start();
_init_session_var('user_application_id','hdn_application_id',0);
_init_session_var('user_application_code','hdn_application_code',"");

//********************************************************************************
//Ten ham		:_get_current_http_and_host()
//Chuc nang	: tra lai gia tri "http://hostname" tu dia chi URL hien tai
//********************************************************************************/
function _get_current_http_and_host(){
	global $_ISA_WEB_SITE_PATH;
	global $_SERVER;
	$v_current_http_host = 'http://'.$_SERVER['HTTP_HOST'];
	//$v_pos = strpos($v_current_url, $_ISA_WEB_SITE_PATH);
	//$v_current_http_host = substr($v_current_url,0,$v_pos);
	return $v_current_http_host;
}	
// Khoi tao 1 bien session va gan gia tri tu 1 bien REQUEST
function _init_session_var($p_session_var_name, $p_request_var_name, $p_default_value){
	if (isset($_SESSION[$p_session_var_name])){
		if (isset($_REQUEST[$p_request_var_name]) And $_REQUEST[$p_request_var_name]!=""){
			if ($_SESSION[$p_session_var_name] != $_REQUEST[$p_request_var_name]){
				$_SESSION[$p_session_var_name] = $_REQUEST[$p_request_var_name];
			}	
		}
	}else{
		if (isset($_REQUEST[$p_request_var_name])){
			$_SESSION[$p_session_var_name] = $_REQUEST[$p_request_var_name];
		}else{
			$_SESSION[$p_session_var_name] = $p_default_value;
		}	
	}
}
//Ham _get_UP_from_DN : Lay U/P tu LDAP DN
function _get_staff_info_by_DN($p_dn){
	global $conn;
	$cmd = "Select TOP 1 PK_STAFF, C_NAME, C_ROLE  From T_USER_STAFF Where C_DN=" ."'" .$p_dn."'";
	$result = @mssql_query($cmd,$conn);
	$row_value = @mssql_fetch_array($result);
	$arr_value[0] = $row_value['PK_STAFF'];
	$arr_value[1] = $row_value['C_NAME'];
	$arr_value[2] = $row_value['C_ROLE'];
	$arr_value[3] = $row_value['C_ROLE'];
	return $arr_value;
}	

?>