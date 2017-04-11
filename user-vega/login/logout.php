<?php
// Initialize the session.
@session_start();
// Xoa thong tin dang nhap trong T_USER_LOGON
if (isset($_SESSION["staff_id"])){
	if (_is_sqlserver()) {
		//$v_ip_address = _get_remote_ip_address();
		$v_ip_address = _get_cookie('guid_cookie');
		$v_app_code = $_ISA_APP_CODE;
		$v_staff_id = $_SESSION["staff_id"];
		/*
		$cmd = @mssql_init("USER_Logout",$conn);
		@mssql_bind($cmd,"@p_ip_address",$v_ip_address,SQLVARCHAR);
		@mssql_bind($cmd,"@p_app_code",$v_app_code,SQLVARCHAR);
		@mssql_bind($cmd,"@p_staff_id",$v_staff_id,SQLINT4);
		$result = mssql_execute($cmd);
		*/
		$v_sql_string = "Exec USER_Logout ";
		$v_sql_string.= "'".$v_ip_address."'";
		$v_sql_string.= ",'".$v_app_code."'";
		$v_sql_string.= ",".$v_staff_id."";
		$result = _adodb_exec_update_delete_sql($v_sql_string);
	}
	// Unset all of the session variables.
	unset($_SESSION["staff_id"]);
	unset($_SESSION["user_id"]);
	unset($_SESSION["user_name"]);
	unset($_SESSION["is_isa_user_admin"]);
	unset($_SESSION["is_isa_app_admin"]);
	unset($_SESSION["public_modul_list"]);
	unset($_SESSION["granted_modul_list"]);
	unset($_SESSION["public_function_list"]);
	unset($_SESSION["granted_function_list"]);
}
// Finally, destroy the session.
@session_destroy();
?>
