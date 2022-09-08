<?php
function path2url($file) {
    return str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
}

	function error($reason, $code = 400) {
		/*
		*  HACK: For some reason php loses the track of the $root, $db, $request variables as soon as i execute a function.
		*  The only needed variables is $root for this function, but it's really strange that php loses them.
		*  FIXME perhaps?
		*/
		$root = $_SERVER["DOCUMENT_ROOT"];
		$board = htmlspecialchars($_POST["board"]);

		require_once("$root/include/header.php");

        http_response_code($code);
		?>
		<div class="box">
			<div class="boxbar">
				<h3>API ERROR</h3>
			</div>
			<div class="boxinner">
				<p><?php print($reason); ?><br><sup>Redirecting in 3 seconds...</sup></p>
				<noscript>
					<hr>
					<p>Unable to redirect, JavaScript is disabled.</p>
					<p><a href="/<?php print($board); ?>">Click here to go back.</a></p>
				</noscript>
				<script>
					<?php
						if($board == null || $board == "") {
							?>
							setTimeout(function() {
								window.location.href = "/";
							}, 3000);
							<?php
						} else {
							?>
							setTimeout(function() {
								window.location.href = "/<?php print($board); ?>";
							}, 3000);
							<?php
						}
					?>
				</script>
			</div>
		</div>
		<?php
		require_once("$root/include/footer.php");
		die();
	}

function thumbnail($file) {
    $imagick = new Imagick(realpath($file));
    $imagick->setImageFormat('jpeg');
    $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
    $imagick->setImageCompressionQuality(90);
    $imagick->thumbnailImage(256, 256, true, false);

    if (file_put_contents($file . '_thumb.jpg', $imagick) === false) {
        throw new Exception("Could not put contents.");
    }
}

function GetDirectorySize($path){
    $bytestotal = 0;
    $path = realpath($path);
    if($path!==false && $path!='' && file_exists($path)){
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
            $bytestotal += $object->getSize();
        }
    }
    return $bytestotal;
}
