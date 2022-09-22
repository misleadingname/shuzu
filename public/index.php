<?php
$request = urldecode($_SERVER["REQUEST_URI"]);

require_once("../include/phpheader.php");
require_once("include/func.php");

require_once("include/header.php");

if ($request == '/') {
    require_once("include/home.php");
} else if ($request == '/admintool') {
    require_once('pages/admintool.php');
} else {
    $stmt = $db->prepare("SELECT * FROM boards WHERE url = ?");
    $stmt->execute([$splitRequest[1]]);
    $board = $stmt->fetch();

    if ($splitRequest[1] == $board["url"] && !isset($splitRequest[2])) {
        require_once("pages/board.php");
    } else if ($splitRequest[2] == "thread") {
        if ($splitRequest[3] != null || $splitRequest[3] != "") {
            require_once("pages/thread.php");
            require_once("include/footer.php");
        } else {
            http_response_code(404);
            require_once("pages/error.php");
            require_once("include/footer.php");
        }
    } else {

        http_response_code(404);
        require_once("pages/error.php");
    }

}

require_once("include/footer.php");