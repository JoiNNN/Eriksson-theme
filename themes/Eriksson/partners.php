<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: partners.php
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

/* TODO: Store data directly as JSON instead of XML */

//	Multiple image rotator
$xmlfile = THEME."logos.xml";
if (file_exists($xmlfile)) {
	$xml = file_get_contents($xmlfile);
	$xmlstr = simplexml_load_string($xml);

	if ($xmlstr) { //Check if the file isn't malformed
	$str_logos = $xmlstr->logo;
	//If any logos found
	if ($str_logos) {
		//Logos to array
		foreach ($str_logos as $str_logo) {
			$json = json_encode($str_logo);
			//Fix for returning empty node values as array
			$json = preg_replace("@\":\{\}@si", '":""', $json);
			$logos[] = json_decode($json,TRUE);
		}
	// used some code from http://www.sitepoint.com/picture-showcase-php-html/
	// (c) 2004 David Pankhurst
	$folder = THEME."partners/";
	$total = count($logos); 
	$secondsFixed = LOGOS_FIXED; // seconds to keep list the same 
	$seedValue = (int)(time()/$secondsFixed); 
	srand($seedValue);
	for ($i = 0;$i < $total;++$i) // shuffle list 'randomly' 
	{
 		$r = rand(0,$total-1); 
 		$temp = $logos[$i]; 
 		$logos[$i] = $logos[$r]; 
 		$logos[$r] = $temp;
	}

	$i = $i%$total; // make sure index always in bounds
	$i++;

	$max = LOGOS_MAX; // number of logos to show
	if ($max != "0") {
		echo "<ul>";
		foreach ($logos as $logo) {
			if ($i <= $max) {
				$image = $logo['image'];
				$url = $logo['link'];
				$title = $logo['title'];
				$target = $logo['target'];

				// keep image proportions when adding width and height
				// code from http://www.php.net/manual/en/function.getimagesize.php#97564
				$max_width = 250; // max logo width
				$max_height = 62; // max logo height
				list($width, $height) = (file_exists($folder.$image) ? getimagesize($folder.$image) : array($max_width, $max_height)); // get image width and height but check first if image exists
				$ratioh = $max_height/$height;
				$ratiow = $max_width/$width;
				$ratio = min($ratioh, $ratiow);
				// new dimensions
				$width = intval($ratio*$width);
				$height = intval($ratio*$height);

				echo "<li><a href='".$url."' ".($target=="1" ? "target='_blank'" : "")." title='".$title."'><img src='".$folder.$image."' alt='".$title."' width='".$width."' height='".$height."' /></a></li>\n";
			$i++;
			}
		}
		echo "</ul>";
	}

}
}
}
?>
