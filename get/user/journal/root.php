<?
//
// Pipecode - distributed social network
// Copyright (C) 2014-2016 Bryan Beicker <bryan@pipedot.org>
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU Affero General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU Affero General Public License for more details.
//
// You should have received a copy of the GNU Affero General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

include("story.php");
include("image.php");

$journal = item_request(TYPE_JOURNAL);
$journal_link = item_link(TYPE_JOURNAL, $journal["journal_id"], $journal);

if (!$journal["published"] && $zid != $auth_zid) {
	fatal("Not published");
}

$spinner[] = ["name" => "Journal", "link" => "/journal/"];
$spinner[] = ["name" => $journal["title"], "short" => $journal["short_code"], "link" => $journal_link];
if ($auth_zid === $zid) {
	$actions[] = ["name" => "Write", "icon" => "notepad", "link" => "/journal/write"];
}

print_header();

print_journal($journal["journal_id"]);
print_comments(TYPE_JOURNAL, $journal);

print_footer();
