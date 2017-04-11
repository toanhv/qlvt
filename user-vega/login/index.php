<?
//echo "OOOOOOOO";
//Khai bao thong so cau hinh dung chung
include "../app_global.php";
//Khai bao cac hang dung chung
include "../const.php";
include "../connect_ado.php";
//Khai bao thong so cau hinh rieng
include "app_local.php";
//khai bao cac hang rieng
include "const.php";
//Khai bao su dung cac ham dung chung 
include "../isa-lib/isa-function/isa_public_function.php";
include "../dsp_modal_dialog_header.php";

?>
<script src="../isa-lib/isa-js/isa_util.js" ></script><?php
switch ($fuseaction) {
	case "CHECK_USER";
		include "act_login.php";
		break;
	default:
		include "logout.php";
		include "dsp_login.php";
		break;		
}
include "../disconnect.php";
include "../dsp_modal_dialog_footer.php";
?>