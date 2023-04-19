<?php
$stmt = $db->prepare("SELECT * FROM passes");
$stmt->execute();
$passes = $stmt->fetchAll();
?>

<div class="box">
	<div class="boxbar">
		<h3>Pass Management</h3>
	</div>
	<div class="boxinner">
		<div class="flex-links">
			<h3>Create a new pass</h3>
			<form action="/api/admintools/pass.php" method="post" enctype="multipart/form-data">
				<input type="hidden" name="type" value="create">

				<?php
				$words = file("$root/include/wordlist.txt");
				$gentoken = $words[rand(0, count($words) - 1)] . "-" . $words[rand(0, count($words) - 1)] . "-" . $words[rand(0, count($words) - 1)] . "-" . $words[rand(0, count($words) - 1)];
				?>

				<input type="text" name="token" id="token" placeholder="Token" required value="<?= $gentoken ?>">
				<input type="password" name="pin" id="pin" placeholder="PIN" required>
				<input type="date" name="expires" id="expires" placeholder="Expires"
					   value="<?= date("Y-m-d", time() + 30 * 24 * 60 * 60) ?>">
				<input type="submit" value="Add">
			</form>
		</div>


		<?php
		if ($_GET['id'] ?? null) {
			$stmt = $db->prepare("SELECT * FROM passes WHERE id = ?");
			$stmt->execute([intval($_GET['id'])]);
			$pass = $stmt->fetch();
			if ($pass != null) {
				?>
				<div class="flex-links">
					<h3>Edit selected pass</h3>
					<form action="/api/admintools/pass.php" method="post" enctype="multipart/form-data">
						<input type="hidden" name="type" value="update">
						<input type="hidden" name="id" value="<?= $pass['id'] ?>">
						<p>ID: <?= $pass['id'] ?> </p>
						<p>Token: <?= $pass['token'] ?> </p>
						<input type="password" name="pin" id="pin" placeholder="PIN">
						<input type="date" name="expires" id="expires" placeholder="Expires"
							   value="<?= date("Y-m-d", $pass['expires']) ?>">
						<input type="checkbox" name="revoked" id="revoked" <?= $pass['revoked'] ? "checked" : "" ?>>
						<label for="revoked">Revoked</label>
						<textarea name="reason" id="reason"
								  placeholder="Revocation reason"><?= $pass['revoke_reason'] ?></textarea>
						<input type="submit" value="Update">
					</form>
				</div>
			<?php }
		} ?>
	</div>
</div>

<div class="box">
	<div class="boxbar">
		<h3>Pass list</h3>
	</div>
	<div class="boxinner">
		<table>
			<tbody>
			<tr>
				<th>ID</th>
				<th>Token</th>
				<th>Started</th>
				<th>Ends</th>
				<th>Revoked</th>
				<th>Actions</th>
			</tr>
			<?php foreach ($passes as $pass): ?>
				<form>
					<input type="hidden" name="id" value="<?= $pass['id'] ?>">
					<tr class="<?= $pass['expires'] < time() ? "expired" : "" ?> <?= $pass['revoked'] ? "revoked" : "" ?>">
						<td>
							<?= $pass["id"] ?>
						</td>
						<td>
							<?= $pass["token"] ?>
						</td>
						<td>
							<?= date("Y-m-d", $pass['since']) ?>
						</td>
						<td>
							<?= date("Y-m-d", $pass['expires']) ?>
						</td>
						<td>
							<?= $pass['revoked'] ? "Yes" : "No" ?>
						</td>
						<td>
							<input type="submit" value="Select">
						</td>
					</tr>
				</form>
			<?php endforeach; ?>
			</tbody>
		</table>
		<p>Light red = expired</p>
		<p>Dark red = revoked</p>
	</div>
</div>
