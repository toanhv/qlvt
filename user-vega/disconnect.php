<?
switch($_ISA_DB_TYPE) {
	// Ket thuc ket noi toi MS SQL-SERVER
	case "SQL SEVER";
		$ado_conn->Close();
		break;
	// Ket thuc ket noi toi Postgres
	case "POSTGRES";
		@pg_close($conn);
		break;
}	
?>
 