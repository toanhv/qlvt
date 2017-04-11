<?php
$v_staff_name	= "%".$_REQUEST['hdn_staff_name']."%";
$v_unit_name	= "%".$_REQUEST['hdn_unit_name']."%";
$v_tel_local	= $_REQUEST['hdn_tel_local'];
$v_tel_office	= $_REQUEST['hdn_tel_office'];
$v_tel_mobile	= $_REQUEST['hdn_tel_mobile'];
$v_tel_home	= $_REQUEST['hdn_tel_home'];
if (_is_sqlserver()){
	/*
	$cmd = mssql_init("USER_GetAllStaffByCondition", $conn);
	@mssql_bind($cmd,"@p_staff_name",$v_staff_name,SQLVARCHAR);
	@mssql_bind($cmd,"@p_unit_name",$v_unit_name,SQLVARCHAR);
	@mssql_bind($cmd,"@p_tel_local",$v_tel_local,SQLVARCHAR);
	@mssql_bind($cmd,"@p_tel_office",$v_tel_office,SQLVARCHAR);
	@mssql_bind($cmd,"@p_tel_mobile",$v_tel_mobile,SQLVARCHAR);
	@mssql_bind($cmd,"@p_tel_home",$v_tel_home,SQLVARCHAR);
	$result = @mssql_execute($cmd);
	$arr_all_staff_by_condition = _get_row_to_array($result);
	@mssql_free_result($result);
	*/
	$v_sql_string = "Exec USER_GetAllStaffByCondition ";
	$v_sql_string.= "'".$v_staff_name."'";
	$v_sql_string.= ",'".$v_unit_name."'";
	$v_sql_string.= ",'".$v_tel_local."'";
	$v_sql_string.= ",'".$v_tel_office."'";
	$v_sql_string.= ",'".$v_tel_mobile."'";
	$v_sql_string.= ",'".$v_tel_home."'";
	$arr_all_staff_by_condition = _adodb_query_data_in_number_mode($v_sql_string);
}
?>