<?php
//Khai bao thong so cau hinh dung chung
include "../app_global.php";
//Khai bao cac hang dung chung
include "../const.php";
include "../connect_ado.php";
//Khai bao su dung cac ham dung chung
include "../isa-lib/isa-function/isa_public_function.php";
// Khai bao su dung cac ham dung chung cua ISA-USER
include "../user_function.php";
//Kiem tra dang nhap
include "../test_login.php";
//Khai bao thong so cau hinh rieng
include "app_local.php";
//khai bao cac hang rieng
include "const.php";
include_once("../isa-lib/isa-function/class_xslt.php");
if (!isset($_REQUEST['modal_dialog_mode'])) {
	include "../dsp_header.php";
}else{
	include "../dsp_modal_dialog_header.php";
}

?>
<script src="../isa-lib/isa-js/isa_util.js" ></script>
<script src="js_application.js" ></script>
<?php
switch ($fuseaction) {
	case "DISPLAY_SINGLE_APPLICATION";
		include "qry_all_admin_for_application.php";		
		include "qry_single_application.php";
		include "dsp_single_application.php";
		break;
	case "DISPLAY_ALL_APPLICATION";
		include "qry_all_application.php";
		include "dsp_all_application.php";
		break;	
	case "UPDATE_APPLICATION";
		include "act_update_application.php";
		break;
	case "DELETE_APPLICATION";				
		include "act_delete_application.php";
		break;
	case "DISPLAY_SINGLE_FUNCTION";
		//Phai dat qry_single_function len dau de lay app_id cho qry_all_modul_by_app
		include "qry_single_function.php";
		include "qry_all_application.php";
		include "qry_all_modul_by_application.php";
		include "dsp_single_function.php";
		break;		
	case "UPDATE_FUNCTION";		
		include "act_update_function.php";
		break;
	case "DELETE_FUNCTION";				
		include "act_delete_function.php";
		break;				
	case "DISPLAY_SINGLE_MODUL";
		//include "qry_single_application.php";
		include "qry_all_application.php";
		include "qry_single_modul.php";
		include "dsp_single_modul.php";
		break;
	case "DISPLAY_ALL_MODUL";
		include "qry_all_application.php";
		include "qry_all_modul_by_application.php";
		include "dsp_all_modul.php";
		break;	
	case "UPDATE_MODUL";
		include "act_update_modul.php";
		break;
	case "DELETE_MODUL";				
		include "act_delete_modul.php";
		break;				
	default:
		include "qry_all_application.php";
		include "qry_all_modul.php";
		include "qry_all_function.php";
		include "dsp_all_function.php";		
		break;
}
include "../disconnect.php";
if(isset($_REQUEST['modal_dialog_mode'])){
	include "../dsp_modal_dialog_footer.php";
}else{
	include "../dsp_footer.php";	
}
?>
