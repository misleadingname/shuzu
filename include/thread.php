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

<noscript><style>.js-only { display: none; }</style></noscript>

<p class="path"><?php print("<a href=\"/$splitRequest[1]\">" . $splitRequest[1] . "</a> - " . $splitRequest[3]); ?></p>

<div class="half-size centered">
    <div class="box">
        <div class="boxbar">
            <h3>Reply to this thread:</h3>
        </div>
        <div class="boxinner">
            <form action="/api/post" enctype="multipart/form-data" method="post">
                <div class="flex-links">
                    <input hidden name="type" value="reply">
                    <input hidden name="replyto" value="<?php print($splitRequest[3]); ?>">
                    <input hidden name="board" value="<?php print($splitRequest[1]); ?>">
                    <input type="text" name="name" placeholder="Name" value="Anonymous" required>
                    <textarea name="content" placeholder="Content" required></textarea>
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
            <div id="<?php print($reply["postid"]); ?>" class="thread-reply">
                <div>
                    <span class="green bold"><?php print($reply["name"]) . $op; ?></span> <?php print(date("d/M/o G:i:s")); ?>
                    <a>No.</a><a href="#<?php print($reply["postid"]); ?>"><?php print($reply["postid"]); ?></a>
                </div>
				<?php
					if ($reply["mime"] == "image/gif") {
						print("<span class='mime-hack'>GIF</span>");
					} elseif ($reply["mime"] == "video/webm") {
						print("<span class='mime-hack'>WEBM</span>");
					} elseif ($reply["mime"] == "video/mp4") {
						print("<span class='mime-hack'>MP4</span>");
					} elseif ($reply["mime"] == "image/gif") {
						print("<span class='mime-hack'>GIF</span>");
					}
				?>
                <blockquote>
					<?php
						if ($reply["attachmenturl"] != null || $reply["attachmenturl"] != "") {
							?>
                            <noscript>
                                <a href="/api/getimg?id=<?php print($reply["postid"]); ?>&thumb=false"><img loading="lazy" class="catalog-image" src="/api/getimg?id=<?php print($reply["postid"]); ?>&thumb=true"></a>
                            </noscript>
                                <a class="js-only catalog-image" onclick="embed(<?php print($reply["postid"]) ?>)"><img loading="lazy" src="/api/getimg?id=<?php print($reply["postid"]); ?>&thumb=true"></a>
                            <?php
						}
                    ?>
                    <pre><?php
							if($reply["title"] != null || $reply["title"] != "") {
								print("<b>" . $reply["title"] . "</b><br><br>");
							}
                            print($reply["text"]); ?></pre>
                </blockquote>
            </div>
			<?php
		}
	?>
</div>

<script src="/js/media-embedder.js"></script>

<div class="document">