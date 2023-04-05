<?php
	$insults = file("../include/insults.txt");

    if(!empty($splitRequest[3])) {
		$stmt = $db->prepare("SELECT * FROM posts WHERE postid=?");
		$stmt->execute([$splitRequest[3]]);
		$result = $stmt->fetch();
	}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=1.0, initial-scale=1.0">

		<link rel="stylesheet" href="/css/haven.css">

		<?php
			if (isset($splitRequest[3]) && !empty($result["title"])) {
                $title = $result["title"];
            }
			if(empty($title)) {
				$title = "shuzu";
			} else {
				$title = "shuzu - " . $title;
			}
			print("<title>$title</title>");

            if(!empty($result["text"])) {
                if(strlen($result["text"]) > 32) {
                    $description = substr($result["text"], 0, 32) . "...\n\nread on shuzu.";
                } else {
                    $description = $result["text"] . "\n\nread on shuzu.";
                }
            } else {
                $description = "???";
            }
		?>

        <meta name="description" content="<?php print($description); ?>">
    </head>
	<body>
		<div class="document">
			<div class="header">
				<div class="logo">
					<h1><a href="/" title="home">shuzu</a></h1>
					<p class="splash"><?php print($insults[rand(0, count($insults) - 1)]); ?></p>
				</div>
				<hr>
			</div>

			<div class="box">
				<div class="boxbar">
					<h3>Announcement</h3>
				</div>
				<div class="boxinner">
					<h3>Today's changes:</h3>
					<ul>
						<li>Fixed a major XSS flaw.</li>
						<li>Hopefully improved the site for mobile.</li>
					</ul>
				</div>
			</div>