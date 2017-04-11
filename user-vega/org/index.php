<?php
//Khai bao thong so cau hinh dung chung
include "../app_global.php";
//Khai bao cac hang dung chung
include "../const.php";
include "../connect_ado.php";
//Khai bao cac ham dung chung
include "../isa-lib/isa-function/isa_public_function.php";
//Kiem tra NSD da dang nhap chua
//Khai bao thong so cau hinh rieng
include "app_local.php";
if ($fuseaction!="DISPLAY_DETAIL_USER" && $fuseaction!="UPDATE_USER"){
	include "../test_login.php";
}else{
	$_SESSION['is_isa_user_admin']=0;
}	
//khai bao cac hang rieng
include "const.php";
$v_modal = $_REQUEST['modal_dialog_mode'];
//File chua class function xu ly xml va xslt
include_once("../isa-lib/isa-function/class_xslt.php");//Khai bao su dung cac ham dung chung 
if (!isset($_REQUEST['modal_dialog_mode'])){
	include "../dsp_header.php";
}else{
	include "../dsp_modal_dialog_header.php";
}
include "org_function.php";
?>
<script src="../isa-lib/isa-js/isa_util.js" ></script>
<script src="../isa-lib/isa-js/md5.js" ></script>
<?
if ($fuseaction!="DISPLAY_ALL_UNIT"){?>
	<script src="js_org.js" ></script><?
}?>	
<script src="../enduser/js_enduser.js" ></script>
<? //echo $fuseaction;
switch ($fuseaction){
	case "DISPLAY_ALL_LDAP_USER";
		include "../connect_ldap.php";
		include "../ldap_functions.php";
		include "qry_all_ldap_user.php";
		include "dsp_all_ldap_user.php";
		break;		
	case "ADD_STAFF_FROM_LDAP_USER";
		include "act_add_staff_from_ldap_user.php";
		break;		
	case "DISPLAY_SINGLE_UNIT";
		include "qry_all_unit.php";
		include "qry_single_unit.php";
		include "dsp_single_unit.php";
		break;
	case "UPDATE_UNIT";
		include "act_update_unit.php";
		break;
	case "DELETE_UNIT";				
		include "act_delete_unit.php";
		break;
	case "DISPLAY_SINGLE_POSITION";
		include "qry_all_position_group.php";
		include "qry_single_position.php";
		include "dsp_single_position.php";
		break;		
	case "DISPLAY_ALL_POSITION";
		include "qry_all_position.php";
		include "dsp_all_position.php";
		break;		
	case "UPDATE_POSITION";		
		include "act_update_position.php";
		break;
	case "DELETE_POSITION";				
		include "act_delete_position.php";
		break;				
	case "DISPLAY_SINGLE_STAFF";
		if ($_ISA_INTEGRATE_LDAP == 1){
			include "../connect_ldap.php";
			include "../ldap_functions.php";
			include "qry_all_ldap_user.php";
		}	
		include "qry_all_position.php";
		include "qry_single_staff.php";
		include "dsp_single_staff.php";
		break;		
	case "DISPLAY_ALL_STAFF";
		include "qry_all_staff.php";
		include "dsp_all_staff.php";
		break;		
	case "UPDATE_STAFF";
		include "act_update_staff.php";
		break;
	case "DELETE_STAFF";				
		include "act_delete_staff.php";
		break;
	case "DISPLAY_ALL_UNIT";
		include "qry_all_unit.php";
		include "dsp_all_unit.php";	
		break;		
	case "UPDATE_USER";
		if ($_ISA_INTEGRATE_LDAP == 1){
			include "../connect_ldap.php";
			include "../ldap_functions.php";
			include "act_update_ldap_password.php";
		}else{
			include "act_update_user.php";
		}	
		break;					
	case "DISPLAY_DETAIL_USER";
		if ($_ISA_INTEGRATE_LDAP == 1){
			include "../connect_ldap.php";
			include "../ldap_functions.php";
		}	
		include "qry_single_detail_user.php";
		include "dsp_single_detail_user.php";
		break;
	default:
	if ($_SESSION['is_isa_user_admin']==1){
		include "qry_all_staff.php";
		include "dsp_all_staff.php";
		break;
	}else{?>
		<script language="javascript">
			window.location="../org/index.php?fuseaction=DISPLAY_DETAIL_USER";
		</script>
	<?php
	}		
}
include "../disconnect.php";
if(isset($_REQUEST['modal_dialog_mode'])){
	include "../dsp_modal_dialog_footer.php";
}else{
	include "../dsp_footer.php";	
}
?>
 