<?
$v_filter_group = "";
if(isset($_REQUEST['hdn_filter_group'])){
	$v_filter_group = _replace_bad_char(trim($_REQUEST['hdn_filter_group']));
}
$v_application_id = intval($_SESSION['user_application_id']);
$v_filter_group = "%".$v_filter_group."%";
$v_status = -1;
if (_is_sqlserver()){
	/*
	$cmd = @mssql_init("USER_GroupGetAll",$conn);
	@mssql_bind($cmd,"@p_application_id",$v_application_id,SQLINT4);
	@mssql_bind($cmd,"@p_filter",$v_filter_group,SQLVARCHAR);
	@mssql_bind($cmd,"@p_status",$v_status, SQLINT4);
	$result = @mssql_execute($cmd);
	$arr_all_group = _get_row_to_array($result);
	@mssql_free_result($result);
	*/
	$v_sql_string = "Exec USER_GroupGetAll ";
	$v_sql_string.= "".$v_application_id."";
	$v_sql_string.= ",'".$v_filter_group."'";
	$v_sql_string.= ",".$v_status."";
	$arr_all_group = _adodb_query_data_in_number_mode($v_sql_string);
}?>