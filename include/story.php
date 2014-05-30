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

function print_story($sid, $ipos = "right")
{
	global $server_name;
	global $auth_user;

	$story = db_get_rec("story", $sid);
	$pipe = db_get_rec("pipe", $story["pid"]);
	$topic = db_get_rec("topic", $story["tid"]);

	$a["story"] = $story["story"];
	$a["time"] = $story["time"];
	$a["sid"] = $sid;
	$a["ipos"] = $ipos;
	$a["topic"] = $topic["topic"];
	$a["icon"] = $story["icon"];
	$a["image_id"] = $story["image_id"];
	$a["tweet_id"] = $story["tweet_id"];
	$a["title"] = $story["title"];
	$a["pid"] = $story["pid"];
	$a["ipos"] = $ipos;
	$a["zid"] = $pipe["zid"];

	if ($sid > 0) {
		$row = run_sql("select count(cid) as comments from comment where sid = ?", array($sid));
		$a["comments"] = $row[0]["comments"];
	} else {
		$a["comments"] = 0;
	}

	print_article($a);
}


function print_article($a)
{
	global $server_name;
	global $auth_user;
	global $protocol;
	global $doc_root;

	if (array_key_exists("time", $a)) {
		$time = $a["time"];
	} else {
		$time = time();
	}
	if (array_key_exists("ipos", $a)) {
		$ipos = $a["ipos"];
	} else {
		$ipos = "right";
	}
	$zid = $a["zid"];
	if ($zid == "") {
		$by = "<b>Anonymous Coward</b>";
	} else {
		$by = "<a href=\"" . user_page_link($zid) . "\"><b>$zid</b></a>";
	}
	if (array_key_exists("sid", $a)) {
		$sid = $a["sid"];
	} else {
		$sid = 0;
	}
	if (array_key_exists("pid", $a)) {
		$pid = $a["pid"];
	} else {
		$pid = 0;
	}
	if (array_key_exists("comments", $a)) {
		$comments = $a["comments"];
	} else {
		$comments = 0;
	}
	if (array_key_exists("pid", $a)) {
		$pid = $a["pid"];
	} else {
		$pid = 0;
	}
	if (array_key_exists("score", $a)) {
		$score = $a["score"];
	} else {
		$score = 0;
	}
	if (array_key_exists("image_id", $a)) {
		$image_id = $a["image_id"];
	} else {
		$image_id = 0;
	}
	if (array_key_exists("tweet_id", $a)) {
		$tweet_id = $a["tweet_id"];
	} else {
		$tweet_id = 0;
	}
	$image_style = $auth_user["story_image_style"];
	$topic = $a["topic"];
	$story = $a["story"];
	$icon = $a["icon"];
	$title = $a["title"];
	$ctitle = clean_url($title);
	$date = date("Y-m-d H:i", $time);
	$day = gmdate("Y-m-d", $time);

	if ($image_style == 1) {
		// no image
		$image_path = "";
	} else if ($image_style == 2) {
		// icon
		$image_path = "/images/$icon-64.png";
		$image_url = "";
		$width = "64";
	} else {
		if ($image_id == 0) {
			$image_path = "";
		} else {
			$image = db_get_rec("image", $image_id);
			if ($image["aspect_width"] == 1 && $image["aspect_height"] == 1) {
				if ($image_style == 3) {
					$size = "160x160";
					$width = "160";
				} else if ($image_style == 4) {
					$size = "320x320";
					$width = "160";
				} else if ($image_style == 5) {
					$size = "320x320";
					$width = "320";
				} else if ($image_style == 6) {
					if ($image["has_640"]) {
						$size = "640x640";
						$width = "320";
					} else {
						$size = "320x320";
						$width = "320";
					}
				}
			} else if ($image["aspect_width"] == 4 && $image["aspect_height"] == 3) {
				if ($image_style == 3) {
					$size = "160x120";
					$width = "160";
				} else if ($image_style == 4) {
					$size = "320x240";
					$width = "160";
				} else if ($image_style == 5) {
					$size = "320x240";
					$width = "320";
				} else if ($image_style == 6) {
					if ($image["has_640"]) {
						$size = "640x480";
						$width = "320";
					} else {
						$size = "320x240";
						$width = "320";
					}
				}
			} else if ($image["aspect_width"] == 16 && $image["aspect_height"] == 9) {
				if ($image_style == 3) {
					$size = "160x90";
					$width = "160";
				} else if ($image_style == 4) {
					$size = "320x180";
					$width = "160";
				} else if ($image_style == 5) {
					$size = "320x180";
					$width = "320";
				} else if ($image_style == 6) {
					if ($image["has_640"]) {
						$size = "640x360";
						$width = "320";
					} else {
						$size = "320x180";
						$width = "320";
					}
				}
			} else {
				die("unknown aspect ratio");
			}
			$image_url = $image["parent_url"];
			$image_path = public_path($image["time"]) . "/i$image_id.$size.jpg";
		}
	}

	writeln("<article class=\"story\">");
	//writeln("	<h1><a href=\"/story/$day/$ctitle\">$title</a><img alt=\"$topic\" class=\"story_icon_$ipos\" src=\"/images/$icon-64.png\"/></h1>");
	writeln("	<h1><a href=\"/story/$day/$ctitle\">$title</a></h1>");
	writeln("	<h2>by $by in <a href=\"$protocol://$server_name/topic/$topic\"><b>$topic</b></a> on $date (<a href=\"/pipe/$pid\">#$pid</a>)</h2>");

	if ($image_path != "") {
		if ($image_url != "") {
			writeln("	<div><a href=\"$image_url\"><img alt=\"thumbnail\" style=\"float: right; margin-left: 8px; margin-bottom: 8px; width: $width" . "px\" src=\"$image_path\"/></a>$story</div>");
		} else {
			writeln("	<div><img alt=\"thumbnail\" style=\"float: right; margin-left: 8px; margin-bottom: 8px;\" src=\"$image_path\"/>$story</div>");
		}
	} else {
		writeln("	<div>$story</div>");
	}
	writeln("	<footer>");
	writeln('		<table class="fill">');
	writeln('			<tr>');
	if ($sid > 0) {
		writeln("				<td><a href=\"/story/$day/$ctitle\"><b>$comments</b> comments</a></td>");
		if (@$auth_user["editor"]) {
			writeln("				<td class=\"right\">");
			if ($tweet_id == 0) {
				if (is_file("$doc_root/images/tweet-16.png")) {
					writeln("					<a href=\"/story/$sid/tweet\" class=\"icon_16\" style=\"background-image: url('/images/tweet-16.png')\">Tweet</a> | ");
				} else {
					writeln("					<a href=\"/story/$sid/tweet\" class=\"icon_16\" style=\"background-image: url('/images/music-16.png')\">Tweet</a> | ");
				}
			}
			writeln("					<a href=\"/story/$sid/image\" class=\"icon_16\" style=\"background-image: url('/images/picture-16.png')\">Image</a> | ");
			writeln("					<a href=\"/story/$sid/edit\" class=\"icon_16\" style=\"background-image: url('/images/notepad-16.png')\">Edit</a>");
			writeln("				</td>");
		}
	} else if ($pid > 0) {
		writeln("				<td><b>$comments</b> comments</td>");
		writeln("				<td class=\"right\">score <b>$score</b></td>");
	}
	writeln('			</tr>');
	writeln('		</table>');
	writeln("	</footer>");
	writeln("</article>");
}

