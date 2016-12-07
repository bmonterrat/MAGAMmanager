<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="robots" content="noindex">
	<title>Adaptation Multi Aspects - Aide</title>
	<link rel="icon" type="image/png" href="images/favicon.png" />
</head>
<body>
	
	<?php
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	//$bdd = new PDO('mysql:host=localhost;dbname=mfa', 'root', '', $pdo_options);
	$bdd = new PDO('mysql:host=193.37.145.61;dbname=bmont721766', 'bmont721766', 'd6H4FAGw', $pdo_options); //commenter en local
	
	session_start();
	$_SESSION['page'] = "aide";
	include "functions.php";
	include "menu.php";
	?>
	
	<?php 
		include "aide.operations.php";
	?>
	
	<fieldset>
		<legend> A propos </legend>
	
	</fieldset>
	<br>
	
	<fieldset>
		<legend> Fonctions avancées </legend>
		
		<!-- initialisation Q-matrice -->
		<a class="bouton3" href="aide.php?construire=oui"> Construire matrice activités </a><br/>
		<br/>
		
		<!-- test du mail -->
		<a class="bouton3" href="aide.php?mail=oui"> Envoyer un mail de test </a><br/>
		<br/>
	
	</fieldset>
	<br>
	
</body>
</html>
