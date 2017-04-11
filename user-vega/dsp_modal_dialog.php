<?
// File lam cac nhiem vu sau: // - Tao mot iframe de goi thu thi mot file .php
// Cac tham so can truyen vao duoi dang URL:
// - goto_url: la duong dan URL cua file duoc chay trong iframe
$v_goto_url = trim($_REQUEST['goto_url']);
if (strpos($v_goto_url,"?" )==0){
	$v_goto_url = $v_goto_url . "?modal_dialog_mode=1";
}else{
	$v_goto_url = $v_goto_url . "&modal_dialog_mode=1";
}
if (isset($_REQUEST['fuseaction'])){
	$v_goto_url = $v_goto_url .  "&fuseaction=" . trim($_REQUEST['fuseaction']);
}
if (isset($_REQUEST['hdn_item_id'])){
	$v_goto_url = $v_goto_url .  "&hdn_item_id=" . trim($_REQUEST['hdn_item_id']);
}
if (isset($_REQUEST['hdn_policy_type'])){
	$v_goto_url = $v_goto_url .  "&hdn_policy_type=" . trim($_REQUEST['hdn_policy_type']);
}
// Neu co bien "allow_editing_in_modal_dialog" thi hien thi nut "Them", "Xoa"
if(isset($_REQUEST['allow_editing_in_modal_dialog'])) {
	$v_goto_url = $v_goto_url .  "&allow_editing_in_modal_dialog=1";
}
?>
<IFRAME name="main"  src="<? echo $v_goto_url; ?>" WIDTH="100%" HEIGHT="100%" FRAMEBORDER=no align=center SCROLLING=yes ></IFRAME>

 