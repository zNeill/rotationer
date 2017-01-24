<?php

require("helpers.php");
require("connection.php");

$types = $db->query("SELECT group_type_id from group_type")->fetchAll(PDO::FETCH_COLUMN);

render("startform.php", ["types" => $types]);

?>