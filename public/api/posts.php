<?php

require_once("../../include/phpheader.php");
require_once("../../include/func.php");

$board = $_GET['board'];

$stmt = $db->prepare(<<<SQL
SELECT postid, timestamp, title, name, text, size, filename, mime, sticky, locked,
       (SELECT COALESCE(MAX(timestamp), p.timestamp)
        FROM posts r
        WHERE r.postid IN (SELECT rr.postid FROM posts rr WHERE rr.replyto = p.postid ORDER BY rr.postid LIMIT ?)) AS bump
FROM posts p
WHERE boardurl = ?
  AND type = 'post'
ORDER BY sticky DESC, bump DESC;
SQL);

$stmt->execute([$BUMP_LIMIT,$board]);
$threads = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($threads == []){
    http_response_code(404);
    exit(404);
}

header("Content-Type: application/json");
echo json_encode($threads);