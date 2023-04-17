<?php

require_once("../../include/phpheader.php");
require_once("../../include/func.php");

$board = $_GET['board'];
$thread = $_GET['thread'];

$stmt = $db->prepare("SELECT postid, timestamp, title, type, name, text, size, filename, mime FROM posts WHERE boardurl = ? AND (postid = ? OR replyto = ?);");
$stmt->execute([$board, $thread, $thread]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($result == []){
    http_response_code(404);
    exit(404);
}

header("Content-Type: application/json");
print(json_encode($result));