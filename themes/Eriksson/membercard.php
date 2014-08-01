<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: membercard.php
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
require_once "../../maincore.php";
//Check if this files exists ?... Sure.
if (!file_exists(THEME."membercard.php")) {exit("...");}

header("Content-type: text/html; charset=".$locale['charset']."");

if (file_exists(THEME."locale/".$settings['locale'].".php")) {
	include THEME."locale/".$settings['locale'].".php";
    } else {
	include THEME."locale/English.php";
    }
//Prevent direct file access, redirect to profile.php if request is not made via AJAX
if ((!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')) && isset($_GET['lookup'])) {
	redirect(BASEDIR."profile.php?lookup=".$_GET['lookup']."");
	} else {
if (!iMEMBER && $settings['hide_userprofiles'] == 1) {
	redirect(BASEDIR."login.php");
	} else {
		if (isset($_GET['lookup']) && isnum($_GET['lookup'])) {
			$user_status = " AND (user_status='0' OR user_status='3' OR user_status='7')";
			if (iADMIN) {
				$user_status = "";
			}
			$result = dbquery(
				"SELECT 	user_id, user_name, user_avatar, user_posts,	user_level, user_joined, user_lastvisit, user_status, user_location
				FROM ".DB_USERS." 
				WHERE user_id='".$_GET['lookup']."'".$user_status."
				LIMIT 1"
			);
			if (!dbrows($result)) { exit("...");
				} else {
			while ($data = dbarray($result)) {
			if ( (($data['user_status'] == 0) || ($data['user_status'] == 3) || ($data['user_status'] == 7)) ||  iADMIN ) {  		
			echo "<div class='memberCard'>";
				//User avatar
				echo "<div class='avatar'>";
				if ($data['user_avatar'] && file_exists(IMAGES."avatars/".$data['user_avatar'])) {
         			echo "<a href='".$settings['siteurl']."profile.php?lookup=".$data['user_id']."' style='background-image: url(".$settings['siteurl']."images/avatars/".$data['user_avatar'].")' class='avatari' title='".$data['user_name']."&#39;s avatar'></a>\n";
           			 } else {
           		 	echo "<a href='".$settings['siteurl']."profile.php?lookup=".$data['user_id']."' style='background-image: url(".$settings['siteurl']."images/avatars/noavatar100.png)' class='avatari' title='".$data['user_name']." has no avatar'></a>\n";
						}
				echo "</div>";
				
				echo "<div class='userInfo'>";
				//Username
				echo "<h3 class='username'><a href='".$settings['siteurl']."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a></h3>";
				//User level
				echo "<div class='userlevel data'>".getuserlevel($data['user_level'])."</div>";
				//Location
				if (!empty($data['user_location'])) {
					echo "<div class='small'>".$locale['from']." <span class='data'>".$data['user_location']."</span></div>";
				}
				//Link
				echo "<div class='userlinks'><a href='".$settings['siteurl']."profile.php?lookup=".$data['user_id']."'>".$locale['profile_page']."</a>";
				//Send a PM
				if (iMEMBER && $userdata['user_id'] != $data['user_id']) {
					echo " | <a href='".$settings['siteurl']."messages.php?msg_send=".$data['user_id']."'>".$locale['send_pm']."</a>";
				}
				echo "</div>";
				//User posts
				if ($data['user_posts'] == 0) {
					echo "<div class='messages small'>".$locale['no_messages_posted']."</div>";
				} else {
					echo "<div class='messages small'>".$locale['messages_posted']." <span class='data'>".$data['user_posts']."</span></div>";
				}
				//Member since
				echo "<div class='info small'>".$locale['member_since']." <span class='data'>".showdate("%d %b %Y", $data['user_joined'])."</span></div>";
				//Last visit
				echo "<div class='info lastseen small'><span class='username flleft'>".$data['user_name']."&nbsp;</span>".$locale['last_seen']." <span class='data'>".showdate("%d %b %Y", $data['user_lastvisit'])."</span></div>";
				echo "</div>";
			echo "</div>"; }
			}
			}
		} elseif (!isset($_GET['lookup'])) {
			redirect(BASEDIR."index.php");
		} else {
			exit("...");
		}
	}
}
?>