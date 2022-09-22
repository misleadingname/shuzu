<?php
require_once("../include/phpheader.php");

$httpStatus = null;

if ($httpStatus == null) {
	$httpStatus = http_response_code();
	if ($httpStatus == null) {
		$httpStatus = $_GET["s"];
		if ($httpStatus == null) {
			$httpStatus = 418;
		}
	}
}

switch ($httpStatus) {
	case 400:
		$httpMessage = "Bad Request";
		break;
	case 401:
		$httpMessage = "Unauthorized";
		break;
	case 403:
		$httpMessage = "Forbidden";
		break;
	case 404:
		$httpMessage = "Not Found";
		break;
	case 405:
		$httpMessage = "Method Not Allowed";
		break;
	case 406:
		$httpMessage = "Not Acceptable";
		break;
	case 418:
		$httpMessage = "I'm a Teapot";
		break;
	case 500:
		$httpMessage = "Internal Server Error";
		break;
	case 501:
		$httpMessage = "Not Implemented";
		break;
	case 502:
		$httpMessage = "Bad Gateway";
		break;
	case 503:
		$httpMessage = "Service Unavailable";
		break;
	case 504:
		$httpMessage = "Gateway Timeout";
		break;
	default:
		$httpStatus = 418;
		$httpMessage = "I'm a teapot";
		break;
}

require_once("$root/include/header.php");
?>
<div class="box">
	<div class="boxbar">
		<h3><?php print($httpStatus . " - " . $httpMessage); ?></h3>
	</div>
	<div class="boxinner">
		<h1>Shame to be you!</h1>
		<?php
		$errDir = glob("$root/images/error/*.*");
		$file = array_rand($errDir);

		$localPath = path2url($errDir[$file]);

		if (pathinfo($localPath, PATHINFO_EXTENSION) == "mp4") {
		?>
			<video autoplay controls width="50%" loop>
				<source src="<?php print($localPath); ?>" type="video/mp4">
			</video>
		<?php
		} else {
		?>
			<img src="<?php print($localPath); ?>" alt="Absolutely zoozled.">
		<?php
		}
		?>
		<p>But actually, if it's not a simple 404 error, it's probably a bug.</p>
		<h2>[ <a href="/">home</a> ]</h2>
	</div>
</div>
<?php
require_once("$root/include/footer.php");
