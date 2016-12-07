
<!-- Création des valeurs lorsqu'elles sont absentes -->
<?php

function initialiser_valeurs($affiche, $bdd)
{
	$initialisation_necessaire = false;
	
	$besoins = $bdd->query('SELECT new_entity FROM bd WHERE id = "'.$_SESSION['bd'].'"');
	$besoin = $besoins->fetch();
	if($besoin['new_entity'] == 1)
		$initialisation_necessaire = true;
	
	if($initialisation_necessaire)
	{
		$type = "inconnu";
		$type2 = "inconnu";
		try
		{
			//récupération de tous les identifiants dans des tableaux		
			$liste_des_proprietes = $bdd->query('SELECT propriete.id
												FROM propriete
												JOIN groupe_propriete
												ON groupe_propriete.id = propriete.id_groupe
												WHERE groupe_propriete.bd = "'.$_SESSION['bd'].'"
												ORDER BY propriete.id' ); //get liste id propriétés
			$ligne = 0;
			while ($donnees_pr = $liste_des_proprietes->fetch())
			{
				$liste_id_pr[$ligne++] = $donnees_pr;
			}
			$liste_des_utilisateurs = $bdd->query('SELECT utilisateur.id
												FROM utilisateur 
												JOIN groupe_utilisateur
												ON groupe_utilisateur.id = utilisateur.id_groupe
												WHERE bd = "'.$_SESSION['bd'].'"
												ORDER BY utilisateur.id' ); //get liste id utilisateurs 
			$ligne = 0;
			$user_ok = false;
			while ($donnees_ut = $liste_des_utilisateurs->fetch())
			{
				$liste_id_ut[$ligne++] = $donnees_ut;
				$user_ok = true;
			}
			$liste_des_activites = $bdd->query('SELECT activite.id
												FROM activite 
												JOIN groupe_activite
												ON groupe_activite.id = activite.id_groupe
												WHERE bd = "'.$_SESSION['bd'].'"
												ORDER BY activite.id' ); //get liste id activités
			$ligne = 0;
			$acti_ok = false;
			while ($donnees_ac = $liste_des_activites->fetch())
			{
				$liste_id_ac[$ligne++] = $donnees_ac;
				$acti_ok = true;
			}
			
			if($affiche=="utilisateurs")
			//identification de la tâche demandée
			{
				$type = "utilisateur";
				$type2 = "uti";
				if($user_ok==true)
					$tableau = $liste_id_ut;
			}
			else if($affiche=="activites")
			{
				$type = "activite";
				$type2 = "act";
				if($acti_ok==true)
					$tableau = $liste_id_ac;
			}
			else if($affiche=="croisees")
			{
				$type = "croisement";
				$type2 = "cro";
			}
			
			//création des valeurs liées aux propriétés
			if($type != "inconnu" && (($affiche=="utilisateurs" && $user_ok==true) || ($affiche=="activites" && $acti_ok==true)) )
			{
				$compteur_valeurs = 0;
				foreach( $liste_id_pr as $id_pr ){
					foreach( $tableau as $id_elem ){
						$nbVal = $bdd->query('SELECT COUNT(*) FROM `valeur_'.$type.'` WHERE `id_propriete` = '.$id_pr['id'].' AND `id_'.$type.'` = '.$id_elem['id'].'' );
						$nb = $nbVal->fetch();
						if($nb['COUNT(*)'] == 0)
						{
							$defaut_val = $bdd->query('SELECT `defaut_'.$type2.'` FROM `groupe_propriete`, `propriete`
													WHERE `propriete`.id = '.$id_pr['id'].' AND `groupe_propriete`.`id` = `propriete`.`id_groupe`');
							$defaut = $defaut_val->fetch();
							$bdd->exec('INSERT INTO `valeur_'.$type.'`(`id_'.$type.'`, `id_propriete`, `valeur`) VALUES ('.$id_elem['id'].','.$id_pr['id'].','.$defaut['defaut_'.$type2.''].')');
							$compteur_valeurs++;
						}
					}
				}
				if($compteur_valeurs > 0){
					echo '<div class=vert >'.$compteur_valeurs.' valeurs par défaut ajoutées dans propriété/'.$type.'</div>';
				}
			}
			
			//création des valeurs croisées (utilisateurs/activités)
			if($type == "croisement" && $user_ok==true && $acti_ok==true )
			{
				$compteur_valeurs = 0;
				foreach( $liste_id_ac as $id_ac ){
					foreach( $liste_id_ut as $id_ut ){
						$nbVal = $bdd->query('SELECT COUNT(*) FROM `valeur_croisee` WHERE `id_activite` = '.$id_ac['id'].' AND `id_utilisateur` = '.$id_ut['id'].'' );
						$nb = $nbVal->fetch();
						if($nb['COUNT(*)'] == 0)
						{
							$defaut_val = 1;
							$bdd->exec('INSERT INTO `valeur_croisee`(`id_activite`, `id_utilisateur`, `valeur`) VALUES ('.$id_ac['id'].','.$id_ut['id'].','.$defaut_val.')');
							$compteur_valeurs++;
						}
					}
				}
				if($compteur_valeurs > 0){
					echo '<div class=vert >'.$compteur_valeurs.' valeurs par défaut ajoutées dans activite/utilisateur</div>';
				}
			}
			
		}
		catch(Exception $e)
		{
			echo '<span class=rouge>erreur pendant la création des valeurs par défaut : </span>';
			die('Erreur : '.$e->getMessage());
		}
	}
}	
	?>
