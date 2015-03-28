<?php

$ime = $_POST["ime"];
$email = $_POST["email"];
$url = $_POST["url"];
$comment = $_POST["comment"];
$ime = Popravi($ime);
$email = Popravi ($email);
$url = Popravi ($url);
$comment = Popravi ($comment);
$sve = "<H1>Podaci</H1>";
$sve = $sve . "<p>Ime: <b>$ime</b></p>";
$sve = $sve . "<p>URL: <b>$url</b></p>";
$sve = $sve . "<p>E-mail: <b>$email</b></p>";
$sve = $sve . "<p>Komentar</p>";
$sve = $sve . "<textarea cols='100' rows='8' readonly>" . $comment . "</textarea>";
$sve = $sve . "<p><b>NOTE:</b> Ovdje je samo navedeno procesiranje ulaza. Nije napravljena adekvatna zastita od XSS-a, niti ovaj fajl radi ista funkcionalno!</p>";

echo $sve;

function Popravi ($d)
	{
		$d = trim($d);
		$d = stripslashes($d);
		$d = htmlspecialchars($d);
		return $d;
	}
?>