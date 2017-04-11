<?php
// Modul hien thoi la quan ly danh muc
$_ISA_CURRENT_MODUL_CODE = "APPLICATION";

if(isset($_REQUEST['modal_dialog_mode'])) {
	$_MODAL_DIALOG_MODE = $_REQUEST['modal_dialog_mode'];
}
if(isset($_REQUEST['fuseaction'])) {
	$fuseaction=$_REQUEST['fuseaction'];
}else{
	$fuseaction="$$$$$$";
}
?>
<script language="JavaScript">
	_MODAL_DIALOG_MODE = "<?php echo $_MODAL_DIALOG_MODE;?>";
</script>