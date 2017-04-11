<?php
// Modul hien thoi la quan ly Co cau to chuc
$_ISA_CURRENT_MODUL_CODE = "ORGNIZATION";

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
// Neu la QUAN TRI ISA-USER thi moi duoc quyen them/sua/xoa, con neu la QUAN TRI ISA-APP thi chi duoc xem
if ($_SESSION['is_isa_user_admin']==1){
	$v_is_granted_update = true;
	$v_is_granted_delete = true;
}		
$v_is_granted_view_list = true; 
?>
<script language="JavaScript">
	_MODAL_DIALOG_MODE = "<? echo $_MODAL_DIALOG_MODE;?>";
</script>
 