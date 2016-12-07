<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8"/>
	<meta name="robots" content="noindex">
	<title>MAGAM manager - Aide</title>
	<link rel="icon" type="image/png" href="images/favicon.png" />
</head>
<body>
	
	<?php
	$pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
	$bdd = new PDO('mysql:host=localhost;dbname=mfa', 'root', '', $pdo_options);
	
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
	
		<strong>MAGAM</strong> (<i>Multi-Aspect Generic Adaptation Model</i>) est un modèle permettant la recommandation d'activités à des utilisateurs. <br/>
		Pour en savoir plus, rendez-vous ici (ajouter lien vers article ORPHEE lorsqu'il sera publié). <br/>
		<br/>
		<strong> MAGAM manager </strong> est une application permettant de gérer des recommandations basées sur MAGAM. <br/>
		Pour cela vous devez : <br/>
		(1) Créer une base de données en lui donnant un nom, et bien conserver ce nom en mémoire. <br/>
		(2) Définir vos <strong>utilisateurs</strong>, <strong>activités</strong> et <strong>propriétés</strong>. <br/>
		Les activités et utilisateurs sont définis dans des groupes. Les propriétés sont regroupées par aspects. <br/>
		(3) Définir les <strong>valeurs</strong> qui lient les propriétés aux utilisateurs et aux activités. <br/>
		(4) Choisir -pour chaque aspect- le <strong>calcul</strong> qui exprimera au mieux les valeurs de recommandations. <br/>
		(5) Choisir les <strong>fusions</strong> qui feront émerger de votre système une recommandation unique pour chaque utilisateur. <br/>
		(6) Choisir le mode de <strong>sélection</strong> qui prendra l'activité la mieux recommandée d'après les valeurs de recommandation. <br/>
		(7) Lire le <strong>résultat</strong> donné par le système de recommandation et le communiquer à vos utilisateurs. <br/>
		
	</fieldset>
	<br>
	
</body>
</html>
