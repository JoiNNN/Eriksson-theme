<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: English.php
| Author: JoiNNN
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------+
| This file is part of the PHP-Fusion localization
| standard.
+--------------------------------------------------------+
| Locale: English
+--------------------------------------------------------*/

////////////////////
//   theme.php    //
////////////////////
$locale['member_area']				= "Member Area";
	//info
$locale['download_fusion']			= "Download PHP-Fusion";
$locale['download_fusion_txt']	= "Click here to download latest<br />version of PHP-Fusion";
$locale['download_fusion_link']	= "#";
$locale['fusion_manual']			= "PHP-Fusion Manual";
$locale['fusion_manual_txt']		= "Click here to download the<br />official PHP-Fusion Manual";
$locale['fusion_manual_link']		= "#";
$locale['code_conduct']				= "Code of conduct";
$locale['code_conduct_txt']		= "Please read this before<br />posting on our website";
$locale['code_conduct_link']		= "#";
$locale['show_info']					= "Show info";
$locale['hide_info']					= "Hide info";
	//footer
$locale['latest_news']				= "Latest News";
$locale['no_news']					= "No news";
$locale['latest_weblinks']			= "Latest Weblinks";
$locale['no_links']					= "No links";
$locale['scroll_top']				= "Scroll to top";

$locale['tcp_warning']				= "<div class=\'admin-message tcp-warn\'>\n
<span id=\'tcp-warn\'></span><strong>Warning:</strong> the Theme Control Panel has not been infused yet.\n
<br />To infuse it go to infusions by <a href=\'".ADMIN."infusions.php".(isset($aidlink) ? $aidlink : "")."\'>clicking here</a>.\n
</div>";

////////////////////
// membercard.php //
////////////////////
$locale['profile_page']				= "Profile page";
$locale['from']						= "from";
$locale['send_pm']					= "Send a PM";
$locale['no_messages_posted']		= "No messages posted yet";
$locale['messages_posted']			= "Messages posted:";
$locale['member_since']				= "Member since:";
$locale['last_seen']					= "was last seen:";

////////////////////
// user_info.php  //
////////////////////
$locale['loggedas']					= "Logged in as:";
$locale['profile']					= "View your profile page";
$locale['loading']					= "Loading...";
$locale['click_register']			= "Click here</a> to register.";
$locale['forgot_pass']				= "Forgotten your password?";
?>