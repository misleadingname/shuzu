<div class="box">
	<div class="boxbar">
		<h3>Warning!</h3>
	</div>
	<div class="boxinner">
		<h1>DO THINK TWICE BEFORE DOING ANYTHING HERE!</h1>
		<p>Any actions done here are <b>irreversible</b> and <b>cannot be undone</b>!</p>
	</div>
</div>

<div class="box">
	<div class="boxbar">
		<h3>Database Management</h3>
	</div>
	<div class="boxinner">
		<form action="/api/admintools/db_action.php" method="post" enctype="multipart/form-data">
				<div class="flex-links">
				<select name="action">
					<option value="nuke">Nuke the database</option>
					<option value="wipe">Delete all posts</option>
					<option value="wipebans">Delete all bans</option>
					<option value="wipemedia">Delete all media</option>
				</select>
				<input type="text" name="confirmation" placeholder="Type 'I absolutely understand what I am doing'" required>
				<input type="submit" value="Execute">
			</div>
		</form>
	</div>
</div>
