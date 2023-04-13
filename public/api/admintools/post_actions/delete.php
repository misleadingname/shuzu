<?php

$stmt = $db->prepare("SELECT * FROM posts WHERE postid = ?");
$stmt->execute([$_GET["id"]]);
$post = $stmt->fetch();

if ($post == null) {
    die("This post does not exist.");
}

if ($post["type"] == "post") {
    $stmt = $db->prepare("SELECT * FROM posts WHERE replyto = ?");
    $stmt->execute([$post['postid']]);
    $posts = $stmt->fetchAll();

    foreach ($posts as $p) {
        unlink("$root/public/usercontent/media/" . $p["attachmenturl"]);
        unlink("$root/public/usercontent/media/" . $p["attachmenturl"] . "_thumb.jpg");

        $stmt = $db->prepare("DELETE FROM posts WHERE postid = ?");
        $stmt->execute([$p["postid"]]);
        print("Deleted post " . $p["postid"] . "<br>");
    }

    $stmt = $db->prepare("DELETE FROM posts WHERE postid = ?");
    $stmt->execute([$post['postid']]);
}