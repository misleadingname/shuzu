<?php
$id = $_GET["id"];

$stmt = $db->prepare("SELECT * FROM posts WHERE postid = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if ($post == null) {
    die("This post does not exist.");
}

if ($post["type"] != "post") {
    die("This is not a thread.");
}

if ($post["locked"] == 1) {
    $stmt = $db->prepare("UPDATE posts SET locked = 0 WHERE postid = ?");
    $type = "unlocked";
} else {
    $stmt = $db->prepare("UPDATE posts SET locked = 1 WHERE postid = ?");
    $type = "locked";
}

$stmt->execute([$id]);

die("Thread $type.");