<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2010 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: eriksson_tcpanel_admin.php
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
require_once "../../maincore.php";
//Check rights
if (!checkrights("ERCP") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../../index.php"); }

require_once THEMES."templates/admin_header.php";
//Locales
if (file_exists(INFUSIONS."eriksson_tcpanel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."eriksson_tcpanel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."eriksson_tcpanel/locale/English.php";
}

include INFUSIONS."eriksson_tcpanel/infusion_db.php";

/*-------------------------+
|	GENERAL SETTINGS TAB
+--------------------------*/
$select_opt = array(
	1 => array(	"desc" => $locale['enabled'],
					"color" => "green"),
	0 => array(	"desc" => $locale['disabled'],
					"color" => "red")
	);

/////////////////////////
// Saving settings
/////////////////////////
if (isset($_POST['save_settings'])) {
	//Check and get all inputs values
	function check_input($name, $values="", $default="") {
		$res = "";
		//For inputs with predefined values
		if (isset($name) && $values != "" && $default != "") {
			if (isset($_POST[$name]) && isnum($_POST[$name]) && array_key_exists($_POST[$name], $values)) {
					$res = stripinput($_POST[$name]);
					} else {
					$res = $default;
					}
		} elseif (isset($name)) {
		//For inputs with no predefined values
		if (isset($_POST[$name]) && isnum($_POST[$name])) {
				$res = stripinput($_POST[$name]);
				} else {
				$res = "0";
				}
		}
		return $res;
	}
	$theme_minwidth 		 = check_input('theme_minwidth');
	$theme_maxwidth 		 = check_input('theme_maxwidth');
	$home_icon				 = check_input('home_icon', 		$select_opt, '1');
	$member_card 			 = check_input('member_card', 	$select_opt, '1');
	$winter_mode 			 = check_input('winter_mode',		$select_opt, '0');
	$scroll_top 			 = check_input('scroll_top', 		$select_opt, '1');
	$chrome_script			 = check_input('chrome_script', 	$select_opt, '1');
	$theme_maxwidth_forum = "0";
		//If the checkbox is checked get width from input field
		if (isset($_POST['cbox_theme_maxwidth_forum']) && isnum($_POST['theme_maxwidth_forum'])) {
			$theme_maxwidth_forum = stripinput($_POST['theme_maxwidth_forum']);
		}
		//theme_maxwidth_forum should not be lower than MinWidth
		if (isset($_POST['cbox_theme_maxwidth_forum']) && $theme_maxwidth_forum < $theme_minwidth) {$theme_maxwidth_forum = $theme_minwidth;}
		
	$theme_maxwidth_admin = "0";
		//If the checkbox is checked get width from input field
		if (isset($_POST['cbox_theme_maxwidth_admin']) && isnum($_POST['theme_maxwidth_admin'])) {
			$theme_maxwidth_admin = stripinput($_POST['theme_maxwidth_admin']);
		}
		//theme_maxwidth_admin should not be lower than MinWidth
		if (isset($_POST['cbox_theme_maxwidth_admin']) && $theme_maxwidth_admin < $theme_minwidth) {$theme_maxwidth_admin = $theme_minwidth;}
	$logos_max_show	= check_input('logos_max_show');
		if ($logos_max_show > 10) {$logos_max_show = 4;}
	$logos_list_fixed	= check_input('logos_list_fixed');
		if (($logos_list_fixed < 1) || ($logos_list_fixed > 60)) {$logos_list_fixed = 1;}

	//Check if any width field is empty
	if (empty($theme_maxwidth) || empty($theme_minwidth)) {
		$err_mess = $locale['invalid'];
		} elseif ($theme_maxwidth < $theme_minwidth) {
		//MaxWidth should not be lower than MinWidth
		$err_mess = $locale['maxwidth_low'];
		} else {
		$result = dbquery("UPDATE ".DB_ERIKSSONTCP." SET 
						theme_maxwidth			= '$theme_maxwidth',
						theme_minwidth			= '$theme_minwidth',
						theme_maxwidth_admin	= '$theme_maxwidth_admin',
						theme_maxwidth_forum	= '$theme_maxwidth_forum',
						home_icon 				= '$home_icon',
						member_card				= '$member_card',
						winter_mode				= '$winter_mode',
						scroll_top				= '$scroll_top',
						chrome_script			= '$chrome_script',
						logos_max_show			= '$logos_max_show',
						logos_list_fixed		= '$logos_list_fixed'
						");
		redirect(FUSION_SELF.$aidlink."&amp;status=su"); //Settings updated, redirect
	}
}
/////////////////////////
// Get settings from DB
/////////////////////////
$theme_settings = dbquery("SELECT * FROM ".DB_ERIKSSONTCP."");
$setting = dbarray($theme_settings);

function render_input($val="", $type="", $values="", $maxlen="2", $default="") {
	global $setting, $locale; $res="";
	//Text inputs
	if ($type == "input") {
		$res = "<input name='$val' id='$val' value='".$setting[$val]."' size='10' type='text' maxlength='$maxlen' class='textbox input' />";
	//Text inputs with checkbox
	} elseif ($type == "cboxinput") {
		$checked = "checked='checked'";
		$disabled = "";
		if ($setting[$val] == 0) {
			$checked = "";
			$disabled = "disabled='disabled'";
			$setting[$val] = $default;
		}
		$res = "<input type='checkbox' name='cbox_".$val."' id='cbox_".$val."' ".$checked." value='0' /> ";
		$res .= "<input name='$val' id='$val' value='".$setting[$val]."' ".$disabled." size='10' type='text' maxlength='$maxlen' class='textbox cboxinput' />";
	//Select inputs
	} elseif ($type == "select") {
		$res = "<select class='textbox select' name='$val' id='$val'>";
		foreach($values as $key => $value){
			$selected = "";
			if ($setting[$val] == $key) {
				$selected = "selected='selected'";
			}
			$res .= "<option style='color:".$value['color']."' value='$key' ".$selected.">".$value['desc']."</option>";							 
		}
		$res .= "</select>";
		if ($setting[$val] == 0) {
			$res .= " <img src='".IMAGES."no.png' width='16' height='16' alt='".$locale['disabled']."' />";
		} else {
			$res .= " <img src='".IMAGES."yes.png' width='16' height='16' alt='".$locale['enabled']."' />";
		}
	}

	return $res;
}

opentable($locale['tcp_title']);
//Tabs CSS
add_to_head("<link type='text/css' href='css/styles.css' rel='stylesheet' media='screen' />");
//The Tabs
echo "<ul class='tabs'>
        <li><a href='#tab-1' class='scroll clean'><span>".$locale['g_sets']."</span></a></li>
        <li><a href='#tab-2' class='scroll clean'><span>".$locale['p_logos']."</span></a></li>
		  <li><a href='#tab-3' class='scroll clean'><span>".$locale['up_images']."</span></a></li>
    	</ul>";
/*-------------------------+
|	GENERAL TAB HTML
+--------------------------*/
echo "<div id='tab-1' class='tab_content'>
			<form name='save_settings' method='post' action='".FUSION_SELF.$aidlink."'>
			<table class='settings center' width='100%' cellspacing='0'> 
			<tbody>
			<tr><th class='tbl2 forum-caption' colspan='4'><h3>".$locale['g_sets']."</h3></th></tr>";

//Theme Max Width
echo "<tr>
			<td class='desc'><label for='theme_maxwidth'><b>".$locale['max_w']."</b></label>
				<p class='small'>".$locale['max_w_des']."</p>
			</td>
			<td class='inputs'>
			".render_input('theme_maxwidth', 'input', '', '4')." px
			</td>
		</tr><tr><td colspan='2'><hr /></td></tr>";

//Theme Min Width
echo "<tr>
			<td class='desc'><label for='theme_minwidth'><b>".$locale['min_w']."</b></label>
				<p class='small'>".$locale['min_w_des']."</p>
			</td>
			<td class='inputs'>
			".render_input('theme_minwidth', 'input', '', '4')." px
			</td>
		</tr><tr><td colspan='2'><hr /></td></tr>";

//Theme Max Width in Forum
echo "<tr>
			<td class='desc'><label for='theme_maxwidth_forum'><b>".$locale['max_wf']."</b></label>
				<p class='small'>".$locale['max_wf_des']."</p>
			</td>
			<td class='inputs'>
			".render_input('theme_maxwidth_forum', 'cboxinput', '', '4', $setting['theme_maxwidth'])." px
			</td>
		</tr><tr><td colspan='2'><hr /></td></tr>";

//Theme Max Width in Administration
echo "<tr>
			<td class='desc'><label for='theme_maxwidth_admin'><b>".$locale['max_wa']."</b></label>
				<p class='small'>".$locale['max_wa_des']."</p>
			</td>
			<td class='inputs'>
			".render_input('theme_maxwidth_admin', 'cboxinput', '', '4', $setting['theme_maxwidth'])." px
			</td>
		</tr><tr><td colspan='2'><hr /></td></tr>";
		
//Home Icon
echo "<tr>
			<td class='desc'><label for='home_icon'><b>".$locale['home_icon']."</b></label>
				<p class='small'>".$locale['home_icon_des']."</p>
			</td>
			<td class='inputs'>
			".render_input('home_icon', 'select', $select_opt)."
			</td>				
		</tr><tr><td colspan='2'><hr /></td></tr>";

//Member Card
echo "<tr>
			<td class='desc'><label for='member_card'><b>".$locale['m_card']."</b></label>
				<p class='small'>".$locale['m_card_des']."</p>
			</td>
			<td class='inputs'>
			".render_input('member_card', 'select', $select_opt)."
			</td>				
		</tr><tr><td colspan='2'><hr /></td></tr>";

//Winter Mode
echo "<tr>
			<td class='desc'><label for='winter_mode'><b>".$locale['winter']."</b></label>
				<p class='small'>".$locale['winter_des']."</p>
			</td>
			<td class='inputs'>
			".render_input('winter_mode', 'select', $select_opt)."
			</td>				
		</tr><tr><td colspan='2'><hr /></td></tr>";

//Scroll top top
echo "<tr>
			<td class='desc'><label for='scroll_top'><b>".$locale['stt_link']."</b></label>
			<p class='small'>".$locale['stt_link_des']."</p>
			</td>
			<td class='inputs'>
			".render_input('scroll_top', 'select', $select_opt)."
			</td>		
		</tr><tr><td colspan='2'><hr /></td></tr>";

//Chrome Script
echo "<tr>
			<td class='desc'><label for='chrome_script'><b>".$locale['chrome_s']."</b></label>
			<p class='small'>".$locale['chrome_s_des']."</p>
			</td>
			<td class='inputs'>
			".render_input('chrome_script', 'select', $select_opt)."
			</td>				
		</tr><tr><td colspan='2'><hr /></td></tr>";

//Max logos
echo "<tr>
			<td class='desc'><label for='logos_max_show'><b>".$locale['logos_max']."</b></label>
				<p class='small'>".$locale['logos_max_des']."</p>
			</td>
			<td class='inputs'>
			".render_input('logos_max_show', 'input')." ".($setting['logos_max_show'] == "1" ? $locale['logo'] : $locale['logos'])."
			</td>
		</tr><tr><td colspan='2'><hr /></td></tr>";

//Fixed list
echo "<tr>
			<td class='desc'><label for='logos_list_fixed'><b>".$locale['logos_fixed']."</b></label>
				<p class='small'>".$locale['logos_fixed_des']."</p>
			</td>
			<td class='inputs'>
			".render_input('logos_list_fixed', 'input')." ".($setting['logos_list_fixed'] == "1" ? $locale['second'] : $locale['seconds'])."
			</td>
		</tr>";

//Save Button
echo "<tr>
			<td colspan='3' align='center'><br /><input type='submit' name='save_settings' value='".$locale['save_sets']."' class='button' /></td>
		</tr>";
		
echo "</tbody></table>";
echo "</form><br /></div>";

	require_once "partners.php";

	require_once "images.php";

//Status messages
if (isset($_GET['status']) && !isset($message)) {
	if ($_GET['status'] 			== "su") {
		$message = $locale['sets_up'];
	//Logos
	} elseif ($_GET['status']	== "sn") {
		$message = $locale['logo_s'];
	} elseif ($_GET['status'] 	== "del") {
		$message = $locale['logo_d'];
	//Images
	} elseif ($_GET['status'] 	== "deli") {
		$message = $locale['deli'];
	} elseif ($_GET['status'] 	== "upn") {
		$err_mess = $locale['upn'];
	} elseif ($_GET['status'] 	== "upy") {
		$message = $locale['upy'];
	}
	//Render message
	if (isset($message)) { replace_in_output("<!--error_handler-->", "<!--error_handler--><div id=\'close-message\'><div class=\'admin-message\'>".$message."</div></div>"); }
}

//If any error message is set show it
if (isset($err_mess)) { replace_in_output("<!--error_handler-->", "<!--error_handler--><div class=\'admin-message\'>".$err_mess."</div>"); };

closeside();
?>
<script type="text/javascript">
/* <![CDATA[ */
jQuery(document).ready(function() {
	$('.inputs select').change(function () {
		var color = $('option:selected', this).attr('style');
		$(this).attr('style', color);
	});
	
	$('#logo_image').change(function () {
		var value = $('option:selected', this).attr('value');
		var folder = '<?php echo $image_folder;?>';
		if (value != '') {
			$('#logo_preview').attr('src',folder + value);
		} else {
			$('#logo_preview').attr('src','images/noimage.png');
		}
	});

	$('.inputs select').each(function () {
		var color = $('option[selected=selected]', this).attr('style');
		$(this).attr('style', color);
	});
	
	$('input[type="checkbox"]').click(function() {
  	if (this.checked) {
   		$(this).next('input').removeAttr('disabled');
	  } else {
   		$(this).next('input').attr('disabled', 'disabled');
	  }
	});

	$('#add_new_button').click(function() {
		$('#add_new').fadeTo('slow',0.01,function(){
			$(this).slideUp('slow',function(){
				$(this).hide()
			});
		});
		$('#add_logo').fadeIn('slow');
	});
});
/* ]]>*/
</script>
<?php

//Tabs javascript
?>
<script type='text/javascript'>
jQuery(document).ready(function() {
    var tabContent = $(".tab_content");
    var tabs = $("ul.tabs li").removeClass("current");
    var hash = window.location.hash;
	//On page load
	if (hash) {
		tabContent.not(hash).hide();
		tabs.find("a[href=" + hash + "]").parent().addClass("current");
		var activeTab = $(this).find(hash);
		$(activeTab).fadeIn();

		$("html, body").scrollTop(0);
	 } else {
	 	var tab = $("ul.tabs li:first");
	   tab.show(function() {
			$(this).addClass("current").siblings().removeClass("current");
			tabContent.hide();
			var activeTab = $(this).find("a").attr("href");

			$(activeTab).fadeIn();
			return false;
    	});
	 }
	 //On click
    tabs.click(function() {
	 	if (!$(this).hasClass("current")) {
        $(this).addClass("current").siblings().removeClass("current");
        tabContent.hide();
        var activeTab = $(this).find("a").attr("href");

        $(activeTab).stop().fadeTo("slow", 1);
		 };
		 return false;
    });
});
</script>
<?php
require_once THEMES."templates/footer.php";
?>
