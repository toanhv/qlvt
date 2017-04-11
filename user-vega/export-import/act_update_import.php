<?php
$v_application_id = 0;
if(isset($_REQUEST['hdn_application_id'])){
	$v_application_id = intval($_REQUEST['hdn_application_id']);
}

$v_form_field = 'file_attach';
if (isset($_FILES[$v_form_field]['tmp_name'])){
		$v_filename = _replace_bad_char(trim($_FILES[$v_form_field]['name']));
		$v_tmp_filename    = $_FILES[$v_form_field]['tmp_name'];
		$v_file_content = _read_file($v_tmp_filename);
		//echo htmlspecialchars($v_file_content);exit;
		user_import_application($v_file_content,$v_application_id);
}	

?>
<form action="index.php" method="post" name="f_back">
	<input type="hidden" name="fuseaction" value="DISPLAY_SINGLE_EXPORT_IMPORT">
</form>
<script language="javascript">
	document.f_back.submit();	
</script>
