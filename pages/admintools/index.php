<?php
?>

<div class="box">
		<div class="boxbar">
			<h3>Admininstration Tools</h3>
		</div>
		<div class="boxinner">
			<h1>Welcome to the new admintools!</h1>
			<p>It's still in development, so some stuff might be missing or broken.</p>
			<p>If there's something that's missing or broken, just use the old <a href="/admintool">admintool</a> for now.</p>
			<div class="flex-container">
				<h2><a href="/admintools/bans">Bans</a></h2>
				<h2><a href="/admintools/boards">Boards</a></h2>
				<h2><a href="/admintools/posts">Posts</a></h2>
				<h2><a href="/admintools/db">DB</a></h2>
			</div>
			<form action="/api/admintools/showasadmin.php" method="post" enctype="multipart/form-data">
					<div class="flex-v center-flex sep">
						<div>
							<input type="checkbox" name="show" <?php if($_SESSION["showasadmin"] == true) { print("checked"); } ?>>
							<span>Display as Admin</span>
						</div>

					<input type="submit" value="Submit">
				</div>
			</form>
		</div>
	</div>
</div>