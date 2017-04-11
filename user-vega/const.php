<?php
// Dinh nghia ban quyen duoi footer
define("_CONST_OWNER_NAME"," VEGA CORPORATION ");
// Dinh nghia cac lua chon trenu men ngang 
define("_CONST_TOP_MENU_ORGNIZATION","QU&#7842;N TR&#7882; T&#7892; CH&#7912;C");
define("_CONST_TOP_MENU_APPLICATION","QU&#7842;N TR&#7882; &#7912;NG D&#7908;NG");
define("_CONST_TOP_MENU_ENDUSER","QU&#7842;N TR&#7882; NG&#431;&#7900;I D&#217;NG");
define("_CONST_TOP_MENU_DATA","Qu&#7843;n tr&#7883; d&#7919; li&#7879;u");
define("_CONST_TOP_MENU_UTILITY","C&#225;c ti&#7879;n &#237;ch");
define("_CONST_TOP_MENU_LOGON","&#272;&#258;NG NH&#7852;P");

// Dinh nghia cac lua chon cua modul "Co cau to chuc"
define("_CONST_LEFT_MENU_ORGNIZATION_UNIT","C&#417; c&#7845;u ph&#242;ng ban");
define("_CONST_LEFT_MENU_ORGNIZATION_STAFF","T&#7893; ch&#7913;c c&#225;n b&#7897;");
define("_CONST_LEFT_MENU_ORGNIZATION_POSITION","Ch&#7913;c danh c&#225;n b&#7897;");
define("_CONST_LEFT_MENU_DETAIL_USER","Th&#244;ng tin c&#225; nh&#226;n");

//Dinh nghia cac lua chon cua modul Cac ung dung
define("_CONST_LEFT_MENU_APPLICATION_APP","Danh m&#7909;c &#7913;ng d&#7909;ng");
define("_CONST_LEFT_MENU_APPLICATION_MODUL","Danh m&#7909;c modul");
define("_CONST_LEFT_MENU_APPLICATION_FUNCTION","Danh m&#7909;c ch&#7913;c n&#462;ng");
define("_CONST_LEFT_MENU_EXPORT_IMPORT","Export v&#224; Import d&#7919; li&#7879;u");

//Dinh nghia cac lua chon cua modul Nguoi su dung
define("_CONST_LEFT_MENU_ENDUSER_GROUP","Nh&#243;m ng&#432;&#7901;i s&#7917; d&#7909;ng");
define("_CONST_LEFT_MENU_ENDUSER_USER","Ng&#432;&#7901;i SD ch&#432;&#417;ng tr&#236;nh");

// Dinh nghia cac button dung chung
define("_CONST_SELECT_BUTTON","Ch&#7885;n");
define("_CONST_ADD_BUTTON","Th&#234;m");
define("_CONST_SAVE_BUTTON","C&#7853;p nh&#7853;t");
define("_CONST_DELETE_BUTTON","Xo&#225;");
define("_CONST_BACK_BUTTON","Quay l&#7841;i");
define("_CONST_CLOSE_BUTTON","&#272;&#243;ng");
define("_CONST_EXIT_BUTTON","Tho&#225;t");
define("_CONST_FILTER_BUTTON","L&#7885;c");
define("_CONST_LOGON_BUTTON","&#272;&#259;ng nh&#7853;p");
define("_CONST_CHANGE_PASSWORD_BUTTON","X&#225;c nh&#7853;n");
define("_CONST_CANCEL_BUTTON","H&#7911;y b&#7887;");

define("_CONST_CALL_FUNCTION_PASS_WEBSERVICE",1);

// Dinh nghia so dong tren mot danh sach
define("_CONST_NUMBER_OF_ROW_PER_LIST",10);
// Dinh nghia chieu cao cua IFRAME chua danh sach 
define("_CONST_HEIGHT_OF_LIST",250);

// Dinh nghia cac thong bao loi dung chung
define("_CONST_DB_CONNECT_ERROR","Kh&#244;ng th&#7875; k&#7871;t n&#7889;i v&#224;o CSDL");

// Dinh nghia tieu de cot TINH TRANG (trong man hinh hien thi cac danh muc)
define("_CONST_STATUS_COLUMN_HEADER","T&#236;nh tr&#7841;ng");
// Dinh nghia dong chu hien thi trong cot TINH TRANH neu status=active (1)
define("_CONST_STATUS_COLUMN_ACTIVE_VALUE","Ho&#7841;t &#273;&#7897;ng");
// Dinh nghia dong chu hien thi trong cot TINH TRANH neu status=inactive (0)
define("_CONST_STATUS_COLUMN_INACTIVE_VALUE","&nbsp;");

// Dinh nghia dong chu "Ghi va them moi"
define("_CONST_SAVE_AND_ADD_NEW_LABEL","Ghi v&#224; th&#234;m m&#7899;i");

// Dinh nghia duong dan toi file hien thi man hinh login cua ISA-USER
define("_CONST_ISA_USER_LOGIN_URL",_get_current_http_and_host(). $_ISA_WEB_SITE_PATH . "login/index.php");
// Dinh nghia duong dan toi file chua cac WEBSERVICE cua ISA-USER
define("_CONST_ISA_USER_WEBSERVICE_URL",_get_current_http_and_host(). $_ISA_WEB_SITE_PATH . "login/webservice.php");
// Dinh nghia duong dan quay ve trang chu
define("_CONST_VTVNET_URL_PATH",$_ISA_WEB_SITE_PATH);
define("_CONST_TOP_MENU_GO_HOME","Trang ch&#7911;");
// Dinh nghia timeout cua ISA-USER (tinh bang giay)
define("_CONST_ISA_USER_TIME_OUT",1800);
// Dinh nghia che do ghi ra file log (>0 la co ghi)
define("_CONST_WRITE_LOG",1);

// Dinh nghia chuoi ki tu dung de phan cach cac phan tu cua mot danh sach (vi du: voi danh sach "1,2,3,4" thi ki tu "," la phan tu phan cach)
define("_CONST_LIST_DELIMITOR","!&~$*");
// Dinh nghia chuoi ki tu dung de phan cach cac phan tu cua mot danh sach trong mot danh sach khac
define("_CONST_SUB_LIST_DELIMITOR","!~~!");
// Dinh nghia ky tu phan cach pha THAP PHAN
define("_CONST_DECIMAL_DELIMITOR",",");

define("_CONST_USER_LOGIN_LABEL", "NSD");
?>
<script>
	_MODAL_DIALOG_MODE = "<? echo $_MODAL_DIALOG_MODE; ?>";
	_DSP_MODAL_DIALOG_URL_PATH = "<? echo $_ISA_DSP_MODAL_DIALOG_URL_PATH; ?>";
	_DSP_FILE_CONTENT_URL_PATH = "<? echo $_ISA_DSP_FILE_CONTENT_URL_PATH; ?>";
	_LIST_DELIMITOR = "<? echo _CONST_LIST_DELIMITOR; ?>";
	_SUB_LIST_DELIMITOR = "<? echo _CONST_SUB_LIST_DELIMITOR; ?>";
	_DECIMAL_DELIMITOR = "<? echo _CONST_DECIMAL_DELIMITOR; ?>";
</script>

