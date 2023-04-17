<?php
	require_once("../../include/phpheader.php");

	$stmt = $db->prepare("SELECT * FROM bans WHERE ip = ? AND (expires > strftime('%s', 'now') OR expires = 0); LIMIT 1;");
	$stmt->execute([$_SERVER["REMOTE_ADDR"]]);
	$ban = $stmt->fetch();

	if ($ban != null) {
		if ($ban["boards"] == "*" || in_array($_POST["board"], explode(",", $ban["boards"]), true)) {
			header("Location: /banned");
			exit();
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
		$title = "";
	} else {
		$title = $_POST["title"];
	}

	if ($type == "reply") {
		$stmt = $db->prepare("SELECT * FROM posts WHERE postid = ?");
		$stmt->execute([$replyto]);
		$thread = $stmt->fetch();

		if ($thread == null) {
			die("Thread doesn't exist.");
		}

		if ($thread["locked"] == 1) {
			die("Thread is locked.");
		}
	}

	$name = $_POST["name"];
	$content = trim($_POST["content"]);

	$uploadedfile = $_FILES["attachment"];

	if ($uploadedfile["error"] != 0 && $type == "post") {
		die("Something went wrong with uploading the attachment.");
	}

	if (empty($name)) {
		die("No name specified.");
	} 

	if (empty($content)) {
		die("No content specified.");
	}

	if ($type == "reply" && !empty($title)) {
		die("Replies can't have titles.");
	}

	if (strlen($title) > 48) {
		die("Title is too long, keep it under 48 characters.");
	}

	if ($type == "reply" && empty($replyto)) {
		die("Nice inspect element.");
	}

	if ($type == "post" && $uploadedfile["error"] == 4) {
		die("No file attached to a thread.");
	}

	if ($uploadedfile["size"] / 1000 > 3000) {
		die("File over 3MB.");
	}

	if (!in_array($uploadedfile["type"], $allowed_types, true) && $uploadedfile["error"] != 4) {
		die("Incorrect file type.");
	}

	if ($uploadedfile["error"] == 0) {
		if (!file_exists("$root/public/usercontent/media/")) {
			error_log("media directory doesn't exist! creating one...", 0);
			if (!mkdir("$root/public/usercontent/media", 0755, true)) {
				die("Internal server error, NOT SHUZHU'S FAULT! THIS ISN'T A BUG!");
				exit();
			}
		}

        $hash = sha1_file($uploadedfile["tmp_name"]);
		$ext = strtolower(pathinfo($uploadedfile["name"], PATHINFO_EXTENSION));
		$target = "$root/public/usercontent/media/" . $hash . ".$ext";
		move_uploaded_file($uploadedfile["tmp_name"], $target);

		thumbnail($target);
	} else {
		$hash = null;
		$ext = null;
	}

	$stmt = $db->prepare("SELECT * FROM boards WHERE url = ? LIMIT 1");
	$stmt->execute([$board]);
	$board = $stmt->fetch();

	if ($board == null) {
		die("Board doesn't exist.");
	}

	$stmt = $db->prepare("SELECT * FROM posts WHERE boardurl = ? ORDER BY timestamp DESC, 1");
	$stmt->execute([$board['url']]);
	$posts = $stmt->fetchAll();

	if (count($posts) > 100) {
		$oldest = $posts[count($posts) - 1];
		$oldestid = $oldest["postid"];
		$oldesthash = $oldest["attachmenturl"];
		$oldestext = pathinfo($oldesthash, PATHINFO_EXTENSION);
		$oldesthash = str_replace(".$oldestext", "", $oldesthash);

		$stmt = $db->prepare("DELETE FROM posts WHERE postid = ?");
		$stmt->execute([$oldestid]);

		if (file_exists("$root/public/usercontent/media/$oldesthash.$oldestext")) {
			unlink("$root/public/usercontent/media/$oldesthash.$oldestext");
		}

		if (file_exists("$root/public/usercontent/media/$oldesthash.thumb.$oldestext")) {
			unlink("$root/public/usercontent/media/$oldesthash.thumb.$oldestext");
		}
	}

	$stmt = $db->prepare("INSERT INTO posts (boardurl, type, timestamp, name, ip, title, text, attachmenturl, size, filename, mime, replyto, sticky, locked) VALUES (:boardurl, :type, :timestamp, :name, :ip, :title, :text, :attachmenturl, :size, :filename, :mime, :replyto, :sticky, :locked)");
	$stmt->execute([
            "boardurl" => $board['url'],
            "type" => $type,
            "timestamp" => time(),
            "replyto" => $type == "reply" ? $replyto : null,
            "ip" => $_SERVER['REMOTE_ADDR'],
            "name" => $name,
            "title" => $title,
            "text" => $content,
            "size" => $uploadedfile['size'] ?? null,
            "filename" => $uploadedfile["name"] ?? null,
            "mime" => $uploadedfile["type"] ?? null,
            "attachmenturl" => $hash . ".$ext" ?? null,
			"sticky" => 0,
			"locked" => 0,
    ]);
	$result = $stmt->fetchAll();

	if ($result != null) {
		die("Unknown error.");
	} else {
		print("Posted!");
	}

	$stmt = $db->prepare("SELECT * FROM posts WHERE ip = ? ORDER BY timestamp DESC LIMIT 1");
	$stmt->execute([$_SERVER["REMOTE_ADDR"]]);
	$post = $stmt->fetch();

	$postid = $post["postid"];

	if ($type == "reply") {
		?>
        <script>
			window.location.replace("/<?php print("$board/thread/$replyto#$postid"); ?>")
        </script>
		<?php
	} else {
		?>
        <script>
			window.location.replace("/<?php print($board); ?>/thread/<?php print($postid); ?>")
        </script>
		<?php
	}