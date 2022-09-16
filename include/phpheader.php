<?php
$root = $_SERVER["DOCUMENT_ROOT"];

require_once $root.'/config.php';

$request = $_SERVER['REQUEST_URI'];
$splitRequest = explode("/", $request);

if(!file_exists("$root/db/")) {
	error_log("/db/ doesn't exist! creating one...");
	mkdir("$root/db/", 0755, true);
}

$db = new PDO("sqlite:$root/db/shuzu.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);