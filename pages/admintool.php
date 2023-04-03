<?php

require_once("../include/phpheader.php");

function info($message)
{
	print("<span><b>INFO:</b> $message</span><br>");
}

function log_error($message)
{
	print("<p><b>error:</b> $message</p><h3>[ <a href=\"/admintool.php\">back</a> ]</h3><h3>[ <a href=\"" . $_SERVER['REQUEST_URI'] . "\">retry</a> ]</h3>");
	exit();
}

function success($message)
{
	print(">back</a> ]</h3>");
	exit();
}

if (!isset($_SERVER["PHP_AUTH_USER"]) || !isset($_SERVER["PHP_AUTH_PW"]) || $_SERVER["PHP_AUTH_USER"] != $ADMIN_USER || $_SERVER["PHP_AUTH_PW"] != $ADMIN_PASSWORD) {
	header("WWW-Authenticate: Basic realm=\"shuzuAdminTool\"");
	http_response_code(401);
	$httpStatus = 401;
	require_once("$root/pages/error.php");
	exit();
} else {

	if (!empty($_POST["action"])) {
		?>
		<div class="box">
			<div class="boxbar">
				<h3>working</h3>
			</div>
			<div class="boxinner">
				<?php
				switch ($_POST["action"]) {
					case "delete board":
						info("deleting board");
						if ($_POST["confirm"] != "YES DO AS I SAY") {
							log_error("powerful action not confirmed");
							break;
						}
						info("action confirmed");
						if ($_POST["url"] == null || $_POST["url"] == "") {
							log_error("no name given");
							break;
						}

						$url = $_POST["url"];

						$stmt = $db->prepare("SELECT * FROM boards WHERE url = ?");
						$stmt->execute(array($url));
						$board = $stmt->fetch();
						if ($board["url"] == "") {
							log_error("board.php does not exist");
							break;
						}

						$stmt = $db->prepare("DELETE FROM boards WHERE url=?");
						info("prepared stmt");
						$stmt->bindParam(1, $url);
						info("bound parameter");

						$stmt->execute();
						$result = $stmt->fetchAll();
						if($result == null) {
							success("board.php deleted");
						} else {
							log_error("board.php doesn't exist or something went wrong");
						}
					case "create board":
						info("creating board");
						if ($_POST["url"] == null || $_POST["url"] == "") {
							log_error("no name given");
							break;
						}
						if ($_POST["description"] == null || $_POST["description"] == "") {
							log_error("no description given");
							break;
						}

						$url = strtolower($_POST["url"]);
						$description = $_POST["description"];
						$nsfw = intval($_POST["nsfw"]);

						info($nsfw);

						$stmt = $db->prepare("INSERT INTO boards (url, desc, nsfw) VALUES (?, ?, ?)");
						info("prepared stmt");
						$stmt->bindParam(1, $url);
						$stmt->bindParam(2, $description);
						$stmt->bindParam(3, $nsfw);
						info("bound parameters");

						$stmt->execute();
						$result = $stmt->fetch();
						if(empty($result)) {
							success("board.php created");
						} else {
							log_error("board.php already exists or something went wrong");
						}
						break;

					case "wipe every board":
						info("wiping every board");
						if ($_POST["confirm"] != "YES DO AS I SAY") {
							log_error("powerful action not confirmed");
							break;
						}
						info("action confirmed");
						// delete every entry from posts
						$stmt = $db->prepare("DELETE FROM posts");
						$stmt->execute();
						$result = $stmt->fetch();
						if(empty($result)) {
							success("all posts deleted");
						} else {
							log_error("something went wrong");
						}
						break;
					case "nuke":
						info("nuking everything");
						if ($_POST["confirm"] != "YES DO AS I SAY") {
							log_error("powerful action not confirmed");
							break;
						}
						info("action confirmed");
						if (isset($db)) {
							info("connected to a database, disconnecting...");
							$db = null;
							info("disconnected");
						}
						if (file_exists("$root/db/shuzu.db")) {
							info("deleting \"$root/db/shuzu.db\"");
							if (unlink("$root/db/shuzu.db")) {
								info("database deleted");
							} else {
								log_error("unable to delete the database");
							}
						} else {
							info("database does not exist");
						}
						info("creating new database");
						$db = new PDO("sqlite:$root/db/shuzu.db");
						info("created successfully");
						$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$stmt = $db->prepare('CREATE TABLE "boards" (
							"url"	TEXT NOT NULL UNIQUE,
							"desc"	TEXT NOT NULL,
							"nsfw"	INTEGER NOT NULL,
							PRIMARY KEY("url")
						)');
						info("prepared stmt (create boards)");
						$stmt->execute();
						$result = $stmt->fetch();
						info("executing SQL");
						if (empty($result)) {
							info("executed successfully");
						} else {
							log_error("unable to execute SQL");
						}
						$stmt = $db->prepare('CREATE TABLE "posts" (
							"boardurl"	TEXT NOT NULL,
							"type"	TEXT NOT NULL,
							"postid"	INTEGER NOT NULL,
							"timestamp"	INTEGER NOT NULL DEFAULT "CURRENT_TIMESTAMP",
							"ip"	TEXT NOT NULL,
							"title"	INTEGER,
							"name"	TEXT NOT NULL,
							"text"	TEXT NOT NULL,
							"attachmenturl"	TEXT,
							"size"	INTEGER,
							"filename"	TEXT,
							"mime"	TEXT,
							"replyto"	INTEGER,
							PRIMARY KEY("postid" AUTOINCREMENT)
						)');
						info("prepared stmt (create posts)");
						$stmt->execute();
						$result = $stmt->fetch();
						info("executing SQL");
						if (empty($result)) {
							info("executed successfully");
						} else {
							log_error("unable to execute SQL");
						}
						success("nuked successfully");
						break;
					case "update databases":
						if ($_POST["confirm"] != "YES DO AS I SAY") {
							log_error("powerful action not confirmed");
							break;
						}
						info("action confirmed");
						$stmt = $db->prepare('SELECT name FROM sqlite_master WHERE name="boards"');
						info("prepared stmt");
						$stmt->execute();
						$result = $stmt->fetch();
						info("executing SQL");
						if(empty($result)) {
							info("[board] doesn't exist. creating...");

							$stmt = $db->prepare('CREATE TABLE "boards" (
							"url"	TEXT NOT NULL UNIQUE,
							"desc"	TEXT NOT NULL,
							"nsfw"	INTEGER NOT NULL,
							PRIMARY KEY("url")
						)');
							info("prepared stmt (create boards)");
							$stmt->execute();
							$result = $stmt->fetch();
							info("executing SQL");
							if (empty($result)) {
								info("executed successfully");
							} else {
								log_error("unable to execute SQL");
							}
						} else {
							info("[board] exists");
						}

						$stmt = $db->prepare('SELECT name FROM sqlite_master WHERE name="posts"');
						info("prepared stmt");
						$stmt->execute();
						$result = $stmt->fetch();
						info("executing SQL");
						if(empty($result)) {
							info("[posts] doesn't exist. creating...");

							$stmt = $db->prepare('CREATE TABLE "posts" (
							"boardurl"	TEXT NOT NULL,
							"type"	TEXT NOT NULL,
							"postid"	INTEGER NOT NULL,
							"timestamp"	INTEGER NOT NULL DEFAULT "UNIXEPOCH()",
							"ip"	TEXT NOT NULL,
							"title"	INTEGER,
							"name"	TEXT NOT NULL,
							"text"	TEXT NOT NULL,
							"attachmenturl"	TEXT,
							"size"	INTEGER,
							"filename"	TEXT,
							"mime"	TEXT,
							"replyto"	INTEGER,
							PRIMARY KEY("postid" AUTOINCREMENT)
						)');
							info("prepared stmt (create posts)");
							$stmt->execute();
							$result = $stmt->fetch();
							info("executing SQL");
							if (empty($result)) {
								info("executed successfully");
							} else {
								log_error("unable to execute SQL");
							}
						} else {
							info("[posts] exists");
						}

						$stmt = $db->prepare('SELECT name FROM sqlite_master WHERE name="ipman"');
						info("prepared stmt");
						$stmt->execute();
						$result = $stmt->fetch();
						info("executing SQL");
						if(empty($result)) {
							info("[ipman] doesn't exist. creating...");
							//TOOD: what tables should banned user managment have?
						} else {
							info("[ipman] exists");
						}
						break;
					case "update databases":
						if ($_POST["confirm"] != "YES DO AS I SAY") {
							log_error("powerful action not confirmed");
							break;
						}
						info("action confirmed");
						$stmt = $db->prepare('SELECT name FROM sqlite_master WHERE name="boards"');
						info("prepared stmt");
						$stmt->execute();
						$result = $stmt->fetch();
						info("executing SQL");
						if(empty($result)) {
							info("[board] doesn't exist. creating...");

							$stmt = $db->prepare('CREATE TABLE "boards" (
							"url"	TEXT NOT NULL UNIQUE,
							"desc"	TEXT NOT NULL,
							"nsfw"	INTEGER NOT NULL,
							PRIMARY KEY("url")
						)');
							info("prepared stmt (create boards)");
							$stmt->execute();
							$result = $stmt->fetch();
							info("executing SQL");
							if (empty($result)) {
								info("executed successfully");
							} else {
								log_error("unable to execute SQL");
							}
						} else {
							info("[board] exists");
						}

						$stmt = $db->prepare('SELECT name FROM sqlite_master WHERE name="posts"');
						info("prepared stmt");
						$stmt->execute();
						$result = $stmt->fetch();
						info("executing SQL");
						if(empty($result)) {
							info("[posts] doesn't exist. creating...");

							$stmt = $db->prepare('CREATE TABLE "posts" (
							"boardurl"	TEXT NOT NULL,
							"type"	TEXT NOT NULL,
							"postid"	INTEGER NOT NULL,
							"timestamp"	INTEGER NOT NULL DEFAULT "UNIXEPOCH()",
							"ip"	TEXT NOT NULL,
							"title"	INTEGER,
							"name"	TEXT NOT NULL,
							"text"	TEXT NOT NULL,
							"attachmenturl"	TEXT,
							"size"	INTEGER,
							"filename"	TEXT,
							"mime"	TEXT,
							"replyto"	INTEGER,
							PRIMARY KEY("postid" AUTOINCREMENT)
						)');
							info("prepared stmt (create posts)");
							$stmt->execute();
							$result = $stmt->fetch();
							info("executing SQL");
							if (empty($result)) {
								info("executed successfully");
							} else {
								log_error("unable to execute SQL");
							}
						} else {
							info("[posts] exists");
						}

						$stmt = $db->prepare('SELECT name FROM sqlite_master WHERE name="ipman"');
						info("prepared stmt");
						$stmt->execute();
						$result = $stmt->fetch();
						info("executing SQL");
						if(empty($result)) {
							info("[ipman] doesn't exist. creating...");
							//TOOD: what tables should banned user managment have?
						} else {
							info("[ipman] exists");
						}
						break;
					default:
						log_error("unknown action");
						break;
				}
				?>
			</div>
		</div>
		<?php
		require_once("$root/include/footer.php");
	} else {
		require_once("$root/include/header.php");
		?>
		<div class="box">
			<div class="boxbar">
				<h3>board managment</h3>
			</div>
			<div class="boxinner">
			<h1>admintool is deprecated</h1>
			<p>admintool is deprecated. please use the new <a href="admintools">admintools</a> instead.</p>
				<form method="POST">
					<fieldset>
						<legend>new board</legend>
						<input type="text" name="url" placeholder="url">
						<input type="text" name="description" placeholder="description">
						<input type="checkbox" name="nsfw" value="1">
						<label for="nsfw">nsfw</label>
						<input type="submit" name="action" value="create board">
					</fieldset>
				</form>

				<form method="POST">
					<fieldset>
						<legend>delete board</legend>
						<input type="text" name="url" placeholder="url">
						<input type="text" name="confirm" placeholder="'YES DO AS I SAY'">
						<input type="submit" name="action" value="delete board">
					</fieldset>
				</form>

				<form method="POST">
					<fieldset>
						<legend>edit description</legend>
						<input type="text" name="url" placeholder="url">
						<input type="text" name="confirm" placeholder="description">
						<input type="submit" name="action" value="edit board">
					</fieldset>
				</form>
			</div>
		</div>

		<div class="box">
			<div class="boxbar">
				<h3>ip managment</h3>
			</div>
			<div class="boxinner">
				<h1>W.I.P</h1>
			</div>
		</div>

		<div class="box">
			<div class="boxbar">
				<h3>!!! NUCLEAR OPTIONS !!!</h3>
			</div>
			<div class="boxinner">
				<form method="POST">
					<fieldset>
						<legend>wipe every board</legend>
						<input type="text" name="confirm" placeholder="'YES DO AS I SAY'">
						<input type="submit" name="action" value="wipe every board">
					</fieldset>
				</form>

				<form method="POST">
					<fieldset>
						<legend>nuke</legend>
						<input type="text" name="confirm" placeholder="'YES DO AS I SAY'">
						<input type="submit" name="action" value="nuke">
					</fieldset>
				</form>

				<form method="POST">
					<fieldset>
						<legend>update databases</legend>
						<p>Possibly destructive, use with caution.<br>checks the database for missing tables and creates them.</p>
						<input type="text" name="confirm" placeholder="'YES DO AS I SAY'">
						<input type="submit" name="action" value="update databases">
					</fieldset>
				</form>
			</div>
		</div>
		<?php
		require_once("$root/include/footer.php");
	}
}
