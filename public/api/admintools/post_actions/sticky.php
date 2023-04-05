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

if ($post["sticky"] == 1) {
    $stmt = $db->prepare("UPDATE posts SET sticky = 0 WHERE postid = ?");
    $type = "unsticked";
} else {
    $stmt = $db->prepare("UPDATE posts SET sticky = 1 WHERE postid = ?");
    $type = "sticked";
}

$stmt->execute([$id]);

die("Thread $type.");