<?php

$token = $_POST["token"];
$pin = $_POST["pin"];
$expires = strtotime($_POST["expires"]);

$stmt = $db->prepare("INSERT INTO passes (token, pin, since, expires) VALUES (?, ?, ?, ?)");
$stmt->execute([$token, password_hash($pin, PASSWORD_DEFAULT), time(), $expires]);

header("Location: /admintools/passes");
