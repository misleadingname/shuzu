<?php

print("Deleting entire board...<br>");

$stmt = $db->prepare("SELECT * FROM boards WHERE url = ?");
$stmt->execute([$_POST["url"]]);
$board = $stmt->fetch();

if ($board == null) {
    die("Board does not exist.");
}

// delete every post in the board
$stmt = $db->prepare("SELECT * FROM posts WHERE boardurl = ?");
$stmt->execute([$board["url"]]);
$posts = $stmt->fetchAll();

foreach ($posts as $post) {
    unlink("$root/public/usercontent/media/" . $post["attachmenturl"]);
    unlink("$root/public/usercontent/media/" . $post["attachmenturl"] . "_thumb.jpg");

    $stmt = $db->prepare("DELETE FROM posts WHERE postid = ?");
    $stmt->execute([$post["postid"]]);
    print("Deleted post " . $post["postid"] . "<br>");
}

print("Deleting board...<br>");

$stmt = $db->prepare("DELETE FROM boards WHERE url = ?");
$stmt->execute([$board["url"]]);
$result = $stmt->fetch();

if ($result == null) {
    print("Done.");
} else {
    print("Failed.");
}