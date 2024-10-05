<?php
$stmt = $db->prepare("SELECT * FROM bans WHERE ip = ? AND expires > strftime('%s', 'now') OR expires = 0; LIMIT 1;");
$stmt->execute([get_ip()]);
$stmt->execute();
$banned = $stmt->fetch();

if($banned != null) {
	$reason = "You are banned in participating in the following boards:<br>" . $banned["boards"] . "<br>For the following reason:<br><code>" . $banned["reason"] . "</code>";
	if ($banned["boards"] == "*") {
		$reason = "You are banned from participating in <b>all</b> boards for the following reason:<br><code>" . $banned["reason"] . "</code>";
	}

	if ($banned["expires"] != 0) {
		$reason = $reason . "<br>This ban will expire on " . date("Y-m-d H:i:s", $banned["expires"]);
	} else {
		$reason = $reason . "<br>This ban will never expire.";
	}
} else {
	$reason = "Your IP address is not currently banned.";
}
?>

<div class="box">
	<div class="boxbar">
		<h3>Banned</h3>
	</div>
	<div class="boxinner">
		<p><?php print($reason); ?></p>
	</div>
</div>
