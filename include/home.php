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
				<p>Even though that JavaScript is completely optional, You'll lose on some quality of life features.</p>
				<p><i>Note: This messsage only appears here.</i></p>
			</div>
		</div>
	</noscript>

	<div class="box">
		<div class="boxbar">
			<h3>Boards</h3>
		</div>
		<div class="boxinner">
			<div class="flex-container">
				<div class="flex-links">
					<?php
					$stmt = $db->prepare("SELECT * FROM boards");
					$stmt->execute();
					$boards = $stmt->fetchAll();
					$i = 0;
					foreach ($boards as $board) {
						$i++;

						print("<div><a href=\"/" . $board["url"] . "\">/" . $board["url"] . "/</a>");
						if ($board["nsfw"] == 1) {
							print("<sup><span class=\"red bold\">(NSFW)</span></sup>");
						}
						print("</div>");


						if ($i == sizeof($boards)) {
							break;
						}

						if ($i % 5 == 0) {
							print("</div><div class=\"flex-links\">");
						}
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
				<p>Hosted content:<br><?php print(number_format(GetDirectorySize("$root/usercontent") / 1024, 2, ".", ".")); ?> <b>KB</b></p>
				<p>Hosting:<br>
					<?php
						$stmt = $db->query("SELECT COUNT(*) FROM 'posts'");
						

						print($stmt->fetchColumn());
					?>
				<b> posts.</b></p>
			</div>
		</div>
	</div>