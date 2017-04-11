<?php
/*
--+Neu la ISA-USER-ADMIN Thi co quyen xem va xoa them moi tat ca cac application
--+Neu la Nguoi su dung ISA-USER thi cho no xem nhung ung dung ma NSD do quan tri va su dung
--+Nhung khong co quyen them ,xoa hoac chinh xua
*/
$v_staff_id = intval($_SESSION['staff_id']);
$v_item_status = -1;
if(_is_sqlserver()){
	/*
	$cmd = mssql_init("USER_ApplicationGetAll",$conn);
	@mssql_bind($cmd,"@p_status",$v_item_status, SQLINT4);
	@mssql_bind($cmd,"@p_user_id",$_SESSION['staff_id'], SQLINT4);
	$result = mssql_execute($cmd);
	$arr_all_application = _get_row_to_array($result);
	@mssql_free_result($result);
	*/
	$v_sql_string = "Exec USER_ApplicationGetAll ";
	$v_sql_string.= "".$v_item_status."";
	$v_sql_string.= ",".$v_staff_id."";
	$arr_all_application = _adodb_query_data_in_number_mode($v_sql_string);
}
?>

