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
		<div>
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
					<tr>
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
						<td style='text-align: left;'><?php
//							$content = str_replace("\n", "</span><span><br>", $post["text"]);

                            $txt = $post["text"];

                            $txt = htmlspecialchars($txt);
                            $txt = preg_replace("/(^|\n)&gt;.*/", '<span class="green">$0</span>', $txt);

                            $txt = preg_replace("/\*\*(.+?)\*\*/", '<b>$1</b>', $txt);
                            $txt = preg_replace("/\*(.+?)\*/", '<i>$1</i>', $txt);
                            $txt = preg_replace("/`(.+?)`/", '<code>$1</code>', $txt);

                            $txt = preg_replace("/(https?:\/\/[^\s]+)/", '<a href="$1">$1</a>', $txt);
                            $txt = str_replace("\n", '<br>', $txt);

							$content = "<span>" . $txt . "</span>";
							print($content);
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