<?php
	session_start();

	require_once("../../include/phpheader.php");

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
		if (!file_exists("$root/public/usercontent/media/")) {
			error_log("media directory doesn't exist! creating one...", 0);
			if (!mkdir("$root/public/usercontent/media", 0755, true)) {
				error("Internal server error, NOT SHUZHU'S FAULT! THIS ISN'T A BUG!");
				exit();
			}
		}

        $hash = sha1_file($uploadedfile["tmp_name"]);
		$ext = strtolower(pathinfo($uploadedfile["name"], PATHINFO_EXTENSION));
		$target = "$root/public/usercontent/media/" . $hash . ".$ext";
		move_uploaded_file($uploadedfile["tmp_name"], $target);

		thumbnail($target);
	}

	$stmt = $db->prepare("INSERT INTO posts (boardurl, type, timestamp, name, ip, title, text, attachmenturl, size, filename, mime, replyto) VALUES (:boardurl, :type, :timestamp, :name, :ip, :title, :text, :attachmenturl, :size, :filename, :mime, :replyto)");

	$stmt->execute([
            "boardurl" => $board,
            "type" => $type,
            "timestamp" => time(),
            "replyto" => $type == "reply" ? $replyto : null,
            "ip" => $_SERVER['REMOTE_ADDR'],
            "name" => $name,
            "title" => $title,
            "text" => $content,
            "size" => $uploadedfile['size'],
            "filename" => $uploadedfile["name"],
            "mime" => $uploadedfile["type"],

            "attachmenturl" => $hash . ".$ext"
    ]);
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