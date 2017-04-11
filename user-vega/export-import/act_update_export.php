<?php
header("Pragma: no-cache");
header("Content-Type: application/download; name=\"application.xml\"");

$v_application_id = 0;
if(isset($_REQUEST['hdn_application_id'])){
	$v_application_id = intval($_REQUEST['hdn_application_id']);
}

require_once('../isa-lib/nusoap/nusoap.php');
include "../isa-lib/isa-function/isa_public_function.php";
include "../db_const.php";
include "../isa-lib/adodb/adodb.inc.php";
include "../isa-lib/isa-function/prax.php";
include "../connect_ado.php";
include "export_import_function.php";

echo user_export_application($v_application_id);

header("Content-disposition: attachment; filename=application.xml");
?>