<?php
require_once("../../../include/phpheader.php");
require_once("./verify.php");

$ip = $_GET["ip"];

$stmt = $db->prepare("SELECT * FROM bans WHERE ip = ?");
$stmt->execute([$ip]);
$banned = $stmt->fetch();

if ($banned == null) {
	die("This IP address is not banned.");
}

$stmt = $db->prepare("UPDATE bans SET expires = ? WHERE ip = ?");
$stmt->execute([1, $ip]);

header("Location: /admintools/bans");