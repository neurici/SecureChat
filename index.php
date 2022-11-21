<?php
require 'inc/constants.php';
require 'inc/functions.php';
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Secure Chat</title>
	<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<meta name="viewport" content="width=device-width" />
	<link href="style.css" rel="stylesheet" />
</head>
<body>
	<?php
	$showContent = true;
	$configIncluded = false;
	if(is_readable(CONFIG_FILE_NAME)) {
		include CONFIG_FILE_NAME;
		$configIncluded = true;
	} else {
		$showContent = false;
		?>
		<h2>Eroare: Fisierul inc/conf.php lipseste</h2>
		<p>
			Secure Chat nu poate accesa fisierul de configurare.<br />
			Copiati <strong>inc/conf.template.php</strong> in <strong>inc/conf.php</strong>, si nu uitati sa il <strong>configurati</strong>.
		</p>
	<?php
	}
	if(DB_TYPE == DATABASE_SQLITE && !is_writable(DB_FILE_NAME)) {
		$showContent = false;
	?>
	<h2>Eroare: acces baza date</h2>
	<p>
		Secure Chat nu poate modifica baza de date.<br />
		Va rog sa acordati toate drepturile utilizatorului apache (sau cel curent) pe fisierul 'chatrooms.sqlite'
                chmod 777 chatrooms.sqlite
	</p>
	<?php
	}
	if (!extension_loaded('PDO')) {
		$showContent = false;
	?>
	<h2>Eroare: Modulul PDO lipseste</h2>
	<p>
		Modulul PDO lipseste.<br />
		Va rog sa il adaugati si sa il incarcati pentru ca acest site sa functioneze.
	</p>
	<?php
	}
	if (DB_TYPE == DATABASE_SQLITE && !extension_loaded('PDO_SQLITE')) {
		$showContent = false;
	?>
	<h2>Eroare: Modulul PDO SQLite lipseste</h2>
	<p>
		Modulul PDO SQLite lipseste.<br />
		Va rog sa il adaugati si sa il incarcati pentru ca acest site sa functioneze.
	</p>
	<?php
	}
	if(!is_writable(LOGS_FILE_NAME)) {
		$showContent = false;
	?>
	<h2>Eroare: acces la fisierele jurnal</h2>
	<p>
		Secure Chat nu poate edita fisierele jurnal.<br />
		Va rog sa acordati toate drepturile de scriere utilizatorului apache (sau cel curent) pe fisierul „logs.txt”.
	</p>
	<?php
	}
	if (version_compare(phpversion(), '5.4.0', '<')) {
		$showContent = false;
	?>
	<h2>Eroare: Versiune PHP</h2>
	<p>
		Versiunea dvs. de PHP este prea veche.<br />
		Trebuie sa aveti PHP cu versiunea 5.4 sau mai nou.
	</p>
	<?php
	}
	if($showContent) {
	?>
	<noscript>
		Acest site are nevoie de JavaScript activat pentru a functiona. 
			  <style>
				  div {
					  display: none;
				  }
			  </style>
	</noscript>
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
	<section class="content-wrapper main-content clear-fix">
		<h1>Creati o camera de chat <span> Conversati prin criptare e2e (end-to-end)</span></h1>
		<form method="POST" onsubmit="return createChatroom()">
			<label for="expire">Camera de chat va fi stearsa dupa: </label>
			<select id="expire" name="expire">
				<?php foreach ($allowedTimes as $minutes => $label) { ?>
					<option value="<?php echo $minutes; ?>"><?php echo $label; ?></option>
				<?php } ?>
			</select>
			<br/>
			<p><b>Nota:</b> Camerele de chat vor fi sterse dupa <?php echo DAYS_TO_DELETE_IDLE_CHATROOM; ?> zile de inactivitate, indiferent de timpul setat de dvs.</p>
			<br/>
			<input type="checkbox" id="removable"/>
			<label for="removable" class="checkbox">Camera de chat poate fi stearsa manual</label>
			<br/>
			<div id="divRemovePassword">
				<p>Puteti specifica o parola sau puteti lasa campul gol. Un camp gol inseamna ca oricine poate sterge camera.</p>
				<input type="password" id="removePassword" placeholder="Specificati o parola" value="" />
			</div>
			<br/>
			<input type="checkbox" id="selfDestroys"/>
			<label for="selfDestroys" class="checkbox">Se autodistruge daca mai mult de doi utilizatori se conecteaza simultan.</label>
			<br/>
			<br/>
			<input type="checkbox" id="key-generate" onchange="toggleKeyMenu()" checked/>
			<label for="key-generate" class="checkbox">Utilizati o cheie de criptare generata aleatoriu</label>
			<div id="key-menu">
				<br/>
				<input type="password" id="key-custom" placeholder="Specificati o parola" value="" />
			</div>
			<br/>
			<br/>
			<input type="submit" value="Creati o noua camera de chat"/>
		</form>
	</section>
	<script type="text/javascript" src="scripts/sjcl.js"></script>
	<script type="text/javascript" src="scripts/myCryptoChat.js"></script>
	<script type="text/javascript">
		function createChatroom()
		{
			var expire 			= document.getElementById("expire").value;
			var removable 		= document.getElementById("removable").checked;
			var removePassword 	= document.getElementById("removePassword").value;
			var customKey		= !document.getElementById("key-generate").checked;
			
			var formData = new FormData();
			formData.append("action", "create_chatroom");
			formData.append("expire", expire);
			formData.append("removable", removable);
			
			if (removable)
			{
				formData.append("removePassword", removePassword);
			}
			
			var xhr = new XMLHttpRequest();
			xhr.open("POST", "action.php");
			xhr.responseType = "json";
			xhr.onreadystatechange = function()
			{
				if (xhr.readyState === XMLHttpRequest.DONE)
				{
					if (xhr.status === 201)
					{
						var key = sjcl.random.randomWords(8);
						var roomid = xhr.response;
						
						if (customKey)
						{
							var password 	= document.getElementById("key-custom").value;
							key 			= sjcl.misc.pbkdf2(password, roomid);
							
							sessionStorage.setItem(roomid, sjcl.codec.base64url.fromBits(key));
							
							window.location = "chatroom.php?id=" + xhr.response;
						}
						else
						{
							window.location = "chatroom.php?id=" + xhr.response + "#" + sjcl.codec.base64url.fromBits(key);
						}
					}
					else
					{
						alert("Failed to create the chatroom!");
					}
				}
			}
			xhr.send(formData);
			
			return false;
		}
		
		function toggleKeyMenu()
		{
			var checkbox = document.getElementById("key-generate");
			var elem = document.getElementById("key-menu");
			
			if (!checkbox.checked)
			{
				elem.style.display = "block";
			}
			else
			{
				elem.style.display = "none";
			}
		}
		
		function removableChanged(event)
		{
			var passwordElement = document.getElementById("divRemovePassword");
			
			if(this.checked)
			{
				passwordElement.style.display = "block";
			}
			else
			{
				passwordElement.style.display = "none";
			}
		}
		
		document.getElementById("removable").addEventListener("change", removableChanged);
	</script>
	<footer>
		<p>&copy; 2022 Secure Chat <?php echo MYCRYPTOCHAT_VERSION; ?> by <a href="https://github.com/neurici/SecureChat/">Cogian Sergiu</a></p>
	</footer>
	<?php } ?>
</body>
</html>
