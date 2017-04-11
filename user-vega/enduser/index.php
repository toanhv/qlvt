<?
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
if (!isset($_REQUEST['modal_dialog_mode'])) {
	include "../dsp_header.php";
}else{
	include "../dsp_modal_dialog_header.php";
}


?>
<script src="../isa-lib/isa-js/isa_util.js" ></script>
<script src="js_enduser.js"></script><?
//echo $fuseaction;
switch ($fuseaction) {
	case "DISPLAY_SINGLE_ENDUSER";
		include "qry_all_modul_for_enduser.php";
		include "qry_all_function_for_enduser.php";
		include "qry_all_group_for_enduser.php";
		include "qry_all_function_belong_group.php";
		include "qry_single_enduser.php";
		include "dsp_single_enduser.php";
		break;
	case "DISPLAY_STAFF_FOR_APPLICATION";
		include "qry_all_staff_for_application.php";
		include "dsp_all_staff_for_application.php";
		break;
	case "INSERT_ENDUSER";
		include "act_insert_enduser.php";
	case "DISPLAY_ALL_ENDUSER";
		include "../application/qry_all_application.php";
		include "qry_all_enduser.php";
		include "dsp_all_enduser.php";
		break;
	case "UPDATE_ENDUSER";		
		include "act_update_enduser.php";
		break;
	case "DELETE_ENDUSER";				
		include "act_delete_enduser.php";
		break;
	case "DISPLAY_SINGLE_GROUP";
		include "qry_all_modul_for_group.php";
		include "qry_all_function_for_group.php";
		include "qry_all_enduser_for_group.php";
		include "qry_single_group.php";
		include "dsp_single_group.php";
		break;				
	case "UPDATE_GROUP";		
		include "act_update_group.php";
		break;
	case "DELETE_GROUP";				
		include "act_delete_group.php";
		break;				
	default:
		include "../application/qry_all_application.php";
		include "qry_all_group.php";
		include "dsp_all_group.php";		
		break;		
}
include "../disconnect.php";
if(isset($_REQUEST['modal_dialog_mode'])){
	include "../dsp_modal_dialog_footer.php";
}else{
	include "../dsp_footer.php";	
}
?>