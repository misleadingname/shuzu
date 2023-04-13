<?php
$stmt = $db->prepare("SELECT * FROM bans");
$stmt->execute();
$bans = $stmt->fetchAll();
?>

<div class="box">
	<div class="boxbar">
		<h3>Ban Managment</h3>
	</div>
	<div class="boxinner">
		<div class="flex-links">
			<form action="/api/admintools/ban.php"  method="post" enctype="multipart/form-data">
				<input type="text" name="ip" id="ip" placeholder="IP" required value="<?= $_GET["ip"] ?? ""?>">
				<input type="text" name="postid" id="postid" placeholder="Reason post" required value="<?= $_GET["post"] ?? ""?>">
				<input type="text" name="reason" id="reason" placeholder="Reason" required>
				<input type="date" name="expires" id="expires" placeholder="Expires">
				<input type="text" name="boards" id="boards" placeholder="Boards" required>
				<input type="submit" value="Ban">
			</form>
		</div>
		<p>Put <code>"*"</code> in boards to ban from all boards</p>
		<p>Leave expiry date empty to ban forever</p>
	</div>
</div>

<div class="box">
	<div class="boxbar">
		<h3>Ban list</h3>
	</div>
	<div class="boxinner">
		<?php
		if ($bans != null) {
		?>
		<table>
			<tbody>
				<tr>
					<th>IP</th>
					<th>Reason</th>
					<th>Created</th>
					<th>Expires</th>
					<th>Boards</th>
				</tr>
				<?php
				foreach ($bans as $ban) {
				?>
				<tr <?php if ($ban["expires"] != 0 && $ban["expires"] < time()) { print("class=\"expired\""); } ?>>
					<td>
						<?php print($ban["ip"]); ?>
					</td>
					<td>
						<?php print($ban["reason"]); ?>
					</td>
					<td>
						<?php print(date("Y-m-d H:i:s", $ban["timestamp"])); ?>
					</td>
					<td>
						<?php
							if ($ban["expires"] == 0) {
								print("Never");
							} else {
								print(date("Y-m-d H:i:s", $ban["expires"]));
							}
							if ($ban["expires"] == 0 || $ban["expires"] > time()) { print("<br><a href=\"/api/admintools/expire.php?ip=" . $ban["ip"] . "\">expire now</a>"); }
						?>
					</td>
					<td>
						<?php
							if ($ban["boards"] == "*") {
								print("All");
							} else {
								print($ban["boards"]);
							}
						?>
					</td>
				<?php
				}
				?>
			</tbody>
		</table>
		<p>Light red = expired</p>
		<?php
		} else {
		?>
		<h1>No bans on record</h1>
		<?php
		}
		?>
	</div>
</div>