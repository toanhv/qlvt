<?php
$v_user_id = 0;
if(isset($_SESSION['user_id'])){
	$v_user_id = intval($_SESSION['user_id']);
}
$v_item_status = -1;
$v_current_item_id = 0;
if (isset($_REQUEST['hdn_item_id'])){
	$v_current_item_id = intval($_REQUEST['hdn_item_id']);
}
$v_current_position = "";
if(isset($_REQUEST['hdn_current_position'])){
	$v_current_position = $_REQUEST['hdn_current_position'];
}
//echo $v_current_position;
if (_is_sqlserver()) {
	/*
	$cmd = @mssql_init("User_FunctionGetAll",$conn);
	@mssql_bind($cmd,"@p_status",$v_item_status, SQLINT4);
	$result = @mssql_execute($cmd);
	$arr_all_function = _get_row_to_array($result);
	@mssql_free_result($result);
	*/
	$v_sql_string = "Exec User_FunctionGetAll ";
	$v_sql_string.= "".$v_item_status."";
	$arr_all_function = _adodb_query_data_in_number_mode($v_sql_string);
}
?>

