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

require_mine();

$contact_id = $s2;
if (!string_uses($contact_id, "[0-9]")) {
	fatal("Invalid contact");
}
$contact = db_get_rec("contact", $contact_id);
if ($contact["zid"] !== $auth_zid) {
	fatal("Not your contact");
}

$name = http_post_string("name", array("len" => 50, "required" => false, "valid" => "[a-z][A-Z][0-9]-_. "));
$email = http_post_string("email", array("len" => 50, "required" => false, "valid" => "[a-z][A-Z][0-9]-_.@+ "));

$contact["name"] = $name;
$contact["email"] = $email;
db_set_rec("contact", $contact);

header("Location: /contact/");

