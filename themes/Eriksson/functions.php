<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }

set_image("pollbar", THEME."images/pollbar.gif");
set_image("edit", THEME."images/edit.png");
set_image("printer", THEME."images/printer.png");
set_image("link", THEME."images/link.png");
//Arrows
set_image("up", THEME."images/up.png");
set_image("down", THEME."images/down.png");
set_image("left", THEME."images/left.png");
set_image("right", THEME."images/right.png");
//Forum folders icons
set_image("folder", THEME."forum/folder.png");
set_image("foldernew", THEME."forum/foldernew.png");
set_image("folderlock", THEME."forum/folderlock.png");
set_image("stickythread", THEME."forum/stickythread.png");
//Forum buttons
set_image("reply", "reply");
set_image("newthread", "newthread");
set_image("web", "web");
set_image("pm", "pm");
set_image("quote", "quote");
set_image("forum_edit", "forum_edit");

// lines by Johan Wilson
function theme_output($output) {

	$search = array(
		"@><img src='reply' alt='(.*?)' style='border:0px' />@si",
		"@><img src='newthread' alt='(.*?)' style='border:0px;?' />@si",		
		"@><img src='web' alt='(.*?)' style='border:0;vertical-align:middle' />@si",
		"@><img src='pm' alt='(.*?)' style='border:0;vertical-align:middle' />@si",
		"@><img src='quote' alt='(.*?)' style='border:0px;vertical-align:middle' />@si",
		"@><img src='forum_edit' alt='(.*?)' style='border:0px;vertical-align:middle' />@si"
	);
	$replace = array(
		' class="button big"><img class="reply-button icon" src="'.THEME.'images/blank.gif" alt="" />$1',
		' class="button big"><img class="newthread-button icon" src="'.THEME.'images/blank.gif" alt="" />$1',
		' class="button" rel="nofollow" title="$1"><img class="web-button icon" src="'.THEME.'images/blank.gif" alt="" />Web',
		' class="button" title="$1"><img class="pm-button icon" src="'.THEME.'images/blank.gif" alt="" />PM',
		' class="button" title="$1"><img class="quote-button icon" src="'.THEME.'images/blank.gif" alt="" />$1',
		' class="negative button" title="$1"><img class="edit-button icon" src="'.THEME.'images/blank.gif" alt="" />$1'
	);
	$output = preg_replace($search, $replace, $output);

	return $output;
}

?>