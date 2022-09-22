<?php
	require_once("../../include/phpheader.php");

	function errimg($roote) {
		http_response_code(404);

		$img = fopen("$roote/images/noimage.png", "r");

		header("Content-Type: image/png");
//		header("Content-Length: " . filesize("$roote/images/noimage.png"));

		fpassthru($img);
	}

	$postId = htmlspecialchars($_GET["id"]);
	$thumb = $_GET["thumb"] ?? false;

	if($postId == null || $postId == "") {
		errimg($root);
	}

	if(!is_numeric($postId)) {
		errimg($root);
	}

	$stmt = $db->query("SELECT attachmenturl, mime FROM posts WHERE postid = ?");
	$stmt->execute([$postId]);
	$result = $stmt->fetch();

	$attachmenturl = $result["attachmenturl"];
	$mime = $result["mime"];

	if($attachmenturl == null) {
		errimg($root);
	}

    $file = "$root/public/usercontent/media/$attachmenturl";

	if(file_exists($file)) {
		if($thumb == "true") {
			$file = $file . "_thumb.jpg";
		}

		$img = fopen($file, "r");

		header("Content-Type: $mime");
//		header("Content-Length: " . filesize($attachmenturl));

		fpassthru($img);
	} else {
		errimg($root);
	}