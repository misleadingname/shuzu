<?php

print("Nuking database...<br><br>");

if (isset($db)) {
	print("Connected to the database, disconnecting...<br>");
	$db = null;
	print("Disconnected!<br>");
}

if (file_exists("$root/db/shuzu.db")) {
	print("Deleting <code>\"$root/db/shuzu.db\"</code><br>");
	if (unlink("$root/db/shuzu.db")) {
		print("Database deleted<br>");
	} else {
		die("Unable to delete the database");
	}
} else {
	print("Database does not exist<br>");
}

print("<br>Creating new database...<br>");
$db = new PDO("sqlite:$root/db/shuzu.db");
print("Created successfully<br>");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $db->prepare('CREATE TABLE "boards" (
	"url"	TEXT NOT NULL UNIQUE,
	"desc"	TEXT NOT NULL,
	"nsfw"	INTEGER NOT NULL,
	PRIMARY KEY("url")
)');
print("prepared STMT (create boards)<br>");

$stmt->execute();
$result = $stmt->fetch();

print("executing SQL<br>");
if (empty($result)) {
	print("Executed successfully<br>");
} else {
	die("Unable to execute SQL");
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
print("Prepared STMT (create posts)<br>");

$stmt->execute();
$result = $stmt->fetch();

print("Executing SQL<br>");
if (empty($result)) {
	print("Executed successfully<br>");
} else {
	die("Unable to execute SQL");
}

$stmt = $db->prepare('CREATE TABLE "bans" (
	"ip"	TEXT NOT NULL,
	"timestamp"	INTEGER NOT NULL DEFAULT "CURRENT_TIMESTAMP",
	"reason"	TEXT NOT NULL,
	"boards"	TEXT NOT NULL,
	"expires"	INTEGER NOT NULL,
	PRIMARY KEY("ip")
)');
print("Prepared STMT (create bans)<br>");

$stmt->execute();
$result = $stmt->fetch();

print("Executing SQL<br>");
if (empty($result)) {
	print("Executed successfully<br>");
} else {
	die("Unable to execute SQL");
}

print("<br>Success!");