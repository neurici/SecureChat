Secure Chat
============
Secure Chat este un chat bazat pe PHP si Javascript, cu criptare e2e(end-to-end). Baza de date va contine doar mesajele dvs. criptate si salva/afisa cheia de decriptare. 
Criptarea este furnizată Stanford Javascript Crypto Library, folosind AES-GCM pe 256 de biți.
In criptografie, PBKDF2 (Password-Based Key Derivation Function 2) sunt functii de derivare a cheilor cu un cost de calcul redus, utilizate pentru a reduce vulnerabilitatile atacurilor cu forta bruta (Brute Force Attack)
PBKDF2 face parte din seria Public-Key Cryptography Standards (PKCS) a RSA Laboratories, in special PKCS #5 v2.0, publicata si ca RFC 2898 al Internet Engineering Task Force. Acesta inlocuieste PBKDF1, care putea produce doar chei derivate cu lungimea de până la 160 de biți. RFC 8018 (PKCS #5 v2.1), publicat in 2017, recomandă PBKDF2 pentru codificarea parolelor. 

# Cerinte

PHP 5.6+

php-pdo

# Instalare

Acordati permisiunea de scriere pentru `db/chatrooms.sqlite` și `db/logs.txt`. Erorile din baza de date vor fi salvate in `db/logs.txt`, puteti cauta de acolo daca aveti probleme

Redenumiti fisierul de configurare din `inc/conf.template.php` in `inc/conf.php`, apoi editati `inc/conf.php` conform serverului dvs.

Tipul bazei de date va fi setat ca implicit ca SQLite, baza de date SQLite este stocata in `db/chatrooms.sqlite`.
## MySQL

Daca preferati sa utilizati MySQL, editati fisierul de configurare, modificați variabila DB_TYPE in DATABASE_MYSQL si setati variabilele DB_NAME, DB_HOST, DB_USER, DB_PASSWORD
cu datele dvs. de conectare la serverul MySQL

Puteti utiliza `structura.sql` pentru a importa structurile tabelului in baza de date.

# Probleme cunoscute
## versiunea 1.2.4+ ##
Internet Explorer nu afisează corect chatul din cauza flexbox. Utilizați un browser mai bun. Indicat Mozzila Firefox, Google Chrome, Edge, etc.
