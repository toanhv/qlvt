<?php 
// Lay cac thong so cau hinh chung
include "../app_global.php";
// Lay cac hang so dung chung
include "../const.php";

include "../connect_ado.php";
// Ham thuc hien lay ten file va hien thi noi dung
include "../isa-lib/isa-function/isa_public_function.php";
require_once("../isa-lib/nusoap/nusoap.php");
include "../isa-lib/isa-function/prax.php";
// Kiem tra NSD da login hay qua timeout chua
include "../test_login.php";
// Lay cac thong so cau hinh rieng cho 
//chuc nang nay
include "app_local.php";
include "const.php";
include "export_import_function.php";

// Lay cac hang so cua chuc nang nay
if (!isset($_REQUEST['modal_dialog_mode'])) {
	include "../dsp_header.php";
}else{
	include "../dsp_modal_dialog_header.php";
}

?>
<script src="../isa-lib/isa-js/isa_util.js" ></script>
<script src="js_export_import.js" ></script>
<?php
switch($fuseaction) {	
    case "DISPLAY_SINGLE_EXPORT_IMPORT";
        include "../application/qry_all_application.php";
		include "dsp_single_export.php"; 
		break;
    case "IMPORT_DATA";
		include "act_update_import.php";
		break;
	default:
		include "../application/qry_all_application.php";
		include "dsp_single_export.php";
	    break;
}
include "../disconnect.php";
if (!isset($_REQUEST['modal_dialog_mode'])) {
	include "../dsp_footer.php";
}else{
	include "../dsp_modal_dialog_footer.php";
}?>