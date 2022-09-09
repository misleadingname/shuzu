<?php
	require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phpheader.php");
	require_once("$root/include/func.php");

	function errimg($roote) {
//		http_response_code(404);

		$img = fopen("$roote/images/noimage.png", "r");

		header("Content-Type: image/png");
		header("Content-Length: " . filesize("$roote/images/noimage.png"));

		fpassthru($img);
	}

	$postId = htmlspecialchars($_GET["id"]);
	$thumb = htmlspecialchars($_GET["thumb"]);

	if($postId == null || $postId == "") {
		errimg($root);
	}

	if(!is_numeric($postId)) {
		errimg($root);
	}

	if($thumb == null || $thumb == "") {
		errimg($root);
	}

	$stmt = $db->query("SELECT attachmenturl, mime FROM posts WHERE postid = ?");
	$stmt->execute([$postId]);
	$result = $stmt->fetchAll();

	$attachmenturl = $result[0]["attachmenturl"];	// What the fuck???
	$mime = $result[0]["mime"];						// Why in the actual fuck does it need $result[0] for?! WHYYYY.

	if($attachmenturl == null) {
		errimg($root);
	}

	if(file_exists($attachmenturl)) {
		if($thumb == "true") {
			$attachmenturl = $attachmenturl . "_thumb.jpg";
		}

		$img = fopen($attachmenturl, "r");

		header("Content-Type: $mime");
		header("Content-Length: " . filesize($attachmenturl));

		fpassthru($img);
	} else {
		errimg();
	}