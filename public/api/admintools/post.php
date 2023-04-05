<?php
require_once("../../../include/phpheader.php");
require_once("./verify.php");

print("Executing action: <b>" . $_GET["action"] . "</b><br><br>");
require_once("./post_actions/" . $_GET["action"] . ".php");