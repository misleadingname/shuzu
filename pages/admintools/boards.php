`<?php
$stmt = $db->prepare("SELECT * FROM boards");
$stmt->execute();
$boards = $stmt->fetchAll();
?>

<div class="box">
	<div class="boxbar">
		<h3>Create a new board</h3>
	</div>
	<div class="boxinner">
		<div class="flex-links">
			<form action="/api/admintools/board.php" method="post" enctype="multipart/form-data">
				<input type="text" name="name" placeholder="Name" required>
				<input type="text" name="url" placeholder="URL" required>
				<input type="submit" value="Create">
			</form>
		</div>
	</div>
</div>

<div class="box">
	<div class="boxbar">
		<h3>Board list</h3>
	</div>
	<div class="boxinner">
		<table>
			<tbody>
				<tr>
					<th>URL</th>
					<th>Description</th>
					<th>NSFW</th>
					<th>Posts</th>
				</tr>
				<?php
				foreach ($boards as $board) {
				?>
				<tr>
					<td>
						<?php print($board["url"]); ?>
					</td>
					<td>
						<?php print($board["desc"]); ?>
					</td>
					<td>
						<?php
							if ($board["nsfw"] == 1) {
								print("Yes");
							} else {
								print("No");
							}
						?>
					</td>
					<td>
						<?php
							$stmt = $db->prepare("SELECT COUNT(postid) FROM posts WHERE boardurl = ?");
							$stmt->execute([$board["url"]]);
							$posts = $stmt->fetchColumn();
							print($posts);
						?>
					</td>
 				</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>
</div>

<div class="box">
	<div class="boxbar">
		<h3>Board update</h3>
	</div>
	<div class="boxinner">
		<div class="flex-links fullImage">
			<form action="/api/admintools/board.php" method="post" class="fullImage" enctype="multipart/form-data">
				<input type="text" name="url" placeholder="URL" required>
				<select name="setting" required>
					<option value="desc">Description</option>
					<option value="nsfw">NSFW</option>
				<input type="submit" value="Update">
			</form>
		</div>
	</div>
</div>`