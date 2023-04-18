<?php

require_once '../config.php';

if (isset($_SERVER['HTTP_X_HUB_SIGNATURE'])){
    list($algo, $hash) = explode('=', $_SERVER['HTTP_X_HUB_SIGNATURE'], 2) + array('', '');
    if (!in_array($algo, hash_algos(), TRUE)) {
        throw new \Exception("Hash algorithm '$algo' is not supported.");
    }

    $rawPost = file_get_contents('php://input');
    if (!hash_equals($hash, hash_hmac($algo, $rawPost, $ADMIN_PASSWORD))) {
        throw new \Exception('Hook secret does not match.');
    }
} else {
    require 'api/admintools/verify.php';
}

shell_exec("git config --global --add safe.directory '*'");

$result = array();
exec("cd .. && git pull 2>&1", $result);
foreach ($result as $line) {
    print($line . "\n");
}
