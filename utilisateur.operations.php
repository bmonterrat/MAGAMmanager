
	<?php include "get_visu.php"; ?>
	
	<!-- suppression d'un utilisateur --> <?php
	if (isset($_GET['suppr_ut']) && $_GET['suppr_ut']!="")
	{
		$suppr = ''.$_GET['suppr_ut'].'';
		if($user_visu==$suppr){
			echo '<span class=rouge>Il est impossible de supprimer l\'utilisateur qui sert à la visualisation des opérations. Rendez-vous sur la page des <a class=bouton href="recommandation.php?affiche=calculs">calculs</a> pour en choisir un autre. </span>';
		}
		else
		{
			try
			{
				$bdd->exec('DELETE FROM valeur_utilisateur
							WHERE id_utilisateur ='.$suppr.''); //supprimer les valeurs_utilisateur attachees
				$bdd->exec('DELETE FROM utilisateur WHERE id='.$suppr.''); //supprimer la personne
				echo '<div class=vert >L\'utilisateur a été supprimée </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant la suppression de l\'utilisateur : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
	}
	?>
	
	<!-- suppression d'un groupe --> <?php
	if (isset($_GET['suppr_gp']) && $_GET['suppr_gp']!="")
	{
		$suppr = ''.$_GET['suppr_gp'].'';
		if($user_ref_groupe==$suppr){
			echo '<span class=rouge>Il est impossible de supprimer le groupe de l\'utilisateur qui sert à la visualisation des opérations. Rendez-vous sur la page des <a class=bouton href="recommandation.php?affiche=calculs">calculs</a> pour en choisir un autre. </span>';
		}
		else
		{
			try
			{
				$nb_util = $bdd->query('SELECT COUNT(*) as nb FROM utilisateur WHERE id_groupe="'.$suppr.'"');
				$res = $nb_util->fetch();
				$nb = $res['nb'];
				$nb_util->closeCursor();
				$bdd->exec('DELETE FROM valeur_utilisateur
							WHERE id_utilisateur IN 
								(SELECT id FROM utilisateur
								WHERE id_groupe="'.$suppr.'")'); //supprimer les valeurs_utilisateur attachees
				$bdd->exec('DELETE FROM utilisateur WHERE id_groupe="'.$suppr.'"'); //supprimer les utilisateur attachés
				$bdd->exec('DELETE FROM groupe_utilisateur WHERE id="'.$suppr.'"'); //supprimer le groupe
				echo '<div class=vert >Le groupe d\'utilisateur et ses '.$nb.' utilisateurs ont été supprimés </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant la suppression du groupe de utilisateur : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
	}
	?>
	
	<!-- ajout d'un nouvel utilisateur --> <?php
	if (isset($_POST['new_utilisateur']))
	{
		if($_POST['new_utilisateur']!="")
		{
			try
			{
				$Q='INSERT INTO `utilisateur`(`id`, `id_groupe`, `nom`)
						VALUES (NULL,
						'.$_POST['nouveau_id_gp'].',
						"'.addslashes($_POST['new_utilisateur']).'"
						)';
				$bdd->exec($Q);
				echo '<div class=vert > Nouvel utilisateur ajouté </div>';
				
				//spécifier qu'une nouvelle entité a été ajoutée
				$Q='UPDATE `bd` 
						SET `new_entity` = 1
						WHERE id = "'.$_SESSION['bd'].'"';
				$bdd->exec($Q);
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant l\'ajout d\'une utilisateur dans la base de données : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
		else
		{ //afficher un message d'erreur
			echo '<span class=rouge>impossible d\'ajouter une utilisateur sans nom</span>';
		}
	}
	?>	

	<!-- ajout d'un nouveau groupe --> <?php
	if (isset($_POST['new_groupe_nom']))
	{
		$erreur = "ok";
		if($_POST['new_groupe_nom']=="")
		{
			$erreur = "no_name";
			echo '<span class=rouge>Erreur : impossible d\'ajouter un groupe d\'utilisateur sans nom : </span>';
		}
		if($erreur == "ok")
		{			
			try
			{
				$Q='INSERT INTO `groupe_utilisateur`(`id`, `nom`, `open`, `bd`)
						VALUES (NULL,
						"'.addslashes($_POST['new_groupe_nom']).'",
						0,
						"'.$_SESSION['bd'].'"
						)';
				$bdd->exec($Q);
				echo '<div class=vert > Nouveau groupe d\'utilisateur ajouté </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant l\'ajout d\'un groupe d\'utilisateur dans la base de données : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
	}
	?>	
	
	<!-- modification d'une utilisateur --> <?php
	if (isset($_POST['edit_nom_utilisateur']))
	{
		if($_POST['edit_nom_utilisateur']!="")
		{
			try
			{
				$Q='UPDATE `utilisateur` SET `nom` = "'.addslashes($_POST['edit_nom_utilisateur']).'" WHERE `id` = '.$_POST['edit_id_utilisateur'];
				$bdd->exec($Q);
				echo '<div class=vert > utilisateur modifié </div>';
			}
			catch(Exception $e)
			{
				echo '<span class=rouge>erreur pendant la modification du nom d\'une utilisateur : </span>';
				die('Erreur : '.$e->getMessage());
			}
		}
		else
		{ //afficher un message d'erreur
			echo '<span class=rouge>impossible d\'ajouter une utilisateur sans nom</span>';
		}
	}
	?>
