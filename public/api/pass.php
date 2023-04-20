<?php

require_once("../../include/phpheader.php");

if(($_POST['action'] ?? '') == 'logout'){
	unset($_SESSION['pass_id']);
	header("Location: /pass");
	die();
}

if(($_POST['action'] ?? '') == 'changepin'){
	$stmt = $db->prepare("SELECT * FROM passes WHERE id = ?");
	$stmt->execute([$_SESSION['pass_id']]);
	$pass = $stmt->fetch(PDO::FETCH_ASSOC);

	if(!password_verify($_POST['pin'], $pass['pin'])){
		error("Wrong pin", 401, "Pass error", "/pass");
	}
	$stmt = $db->prepare("UPDATE passes SET pin = ? WHERE id = ?");
	$stmt->execute([password_hash($_POST['newpin'], PASSWORD_DEFAULT), $_SESSION["pass_id"]]);

	unset($_SESSION['pass_id']);
	header("Location: /pass");
	die();
}

$error = null;
if(!isset($_POST['token']) || !isset($_POST['pin'])){
	$error = "No token or pin provided.";
	goto end;
}

$token = $_POST['token'];
$pin = $_POST['pin'];

$stmt = $db->prepare("SELECT * FROM passes WHERE token = ?");
$stmt->execute([$token]);
$pass = $stmt->fetch(PDO::FETCH_ASSOC);

if($pass == null){
	$error = "Pass not found or expired.";
	goto end;
}

if(!password_verify($pin, $pass['pin'])){
	$error = "Wrong PIN.";
	goto end;
}

if($pass['expires'] < time()){
	$error = "Pass expired.";
	goto end;
}

if($pass['revoked'] == 1){
	$error = "Pass revoked.<br>Reason: ".$pass['revoke_reason'];
	goto end;
}


end:
if($error != null){
	error($error, 401, "Pass error", "/pass");
} else {
	$_SESSION['pass_id'] = $pass['id'];
	header("Location: /pass");
}
