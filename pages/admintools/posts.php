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
					<th>Actions</th>

				</tr>
				<?php
				foreach ($posts as $post) {
					?>
					<tr style="overflow: hidden;">
						<td><a href="/<?php print($post["boardurl"]); ?>/thread/<?php
							$replyto = $post["replyto"];
							if ($replyto == 0) {
								print($post["postid"]);
							} else {
								print($replyto);
							}
							print("#" . $post["postid"]);
							?>
						"><?php print($post["postid"]); ?></a></td>
						<td><?php print($post["boardurl"]); ?></td>
						<td><a href="/<?php print($post["boardurl"]); ?>/thread/<?php print($post["replyto"]); ?>"><?php print($post["replyto"]); ?></a></td>
						<td><?php print(htmlspecialchars($post["name"])); ?></td>
						<td><?php
							$content = str_replace("\n", "</span><span><br>", $post["text"]);
							$content = "<span>" . $content . "</span>";
							print(htmlspecialchars($content));
						?></td>
						<td><?php print($post["ip"]); ?></td>
						<td>
							<a href="/api/admintools/post.php?action=lock&id=<?php print($post["postid"]); ?>">Lock</a>
							<a href="/api/admintools/post.php?action=sticky&id=<?php print($post["postid"]); ?>">Sticky</a>
							<a href="/api/admintools/post.php?action=delete&id=<?php print($post["postid"]); ?>">Delete</a>
					</tr>
				<?php
				}
				?>
			</table>
		</div>
	</div>
</div>