<?php
$root = $_SERVER["DOCUMENT_ROOT"];

$request = $_SERVER['REQUEST_URI'];
$splitRequest = explode("/", $request);

if(!file_exists("$root/config.php")) {
	die("Can't find the configuration file!<br>Shuzu isn't configured!<br><br>please copy the config.default.php to config.php and edit it!");
}

require_once("$root/config.php");

if(!file_exists("$root/db/")) {
	error_log("/db/ doesn't exist! creating one...");
	mkdir("$root/db/", 0755, true);
}

$db = new PDO("sqlite:$root/db/shuzu.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);