<?php

print("Deleting all posts...<br>");

$stmt = $db->prepare("SELECT * FROM posts");
$stmt->execute();
$posts = $stmt->fetchAll();

foreach ($posts as $post) {
    unlink("$root/public/usercontent/media/" . $post["attachmenturl"]);
    unlink("$root/public/usercontent/media/" . $post["attachmenturl"] . "_thumb.jpg");

    $stmt = $db->prepare("DELETE FROM posts WHERE postid = ?");
    $stmt->execute([$post["postid"]]);
    print("Deleted post " . $post["postid"] . "<br>");
}

print("<br>Done.");
