<?php
//Option to disable/enable cache [BUTTON]
//Option to merge files everytime and suppress writing cache file						[RADIO BTN]
//Option to merge and check the files everytime and update only if necesary				[RADIO BTN]
//Option to merge and check the files based on time interval and update only if necesary[RADIO BTN]
//Option to delete old .cache files when a new one is generated [CHECK BOX]
//Option to set expiry date [TEXT INPUT]
//Option to set the folder where to store cache file [TEXT INPUT]
//Option to Delete and Regenerate a new cache [BUTTON]


require_once "../../maincore.php";
	// Written by Ed Eliot (www.ejeliot.com) - provided as-is, use at your own risk
	// Modified by JoiNNN for use with Eriksson Theme for Sverige NSS (http://www.php-fusion.se)
	/****************** start of config ******************/
	define('FILE_TYPE', 'text/javascript'); 	// type of code we're outputting
	define('CACHE_LENGTH', 3135600); 			// length of time to cache output file, default approx 1 month
	define('CREATE_ARCHIVE', true); 			// set to false to suppress writing of code archive, files will be merged on each request
	define('ARCHIVE_FOLDER', THEME.'_cache/');	// location to store archive

	require_once THEME."theme_settings.php";

	// files to merge
	$aFiles = array(
	'js/jquery.cookie.js',
	'js/infoslider.js',
	'js/scrolltopcontrol.js'
	);
	if (MCARD == 1) {
		$aFiles[] .= 'js/jquery.simplemodal.min.js';
	}
	if (CHSCRIPT == 1) {
		$aFiles[] .= 'js/chromeinputcolor.js';
	}
	/****************** end of config ********************/
		$sCode = "";
		$sLastModified = array();
			foreach ($aFiles as $sFile) {
				if (file_exists(THEME.$sFile)) {
					$sLastModified[] = filemtime(THEME.$sFile);
					$sCode .= file_get_contents(THEME.$sFile);		// files content
				}
			}
		// sort dates, newest first
		rsort($sLastModified);
		$iETag = $sLastModified[0];
		$sLastModified = gmdate('D, d M Y H:i:s', $iETag)." GMT";
		// remove comments
		$sCode = trim(preg_replace(array("@/\*(.*?|[\r\n])?\*/|//(.*?)[\r\n]@si", "@\s+@si"), " ", $sCode));

		// files content length
		$aLen = strlen($sCode);
		// cached file content length
		$sLen = "";
		if (CREATE_ARCHIVE && file_exists(ARCHIVE_FOLDER.$iETag.".cache")) {
			$sLen = strlen(file_get_contents(ARCHIVE_FOLDER.$iETag.".cache"));	
		}

		// see if the user has an updated copy in browser cache
		if ($aLen == $sLen  && 
			((isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && $_SERVER['HTTP_IF_MODIFIED_SINCE'] == $sLastModified) ||
			(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $iETag))) {
				header("{$_SERVER['SERVER_PROTOCOL']} 304 Not Modified");
				exit;
			}

		if (CREATE_ARCHIVE) {
			// create the directory for storing cache files
			if (!is_dir(ARCHIVE_FOLDER)) {
				mkdir(ARCHIVE_FOLDER);
			}
			// get code from archive folder if exists and if has the same content length as the files, otherwise grab files, merge and save in archive folder
			if (file_exists(ARCHIVE_FOLDER.$iETag.".cache") && $aLen == $sLen) {
				$sCode = file_get_contents(ARCHIVE_FOLDER.$iETag.".cache");
			} else {
				$oFile = fopen(ARCHIVE_FOLDER.$iETag.".cache", 'w');
					if (flock($oFile, LOCK_EX)) {
						fwrite($oFile, $sCode);
						flock($oFile, LOCK_UN);
					}
				fclose($oFile);
				// check for old .cache files and delete them
				foreach(glob(ARCHIVE_FOLDER."*.cache") as $cFile){
					$olddate = time()-3600;
					if (filemtime($cFile)<$olddate) { unlink($cFile); }
				}
			}
		}
		// send HTTP headers to ensure aggressive caching
		header("Expires: ".gmdate('D, d M Y H:i:s', time() + CACHE_LENGTH)." GMT"); // 1 month from now
		header("Content-Type: ".FILE_TYPE);
		header("Content-Length: ".strlen($sCode));
		header("Last-Modified: ".$sLastModified);
		header("ETag: ".$iETag);
		header("Cache-Control: max-age=".CACHE_LENGTH);
		// output the code
		echo $sCode;
?>