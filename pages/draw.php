<?php
	// die("(¬‿¬)");
	$stmt = $db->prepare("SELECT url FROM `boards` WHERE `url` = 'draw'");
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($result == null) {
		http_response_code(404);
		require_once("include/header.php");
		require_once("pages/error.php");
		require_once("include/footer.php");
		die();
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/css/draw.css">
	<script src="/js/canvas.js" defer></script>
	<title>shuzu - the canvas</title>
</head>
<body>
	<div class="container">
		<div class="logo"></div>
		<div class="info"></div>
		<canvas></canvas>
	</div>
	<div class="err">
		<h1>You need a PC</h1>
		<p>The canvas will only work on PC's.</p>
		<p><sup>Or a wide enough monitor.</sup></p>
	</div>
	<div class="cross">
		<div></div>
	</div>

	<div class="toolbox bottom">
		<div class="colors">
			<!-- A small color palette that Windows 95 used to have -->
			<div class="color active" data-color="#000000"></div>
			<div class="color" data-color="#000080"></div>
			<div class="color" data-color="#0000C8"></div>
			<div class="color" data-color="#0000FF"></div>
			<div class="color" data-color="#006400"></div>
			<div class="color" data-color="#008000"></div>
			<div class="color" data-color="#008080"></div>
			<div class="color" data-color="#00FFFF"></div>
			<div class="color" data-color="#800000"></div>
			<div class="color" data-color="#800080"></div>
			<div class="color" data-color="#808000"></div>
			<div class="color" data-color="#808080"></div>
			<div class="color" data-color="#C0C0C0"></div>
			<div class="color" data-color="#FF0000"></div>
			<div class="color" data-color="#FF00FF"></div>
			<div class="color" data-color="#FFFF00"></div>
			<div class="color" data-color="#FFFFFF"></div>
		</div>
	</div>

	<div class="toolbox right column">
		<h3>Message</h3>
		<form action="">
			<textarea name="" id="" cols="30" rows="10"></textarea>
			<input type="submit" value="Send">
		</form>
	</div>
</body>
</html>