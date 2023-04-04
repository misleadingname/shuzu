<?php

print("Creating board...<br>");

$stmt = $db->prepare("SELECT * FROM boards WHERE url = ?");
$stmt->execute([$_POST["url"]]);
$board = $stmt->fetch();

if ($board != null) {
    die("Board already exists.");
}

if (isset($_POST["url"]) && isset($_POST["desc"]) && isset($_POST["nsfw"])) {
    $url = $_POST["url"];
    $desc = $_POST["desc"];
    $nsfw = $_POST["nsfw"];
    if ($nsfw == "on") {
        $nsfw = 1;
    } else {
        $nsfw = 0;
    }
    $stmt = $db->prepare("INSERT INTO boards (url, desc, nsfw) VALUES (?, ?, ?)");
    $stmt->execute([$url, $desc, $nsfw]);
    print("Done.");
} else {
    print("Missing parameters.");
}