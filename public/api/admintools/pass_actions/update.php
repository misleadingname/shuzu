<?php

print_r($_POST);

if (isset($_POST["pin"]) && $_POST["pin"] != "") {
	$stmt = $db->prepare("UPDATE passes SET pin = ? WHERE id = ?");
	$stmt->execute([password_hash($_POST['pin'], PASSWORD_DEFAULT), $_POST["id"]]);
}

if (isset($_POST["expires"])) {
	$stmt = $db->prepare("UPDATE passes SET expires = ? WHERE id = ?");
	$stmt->execute([strtotime($_POST["expires"]), $_POST["id"]]);
}

$stmt = $db->prepare("UPDATE passes SET revoked = ? WHERE id = ?");
$stmt->execute([$_POST["revoked"] ? 1 : 0, $_POST["id"]]);

if (isset($_POST["reason"])) {
	$stmt = $db->prepare("UPDATE passes SET revoke_reason = ? WHERE id = ?");
	$stmt->execute([$_POST["reason"], $_POST["id"]]);
};

header("Location: /admintools/passes");
