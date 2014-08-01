<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2010 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: theme_settings.php
| Author: JoiNNN // Spo0kye@yahoo.com
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

//Check if TCP table exists
$table = dbrows(dbquery("SHOW TABLES LIKE '".DB_PREFIX."eriksson_tcp'"));
if (!$table) {
//If TCP is not infused use this settings
define("TCPINFUSED", 0);
	//Lines below can be changed as an alternative to TCP
	$theme_maxwidth = 1024;
	$theme_minwidth = 980;
	$theme_maxwidth_forum = 0;
	$theme_maxwidth_admin = 0;
	$home_icon = 1;
	$member_card = 1;
	$winter_mode = 0;
	$scroll_top = 1;
	$chrome_script = 1;
	$logos_max_show = 4;
	$logos_list_fixed = 1;
	////////////////////////////////////////////////////
} else {
//If TCP is infused get settings from DB
define("TCPINFUSED", 1);
$result = dbquery("SELECT * FROM ".DB_PREFIX."eriksson_tcp");
$data = dbarray($result);
	$theme_maxwidth = $data['theme_maxwidth'];
	$theme_minwidth = $data['theme_minwidth'];
	$theme_maxwidth_forum = $data['theme_maxwidth_forum'];
	$theme_maxwidth_admin = $data['theme_maxwidth_admin'];
	$home_icon = $data['home_icon'];
	$member_card = $data['member_card'];
	$winter_mode = $data['winter_mode'];
	$scroll_top = $data['scroll_top'];
	$chrome_script = $data['chrome_script'];
	$logos_max_show = $data['logos_max_show'];
	$logos_list_fixed = $data['logos_list_fixed'];
}

//Check if different width is set for Forum
if ($theme_maxwidth_forum >= $theme_minwidth) {
	if (strpos(TRUE_PHP_SELF, '/forum/') !== FALSE) {
		$theme_maxwidth = $theme_maxwidth_forum;
	}
}
//Check if different width is set for Administration
if ($theme_maxwidth_admin >= $theme_minwidth) {
	if (strpos(TRUE_PHP_SELF, '/administration/') !== FALSE) {
		$theme_maxwidth = $theme_maxwidth_admin;
	}
}
define("THEME_WIDTH", $theme_maxwidth."px"); // For compatibility
define("THEME_MAXWIDTH", $theme_maxwidth."px");
define("THEME_MINWIDTH", $theme_minwidth."px");
define("HOME_ICON", $home_icon);
define("MCARD", $member_card);
define("WINTER", $winter_mode);
define("SCROLLTOP", $scroll_top);
define("CHSCRIPT", $chrome_script);
define("LOGOS_MAX", $logos_max_show);
define("LOGOS_FIXED", $logos_list_fixed);
?>