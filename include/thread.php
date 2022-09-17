<?php
	date_default_timezone_set('UTC');

	$stmt = $db->prepare("SELECT type FROM posts WHERE (postid=" . $splitRequest[3] . ")");
	$stmt->execute();
	$type = $stmt->fetchAll()[0][0];

	if ($type != "post") {
		http_response_code(404);
		require_once("$root/error/index.php");
		require_once("$root/include/footer.php");
		exit();
	}

?>

<p class="path"><?php
		print("<a href=\"/$splitRequest[1]\">" . $splitRequest[1] . "</a> - " . $splitRequest[3]); ?></p>

<div class="half-size centered">
    <div class="box">
        <div class="boxbar">
            <h3>Reply to this thread:</h3>
        </div>
        <div class="boxinner">
            <form action="/api/post" enctype="multipart/form-data" method="post">
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
</div>

<?php
	$stmt = $db->prepare("SELECT * FROM posts WHERE (replyto=" . $splitRequest[3] . ") OR (postid=" . $splitRequest[3] . ")");
	$stmt->execute();
	$replies = $stmt->fetchAll();
?>

<div class="thread">
	<?php
		foreach ($replies as $reply) {

			if ($reply["postid"] == $splitRequest[3]) {
				$op = " (OP)";
			} else {
				$op = "";
			}

			?>
            <div id="<?= $reply["postid"] ?>" class="thread-reply">
                <div class="reply-top">
                    <span class="green bold"><?php
                            print($reply["name"] . $op); ?></span> <?php
                            print(date("d/M/o G:i:s", $reply["timestamp"])); ?>
                        <a href="#<?=$reply["postid"] ?>">No.</a><a href="#<?=$reply["postid"] ?>" onclick="mention(event)"><?= $reply["postid"] ?></a>
                </div>
				<?php
					if ($reply["mime"] == "image/gif") {
						print("<span class='mime-hack'>GIF</span>");
					} else if ($reply["mime"] == "video/webm") {
						print("<span class='mime-hack'>WEBM</span>");
					} else if ($reply["mime"] == "video/mp4") {
						print("<span class='mime-hack'>MP4</span>");
					} else if ($reply["mime"] == "image/gif") {
						print("<span class='mime-hack'>GIF</span>");
					}
				?>

                <blockquote>
					<?php
						if ($reply["attachmenturl"] != null || $reply["attachmenturl"] != "") {
							?>
                            <a href="/api/getimg?id=<?= $reply['postid'] ?>" mime="<?php print($reply["mime"]); ?>" onclick="embed(event)">
								<?php
									if (str_starts_with($reply["mime"], "image/")) {
										?>
                                        <img src="/api/getimg?id=<?= $reply['postid'] ?>&thumb=true" alt="">
										<?php
									} else if (str_starts_with($reply["mime"], "video/")) {
										?>
                                            <button class="hidden">Close video</button><br class="hidden">
                                            <img src="/api/getimg?id=<?= $reply['postid'] ?>&thumb=true" alt="">
                                            <video class="hidden" src="/api/getimg?id=<?= $reply['postid'] ?>" controls></video><br>
                                            <sup><?php print($reply["filename"] . " " . number_format($reply["size"] / 1024, 2, ".", ".")); ?>KB</sup>
										<?php
									}
								?>
                            </a>
							<?php
						}
					?>
                    <pre class="reply-text"><?php
							if ($reply["title"] != null || $reply["title"] != "") {
								print("<b>" . $reply["title"] . "</b><br>");
							}

							$txt = $reply["text"];

							$txt = htmlspecialchars($txt);
							$txt = preg_replace('/&gt;&gt;(\d*)/', "<a class=\"mention\" href=\"/$splitRequest[1]/thread/$splitRequest[3]#$1\">>>$1</a>", $txt);
							$txt = preg_replace("/(^|\n)&gt;.*/", '<span class="green">$0</span>', $txt);

							$txt = preg_replace("/\*\*(.+?)\*\*/", '<b>$1</b>', $txt);
							$txt = preg_replace("/\*(.+?)\*/", '<i>$1</i>', $txt);
							$txt = preg_replace("/`(.+?)`/", '<code>$1</code>', $txt);

                            //$txt = str_replace("\n", '<br>');

							print($txt); ?></pre>
                </blockquote>
            </div>
			<?php
		}
	?>
</div>

<script src="/js/thread-qol.js"></script>

<div class="document">