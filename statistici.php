<?php
require 'inc/constants.php';
require 'inc/conf.php';
require 'inc/functions.php';
require 'inc/classes.php';
require 'inc/dbmanager.php';

$dbManager = new DbManager();

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Statistici - Secure Chat</title>
	<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<meta name="viewport" content="width=device-width" />
	<link href="style.css" rel="stylesheet" />
</head>
<body>
	<header>
		<div class="content-wrapper">
			<nav>
				<ul>
					<li id="site-title"><a href="index.php">Secure Chat</a></li>
					<div id="site-links">
						<li><a href="statistici.php">Statistici</a></li>
						<li><a href="despre.php">Despre</a></li>
					</div>
				</ul>
			</nav>
		</div>
	</header>
	<div id="body">
		<section class="content-wrapper main-content clear-fix">
			<h2>Statistici Secure Chat</h2>
			<p>
				Numarul de camere de chat: <?php echo $dbManager->countChatrooms(); ?><br />
				Numarul de mesaje: <?php echo $dbManager->countMessages(); ?>
			</p>
		</section>
	</div>
	<footer>
		<p>&copy; 2022 Secure Chat <?php echo MYCRYPTOCHAT_VERSION; ?> by <a href="https://github.com/neurici/SecureChat/">Cogian Sergiu</a></p>
	</footer>
</body>
</html>
