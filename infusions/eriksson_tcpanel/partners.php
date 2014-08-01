<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }
/*-------------------------+
|	PARTNERS SETTINGS TAB
+--------------------------*/
//XML file path
$xmlfile = THEME."logos.xml";
$image_folder = THEME."partners/";

$image_types = array(
		".gif",
		".GIF",
		".jpeg",
		".JPEG",
		".jpg",
		".JPG",
		".png",
		".PNG"
	);
//File extension filter
function filter_ext($element) {
	global $image_types;
	$ext = strrchr($element, ".");
	if(!in_array($ext, $image_types)) return;

	return $element;
}
$image_files = makefilelist($image_folder, ".|..", true, "files", "php|js|ico|DS_Store|SVN");
//Filter files by extension
$image_files = array_filter($image_files, "filter_ext");
//Reset array keys
$image_files = array_values($image_files);

if (!file_exists($xmlfile)) {	//If XML file doesn't exists
	$err_mess = $locale['error_nofile'];
	} else {
	$xml = file_get_contents($xmlfile);
	$xmlstr = simplexml_load_string($xml);
	if (!$xmlstr) {	//If the file is malformed -- can't be loaded
		$err_mess = $locale['error_noload'];
		} else {
	$str_logos = $xmlstr->logo;
		//Put all logos in an array
		foreach ($str_logos as $str_logo) {
			$json = json_encode($str_logo);
			//Fix for returning empty nodes values as array
			$json = preg_replace("@\":\{\}@si", '":""', $json);
			$logos[] = json_decode($json,TRUE);			
		}
		//If any logos
		if (isset($logos)) {
			//Put ids in an array
			foreach ($logos as $logo) {		
				$id = $logo['@attributes'];
				$ids[] = $id['id'];
			}
		}

//Write to file function
function write_to_file($file, $data) {
	$oFile = fopen($file, 'w');
	if (flock($oFile, LOCK_EX)) {
		fwrite($oFile, $data);
		flock($oFile, LOCK_UN);
			} else {
		echo "Ooops! Something went wrong while atempting to write the file.<br />Please try again.";
	}
	fclose($oFile);	//Done writing, close file
}

//If 'action' string is set it has to be 'edit' or 'delete' and 'logo_id' must be set and have a valid value
if (isset($_GET['action']) && ($_GET['action'] != ("edit" || "delete") || !isset($_GET['logo_id']) || !isnum($_GET['logo_id']) || !in_array($_GET['logo_id'], $ids))) {
	redirect(FUSION_SELF.$aidlink);
}

/*-----------------+
|	Deleting logos
+------------------*/
if (isset($_GET['action']) && $_GET['action'] == "delete") {
	$dlogo = preg_replace("@\n	<logo id=\"".$_GET['logo_id']."\">(.*?)</logo>@si", "", $xml);
	//Save the file
	write_to_file($xmlfile, $dlogo);
	//Successfully deleted, redirect
	redirect(FUSION_SELF.$aidlink."&amp;status=del");
}

/*-----------------+
|	Editing logos
+------------------*/
if (isset($_GET['action']) && $_GET['action'] == "edit") {
	//Show properties of logo that is being edited
	foreach ($logos as $logo) {					//Loop thru all logos
			$id = $logo['@attributes']['id'];	//Get their ids
			if ($id == $_GET['logo_id']) { 		//Stop when the id of the logo being edited is found
				$image = $logo['image'];
				$link = $logo['link'];
				$title = $logo['title'];
				$target = $logo['target'];
		}
	}
		$id = $_GET['logo_id'];
	$formaction = FUSION_SELF.$aidlink."&amp;action=edit&amp;logo_id=".$id."#tab-2";
	$titlea = $locale['editing_logo'];
	$btn_class = "hide";
	$fld_class = "show";
	} else {
/*-----------------+
|	Adding logos
+------------------*/
	$image = "";
	$link = "http://";
	$target = "0";
	$title = "";

	$formaction = FUSION_SELF.$aidlink."#tab-2";
	$titlea = $locale['new_logo'];
	$btn_class = "show";
	$fld_class = "hide";
}

//Build images list options
$image_list = "<option value='' style='font-style:italic'>".$locale['sel_img']."</option>\n";
$image_list .= makefileopts($image_files, $image);

/*-----------------+
|	Saving logo
+------------------*/
if (isset($_POST['save_logo'])) {
	$str_image = stripinput($_POST['logo_image']);
	$str_link = stripinput($_POST['logo_link']);
	$str_target = "0";
		//Check if the checkbox is checked and it's value is 1
		if (isset($_POST['link_target']) && $_POST['link_target'] == "1") {$str_target = stripinput($_POST['link_target']);};
	$str_title = stripinput($_POST['logo_title']);

	//Show warning if image is invalid
	if (!in_array($str_image, $image_files)) {
		$btn_class = "hide";
		$fld_class = "show";
		$err_mess = $locale['invalid_img'];
	} else {	
		/////////////////////////////////////////
		// If editing the logo, update the entry
		/////////////////////////////////////////
		if (isset($_GET['action']) && $_GET['action'] == "edit") {
				//Build logo properties nodes
				$nlogo = "<logo id=\"".$_GET['logo_id']."\">\n";
				$nlogo .= "		<image>".$str_image."</image>\n";
				$nlogo .= "		<link>".$str_link."</link>\n";
				$nlogo .= "		<title>".$str_title."</title>\n";
				$nlogo .= "		<target>".$str_target."</target>\n";
				$nlogo .= "	</logo>";
				//Update the entry
				$nlogo = preg_replace("@<logo id=\"".$_GET['logo_id']."\">(.*?)</logo>@si", $nlogo, $xml);
				//Save the file
				write_to_file($xmlfile, $nlogo);
				//Successfully updated, redirect
				redirect(FUSION_SELF.$aidlink."&amp;status=su#tab-2");
		} else {
		////////////////////////////////////////
		// If not editing add new entry
		////////////////////////////////////////
		//Get the last logo id from file
		if (preg_match("#(?:.*<logo id=\")(.*)(\">.*)#si", $xml, $lastid)) {
			$newid = $lastid[1]+1;	//And increase id by 1
			} else {
			$newid = 1;	//If no id found make it 1
			}
		//Build logo properties nodes
		$nlogo = "	<logo id=\"".$newid."\">\n";
		$nlogo .= "		<image>".$str_image."</image>\n";
		$nlogo .= "		<link>".$str_link."</link>\n";
		$nlogo .= "		<title>".$str_title."</title>\n";
		$nlogo .= "		<target>".$str_target."</target>\n";
		$nlogo .= "	</logo>\n";
		$nlogo .= "</Logos>";
		//Insert logo into the file
		$nlogo = preg_replace("@</Logos>@si", $nlogo, $xml);
		//Save the file
		write_to_file($xmlfile, $nlogo);
		//Successfully added, redirect
		redirect(FUSION_SELF.$aidlink."&amp;status=sn#tab-2");
		}
	}
}

/*-------------------------+
|	PARTNERS LOGOS TAB HTML
+--------------------------*/
echo "<div id='tab-2' class='tab_content'>";
//Logos
echo "<div class='clearfix'>
			<table align='left' width='100%' cellpadding='3' cellspacing='0'>
			<tr>
				<th colspan='5' class='tbl2 forum-caption'>
					<h3>".$locale['p_logos']."</h3>
				</th>
			</tr>
			<tr>
				<td class='tbl2' width='15%'><b>".$locale['preview']."</b></td>
				<td class='tbl2' width='30%'><b>".$locale['link']."</b></td>
				<td class='tbl2' width='10%' align='center'><b>".$locale['new_tab']."</b></td>
				<td class='tbl2' width='20%'><b>".$locale['title']."</b></td>
				<td class='tbl2' width='10%' align='center'><b>".$locale['opts']."</b></td>
			</tr>\n";
				//Check if file contains any logos
				if ($str_logos) {
					foreach ($logos as $logo) {
						$rid = $logo['@attributes']['id'];
						$rimage = $logo['image'];
						$rlink = $logo['link'];
						$rtitle = $logo['title'];
						$rtarget = $logo['target'];

				//Cell color
				$cell_color = ($i % 2 == 0 ? "tbl2" : "tbl1"); $i++;
				//While editig logo add highlighting to coresponding cell
				if (isset($_GET['action']) && $_GET['action'] == "edit" && $rid == $_GET['logo_id']) {
					$cell_color .= " editing";
				}
				echo "<tr>
						<td class='$cell_color'>
							<a href='".$rlink."' target='".$rtarget."' title='".$rtitle."'>
								<img width='165' src='".$image_folder.$rimage."' title='".$rtitle."' alt='Loading ".$rimage."' />
							</a>
						</td>
						<td class='$cell_color'><a href='".$rlink."' target='_blank'>".$rlink."</a></td>\n
						<td class='$cell_color' align='center'>".($rtarget=="1" ? $locale['yes'] : $locale['no'])."</td>\n
						<td class='$cell_color'>".$rtitle."</td>\n
						<td class='$cell_color' align='center'>
							<a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;logo_id=".$rid."#tab-2'><img src='".IMAGES."edit.png' width='16' height='16' alt='".$locale['edit']."' title='".$locale['edit']."' /></a>&nbsp;
							<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;logo_id=".$rid."#tab-2' onclick=\"return confirm('".$locale['delete_this'].$locale['logo']."?');\"><img src='".IMAGES."no.png' width='16' height='16' alt='".$locale['delete']."' title='".$locale['delete']."' /></a>
						</td>
						</tr>\n";
  				}
			//If no found logos show warn message
			} else {
			echo "<tr>
						<td colspan='5' align='center'><br />
							<span class='admin-message' style='padding: 4px 8px;background-image:none;'>".$locale['no_logos']."</span><br /><br />								
						</td>
				</tr>";
		}
		echo "</table></div>\n";

//Checkbox state
$checkboxCheckT = "";
if ($target == "1") {
	$checkboxCheckT = "checked='checked'";
}

//Add new logo button
echo "<br />";
echo "<div id='add_new' class='".$btn_class."' style='text-align:center'>".$locale['click_button']."<br />";
echo "<input type='button' name='add_logo' id='add_new_button' value='".$locale['new_logo']."' class='button' />";
echo "</div>";

//Logo inputs				
echo "<br />";
echo "<form id='add_logo' name='add_logo' method='post' action='".$formaction."' class='".$fld_class." clearfix'>
	<table align='left' cellspacing='0' cellpadding='0'>
			<tr>
				<th colspan='5' class='tbl2 forum-caption'>
						<h3>".$titlea."</h3>
				</th>
			</tr>
			<tr>
					<td class='tbl2' width='15%'><b>".$locale['image']."</b></td>
					<td class='tbl2' width='30%'><b>".$locale['link']."</b></td>
					<td class='tbl2' width='10%' align='center'><b>".$locale['new_tab']."</b></td>
					<td class='tbl2' width='20%'><b>".$locale['title']."</b></td>
			</tr>
			<tr>
				<td valign='top' class='tbl1'>
						<select name='logo_image' id='logo_image' class='textbox flleft' style='width:100%;'>".$image_list."</select>
						<br /><img id='logo_preview' class='flleft' src='".($image == "" ? "images/noimage.png" : $image_folder.$image)."' alt='' width='165' height='62' />
				</td>

				<td valign='top' class='tbl1'>
					<input type='text' name='logo_link' value='".$link."' class='textbox' style='width:300px;' />
				</td>

				<td valign='top' align='center' class='tbl1'>
					<input type='checkbox' name='link_target' id='link_target' title='".$locale['new_tab_des']."' ".$checkboxCheckT." value='".$target."' />
				</td>

				<td valign='top' class='tbl1'>
					<input type='text' name='logo_title' value='".$title."' class='textbox' style='width:180px;' />
				</td>
			</tr>

			<tr>
				<td align='center' colspan='4'><input type='submit' name='save_logo' value='".$locale['save_logo']."' class='button' /></td>
			</tr>
	</table>
</form>
</div>";
	}
}