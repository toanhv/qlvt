<?
// Modul hien thoi la quan ly nguoi dung
$_ISA_CURRENT_MODUL_CODE = "ENDUSER";
if(isset($_REQUEST['modal_dialog_mode'])) {
	$_MODAL_DIALOG_MODE = $_REQUEST['modal_dialog_mode'];
}
if(isset($_REQUEST['fuseaction'])) {
	$fuseaction=$_REQUEST['fuseaction'];
}else{
	$fuseaction="$$$$$$";
}

if(isset($_REQUEST['allow_select'])) {
	$v_allow_select = true;
}else{
	$v_allow_select = false;
}

$v_is_granted_view_list = true; // Bien xac dinh xem NSD hien thoi co quyen xem danh muc khong
$v_is_granted_update = true; // Bien xac dinh xem NSD hien thoi co quyen them/sua mot doi tuong khong
$v_is_granted_delete = true; // Bien xac dinh xem NSD hien thoi co quyen XOA mot doi tuong khong
?>
<script language="JavaScript">
	_MODAL_DIALOG_MODE = "<? echo $_MODAL_DIALOG_MODE;?>";
</script>