<script src="isa-lib/isa-js/isa_util.js"></script>
<?
include "app_global.php";
include "isa-lib/isa-function/xml_public_function.php";
include "isa-lib/isa-function/isa_public_function.php";
include "isa-lib/isa-function/prax.php";
include "connect.php";
include "connect_ado.php";
$v_file_url = trim(_replace_bad_char($_REQUEST['file_url']));
$v_table = _replace_bad_char($_REQUEST['table']);
$v_file_id_column = _replace_bad_char($_REQUEST['file_id_column']);
$v_file_name_column = _replace_bad_char($_REQUEST['file_name_column']);
$v_file_content_column = _replace_bad_char($_REQUEST['file_content_column']);
$v_file_id = intval($_REQUEST['file_id']);

$v_status = _create_file_from_database($v_table, $v_file_id_column, $v_file_name_column, $v_file_content_column, $v_file_id, $v_file_url);
if ($v_status>0){?>
	<script>
		show_file_content("<? echo $v_file_url; ?>");
	</script><?
}
?>

 