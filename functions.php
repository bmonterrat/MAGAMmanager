<?php

function sendmailtest()
{
	$envoi = mail( 
			"baptistemonterrat@gmail.com", 
			"Patates", 
			"Ceci est un message de test.\r\n
			 \r\n
			 Baptiste\r\n" );
	if($envoi)
		echo '<span class="vert"> Mail test envoyé avec succès.</span>';
	else
		echo '<span class="vert"> Echec lors de l\'envoi du mail test.</span>';
}

function sendmail($destinataire, $act1, $act2, $act3, $idMoodle, $deadline, $nom)
{
	$envoi = mail( 
		$destinataire.', experimentation.info@gmail.com',
		"Activités pour ".$deadline." ", 
		"Bonjour ".$nom.",
		
		Voici les activités qui vous sont recommandées pour cette semaine :
		Activité obligatoire 1 : ".$act1."
		Activité obligatoire 2 : ".$act2."
		Activité optionnelle : ".$act3."
		
		Les activités sont à réaliser avant le ".$deadline.", minuit.
		Elles comptent pour votre note de participation au cours ACEO (ou TEC).
		
		Vous pouvez accéder à Moodle avec ce lien :
		http://lutes.upmc.fr/moodle2/login/index.php
		Votre identifiant est : ".$idMoodle."
		Votre mot de passe est : ".$idMoodle."m?
		Le cours s'appelle 'Activités ACEO'.
		Pour le bon déroulement de l'expérimentation, merci de ne pas 
		faire d'autres activités que celles qui vous sont recommandées.
		
		Attention, il n'est pas possible de répondre à cet e-mail.
		Pour toute question, écrivez à experimentation.info@gmail.com, 
		ou adresser-vous à votre professeur de Français.");
	
	if($envoi)
		echo '<span class="vert"> Mail envoyé avec succès à '.$destinataire.'.</span>';
	else
		echo '<span class="rouge"> Echec lors de l\'envoi du mail à '.$destinataire.'.</span>';
}

function get_color($groupe_xp)
{
	if($groupe_xp == 'O')
		return "vert";
	else if($groupe_xp == 'A')
		return "rouge";
	else if($groupe_xp == 'B')
		return "bleu";
	else if($groupe_xp == 'AB')
		return "violet";
	else
		return "noir";
}

function matching($nom_prop, $nom_acti)
{
	$match = false;
	if(
		($nom_prop == "Seeker" && stripos($nom_acti, "S1", 3)) ||
		($nom_prop == "Survivor" && stripos($nom_acti, "S2", 3)) ||
		($nom_prop == "Daredevil" && stripos($nom_acti, "D", 3)) ||
		($nom_prop == "Mastermind" && stripos($nom_acti, "M", 3)) ||
		($nom_prop == "Socializer" && stripos($nom_acti, "S3", 3)) ||
		($nom_prop == "Conqueror" && stripos($nom_acti, "C", 3)) ||
		($nom_prop == "Achiever" && stripos($nom_acti, "A", 3)) ||
		(substr($nom_prop, 0, 2) == "OL" && substr($nom_acti, 0, 2) == "OL") ||
		(substr($nom_prop, 0, 2) == "OG" && substr($nom_acti, 0, 2) == "OG") ||
		(substr($nom_prop, 0, 2) == "SC" && substr($nom_acti, 0, 2) == "SC") ||
		(substr($nom_prop, 0, 2) == "CT" && substr($nom_acti, 0, 2) == "CT") ||
		(substr($nom_prop, 0, 2) == "CM" && substr($nom_acti, 0, 2) == "CM") ||
		(substr($nom_prop, 0, 1) == "V" && substr($nom_acti, 0, 1) == "V")
		)
		$match = true;
		
	return $match;
}
	
?>