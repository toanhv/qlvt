<?php
$v_staff_id = $_SESSION['staff_id'];
if (isset($_REQUEST['hdn_item_id'])){
	$v_staff_id = intval($_REQUEST['hdn_item_id']);
}
if ($_ISA_INTEGRATE_LDAP == 1 && (substr($v_staff_id,0,3)== $_ISA_LDAP_USERNAME_ATTRIBUTE."=")){
	$arr_single_user_detail = LDAP_GetSingleUser($v_staff_id);
	//echo $v_staff_id;
	//var_dump($arr_single_user_detail);
}else{	
	if ($v_staff_id !="0"){
		if(_is_sqlserver()){
			$v_sql_string = "Exec USER_UserGetDetail ";
			$v_sql_string.= "".$v_staff_id."";
			$arr_single_user_detail = _adodb_query_data_in_number_mode($v_sql_string);
		}
	}
}
//echo $v_staff_id;
//exit;

?>
 