<?php
require_once("../../../include/phpheader.php");
require_once("./verify.php");

$show = $_POST["show"];

if ($show == "on") {
    $_SESSION["showasadmin"] = true;
} else {
    $_SESSION["showasadmin"] = false;
}

header("Location: /admintools");