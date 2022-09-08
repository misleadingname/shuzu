<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/include/phpheader.php");
require_once("$root/include/func.php");

$request = urldecode($_SERVER["REQUEST_URI"]);

require_once("$root/include/header.php");
if ($request == "/") {
	require_once("$root/include/home.php");
	require_once("$root/include/footer.php");
	exit();
} else {
	$stmt = $db->prepare("SELECT * FROM boards WHERE url = :url");
	$stmt->bindParam(":url", $splitRequest[1]);
	$stmt->execute();
	$board = $stmt->fetch();

	if ($splitRequest[1] == $board["url"] && $splitRequest[2] == null) {
		require_once("$root/include/board.php");
		require_once("$root/include/footer.php");
		exit();
	}

	if ($splitRequest[2] == "thread") {
		if ($splitRequest[3] != null || $splitRequest[3] != "") {
			require_once("$root/include/thread.php");
			require_once("$root/include/footer.php");
			exit();
		} else {
			http_response_code(404);
			require_once("$root/error/index.php");
			require_once("$root/include/footer.php");
			exit();
		}
	}

	http_response_code(404);
	require_once("$root/error/index.php");
	require_once("$root/include/footer.php");
	exit();
}
