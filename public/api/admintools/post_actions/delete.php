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

    foreach ($posts as $post) {
        unlink("$root/public/usercontent/media/" . $post["attachmenturl"]);
        unlink("$root/public/usercontent/media/" . $post["attachmenturl"] . "_thumb.jpg");

        $stmt = $db->prepare("DELETE FROM posts WHERE postid = ?");
        $stmt->execute([$post["postid"]]);
        print("Deleted post " . $post["postid"] . "<br>");
    }

    $stmt = $db->prepare("DELETE FROM posts WHERE postid = ?");
    $stmt->execute([$post['postid']]);
}