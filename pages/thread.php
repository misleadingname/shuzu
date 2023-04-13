<?php
	date_default_timezone_set('UTC');

	$stmt = $db->prepare("SELECT type FROM posts WHERE postid=?");
	$stmt->execute([$splitRequest[3]]);
	$result = $stmt->fetch();

	if ($result == null) {
		http_response_code(404);
		require_once("$root/pages/error.php");
		require_once("$root/include/footer.php");
		exit();
	}

	$type = $result[0];

	if ($type != "post") {
		http_response_code(404);
		require_once("$root/pages/error.php");
		require_once("$root/include/footer.php");
		exit();
	}

	$stmt = $db->prepare("SELECT boardurl FROM posts WHERE postid=?");
	$stmt->execute([$splitRequest[3]]);
	$board = $stmt->fetch()[0];

	if ($board != $splitRequest[1]) {
		http_response_code(404);
		require_once("$root/pages/error.php");
		require_once("$root/include/footer.php");
		exit();
	}
?>

<p class="path"><?php print("<a href=\"/$splitRequest[1]\">" . $splitRequest[1] . "</a> - " . $splitRequest[3]); ?></p>
<?php
	$stmt = $db->prepare("SELECT locked FROM posts WHERE postid=?");
	$stmt->execute([$splitRequest[3]]);
	$locked = $stmt->fetch()[0];

	if ($locked == 1) {
		?>
			<h1 class="board-title">This thread is locked.</h1>
			<p class="board-description">You cannot reply to this thread.</p>
		<?php
	} else { ?>
<div class="half-size centered">
	<div class="box">
		<div class="boxbar">
			<h3>Reply to this thread:</h3>
		</div>
		<div class="boxinner">
			<form action="/api/post.php" enctype="multipart/form-data" method="post">
				<div class="flex-links">
					<input hidden name="type" value="reply">
					<input hidden name="replyto" value="<?php
						print($splitRequest[3]); ?>">
					<input hidden name="board" value="<?php
						print($splitRequest[1]); ?>">
					<input type="text" name="name" placeholder="Name" value="Anonymous" required>
					<textarea id="replyTextarea" name="content" placeholder="Content" required></textarea>
					<input type="file" name="attachment">
					<p>Files up to 3MB are allowed.</p><sup>WEBM, WEBP, MP4, PNG, JPG, GIF.</sup>
					<input type="submit" value="Post">
				</div>
			</form>
		</div>
	</div>

	<hr>

</div>

<?php
	}

	$stmt = $db->prepare("SELECT * FROM posts WHERE replyto=? OR postid=?");
	$stmt->execute([$splitRequest[3],$splitRequest[3]]);
	$replies = $stmt->fetchAll();
?>
</div>

<div class="thread">
	<?php if($_SESSION["showasadmin"] ?? false): ?>
		<div class="half-size centered">
			<div class="box">
				<div class="boxbar">
					<h3>Admin toolbox</h3>
				</div>
				<div class="boxinner">
					<a href="/api/admintools/post.php?action=lock&id=<?php print($splitRequest[3]); ?>">Lock</a>
					<a href="/api/admintools/post.php?action=sticky&id=<?php print($splitRequest[3]); ?>">Sticky</a>
					<a href="/api/admintools/post.php?action=delete&id=<?php print($splitRequest[3]); ?>">Delete</a>
				</div>
			</div>

			<hr>

		</div>
	<?php endif; ?>
	<?php
		foreach ($replies as $reply) {

			if ($reply["postid"] == $splitRequest[3]) {
				$op = " (OP)";
			} else {
				$op = "";
			}

			if ($reply["ip"] == $_SERVER["REMOTE_ADDR"]) {
				$op .= " (You)";
			}

			?>
			<div id="<?= $reply["postid"] ?>" class="thread-reply">
				<div class="reply-top">
					<span class="green bold"><?= htmlspecialchars($reply["name"]) . $op ?></span> <?php
							print(date("d/M/o G:i:s", $reply["timestamp"])); ?>
						<a href="#<?=$reply["postid"] ?>">No.</a><a href="#<?=$reply["postid"] ?>" onclick="mention(event)"><?= $reply["postid"] ?></a>
					<?php if($_SESSION["showasadmin"] ?? false): ?>
						<span class="red">IP: <?= $reply["ip"] ?></span>
						<a href="/admintools/bans?ip=<?= $reply["ip"] ?>&post=<?= $reply["postid"] ?>">Ban</a>
					<?php endif; ?>
				</div>
				<?php
					if ($reply["mime"] == "image/gif") {
						print("<span class='mime-hack'>GIF</span>");
					} else if ($reply["mime"] == "video/webm") {
						print("<span class='mime-hack'>WEBM</span>");
					} else if ($reply["mime"] == "video/mp4") {
						print("<span class='mime-hack'>MP4</span>");
					}
				?>

				<blockquote class="reply-inner">
					<div class="reply-body">
						<?php if ($reply["attachmenturl"] != ".") {?>
								<a href="/api/getimg.php?id=<?= $reply['postid'] ?>" mime="<?=$reply["mime"] ?>" onclick="embed(event)" class="reply-image">
									<button class="hidden">Close video</button>
									<img loading="lazy" src="/api/getimg.php?id=<?= $reply['postid'] ?>&thumb=true" alt="">
								</a>
								<?php } ?>
						<pre class="reply-text"><?php
								if (!empty($reply["title"])) {
									print("<b>" . htmlspecialchars($reply["title"]) . "</b><br>");
								}
								$txt = $reply["text"];

								$txt = htmlspecialchars($txt);
								$txt = preg_replace('/&gt;&gt;(\d*)/', "<a class=\"mention\" href=\"/$splitRequest[1]/thread/$splitRequest[3]#$1\">>>$1</a>", $txt);
								$txt = preg_replace("/(^|\n)&gt;.*/", '<span class="green">$0</span>', $txt);

								$txt = preg_replace("/\*\*(.+?)\*\*/", '<b>$1</b>', $txt);
								$txt = preg_replace("/\*(.+?)\*/", '<i>$1</i>', $txt);
								$txt = preg_replace("/`(.+?)`/", '<code>$1</code>', $txt);

							$txt = preg_replace("/(https?:\/\/[^\s]+)/", '<a href="$1">$1</a>', $txt);

							//$txt = str_replace("\n", '<br>');
							print($txt); ?>
							<?php if ($reply["banned"]): ?>
								<br><br><b class="red">USER WAS BANNED FOR THIS POST</b>
							<?php endif; ?>
						</pre>

					</div>
					<?php if ($reply["attachmenturl"] != ".") {?>
						<sup class="file-info"><?=htmlspecialchars($reply["filename"]) . " " . number_format($reply["size"] / 1024, 2, ".", ".") ?>KB</sup>
					<?php } ?>
				</blockquote>
			</div>
			<?php
		}
	?>
</div>

<script src="/js/thread-qol.js"></script>

<div class="document">