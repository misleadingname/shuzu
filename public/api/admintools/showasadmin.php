<?php
require_once("../../../include/phpheader.php");
require_once("./verify.php");

$_SESSION["showasadmin"] = $_POST["show"] == "on";

header("Location: /admintools");