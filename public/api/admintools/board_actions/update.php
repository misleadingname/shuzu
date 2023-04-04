<?php

print("Updating board description...<br>");

$stmt = $db->prepare("SELECT * FROM boards WHERE url = ?");
$stmt->execute([$_POST["url"]]);
$board = $stmt->fetch();

if ($board == null) {
    die("Board does not exist.");
}

if (isset($_POST["url"]) && isset($_POST["desc"])) {
    $url = $_POST["url"];
    $desc = $_POST["desc"];
    $stmt = $db->prepare("UPDATE boards SET desc = ? WHERE url = ?");
    $stmt->execute([$desc, $url]);
    print("Done.");
} else {
    print("Missing parameters.");
}