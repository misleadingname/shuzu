<?php
global $db, $splitRequest;
$request = urldecode($_SERVER["REQUEST_URI"]);

require_once("../include/phpheader.php");
require_once("include/func.php");

require_once("include/header.php");

$request = explode("?", $request)[0];

if ($request == '/') {
    require_once("pages/home.php");
} else if (str_starts_with($request, '/admintools')) {
    require_once("./api/admintools/verify.php");
    if ($request == "/admintools") {
        require_once("pages/admintools/index.php");
    } else {
        require_once("pages/admintools/" . substr($request, 12) . ".php");
    }
} else if ($request == '/rules') {
    require_once('pages/rules.php');
} else if ($request == '/banned') {
    require_once('pages/banned.php');
} else if ($request == "/pass"){
	require_once("pages/pass.php");
} else {
    $stmt = $db->prepare("SELECT * FROM boards WHERE url = ?");
    $stmt->execute([$splitRequest[1]]);
    $board = $stmt->fetch();

    if ($board == null) {
        http_response_code(404);
        require_once("pages/error.php");
    } elseif ($splitRequest[1] == $board["url"] && !isset($splitRequest[2])) {
        require_once("pages/board.php");
    } else if ($splitRequest[2] == "thread") {
        if (!empty($splitRequest[3])) {
            require_once("pages/thread.php");
        } else {
            http_response_code(404);
            require_once("pages/error.php");
        }
    } else {
        http_response_code(404);
        require_once("pages/error.php");
    }

}

require_once("include/footer.php");
