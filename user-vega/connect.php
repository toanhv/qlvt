<?
switch($_ISA_DB_TYPE) {
	// Ket thuc ket noi toi MS SQL-SERVER
	case "SQL SEVER";
		$conn=@mssql_connect($_ISA_SERVER_NAME,$_ISA_DB_USER ,$_ISA_DB_PASSWORD) or die(_CONST_DB_CONNECT_ERROR);
		mssql_select_db($_ISA_DB_NAME);
		break;
	// Ket thuc ket noi toi Postgres
	case "POSTGRES";
		$conn = @pg_connect("host=" . $_ISA_SERVER_NAME . "user='" . $_ISA_DB_USER . "' password='" . $_ISA_DB_PASSWORD . "'dbname=" . $_ISA_DB_NAME) or die (_CONST_DB_CONNECT_ERROR);
		break;
}
?>
