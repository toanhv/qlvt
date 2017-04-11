<?php
// Modul hien thoi la quan ly danh muc
$fuseaction="$$$$$$$";
if(isset($_REQUEST['fuseaction'])){
	$fuseaction=$_REQUEST['fuseaction'];
}
if(isset($_REQUEST['modal_dialog_mode'])) {
	$_MODAL_DIALOG_MODE = $_REQUEST['modal_dialog_mode'];
}
if(isset($_REQUEST['front_end_mode'])){
	$_FRONT_END_MODE = $_REQUEST['front_end_mode'];
}else{
	$_FRONT_END_MODE = 0;
}
// Neu trong URL co tham so "editing_mode" thi khi NSD nhan chuot vao ten can bo se hien thi cua so de hieu chinh cac thong tin ca nhan
$v_editing_mode = 0;
if (isset($_REQUEST['editing_mode'])){
	$v_editing_mode = $_REQUEST['editing_mode'];
}
?>
<script language="JavaScript">
	_MODAL_DIALOG_MODE = "<?php echo $_MODAL_DIALOG_MODE;?>";
	_EDITING_MODE = "<?php echo $v_editing_mode;?>";
</script>