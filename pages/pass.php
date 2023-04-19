<?php
require_once("../include/phpheader.php");

function ShuzuPassLogo()
{ ?>
	<span class="shuzupass">ShuzuPass+</span>
<?php }

?>
<?php if (isset($_SESSION['pass_id'])): ?>

	<?php
	$stmt = $db->prepare("SELECT * FROM passes WHERE id = ?");
	$stmt->execute([$_SESSION['pass_id']]);
	$pass = $stmt->fetch();
	?>

	<div class="box">
		<div class="boxbar">
			<h3><?php ShuzuPassLogo() ?>status</h3>
		</div>
		<div class="boxinner flex-container">
			<div class="flex-links">
				<p>You are a pass user since: <?= date("Y-m-d", $pass['since']) ?></p>
				<p>Your pass expires on: <?= date("Y-m-d", $pass['expires']) ?></p>
				<form action="/api/pass.php" method="post" enctype="multipart/form-data">
					<input type="hidden" name="action" value="logout">
					<button>Logout</button>
				</form>
			</div>
			<form action="/api/pass.php" method="post" enctype="multipart/form-data">
				<div class="flex-container flex-v">
					<h3>Change your PIN</h3>
					<input type="password" name="pin" placeholder="Current PIN" required>
					<input type="password" name="pin" placeholder="PIN" required>
					<input type="submit" value="Change">
				</div>
			</form>
		</div>
	</div>
<?php else: ?>

	<div class="box">
		<div class="boxbar">
			<h3><?php ShuzuPassLogo() ?>login</h3>
		</div>
		<div class="boxinner">
			<form action="/api/pass.php" method="post">
				<div class="flex-links half-size centered">
					<input type="text" name="token" placeholder="Token" required>
					<input type="password" name="pin" placeholder="PIN" required>
					<input type="submit" value="Login">
				</div>
			</form>
		</div>
	</div>
<?php endif; ?>
<div class="box">
	<div class="boxbar">
		<h3>FAQ</h3>
	</div>
	<div class="boxinner" style="text-align: left;">
		<ol>
			<li>
				<h3>What is <span class="shuzupass">ShuzuPass+</span>?</h3>
				<p><?php ShuzuPassLogo() ?>is a total scam, you should never use it. But if you have too much money
					then go ahead and buy it!</p>
			</li>
			<li>
				<h3>What are the benefits?</h3>
				<ul>
					<li>Post delay reduced by 10ms</li>
					<li>File size increased by 1B</li>
					<li>Access to the private <?php ShuzuPassLogo() ?>furry RP board</li>
				</ul>
			</li>
			<li>
				<h3>What is the price?</h3>
				<ul>
					<li>Men: 10PLN</li>
					<li>Women: 20PLN</li>
					<li>Other: 50PLN</li>
				</ul>
				<p>Accepted currencies</p>
				<ul>
					<li>BLIK</li>
					<li>Bank transfer</li>
					<li>Physical money</li>
					<li>Icecream</li>
				</ul>
			</li>
	</div>
</div>
