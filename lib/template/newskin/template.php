<?php
 // $Id$
 // $Author$

// Check for fscked up gecko rendering engine prior to 20031030
// which causes the menus not to work. Default back to the default
// template quasi-seamlessly if this happens, based on user agent.
$_ua = getenv('HTTP_USER_AGENT').' ';
if (eregi('Gecko/', $_ua)) {
	$gecko_pos = strpos($_ua, 'Gecko/');
	$next_space = strpos($_ua, ' ', $gecko_pos);
	$version = substr($_ua, $gecko_pos+6,
		($next_space - $gecko_pos)-6);
	//die ( "version = -".$version."-");
	if ($version < '20031030') {
		include "lib/template/default/template.php";
		die();
	}
}

// Check for avoiding template
if (!$GLOBALS['__freemed']['no_template_display']) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2 Final//EN">
<html>
<head>
	<title><?php print prepare(PACKAGENAME) . " v". DISPLAY_VERSION . " - " .
		( !empty($GLOBALS['page_title']) ? $GLOBALS['page_title']." - " : "" ) .
		prepare(INSTALLATION); ?></title>
	<meta HTTP-EQUIV="Content-Type" 
		CONTENT="text/html; CHARSET=<?php print $__ISO_SET__; ?>">
<?php
//----- Handle refresh
if (isset($refresh)) {
?>
	<meta HTTP-EQUIV="REFRESH" CONTENT="0;URL=<?php print $refresh; ?>">
<?php
} else if (isset($GLOBALS['__freemed']['automatic_refresh'])) { // automatic refreshes
?>
	<meta HTTP-EQUIV="REFRESH" CONTENT="<?php
	print $GLOBALS['__freemed']['automatic_refresh'];
	?>;URL=<?php print basename($_SERVER['REQUEST_URI']); ?>">
<?php
} // end handle refresh
?>
	<link REL="StyleSheet" TYPE="text/css"
		HREF="lib/template/newskin/stylesheet.css" />
	<link REL="StyleSheet" TYPE="text/css"
		HREF="lib/template/newskin/dynamic.css.php" />
	<script type="text/javascript" src="lib/template/newskin/menu.js"></script>
</head>

<body BGCOLOR="#ffffff" TEXT="#555555"
 ALINK="#000000" VLINK="#000000" LINK="#000000"
 MARGINWIDTH="0" MARGINHEIGHT="0" LEFTMARGIN="0" RIGHTMARGIN="0"
 <?php
	// Check for close_on_load
	if ($GLOBALS['__freemed']['close_on_load']) {
		print "onLoad=\"window.close(); return true;\"";
	} elseif (!empty($GLOBALS['__freemed']['on_load'])) {
		print " onLoad=\"".$GLOBALS['__freemed']['on_load']."(); return true;\"";
	}
 ?>>

<!-- menu -->

<?php if (!$GLOBALS['__freemed']['no_menu_bar']) {
	include "lib/template/newskin/menu.php";
} else { ?>
<div id="top" align="left" style="width: 100%;" class="menuBar">
	<b><?php print PACKAGENAME." v".DISPLAY_VERSION.
		( $GLOBALS['page_title'] ? ' - '.$GLOBALS['page_title'] : '' );
		?></b>
</div>
<!-- Move main body down a little bit... -->
<br/>
<?php } ?>

<!-- body -->

<div class="main" id="main">
	<?php print $display_buffer; ?>
	<br/><br/>
</div>

<!-- copyright notice -->
<br/><br/>
<div id="bottom" align="CENTER" style="width: 100%;">
<span align="left">
	<small>&copy; 1999-<?php print date("Y"); ?> by the FreeMED Software Foundation</small>
</span>
<span align="center">
<?php
//----- Create user object if it doesn't exist and we're logged in
if (freemed::verify_auth() and !is_object($this_user)) {
	$this_user = CreateObject('FreeMED.User');
} // end check to see if we're logged in

//----- Generate session information portion of the bar
if (is_object($this_user)) {
	print "<big>|</big> ".__("User")." : ".$this_user->getDescription().
		"\n";
	if ($this_user->newMessages()) {
		print "<big>|</big> ".
			"<a href=\"messages.php\">".
			"<img src=\"img/messages_small.gif\" alt=\"\" ".
			"width=\"16\" height=\"16\" border=\"0\"/></a>\n";
	}
} else {
	print "&nbsp;\n";
} // end checking if this_user exists
?>
</span>
</div>

</body>
</html>
<?php
} else {
	// Show what we have, if that's what we're doing
	print "<html>\n".
		"<head>\n".
		"<title>".prepare(PACKAGENAME)." v".DISPLAY_VERSION." - ".
		( !empty($GLOBALS['page_title']) ? $GLOBALS['page_title']." - " : "" ) .
		prepare(INSTALLATION)."</title>\n".
		"<link REL=\"StyleSheet\" TYPE=\"text/css\" ".
		"HREF=\"lib/template/newskin/stylesheet.css\"/>\n".
		"<link REL=\"StyleSheet\" TYPE=\"text/css\" ".
		"HREF=\"lib/template/newskin/dynamic.css.php\" />\n".
		"</head>\n".
		"<body";
	// Check for close_on_load
	if ($GLOBALS['__freemed']['close_on_load']) {
		print " onLoad=\"window.close(); return true;\"";
	} elseif (!empty($GLOBALS['__freemed']['on_load'])) {
		print " onLoad=\"".$GLOBALS['__freemed']['on_load']."(); return true;\"";
	}
	print "><div class=\"main\">\n";
	print $display_buffer;
	print "</div></body>\n".
		"</html>\n";
} // end checking for "no_template_display"
?>
