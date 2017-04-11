<?php
$v_url = "index.php";
if (isset($_REQUEST['editing_mode'])){
	$v_url = "index.php?editing_mode=" . $_REQUEST['editing_mode'];
}
?>
<frameset topmargin = "0" rows="92,*" frameborder="0" border="0">
	<frame name="banner" src="baner.php" frameborder ="0" scrolling="no">
	<frameset framespacing="0" ROWS="0" border="0" frameborder="0" cols="220,*">
		<frame name="menu" src="<?php echo $v_url;?>" frameborder="0" marginwidth="0" 
			   marginwidth=0 marginheight="0" framespacing="0" scrolling="auto" noresize>
	   <frame name="master" src="index.php?fuseaction=DISPLAY_PHONE_TO_MIND" frameborder="0" marginwidth="0" 
			   marginheight="0" framespacing="0" scrolling="auto" noresize>
	<frame src="UntitledFrame-9"></frameset>
</frameset><noframes></noframes>
 