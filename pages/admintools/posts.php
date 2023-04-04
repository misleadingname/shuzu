<?php
$stmt = $db->prepare("SELECT * FROM posts ORDER BY timestamp DESC");
$stmt->execute();
$posts = $stmt->fetchAll();
?>

<div class="box">
	<div class="boxbar">
		<h3>Posts</h3>
	</div>
	<div class="boxinner">
		<div class="flex-container">
			<table>
				<tr>
					<th>Post ID</th>
					<th>Board</th>
					<th>Thread</th>
					<th>Author</th>
					<th>Content</th>
					<th>IP</th>

				</tr>
				<?php
				foreach ($posts as $post) {
					?>
					<tr style="overflow: hidden;">
						<td><?php print($post["postid"]); ?></td>
						<td><?php print($post["boardurl"]); ?></td>
						<td><?php print($post["replyto"]); ?></td>
						<td><?php print($post["name"]); ?></td>
						<td><?php
							$content = str_replace("\n", "</span><span><br>", $post["text"]);
							$content = "<span>" . $content . "</span>";
							print($content);
						?></td>
						<td><?php print($post["ip"]); ?></td>
					</tr>
				<?php
				}
				?>
			</table>
		</div>
	</div>
</div>