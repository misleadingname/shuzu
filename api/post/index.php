<?php
	session_start();

	require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phpheader.php");
	require_once("$root/include/func.php");

	$allowed_types = ["image/webp", "video/webm", "video/mp4", "audio/webm", "image/png", "image/jpeg", "image/gif"];

//TODO: Implement captcha.

	$board = htmlspecialchars($_POST["board"]);
	$type = htmlspecialchars($_POST["type"]);
	if ($type == "reply") {
		$replyto = htmlspecialchars($_POST["replyto"]);
	}

	$name = htmlspecialchars($_POST["name"]);
	$title = htmlspecialchars($_POST["title"]);
	$content = trim($_POST["content"]);

	$uploadedfile = $_FILES["attachment"];

	if ($uploadedfile["error"] != 0 && $type == "post") {
		error("Something went wrong with uploading the attachment.");
	}

	if (empty($name)) {
		error("No name specified.");
	}

	if (empty($content)) {
		error("No content specified.");
	}

	if ($type == "reply" && $title != null || $type == "reply" && $title != "") {
		error("Replies can't have titles.");
	}

	if (strlen($title) > 32) {
		error("Title is too long, keep it under 32 characters.");
	}

	if ($type == "reply" && ($replyto == null || $replyto == "")) {
		error("Nice inspect element.");
	}

	if ($type == "post" && $uploadedfile["error"] == 4) {
		error("No file attached to a thread.");
	}

	if ($uploadedfile["size"] / 1000 > 3000) {
		error("File over 3MB.");
	}

	if (!in_array($uploadedfile["type"], $allowed_types, true) && $uploadedfile["error"] != 4) {
		error("Incorrect file type.");
	}

	if ($uploadedfile["error"] == 0) {
		if (!file_exists("$root/usercontent/media/")) {
			error_log("media directory doesn't exist! creating one...", 0);
			if (!mkdir("$root/usercontent/media", 0755, true)) {
				error("Internal server error, NOT SHUZHU'S FAULT! THIS ISN'T A BUG!");
				exit();
			}
		}

		$ext = strtolower(pathinfo($uploadedfile["name"], PATHINFO_EXTENSION));
		$target = "$root/usercontent/media/" . sha1_file($uploadedfile["tmp_name"]) . ".$ext";
		move_uploaded_file($uploadedfile["tmp_name"], $target);

		thumbnail($target);
	}

	$stmt = $db->prepare("INSERT INTO posts (boardurl, type, timestamp, name, ip, title, text, attachmenturl, size, filename, mime, replyto) VALUES (:boardurl, :type, :timestamp, :name, :ip, :title, :text, :attachmenturl, :size, :filename, :mime, :replyto)");

	$stmt->bindParam(":boardurl", $board);
	$stmt->bindParam(":type", $type);
	$stmt->bindParam(":timestamp", time());
	if ($type == "reply") {
		$stmt->bindParam(":replyto", $replyto);
	}

	$stmt->bindParam(":ip", $_SERVER['REMOTE_ADDR']);

	$stmt->bindParam(":name", $name);
	$stmt->bindParam(":title", $title);
	$stmt->bindParam(":text", $content);

	$stmt->bindParam(":attachmenturl", $target);
	$stmt->bindParam(":size", $uploadedfile["size"]);
	$stmt->bindParam(":filename", $uploadedfile["name"]);
	$stmt->bindParam(":mime", $uploadedfile["type"]);

	$stmt->execute();
	$result = $stmt->fetchAll();

	if ($result != null) {
		error("Unknown error.");
	} else {
		print("Posted!");
	}

	if ($type == "reply") {
		?>
        <script>
			window.location.replace("/<?php print("$board/thread/$replyto"); ?>")
        </script>
		<?php
	} else {
		?>
        <script>
			window.location.replace("/<?php print($board); ?>")
        </script>
		<?php
	}