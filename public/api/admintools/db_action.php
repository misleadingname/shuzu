<?php
require_once("../../../include/phpheader.php");
require_once("./verify.php");

if ($_POST["confirmation"] == "I absolutely understand what I am doing") {
	print("Confirmed<br>");
} else {
	die("Invalid confirmation.");
}

print("Executing action: <b>" . $_POST["action"] . "</b><br><br>");
require_once("./db_actions/" . $_POST["action"] . ".php");