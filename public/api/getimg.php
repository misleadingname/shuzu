<?php
require_once("../../include/phpheader.php");
$postId = $_GET["id"];
$thumb = $_GET["thumb"] ?? false;

error_reporting(0);

$stmt = $db->query("SELECT attachmenturl, mime FROM posts WHERE postid = ?");
$stmt->execute([$postId]);
$result = $stmt->fetch();

$attachmenturl = $result["attachmenturl"];
$mime = $result["mime"];

if($attachmenturl == null) {
	http_response_code(404);

	$img = fopen("$root/public/images/noimage.png", "rb");

	header("Content-Type: image/png");
	header("Content-Length: " . filesize("$root/public/images/noimage.png"));

	fpassthru($img);
	exit();
}

$file = "$root/public/usercontent/media/$attachmenturl";

if(file_exists($file)) {
	if($thumb == "true") {
		$file = $file . "_thumb.jpg";
	}

	$img = fopen($file, "r");

	header("Content-Type: $mime");
	header("Content-Length: " . filesize($file));

	fpassthru($img);
} else {
	http_response_code(404);

	$img = fopen("$root/public/images/noimage.png", "r");

	header("Content-Type: image/png");
	header("Content-Length: " . filesize("$root/public/images/noimage.png"));

	fpassthru($img);
}