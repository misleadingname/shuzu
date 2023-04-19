<?php
function path2url($file) {
    return str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
}

function error($reason, $code = 400, $errorTitle = "API ERROR", $redirect = null) {
		global $root;
		global $board;

		require_once("include/header.php");

        http_response_code($code);
		?>
		<div class="box">
			<div class="boxbar">
				<h3><?= $errorTitle ?></h3>
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
						if($redirect != null){
							?>
							setTimeout(function() {
								window.location.href = "<?= $redirect ?>";
							}, 3000);
							<?php
						} else if($board == null || $board == "") {
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
