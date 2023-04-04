<?php
	require_once("../../include/phpheader.php");

	$stmt = $db->prepare("SELECT * FROM bans WHERE ip = ?");
	$stmt->execute([$_SERVER["REMOTE_ADDR"]]);
	$ban = $stmt->fetch();

	if ($ban != null) {
		if ($ban["expires"] == 0 || $ban["expires"] > time()) {
			if ($ban["boards"] == "*" || in_array($_POST["board"], explode(",", $ban["boards"]), true)) {
				header("Location: /banned");
				exit();
			}
		}
	}

	$stmt = $db->prepare("SELECT * FROM posts WHERE ip = ? ORDER BY timestamp DESC LIMIT 1");
	$stmt->execute([$_SERVER["REMOTE_ADDR"]]);
	$lastpost = $stmt->fetch();

	if ($lastpost != null) {
		if (time() - $lastpost["timestamp"] < 30) {
			die("You can only post every 30 seconds.");
		}
	}

	$allowed_types = ["image/webp", "video/webm", "video/mp4", "audio/webm", "image/png", "image/jpeg", "image/gif"];

	$board = $_POST["board"];
	$type = $_POST["type"];
	if ($type == "reply") {
		$replyto = $_POST["replyto"];
	}

	$name = $_POST["name"];
	$title = $_POST["title"];
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

	if ($type == "reply" && !empty($title)) {
		error("Replies can't have titles.");
	}

	if (strlen($title) > 32) {
		error("Title is too long, keep it under 32 characters.");
	}

	if ($type == "reply" && empty($replyto)) {
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
			//window.location.replace("/<?php //print("$board/thread/$replyto"); ?>//")
        </script>
		<?php
	} else {
		?>
        <script>
			//window.location.replace("/<?php //print($board); ?>//")
        </script>
		<?php
	}