<?php 
include "../app_global.php";
include "../const.php";
include "../isa-lib/isa-function/isa_public_function.php";
include_once("../isa-lib/isa-function/class_xslt.php");
include "const.php";
include "app_local.php";
include "../dsp_modal_dialog_header.php";
include "../connect.php";
include "phone_function.php";
?>
<script src="js_staff.js"></script>
<script src="../isa-lib/isa-js/isa_util.js" ></script>
<?php
switch($fuseaction) {
	case "DISPLAY_ALL_STAFF_BY_UNIT";
		include "../org/qry_single_unit.php";
		include "qry_all_staff_by_unit.php";
		include "dsp_all_staff_by_unit.php";
		break;
	case "DISPLAY_PHONE_TO_MIND";
		include "default.php";
		break;
	case "DISPLAY_RESULT";
		include "../org/qry_all_unit.php";
		include "qry_all_staff_by_condition.php";
		include "dsp_all_staff_by_condition.php";
		break;	
	case "DISPLAY_SINGLE_STAFF";	
		include "dsp_single_staff.php";
		break;
	 default:
	 	include "../org/qry_all_unit.php";	 
		include "dsp_all_unit.php";
		break;
}
include "../dsp_modal_dialog_footer.php";
?>