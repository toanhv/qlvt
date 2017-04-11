<?
// Modul hien thoi la quan ly danh muc
$_ISA_CURRENT_MODUL_CODE = "APPLICATION";
if(isset($_REQUEST['modal_dialog_mode'])) {
	$_MODAL_DIALOG_MODE = $_REQUEST['modal_dialog_mode'];
}
if(isset($_REQUEST['fuseaction'])) {
	$fuseaction=$_REQUEST['fuseaction'];
}else{
	$fuseaction="$$$$$$$$";
}

if(isset($_REQUEST['allow_select'])) {
	$v_allow_select = true;
}else{
	$v_allow_select = false;
}
// Neu la QUAN TRI ISA-USER thi moi duoc quyen them/sua/xoa ung dung
// Neu la QUAN TRI ISA-APP thi chi duoc xem cac ung dung, sua/xoa cac modul, function
// Neu la nguoi su dung ung dung thi chi duoc xem
$v_is_granted_update_app = false;
$v_is_granted_update_modul = false;
$v_is_granted_update_function = false;
$v_is_granted_delete = false;

if ($_SESSION['is_isa_user_admin']==1){
	$v_is_granted_update_app = true;
	$v_is_granted_update_modul = true;
	$v_is_granted_update_function = true;
	$v_is_granted_delete = true;
}
if ($_SESSION['is_isa_app_admin']==1){
	$v_is_granted_update_modul = true;
	$v_is_granted_update_function = true;
	$v_is_granted_delete = true;
}
$v_is_granted_view_list = true; 
?>
<script language="JavaScript">
	_MODAL_DIALOG_MODE = "<? echo $_MODAL_DIALOG_MODE;?>";
</script>