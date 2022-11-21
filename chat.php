<?php
	require 'inc/constants.php';
	require 'inc/conf.php';
	require 'inc/functions.php';
	require 'inc/classes.php';
	require 'inc/dbmanager.php';

	$dbManager 	= new DbManager();
	$roomid		= filter_input(INPUT_GET, "id");
	
	if (!$roomid)
	{
		header("HTTP/1.1 400 Bad Request");
		die("Trebuie sa specificati un ID");
	}
	
	$chatRoom 	= $dbManager->GetChatroom($roomid);
	$session	= ChatUser::GetSession($roomid);
	$user 		= $dbManager->getUser($session);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Secure Chat</title>
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
	<div id="body" style="display: none">
		<section class="content-wrapper main-content clear-fix">
			<div class="container">
				<div class="chat-container">
					<div id="chatroom"></div>
					<div id="chatusers"></div>
				</div>
				<div id="chatbar">
					<input type="text" id="textMessage" placeholder="Tastati mesajul aici" onkeydown="if (event.keyCode == 13) { sendMessage(); }"/>
				</div>
			</div>
			<div id="chatroom-footer">
			<!-- Let's wait with the render media option -->
				<label style="display:none"><input type="checkbox" id="chatroom-render" onchange="toggleRender(this)"/> Render media</label>
				<span id="chatroom-expire"></span>
			</div>
			
			<div>
				<?php 
					if($chatRoom && $chatRoom->isRemovable) {
				?>
					<br/>
					<div id="divButtonRemoveChatroom">
						<input type="button" value="Stergere camera chat" onclick="removeChatroom(<?php if($chatRoom->removePassword != '') { echo 'true'; } else { echo 'false'; } ?>);" />
					</div>
				<?php
					}
				?>
			</div>
		</section>
	</div>
	<div id="body_username">
		<section class="content-wrapper main-content clear-fix">
			<h1>Alaturati-va camerei de chat</h1>
			<p>Trebuie sa alegeti un nume de utilizator inainte de a intra in camera de chat.</p>
			<form method="POST" onsubmit="return setUsername()">
				<input type="text" id="username" placeholder="Utilizator" required/>
				<br/>
				<input type="password" id="key-custom" placeholder="Parola" style="display:none"/>
				<input type="submit" value="Intra"/>
			</form>
		</section>
	</div>
	<footer>
		<p>&copy; 2022 Secure Chat <?php echo MYCRYPTOCHAT_VERSION; ?> by <a href="hhttps://github.com/neurici/SecureChat/">Cogian Sergiu</a></p>
	</footer>
	<script type="text/javascript" src="scripts/sjcl.js"></script>
	<script type="text/javascript" src="scripts/securechat.js"></script>
	<script type="text/javascript">
		var roomId = '<?php echo htmlspecialchars($roomid, ENT_QUOTES, 'UTF-8'); ?>';
		var checkIntervalTimer;
		var isRefreshTitle = false;
		var refreshTitleInterval;
		
		function displayChat()
		{
			document.getElementById("body").style.display 			= "block";
			document.getElementById("body_username").style.display 	= "none";

			getMessages(false);

			// try to get new messages every 1.5 seconds
			checkIntervalTimer = setInterval("getMessages(true)", 1500);
		}
		
		// Retrieve all messages again which makes the URL parser run with the new settings, I could also loop through the elements and refresh them that way
		// but this seems like a more reasonable way to do it
		function toggleRender(elem)
		{
			// Empty the chat panel
			document.getElementById("chatroom").innerHTML = "";
			
			// Set the last message id to 0, this forces it to retrieve all messages
			lastMessageId = 0;
			getMessages(false);
		}
		
		function setUsername()
		{
			var key = pageKey();
			var username = document.getElementById("username").value;
			
			if (key != "" && key != "=")
			{
				key = sjcl.codec.base64url.toBits(key);
			}
			else
			{
				var password 	= document.getElementById("key-custom").value;
				key 			= sjcl.misc.pbkdf2(password, roomId);
				
				sessionStorage.setItem(roomId, sjcl.codec.base64url.fromBits(key));
			}
			
			username = sjcl.encrypt(key, username, cryptoOptions);
			
			var formData = new FormData();
			formData.append("roomId", roomId);
			formData.append("action", "register");
			formData.append("username", username);
			
			var xhr = new XMLHttpRequest();
			xhr.open("POST", "actiune.php");
			
			xhr.onreadystatechange = function()
			{
				if (xhr.readyState == XMLHttpRequest.DONE)
				{
					if (xhr.status === 201)
					{
						displayChat();
					}
				}
			}
			
			xhr.send(formData);
			return false;
		}

		function stopTimerCheck()
		{
			clearInterval(checkIntervalTimer);
		}
		
		function stopRefreshTitle()
		{
			if (isRefreshTitle)
			{
				clearInterval(refreshTitleInterval);
				document.title = "MyCryptoChat";
				isRefreshTitle = false;
			}
		}
		
		function convertUnixTimestamp(timestamp)
		{
			var date = new Date(timestamp * 1000);
			
			return date.toLocaleString();
		}

		function onLoad()
		{
			var key = pageKey();
			
			// If an encryption key has not been set
			if (key === "=" || key == "")
			{
				// Display the encryption key input box
				var keyElement = document.getElementById("key-custom");
				
				keyElement.style.display = "block";
			}
			
			<?php if ($user) { ?>
				displayChat();
			<?php } if ($chatRoom && $chatRoom->dateEnd > 0) { ?>
				var timestamp = convertUnixTimestamp(<?php echo $chatRoom->dateEnd; ?>);
			
				document.getElementById("chatroom-expire").innerHTML = "Camera de chat va expira pe " + htmlEncode(timestamp);
			<?php } ?>
		}
		
		// Send disconnect message after closing the window
		function onUnload()
		{
			var formData = new FormData();
			formData.append("roomId", roomId);
			formData.append("action", "disconnect");
			
			var xhr = new XMLHttpRequest();
			xhr.open("POST", "actiune.php");
			xhr.send(formData);
		}
		
		window.addEventListener("load", onLoad);
		window.addEventListener("unload", onUnload);
		window.addEventListener("mousemove", stopRefreshTitle);
	</script>
</body>
</html>
