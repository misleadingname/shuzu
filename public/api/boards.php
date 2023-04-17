<?php

require_once("../../include/phpheader.php");
require_once("../../include/func.php");

$stmt = $db->prepare("SELECT * FROM boards;");
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($result == []){
    http_response_code(404);
    exit(404);
}

header("Content-Type: application/json");
print(json_encode($result));