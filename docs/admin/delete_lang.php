<?php
/****************************************************************/
/* ATutor														*/
/****************************************************************/
/* Copyright (c) 2002-2003 by Greg Gay & Joel Kronenberg        */
/* Adaptive Technology Resource Centre / University of Toronto  */
/* http://atutor.ca												*/
/*                                                              */
/* This program is free software. You can redistribute it and/or*/
/* modify it under the terms of the GNU General Public License  */
/* as published by the Free Software Foundation.				*/
/****************************************************************/

define('AT_INCLUDE_PATH', '../include/');
require(AT_INCLUDE_PATH.'vitals.inc.php');
if ($_SESSION['course_id'] > -1) { exit; }

if ($_POST['cancel']) {
	Header('Location: language.php?f='.urlencode_feedback(AT_FEEDBACK_CANCELLED));
	exit;
}

if ($_POST['submit']) {
	$sql = "DELETE FROM ".TABLE_PREFIX."lang2 WHERE lang='$_POST[delete_lang]'";
	$result = mysql_query($sql, $db);

	$sql	= "UPDATE ".TABLE_PREFIX."members SET language='".DEFAULT_LANGUAGE."' WHERE language='$_POST[delete_lang]'";
	mysql_query($sql, $db);

	cache_purge('system_langs', 'system_langs');
	Header('Location: language.php?f='.urlencode_feedback(AT_FEEDBACK_LANG_DELETED));
	exit;
}

if($_GET['delete_lang']){
		require(AT_INCLUDE_PATH.'admin_html/header.inc.php');
?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="hidden" name="delete_lang" value="<?php echo $_GET['delete_lang'] ?>" />
<?php

		echo '<h2>'._AT('lang_manager').'</h2>';
		echo '<h3>'._AT('delete_language').'</h3>';
		if (isset($_GET['f'])) { 
			$f = intval($_GET['f']);
			if ($f <= 0) {
				/* it's probably an array */
				$f = unserialize(urldecode($_GET['f']));
			}
			print_feedback($f);
		}
		if (isset($errors)) { print_errors($errors); }
		$warnings[]=array(AT_WARNING_DELETE_LANG, $_GET['delete_lang']);
		print_warnings($warnings);

	?>
	<input type="submit" name="submit" value="<?php echo _AT('delete'); ?>" class="button" /> - <input type="submit" name="cancel" class="button" value=" <?php echo _AT('cancel'); ?> " />
	</form>
<?php
		require(AT_INCLUDE_PATH.'admin_html/footer.inc.php');
}
?>