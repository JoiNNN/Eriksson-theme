<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: theme.php
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
| This file is part of the PHP-Fusion Theme standard.
+--------------------------------------------------------+
| Theme: Eriksson
| Author: JoiNNN //spo0kye@yahoo.com
| PHP-Fusion version: 7.02.04
| Theme version: 1.1 BETA
+--------------------------------------------------------+
| Last changed: 11 mar 2012
| Originally released for http://www.php-fusion.se
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

//Theme settings
require_once THEME."theme_settings.php";

define("THEME_BULLET", "<img src='".THEME."images/bullet.png' class='bullet' width='3' height='5' alt='>' />");
require_once THEME."functions.php";
require_once INCLUDES."theme_functions_include.php";

function get_head_tags(){
	echo "<!--[if lte IE 7]><style type='text/css'>hr{height: 2px;} .button{padding: 2px 4px} #navigation h2{margin:0 -1px} .member_wrap,.subs{position: relative} .clearfix {display:inline-block;} * html .clearfix{height: 1px;}</style><![endif]-->\n";
	//Theme width class
	echo "<style type='text/css'>.theme-width {max-width: ".THEME_MAXWIDTH.";min-width: ".THEME_MINWIDTH."}</style>\n";
}

function render_page($license=false) {
global $aidlink, $locale, $settings, $main_style;

add_handler("theme_output");

//Check for locale
if (file_exists(THEME."locale/".$settings['locale'].".php")) {
	include THEME."locale/".$settings['locale'].".php";
	} else {
	include THEME."locale/English.php";
	}

//Member Area
echo "<div id='member-area'>
		<div class='line'></div>
		<div class='theme-width center' style='position:relative;'>
		<div class='member_wrap center'>";
		require_once THEME."user_info.php";

echo "</div>
			<a href='".BASEDIR."login.php' class='show' id='topMember'>
					<span class='micon flleft'></span>".$locale['member_area']."
			</a>
		</div>
		</div>\n";
//Header
$links = showsublinks("","link");
	//Home link as icon
	if (HOME_ICON == 1) {
		$links = preg_replace("#(<li class=')link( first-link)?( current-link)?('><a href=')(\.\./|\.\./\.\./)?(".$settings['opening_page']."|index.php)?('.*)#s", "$1link home$2$4$5$6$7", $links);
	}
	//add class last-link
	$links = preg_replace("#(.*<li class=')link( current-link)?( home)?('.*)#s", "$1link last-link$2$3$4", $links);
echo "<div id='header' class='clearfix'>
		<div id='mainheader' class='theme-width'>
		<h1 id='logo' class='flleft'>".showbanners()."</h1>
		<div id='subheader' class='flleft'>".$links."</div>\n";
	//Winter Mode
	if (WINTER == 1) {
		echo "<span class='top-snow'></span>\n";
		add_to_head("<link type='text/css' href='".THEME."css/winter.css' rel='stylesheet' media='screen' />");
	}
echo "</div>\n</div>\n";

echo "<div id='phpfusion-info'>
			<div class='info_wrap center'>
				<div class='download_fusion flleft'>
				<a href='".$locale['download_fusion_link']."'>
					<img src='".THEME."images/blank.gif' alt='' width='58' height='58' />
					<span class='txt flleft'>
					<span class='h1'>".$locale['download_fusion']."</span>
					<span>".$locale['download_fusion_txt']."</span>
					</span>
				</a>
				</div>
				<div class='fusion_manual flleft'>
				<a href='".$locale['fusion_manual_link']."'>
					<img src='".THEME."images/blank.gif' alt='' width='58' height='58' />
					<span class='txt flleft'>
					<span class='h1'>".$locale['fusion_manual']."</span>
					<span>".$locale['fusion_manual_txt']."</span>
					</span>
				</a>
				</div>
				<div class='code_conduct flleft'>
				<a href='".$locale['code_conduct_link']."'>
					<img src='".THEME."images/blank.gif' alt='' width='58' height='58' />
					<span class='txt flleft'>
					<span class='h1'>".$locale['code_conduct']."</span>
					<span>".$locale['code_conduct_txt']."</span>
					</span>
				</a>
				</div>
		</div>
			<div class='openCloseWrap center'>
				<span id='infoSlideBtn' class=''></span>
			</div>
	</div>\n";

	echo "<div id='main' class='$main_style theme-width clearfix'>\n";

//Panels structure
	echo (LEFT ? "<div id='side-border-left' class='side'>\n".LEFT."</div>\n" : "");
	echo (RIGHT ? "<div id='side-border-right' class='side'>\n".RIGHT."</div>\n" : "");
//Main structure
	echo "<div id='main-bg'><div id='container'>";

	echo U_CENTER.CONTENT.L_CENTER."</div></div></div>\n";
//Partners logos
	echo "<div id='footer-part'>";
				include THEME."partners.php";
	echo "</div>\n";

//Footer
	echo "<div class='footer-bg'>
			<div id='footer' class='theme-width'>
			<div class='footernav clearfix'>";
			//Footer links
			require_once THEME."footer_links.php";
	echo "</div>\n";

//Subfooter			
	echo "<div class='flleft' style='width: 24%;padding: 14px 0 30px;text-align: left;'>Theme designed by <a href='http://www.php-fusion.co.uk'>JoiNNN</a></div>
			<div class='flright' style='width: 24%;padding: 14px 0 30px;text-align: right;'>";
	//Scroll to top link
	if (SCROLLTOP == 1) { echo "<a href='#member-area' id='top-link' class='scroll clean' >".$locale['scroll_top']."</a>"; }
	echo "</div>";
	echo (!$license ? "<div id='copyright'>".showcopyright()."</div>" : "");
	echo "<div style='margin-top: 20px'>".stripslashes($settings['footer'])."</div>\n";
	
	echo "<div id='subfooter' class='clearfix'>";
	echo "<div class='flleft' style='width: 50%'>";
			if ($settings['rendertime_enabled'] == 1 || ($settings['rendertime_enabled'] == 2 && iADMIN)) {echo showrendertime();}
	echo "</div>
			<div class='flright' style='width: 50%; text-align: right;'>".showcounter()."</div>
			</div>
			</div>
			</div>\n";

/*--------------+
|	javaScripts
+---------------*/
add_to_footer("<script type='text/javascript' src='".THEME."jscombined.php'></script>");

//jQuery cookie plugin
//add_to_footer("<script type='text/javascript' src='".THEME."js/jquery.cookie.js'></script>");

//Show warning if Theme Control Panel is not infused and user has access to Infusions
if (TCPINFUSED == 0 && checkrights("I")) {
	//Render the warning
	replace_in_output("<!--error_handler-->", "<!--error_handler-->".$locale['tcp_warning']);
	//Message close script
	add_to_footer("<script type='text/javascript'>
	$(document).ready(function() {
	$('#tcp-warn').click( function() {
		$('.tcp-warn').fadeTo('slow',0.01,function(){
			$(this).slideUp('slow',function(){
				$(this).hide()
			});
		$.cookie('showWarn', 'hide', {expires: 1, path:'/' });
		});
	});
	var showWarn = $.cookie('showWarn');
	if (showWarn == 'hide') {
		$('.tcp-warn').hide();
	}
	});
	</script>");	 
}

add_to_footer("<script type='text/javascript'>
var locale_show = '".$locale['show_info']."';
var locale_hide = '".$locale['hide_info']."';
</script>");

//Member Area and Info Slider script
//add_to_footer("<script type='text/javascript' src='".THEME."js/infoslider.js'></script>");

//Smooth scroll to #anchor script
//add_to_footer("<script type='text/javascript' src='".THEME."js/scrolltopcontrol.js'></script>");

//Stop chrome's autocomplete from making your input fields that nasty yellow. Yuck.
//if (CHSCRIPT == 1) {
	//$js_input = file_get_contents(THEME."js/chromeinputcolor.js"); 
	//add_to_footer("<script type='text/javascript'>".$js_input."</script>");	//Inline script
//}

//Member Card
if (MCARD == 1) {
add_to_head("<link type='text/css' href='".THEME."css/basicw.css' rel='stylesheet' media='screen' />");
//add_to_footer("<script type='text/javascript' src='".THEME."js/jquery.simplemodal.min.js'></script>");
add_to_footer("<script type='text/javascript'>
jQuery(function ($) {
	var mCard = {
		init: function () {
			$('.profile-link').click(function (e) {
				e.preventDefault();

				//split the url by = and get the id number
				var parts = this.href.split('=');
				var trgt = parts[1];
				$.get(\"".THEME."membercard.php?lookup=\" + trgt, function (data) {
				//load the basic form using ajax
					//create a modal info with the data
					$(data).modal({
						overlayId: 'memberCard-overlay',
						containerId: 'memberCard-container',
						dataId: 'memberCard-data',
						closeClass: 'memberCard-close',
						overlayClose: true,
						opacity: 70,
						onOpen: mCard.open,
						onClose: mCard.close,
						position: ['18%' ]
					});
				});
			});
		},
		open: function (info) {
			info.overlay.fadeIn(100, function () {
				info.container.fadeIn(80, function () {});
				info.data.fadeIn(100, function () {});		
			});
		},
		close: function (info) {
				info.data.fadeOut(100, function () {});
				info.container.fadeOut(100, function () {});
						info.overlay.fadeOut(100, function () {
							$.modal.close();
						});
		}
	};
	mCard.init();
});
</script>");
}
?><script type='text/javascript'>
$(function() {

    var $sidebar   = $('#side-border-left'),
        $window    = $(window),
        $footer    = $('#footer-part'), 							// use your footer ID here
        offset     = $sidebar.offset(),								//CONSTANT
        foffset    = $footer.offset(),								//CONSTANT
        threshold  = foffset.top - $sidebar.height() - offset.top,	//CONSTANT // may need to tweak
		mheight 	= $('#main').height();							//CONSTANT
        topPadding = 40;
		//$sidebar.append('<span class="debug">0</span>');
	
    $window.scroll(function() {
        if (($window.scrollTop() - offset.top + $sidebar.height()) > mheight){
			//$(".debug").html('1');
            $sidebar.stop().animate({
             	marginTop: threshold
            });
        } else if ($window.scrollTop() > offset.top) {
			//$(".debug").html('2');
            $sidebar.stop().animate({
                marginTop: $window.scrollTop() - offset.top + topPadding
            });
        } else {
			//$(".debug").html('3');
            $sidebar.stop().animate({
                marginTop: '0px'
            });
        }
    });

});
</script><?php
}

//Render News
function render_news($subject, $news, $info) {
global $locale, $settings;
opentable($subject, "post", $info, "N");
	echo "<ul class='news-info'>\n";
	//Author
	echo "<li class='author'>".profile_link($info['user_id'], $info['user_name'], $info['user_status'])."</li>\n";
	//Date
	echo "<li class='date'>".showdate("%d %b %Y", $info['news_date'])."</li>\n";
	//Category
	echo "<li class='cat'>\n";
		if ($info['cat_id']) { echo "<a href='".BASEDIR."news_cats.php?cat_id=".$info['cat_id']."'>".$info['cat_name']."</a>\n";
	} else { echo "<a href='".BASEDIR."news_cats.php?cat_id=0'>".$locale['global_080']."</a>"; }
	echo "</li>\n";
	//Reads
	if ($info['news_ext'] == "y" || ($info['news_allow_comments'] && $settings['comments_enabled'] == "1")) {
	echo "<li class='reads'>\n";
		echo $info['news_reads'].$locale['global_074']; 
	echo "</li>\n";}
	//Comments
	if ($info['news_allow_comments'] && $settings['comments_enabled'] == "1") { echo "<li class='comments'><a ".(isset($_GET['readmore']) ? "class='scroll'" : "")." href='".BASEDIR."news.php?readmore=".$info['news_id']."#comments'>".$info['news_comments']."".($info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a></li>\n"; }
	echo "</ul>\n";
	//The message
	echo $info['cat_image'].$news;

	//Read more button
	if (!isset($_GET['readmore']) && $info['news_ext'] == "y")
	echo "<!--news_readmore-->
	<div class='read_more'>
		<a href='news.php?readmore=".$info['news_id']."'>
			<span>".$locale['global_072']."...</span>
		</a>
	</div>";
closetable();
}

//Render Articles
function render_article($subject, $article, $info) {
global $locale, $settings;
opentable($subject, "article", $info, "A");
	echo "<ul class='article-info'>\n";
	//Author
	echo "<li class='author'>".profile_link($info['user_id'], $info['user_name'], $info['user_status'])."</li>\n";
	//Date
	echo "<li class='date'>".showdate("%d %b %Y", $info['article_date'])."</li>\n";
	//Category
	echo "<li class='cat'>\n";
		if ($info['cat_id']) { echo "<a href='".BASEDIR."articles.php?cat_id=".$info['cat_id']."'>".$info['cat_name']."</a>\n";
	} else { echo "<a href='".BASEDIR."articles.php?cat_id=0'>".$locale['global_080']."</a>"; }
	echo "</li>\n";
	//Reads
	echo "<li class='reads'>".$info['article_reads'].$locale['global_074']."</li>\n";
	//Comments
	if ($info['article_allow_comments'] && $settings['comments_enabled'] == "1") { echo "<li class='comments'><a class='scroll' href='".BASEDIR."articles.php?article_id=".$info['article_id']."#comments'>".$info['article_comments'].($info['article_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a></li>\n"; }
	echo "</ul>\n";
	//The message
	echo ($info['article_breaks'] == "y" ? nl2br($article) : $article)."\n";
closetable();
}

//Render comments
function render_comments($c_data, $c_info){
		global $locale, $settings;
		opentable($locale['c100']);
		if (!empty($c_data)){
			echo "<div class='user-comments floatfix'>\n";
 			$c_makepagenav = '';
 			if ($c_info['c_makepagenav'] !== FALSE) { 
				echo $c_makepagenav = "<div style='text-align:center;margin-bottom:5px;'>".$c_info['c_makepagenav']."</div>\n"; 
			}
 			foreach($c_data as $data) {
				echo "<div id='c".$data['comment_id']."' class='comment'>\n";
					//User avatar
					if ($settings['comments_avatar'] == "1") { echo "<span class='user_avatar'>".$data['user_avatar']."</span>\n"; $noav = ""; } else { $noav = "noavatar"; }
					echo "<div class='tbl1 comment_wrap $noav'>";
					//Pointer tip
					if ($settings['comments_avatar'] == "1") { echo "<div class='pointer'><span></span></div>\n"; }
					//Options
					echo "<div class='comment-info'>";
					if ($data['edit_dell'] !== FALSE) { 
						echo "<div class='actions flright'>".$data['edit_dell']."\n</div>\n";
					}
					//Info
					echo "<a class='scroll' href='".FUSION_REQUEST."#c".$data['comment_id']."'>#".$data['i']."</a> |\n";
					echo "<span class='comment-name'>".$data['comment_name']."</span>\n";
					echo "<span class='small'>".$data['comment_datestamp']."</span></div>\n";
					//The message
					echo "<div class='comment-msg'>".$data['comment_message']."</div></div></div>\n";
			}

			echo $c_makepagenav;
			if ($c_info['admin_link'] !== FALSE) {
				echo "<div class='comment_admin flright'>".$c_info['admin_link']."</div>\n";
			}
			echo "</div>\n";
		} else {
			echo $locale['c101']."\n";
		}
		closetable();   
}

function itemoptions2($item_type, $item_id, $info) {
	global $locale, $aidlink; $res = "";
	if ($item_type == "N") {
		//Edit
		if (iADMIN && checkrights($item_type)) { $res .= "<span class='edit'><!--article_news_opts--> <a href='".ADMIN."news.php".$aidlink."&amp;action=edit&amp;news_id=".$item_id."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' width='16' height='16' style='border:0;' /></a></span>\n"; }
		//Print
		$res .= "<!--news_opts--><span class='print'><a href='print.php?type=N&amp;item_id=".$info['news_id']."'><img src='".get_image("printer")."' alt='".$locale['global_075']."' title='".$locale['global_075']."' width='20' height='16' style='border:0;' /></a></span>\n";
	} elseif ($item_type == "A") {
		//Edit
		if (iADMIN && checkrights($item_type)) { $res .= "<span class='edit'><!--article_admin_opts--> <a href='".ADMIN."articles.php".$aidlink."&amp;action=edit&amp;article_id=".$item_id."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' width='16' height='16' style='border:0;' /></a></span>\n"; }
		//Print
		$res .= "<!--article_opts--><span class='print'><a href='print.php?type=A&amp;item_id=".$info['article_id']."'><img src='".get_image("printer")."' alt='".$locale['global_075']."' title='".$locale['global_075']."' width='20' height='16' style='border:0;' /></a></span>\n";
	}
	return $res;
}

//Content Panels
function opentable($title, $custom_class="", $info="", $type="") {
global $p_data;
	//News and Articles IDs and Options(Edit/Print)
	$id = "";
	$options = "";
	if ($type == "N") {
		$id = "id='news-".$info['news_id']."'";
		$options = itemoptions2("N", $info['news_id'], $info);
	} elseif ($type == "A") {
		$id = "id='article-".$info['article_id']."'";
		$options = itemoptions2("A", $info['article_id'], $info);
	}

	//Wrapp panel in div with class based on panel name
	$class = $p_data['panel_filename'];
	if ($class == "" && $custom_class == "") {
		//Panel with no custom class and no panel name file
		$class = "panel";
	} elseif ($class == "" && $custom_class != "") {
		//Panel with custom class and no panel name file
		$class = $custom_class." panel";
	} else {
		//Panel with panel name file
		$class = str_replace("_panel", " panel", $class);
	}

	echo "<div ".$id." class='".$class."'>
			<h2 class='maincap'>
				<span>
					<span>
						<span class='title'>".$title."</span>";
						if ($info != "") {
							echo "<span class='options'>";
								echo $options;
							echo "</span>";
						}
					echo "</span>
				</span>
			</h2>\n";
	echo "<div class='contentbody clearfix'>";
}

function closetable() {
	echo "</div></div>\n";
}

//Side panels
function openside($title, $collapse = false, $state = "on") {
global $panel_collapse, $p_data; $panel_collapse = $collapse;
	//Wrapp panel in div with class based on panel name
	$class = $p_data['panel_filename'];
	if ($class == "") {	$class = "panel"; } else { $class = str_replace("_panel", " panel", $class); }

	echo "<div class='".$class."'>";
echo "<h2 class='panelcap'>
			<span>
				<span>
					<span class='title'>".$title."</span>\n";
					if ($collapse == true) {
						$boxname = str_replace(" ", "", $title);
						echo "<span class='switcherbutton flright'>".panelbutton($state, $boxname)."</span>\n";
					}
			echo "</span>
			</span>
		</h2>
<div class='panelbody clearfix'>\n";
if ($collapse == true) { echo panelstate($state, $boxname); }
}

function closeside() {
global $panel_collapse;

echo "</div>\n";
	echo "</div>\n";
if ($panel_collapse == true) { echo "</div>\n"; }
}

?>