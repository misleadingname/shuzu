<?php
require_once("../../../include/phpheader.php");
require_once("./verify.php");

$ip = $_POST["ip"];
$boards = $_POST["boards"];
$reason = $_POST["reason"];
$expires = $_POST["expires"];

if (!filter_var($ip, FILTER_VALIDATE_IP)) {
	die("Invalid IP address.");
}

$stmt = $db->prepare("SELECT * FROM bans WHERE ip = ?");
$stmt->execute([$ip]);
$banned = $stmt->fetch();

if ($banned != null && $banned["expires"] == 0 || $banned["expires"] > time()) {
	die("This IP address is already banned.");
}

if ($boards != "*") {
	$boards = explode(",", $boards);
	$boards = array_map(function($board) {
		return trim($board);
	}, $boards);
	$boards = implode(",", $boards);
}

if ($expires == "") {
	$expires = 0;
} else {
	$expires = strtotime($expires);
}

$stmt = $db->prepare("INSERT INTO bans (ip, timestamp, boards, reason, expires) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$ip, time(), $boards, $reason, $expires]);

header("Location: /admintools/bans");