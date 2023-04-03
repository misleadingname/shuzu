<?php
if (!isset($_SERVER["PHP_AUTH_USER"]) || !isset($_SERVER["PHP_AUTH_PW"]) || $_SERVER["PHP_AUTH_USER"] != $ADMIN_USER || $_SERVER["PHP_AUTH_PW"] != $ADMIN_PASSWORD) {
	header("WWW-Authenticate: Basic realm=\"shuzuAdminTool\"");
	http_response_code(401);
	$httpStatus = 401;
	require_once("$root/pages/error.php");
	exit();
}