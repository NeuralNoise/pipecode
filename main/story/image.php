<?
//
// Pipecode - distributed social network
// Copyright (C) 2014 Bryan Beicker <bryan@pipedot.org>
//
// This file is part of Pipecode.
//
// Pipecode is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Pipecode is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Pipecode.  If not, see <http://www.gnu.org/licenses/>.
//

set_time_limit(15 * 60);

include("image.php");

if (!@$auth_user["editor"]) {
	die("you are not an editor");
}

clean_tmp_images();
$sid = (int) $s2;
$story = db_get_rec("story", $sid);

if (http_post()) {
	$tmp_image_id = http_post_int("tmp_image_id");

	if ($tmp_image_id > 0) {
		$image_id = promote_image($tmp_image_id);
	}
	$story["image_id"] = $image_id;
	db_set_rec("story", $story);

	header("Location: /story/$sid");
	die();
}

$images = build_preview_images($story["story"]);

print_header();

writeln('<table class="fill">');
writeln('<tr>');
writeln('<td class="left_col">');
print_left_bar("main", "stories");
writeln('</td>');
writeln('<td class="fill">');

beg_form();
writeln('<h1>Select Image</h1>');

writeln('<label style="border: 1px solid #888888; border-radius: 4px; float: left; padding: 8px; margin-right: 8px; margin-bottom: 8px;">');
writeln('	<table>');
writeln('		<tr>');
writeln('			<td style="vertical-align: middle;"><input name="tmp_image_id" value="0" type="radio"/></td>');
writeln('			<td><img alt="thumbnail" src="/images/missing-128.png"/></td>');
writeln('		</tr>');
writeln('		<tr>');
writeln('			<td colspan="2" style="padding-top: 4px; text-align: center">No Image</td>');
writeln('		</tr>');
writeln('	</table>');
writeln('</label>');

for ($i = 0; $i < count($images); $i++) {
	$tmp_image = db_get_rec("tmp_image", $images[$i]);
	$path = public_path($tmp_image["time"]);

	writeln('<label style="border: 1px solid #888888; border-radius: 4px; float: left; padding: 8px; margin-right: 8px; margin-bottom: 8px;">');
	//writeln('	<div style="display: table;">');
	//writeln('		<div style="display: table-cell; vertical-align: middle;"><input name="tmp_image_id" value="' . $images[$i] . '" type="radio"/></div>');
	//writeln('		<img alt="thumbnail" src="' . $path . '/t' . $images[$i] . '.128x128.jpg" style="display: table-cell;"/>');
	//writeln('		<div style="text-align: center">' . $tmp_image["original_width"] . ' x ' . $tmp_image["original_height"] . '</div>');
	//writeln('	</div>');

	writeln('	<table>');
	writeln('		<tr>');
	writeln('			<td style="vertical-align: middle;"><input name="tmp_image_id" value="' . $images[$i] . '" type="radio"/></td>');
	writeln('			<td><img alt="thumbnail" src="' . $path . '/t' . $images[$i] . '.128x128.jpg"/></td>');
	writeln('		</tr>');
	writeln('		<tr>');
	writeln('			<td colspan="2" style="padding-top: 4px; text-align: center">' . $tmp_image["original_width"] . ' x ' . $tmp_image["original_height"] . '</td>');
	writeln('		</tr>');
	writeln('	</table>');
	writeln('</label>');
}

right_box("Continue");
end_form();

writeln('</td>');
writeln('</tr>');
writeln('</table>');

print_footer();
