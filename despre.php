<?php
	require "inc/constants.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Despre - Secure Chat</title>
		<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<meta name="viewport" content="width=device-width" />
		<link href="style.css" rel="stylesheet"/>
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
				<h2>Despre</h2>
				<p>
					Secure Chat este un chat bazat pe PHP și Javascript, cu criptare e2e (end-to-end).
					Baza de date va contine doar mesajele dvs. criptate, cheia de decriptare nu va fi stocata.
					Numele de utilizator sunt, de asemenea, criptate in baza de date
					Criptarea este furnizată de Stanford Javascript Crypto Library, folosind AES-GCM pe 256 de biti.</p>
</p>                                        In criptografie, PBKDF2 (Password-Based Key Derivation Function 2) sunt functii de derivare a cheilor cu un cost de calcul redus, utilizate pentru a reduce vulnerabilitatile atacurilor cu forta bruta (Brute Force Attack)
                                        PBKDF2 face parte din seria Public-Key Cryptography Standards (PKCS) a RSA Laboratories, in special PKCS #5 v2.0, publicata si ca RFC 2898 al Internet Engineering Task Force. 
                                        Acesta inlocuieste PBKDF1, care putea produce doar chei derivate cu lungimea de până la 160 de biți. RFC 8018 (PKCS #5 v2.1), publicat in 2017, recomandă PBKDF2 pentru codificarea parolelor.
					<br/>
				</p>
				<h3>Utilizare</h3>
				<p>
					Creati o camera de chat, copiati adresa URL a camerei de chat si trimiteti-o prietenului dvs.
				</p>
				<h3>Cum functioneaza</h3>
				<p>
					Cand creati o camera de chat cu o parola personalizata, cheia de criptare va fi generata cu pbkdf2. <span class="roomid">ID-ul camerei</span> va fi folosit pentru a genera cheia de criptare cu
					pbkdf2. Daca nu este specificata nicio parola, <span class="key">cheia</span> va fi generata aleatoriu.
					Cand camera de chat este creata cu o <span class="key">cheie</span> aleatorie, <span class="key">cheia</span> va fi stocata in adresa URL, si va arata cam asa.
					<br/><br/>
					http://neuro.sytes.net/schat/chatroom.php?id=<span class="roomid">27SJrBVkQCsQFaCnjU94</span>#<span class="key">1BZX3QOXF78qq0r9HgZk1AeZK-sKkX3VZVKf40VdE6A</span>
					<br/><br/>
					Partea <span class="key">mov</span> este <span class="key">cheia</span> in sine criptata in base64.
					Partea <span class="roomid">verde</span> este <span class="roomid">ID-ul camerei</span>.
					<span class="roomid">ID-ul camerei</span> va fi prezent mereu, totusi, <span class="key">cheia</span> de criptare, va fi afisata in URL numai atunci cand se utilizeaza o <span class="key">cheie</span> aleatorie.
					Cand se utilizeaza o parola personalizata, utilizatorilor li se va cere sa introduca parola atunci cand se alatura unei camere de chat.
					Adresa URL are un hashtag (#) inainte de <span class="key">cheia</span> de criptare si nu va fi trimisa catre server.Chiar daca aveti jurnalul de acces activat pe serverul web, numai <span class="roomid">ID-ul camerei</span> va fi vizibil in jurnal.
					
					
				</p>
			</section>
		</div>
		<footer>
			<p>&copy; 2022 Secure Chat <?php echo MYCRYPTOCHAT_VERSION; ?> by <a href="https://github.com/neurici/SecureChat/">Cogian Sergiu</a></p>
		</footer>
	</body>
</html>
