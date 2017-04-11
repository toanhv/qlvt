<?php
$v_item_id = _replace_bad_char($_REQUEST['hdn_user_id']);
$v_item_name = _replace_bad_char($_REQUEST['txt_fullname']);
$v_position_group_id = intval($_REQUEST['hdn_position_group_id']);
$v_item_address = _replace_bad_char($_REQUEST['hdn_address']);
$v_item_tel_office = _replace_bad_char($_REQUEST['hdn_tel_office']);
$v_item_tel_mobile = _replace_bad_char($_REQUEST['hdn_tel_mobile']);
$v_item_tel_home = _replace_bad_char($_REQUEST['hdn_tel_home']);
$v_item_fax = _replace_bad_char($_REQUEST['hdn_fax']);
$v_item_email = _replace_bad_char($_REQUEST['hdn_email']);
$v_item_username = _replace_bad_char($_REQUEST['txt_username']);
$v_item_password = _replace_bad_char($_REQUEST['txt_password']);
$v_unit_id = intval($_REQUEST['hdn_unit_id']);
//Cap nhat thong tin nguoi su dung
//LDAP_UpdateUser($v_item_id,$v_item_username,$v_item_password,$v_item_name,$v_position_group_id,$v_item_address,$v_item_email,$v_item_tel_office,$v_item_tel_mobile,$v_item_tel_home,$v_item_fax,$v_unit_id,$_ISA_LDAP_UNIT_DN);
LDAP_UpdatePassword($v_item_id,$v_item_password);
?>
<form action="index.php" method="post" name="f_back">
	<input type="hidden" name="fuseaction" value="DISPLAY_DETAIL_USER">
</form>
<script>
	if (_MODAL_DIALOG_MODE==1){
		window.close();
	}else{	
		document.forms(0).action = "index.php";
		document.forms(0).submit();
	}
</script>

 