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

require_admin();

$spinner[] = ["name" => "Page", "link" => "/page/"];
$spinner[] = ["name" => "Add", "link" => "/page/add"];

print_header(["title" => "Add Page", "form" => true]);

writeln('<h1>' . get_text("Add Page") . '</h1>');

beg_tab();
print_row(array("caption" => "Title", "text_key" => "title"));
print_row(array("caption" => "Slug", "text_key" => "slug"));
end_tab();

box_right("Add");

print_footer(["form" => true]);
