<?php
	include "isa-lib/adodb/adodb.inc.php";
	$ado_conn = &ADONewConnection("ado_mssql");  // create a connection
	$db_name = str_replace('[','',$_ISA_DB_NAME);
	$db_name = str_replace(']','',$db_name);
	$conn_string = "Provider=SQLOLEDB; Data Source=" . $_ISA_SERVER_NAME . ";Initial Catalog='" . $db_name . "'; User ID=" . $_ISA_DB_USER . "; Password=" .$_ISA_DB_PASSWORD;
	$ado_conn->Connect($conn_string) or die(_CONST_DB_CONNECT_ERROR);	
?>
