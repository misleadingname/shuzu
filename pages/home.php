	<div class="box">
		<div class="boxbar">
			<h3>Welcome to shuzu!</h3>
		</div>
		<div class="boxinner">
			<p>shuzu is a basic and open source imageboard where anyone can create threads, reply and most importantly share images!<br>
				I think everyone knows how 4chan works, so feel free to dive in into any of the boards!</p>

			<p>Quick note: Most of 4chan's rules also apply here.<br>More details <a href="/rules">here.</a></p>
		</div>
	</div>

	<noscript>
		<div class="box">
			<div class="boxbar">
				<h3>JavaScript is disabled</h3>
			</div>
			<div class="boxinner">
				<p>Even though that JavaScript is completely optional, You'll lose on some quapty of pfe features.</p>
				<p><i>Note: This messsage only appears here.</i></p>
			</div>
		</div>
	</noscript>

	<div class="box">
		<div class="boxbar">
			<h3>Today's changes</h3>
		</div>
		<div class="boxinner">
			<p>Fixed a major XSS flaw.</p>
			<p>Hopefully improved the site for mobile.</p>
			<p>Even <b>more</b> css mobile fixes.</p>
			<p>"Today's Quote"</p>
		</div>
	</div>

	<div class="box">
		<div class="boxbar"><h3>Today's quote</h3></div>
		<div class="boxinner">
			<div style="width: fit-content;margin: auto;">
				<?php
				$stmt = $db->prepare("SELECT COUNT(*) FROM posts WHERE timestamp < strftime('%s', 'now') - (strftime('%s', 'now') % 86400);");
				$stmt->execute();
				$result = $stmt->fetch()[0];

				srand(date("dmy"));
				$random = rand(0, $result);

				$stmt = $db->prepare("SELECT * FROM posts WHERE postid < ? AND type = 'reply' ORDER BY postid DESC LIMIT 1;");
				$stmt->execute([$random]);
				$result = $stmt->fetch();

				print("<p style='font-size: 1.5em; margin: 0;'><i>" . $result["text"] . "</i></p>");
				print("<p style='margin: 0;text-align: right;'><b>-" . $result["name"] . "</b></p>");
			?>
			</div>
		</div>
	</div>

	<div class="box">
		<div class="boxbar">
			<h3>Boards</h3>
		</div>
		<div class="boxinner">
			<div class="flex-container">
				<div class="board-list">
					<?php
					$stmt = $db->prepare("SELECT * FROM boards");
					$stmt->execute();
					$boards = $stmt->fetchAll();
					foreach ($boards as $board) {
						print("<div><a href=\"/" . $board["url"] . "\">/" . $board["url"] . "/</a>");
						if ($board["nsfw"] == 1) {
							print("<sup><span class=\"red bold\">(NSFW)</span></sup>");
						}
						print("</div>");
					}
					?>
				</div>
			</div>
		</div>
	</div>

	<div class="box">
		<div class="boxbar">
			<h3>Statistics</h3>
		</div>
		<div class="boxinner">
			<div class="flex-container">
				<p>Hosted content:<br><?php
					print(number_format(GetDirectorySize("$root/public/usercontent") / 1024 / 1024, 2, ".", "."));
				?> <b>MB</b></p>
				<p>Hosting:<br>
					<?php
						$stmt = $db->query("SELECT COUNT(*) FROM 'posts'");
						
						print($stmt->fetchColumn());
					?>
				<b> posts.</b></p>
			</div>
		</div>
	</div>