<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }
/*-------------------------+
|	UPLOAD IMAGES TAB
+--------------------------*/
if ($image_files) {
	$image_count = count($image_files);
} else {
	$image_count = 0;
}
//Deleting images
if (isset($_GET['deli']) && in_array($_GET['deli'], $image_files)) {
	unlink($image_folder.stripinput($_GET['deli']));
	//Redirect
	redirect(FUSION_SELF.$aidlink."&status=deli#tab-3");
} elseif (isset($_POST['uploadimage'])) {
	$imgext = strrchr(strtolower($_FILES['myfile']['name']), ".");
	$imgname = stripfilename(strtolower(substr($_FILES['myfile']['name'], 0, strrpos($_FILES['myfile']['name'], "."))));
	$imgsize = $_FILES['myfile']['size'];
	$imgtemp = $_FILES['myfile']['tmp_name'];

	if (!in_array($imgext, $image_types)) { //Check the extension
		redirect(FUSION_SELF.$aidlink."&status=upn#tab-3");
	} elseif (is_uploaded_file($imgtemp)){
		//Save the image
		move_uploaded_file($imgtemp, $image_folder.$imgname.$imgext);
		@chmod($image_folder.$imgname.$imgext, 0644);
		//Redirect
		redirect(FUSION_SELF.$aidlink."&status=upy#tab-3");
	}
} else {
/*-------------------------+
|	UPLOAD IMAGES TAB HTML
+--------------------------*/
		echo "<div id='tab-3' class='tab_content'>";
		echo "<table cellpadding='0' cellspacing='0' width='100%' class='center'>\n";
		echo "<tr>
				<th colspan='5' class='tbl2 forum-caption'>
					<h3>".$locale['images']."</h3>
				</th>
				</tr>
				<tr>
					<td class='tbl2' width='25%'><b>".$locale['image']."</b></td>
					<td class='tbl2' width='25%'><b>".$locale['filename']."</b></td>
					<td class='tbl2'><b>".$locale['dimensions']."</b></td>
					<td class='tbl2'><b>".$locale['size']."</b></td>
					<td class='tbl2'><b>".$locale['opts']."</b></td>
				</tr>";

		if ($image_files) {
			for ($i=0; $i < $image_count; $i++) {
				if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
				$image = $image_folder.stripinput($image_files[$i]);
					echo "<tr>\n<td class='".$row_color."'><img width='165' class='flleft' src='".$image."' alt='".stripinput($image_files[$i])."' /></td>\n";
					echo "<td class='".$row_color."'><span class='flleft'>".$image_files[$i]."</span></td>";
						list($width, $height) = getimagesize($image);
					echo "<td class='".$row_color."'>".$width." &#735; ".$height." <span class='small'>px</span></td>";
					echo "<td class='".$row_color."'>".parsebytesize(filesize($image))."</td>";
					echo "<td align='center' width='4%' class='".$row_color."' style='white-space:nowrap'>\n";
					echo "<a href='".FUSION_SELF.$aidlink."&amp;deli=".$image_files[$i]."' onclick=\"return confirm('".$locale['delete_this'].$locale['image']."?');\"><img src='".IMAGES."no.png' width='16' height='16' alt='".$locale['delete']."' title='".$locale['delete']."' /></a></td>\n";
					echo "</tr>\n";
			}
		} else {
			echo "<tr><td colspan='5' align='center'><br />
							<span class='admin-message' style='padding: 4px 8px;background-image:none;'>".$locale['folder_e']."</span><br /><br />								
					</td></tr>\n";
		}
		echo "</table><br /><br />\n";
	opentable($locale['up_image']);
	echo "<form name='uploadform' method='post' action='".FUSION_SELF.$aidlink."' enctype='multipart/form-data'>\n";
	echo "<table cellpadding='0' cellspacing='0' width='350' class='center'>\n<tr>\n";
	echo "<td width='80' class='tbl'>".$locale['filename'].":</td>\n";
	echo "<td class='tbl'><input type='file' name='myfile' class='textbox' style='width:250px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td align='center' colspan='2' class='tbl'>\n";
	echo "<input type='submit' name='uploadimage' value='".$locale['up_image']."' class='button' /></td>\n";
	echo "</tr>\n</table>\n</form>\n";
	closetable();
}
echo "</div>";
?>