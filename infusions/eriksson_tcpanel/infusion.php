<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2010 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion.php
| Author: JoiNNN
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

if (file_exists(INFUSIONS."eriksson_tcpanel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."eriksson_tcpanel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."eriksson_tcpanel/locale/English.php";
}

include INFUSIONS."eriksson_tcpanel/infusion_db.php";

// Infusion general information
$inf_title = "Eriksson Theme Control Panel";
$inf_description = "Eriksson Theme Control Panel";
$inf_version = "1.0";
$inf_developer = "JoiNNN";
$inf_email = "Spo0kye@yahoo.com";
$inf_weburl = "http://www.php-fusion.se";

$inf_folder = "eriksson_tcpanel";

$inf_newtable[1] = DB_ERIKSSONTCP." (
   theme_maxwidth VARCHAR(4) NOT NULL DEFAULT '',
   theme_minwidth VARCHAR(4) NOT NULL DEFAULT '',
	theme_maxwidth_forum VARCHAR(4) NOT NULL DEFAULT '',
	theme_maxwidth_admin VARCHAR(4) NOT NULL DEFAULT '',
	home_icon TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
   member_card TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
   winter_mode TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	scroll_top TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
	chrome_script TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
	logos_max_show TINYINT(2) UNSIGNED NOT NULL DEFAULT '4',
	logos_list_fixed TINYINT(2) UNSIGNED NOT NULL DEFAULT '1',
   PRIMARY KEY (theme_maxwidth)
) ENGINE=MyISAM;";
$inf_insertdbrow[1] = DB_ERIKSSONTCP." (
theme_maxwidth,
theme_minwidth,
theme_maxwidth_forum,
theme_maxwidth_admin,
home_icon,
member_card,
winter_mode,
scroll_top,
chrome_script,
logos_max_show,
logos_list_fixed
) VALUES (
'1024',
'980',
'0',
'0',
'1',
'1',
'0',
'1',
'1',
'4',
'1'
)";

$inf_adminpanel[1] = array(
	"title" => "Eriksson Theme Control Panel",
	"image" => "erikssontcp.png",
	"panel" => "eriksson_tcpanel_admin.php",
	"rights" => "ERCP"
);

$inf_droptable[1] = DB_ERIKSSONTCP;
?>