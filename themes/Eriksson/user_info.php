<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_info.php
| Author: Nick Jones (Digitanium)
| Modified by: JoiNNN
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

if (iMEMBER) {
	global $userdata;
	$msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0' AND message_folder='0'");

	echo "<div class='memberavatar flleft'><span></span><a href='".BASEDIR."profile.php?lookup=".$userdata['user_id']."'>\n";
	if ($userdata['user_avatar'] && file_exists(IMAGES."avatars/".$userdata['user_avatar'])) {
			echo "<img src='".IMAGES."avatars/".$userdata['user_avatar']."' width='50' height='50' alt='".$locale['loading']."' title='".$locale['profile']."' />\n";
			} else {
			echo "<img src='".IMAGES."avatars/noavatar100.png' width='50' height='50' alt='".$locale['loading']."' title='".$locale['profile']."' />\n";
			}
	echo "</a></div>\n";
	echo "<div class='logged flleft'><span>".$locale['loggedas']."</span><br />\n";
	echo "<strong>".$userdata['user_name']."</strong>";
	echo "</div>\n";

	echo "<div class='memberlinks flright'>";
	echo "<a href='".BASEDIR."edit_profile.php' class='side editprofile'>".$locale['global_120']."</a> | \n";
	echo "<span style='position:relative'><a href='".BASEDIR."messages.php' class='side pm'>".$locale['global_121']."</a>";
			if ($msg_count != 0) {
			echo "<span class='msg'><span></span>".$msg_count."</span>\n";
			add_to_footer("<script type='text/javascript'>/* <![CDATA[ */
			$(document).ready(function() {
			var blink = function(){
				$('.msg').fadeToggle('slow');
			};
			setInterval(blink, 1000);
			$.cookie('showMember', 'expanded', { path:'/' });
			});
			/* ]]> */\n</script>\n");}
			echo "</span> | \n";
	echo "<a href='".BASEDIR."members.php' class='side members'>".$locale['global_122']."</a> | \n";

	if (iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
		echo "<a href='".ADMIN."index.php".$aidlink."' class='side adminpanel'>".$locale['global_123']."</a> | \n";
	}

	echo "<a href='".BASEDIR."index.php?logout=yes' class='side logout'>".$locale['global_124']."</a></div>\n";

	if (iADMIN && checkrights("SU") && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
		$subm_count = dbcount("(submit_id)", DB_SUBMISSIONS);

		if ($subm_count) {
			echo "<div class='subs'>\n";
			echo "<strong><a href='".ADMIN."submissions.php".$aidlink."' class='side'>".sprintf($locale['global_125'], $subm_count);
			echo ($subm_count == 1 ? $locale['global_128'] : $locale['global_129'])."</a></strong>\n";
			echo "</div>\n";
		}
	}
} else {
		$action_url = FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "");
		if (isset($_GET['redirect']) && strstr($_GET['redirect'], "/")) {
			$action_url = cleanurl(urldecode($_GET['redirect']));
		}
		echo "<div class='loginform flright'>\n";
		echo "<div class='flright'><form name='loginform' method='post' action='".$action_url."'>\n";
		echo "<input type='text' name='user_name' class='textbox user' style='width:130px' />\n";
		echo "<input type='password' name='user_pass' class='textbox pass' style='width:130px' />\n";
		echo "<label><input type='checkbox' name='remember_me' value='y' title='".$locale['global_103']."' style='vertical-align:middle;' /></label>\n";
		echo "<input type='submit' name='login' value='".$locale['global_104']."' class='button' />\n";
		echo "</form>\n";
		if ($settings['enable_registration']) {
			echo "<div style='width: 162px' class='flleft'><a class='side' href='".BASEDIR."register.php'>".$locale['click_register']."</div>\n";
		}
		echo "<div style='width: 162px' class='flleft'><a class='side' href='".BASEDIR."lostpassword.php'>".$locale['forgot_pass']."</a></div></div>\n";
		echo "<img src='".THEME."images/lock.png' alt='Login' class='flright' /></div>\n";
}