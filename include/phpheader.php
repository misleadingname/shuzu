<?php
$root = $_SERVER["DOCUMENT_ROOT"];

$request = $_SERVER['REQUEST_URI'];
$splitRequest = explode("/", $request);

$db = new PDO("sqlite:$root/db/shuzu.db");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);